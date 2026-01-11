<?php
/**
 * Admin: Edit/Create Post
 *
 * Handles form submission for adding or updating posts.
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = get_db_connection();
$error = '';
$post = [
    'id' => '',
    'post_title' => '',
    'post_slug' => '',
    'post_content' => '',
    'post_status' => 'publish',
    'post_type' => $_GET['type'] ?? 'post',
    'is_home' => 0
];

// --- Handle POST Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $title = trim($_POST['post_title']);
    $slug = trim($_POST['post_slug']);
    $content = $_POST['post_content'];
    $status = $_POST['post_status'];
    $type = $_POST['post_type'] ?? 'post';
    $is_home = isset($_POST['is_home']) ? 1 : 0;
    $author_id = $_SESSION['user_id'];

    // Auto-generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }

    if (empty($title)) {
        $error = "Title is required.";
    } else {
        try {
            $pdo->beginTransaction();

            // If setting as home, reset all other posts first
            if ($is_home) {
                $pdo->exec("UPDATE posts SET is_home = 0");
            }

            if ($id) {
                // UPDATE existing post
                $stmt = $pdo->prepare("UPDATE posts SET post_title = ?, post_slug = ?, post_content = ?, post_status = ?, post_type = ?, is_home = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $content, $status, $type, $is_home, $id]);
            } else {
                // INSERT new post
                $stmt = $pdo->prepare("INSERT INTO posts (author_id, post_title, post_slug, post_content, post_status, post_type, is_home) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$author_id, $title, $slug, $content, $status, $type, $is_home]);
                $id = $pdo->lastInsertId();
            }

            // --- Handle Categories ---
            // 1. Clear existing
            $stmt = $pdo->prepare("DELETE FROM post_categories WHERE post_id = ?");
            $stmt->execute([$id]);

            // 2. Insert selected
            if (!empty($_POST['categories']) && is_array($_POST['categories'])) {
                $cat_stmt = $pdo->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)");
                foreach ($_POST['categories'] as $cat_id) {
                    $cat_stmt->execute([$id, $cat_id]);
                }
            }

            // --- Handle Tags ---
            // 1. Clear existing
            $stmt = $pdo->prepare("DELETE FROM post_tags WHERE post_id = ?");
            $stmt->execute([$id]);

            // 2. Process and Insert
            if (!empty($_POST['tags'])) {
                $tags = explode(',', $_POST['tags']);
                $tag_lookup = $pdo->prepare("SELECT id FROM tags WHERE slug = ?");
                $tag_insert = $pdo->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
                $pt_insert  = $pdo->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");

                foreach ($tags as $tag_name) {
                    $tag_name = trim($tag_name);
                    if (empty($tag_name)) continue;
                    
                    $tag_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tag_name)));
                    
                    // Check if tag exists, if not create it
                    $tag_lookup->execute([$tag_slug]);
                    $existing_tag = $tag_lookup->fetch();

                    if ($existing_tag) {
                        $tag_id = $existing_tag['id'];
                    } else {
                        $tag_insert->execute([$tag_name, $tag_slug]);
                        $tag_id = $pdo->lastInsertId();
                    }

                    // Link to post
                    $pt_insert->execute([$id, $tag_id]);
                }
            }

            $pdo->commit();

            $redirect = ($type === 'page') ? 'pages.php' : 'posts.php';
            header("Location: $redirect?status=saved");
            exit;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

// --- Handle GET (Edit Mode) ---
$current_cats = [];
$current_tags_str = '';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $fetched_post = $stmt->fetch();
    if ($fetched_post) {
        $post = $fetched_post;

        // Fetch Categories
        $stmt = $pdo->prepare("SELECT category_id FROM post_categories WHERE post_id = ?");
        $stmt->execute([$post['id']]);
        $current_cats = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Fetch Tags
        $stmt = $pdo->prepare("SELECT t.name FROM tags t JOIN post_tags pt ON t.id = pt.tag_id WHERE pt.post_id = ?");
        $stmt->execute([$post['id']]);
        $current_tags_str = implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }
}

// Fetch All Categories for the UI
$all_categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

$label = ($post['post_type'] === 'page') ? 'Page' : 'Post';

// --- Prepare Content for Editor.js ---
$editor_data = '{}';
if (!empty($post['post_content'])) {
    // Check if content is already JSON
    $decoded = json_decode($post['post_content'], true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && isset($decoded['blocks'])) {
        $editor_data = $post['post_content'];
    } else {
        // Legacy HTML: Wrap in a Raw block so it's not lost
        $editor_data = json_encode([
            'time' => time() * 1000,
            'blocks' => [[
                'type' => 'raw',
                'data' => ['html' => $post['post_content']]
            ]]
        ]);
    }
}
?>
<?php $page_title = $post['id'] ? "Edit $label" : "New $label"; require_once __DIR__ . '/includes/header.php'; ?>
    <style>
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; box-sizing: border-box; }
        .form-group textarea { height: 300px; resize: vertical; }
        .error { color: red; margin-bottom: 15px; }
        
        /* Editor.js Overrides */
        #editorjs { 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            padding: 20px; 
            background: #fff; 
            min-height: 300px; 
        }
        .codex-editor__redactor { padding-bottom: 50px !important; }
    </style>
    <div class="container">
        <div class="card">
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" id="post-form">
                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                <input type="hidden" name="post_type" value="<?php echo htmlspecialchars($post['post_type']); ?>">

                <div class="form-group">
                    <label for="post_title">Title</label>
                    <input type="text" id="post_title" name="post_title" value="<?php echo htmlspecialchars($post['post_title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="post_slug">Slug (URL Friendly Name)</label>
                    <input type="text" id="post_slug" name="post_slug" value="<?php echo htmlspecialchars($post['post_slug']); ?>" placeholder="Leave empty to auto-generate">
                </div>

                <div class="form-group">
                    <label>Content</label>
                    <div id="editorjs"></div>
                    <input type="hidden" name="post_content" id="post_content_input">
                </div>

                <div class="form-group">
                    <label for="post_status">Status</label>
                    <select id="post_status" name="post_status">
                        <option value="publish" <?php echo $post['post_status'] === 'publish' ? 'selected' : ''; ?>>Published</option>
                        <option value="draft" <?php echo $post['post_status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="archived" <?php echo $post['post_status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Categories</label>
                    <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; border-radius: 4px; background: #fff;">
                        <?php foreach ($all_categories as $cat): ?>
                            <label style="font-weight: normal; display: block; margin-bottom: 5px;">
                                <input type="checkbox" name="categories[]" value="<?php echo $cat['id']; ?>" <?php echo in_array($cat['id'], $current_cats) ? 'checked' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </label>
                        <?php endforeach; ?>
                        <?php if (empty($all_categories)): ?>
                            <p style="color: #777; margin: 0; font-size: 0.9em;">No categories found. (Create them in the Categories manager)</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tags">Tags (Comma separated)</label>
                    <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($current_tags_str); ?>" placeholder="e.g. PHP, Update, News">
                </div>

                <?php if ($post['post_type'] === 'page'): ?>
                <div class="form-group">
                    <label style="font-weight: normal;">
                        <input type="checkbox" name="is_home" value="1" <?php echo $post['is_home'] ? 'checked' : ''; ?>>
                        Set as <strong>Homepage</strong>
                    </label>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn">Save Post</button>
            </form>
        </div>
    </div>

    <!-- Editor.js & Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>

    <script>
        // Safely define tools to prevent crashes if a CDN fails
        const tools = {};
        if (typeof Header !== 'undefined') tools.header = { class: Header, inlineToolbar: true, config: { placeholder: 'Header' } };
        if (typeof List !== 'undefined') tools.list = { class: List, inlineToolbar: true };
        if (typeof Quote !== 'undefined') tools.quote = { class: Quote, inlineToolbar: true };
        if (typeof Delimiter !== 'undefined') tools.delimiter = Delimiter;
        if (typeof RawTool !== 'undefined') tools.raw = RawTool;
        if (typeof CodeTool !== 'undefined') tools.code = CodeTool;
        if (typeof ImageTool !== 'undefined') {
            tools.image = {
                class: ImageTool,
                config: { endpoints: { byFile: 'api/upload.php' } }
            };
        }

        const editor = new EditorJS({
            holder: 'editorjs',
            placeholder: 'Let`s write an awesome story!',
            tools: tools,
            data: <?php echo $editor_data; ?>
        });

        // Intercept Form Submit
        document.getElementById('post-form').addEventListener('submit', function(e) {
            e.preventDefault();
            editor.save().then((outputData) => {
                document.getElementById('post_content_input').value = JSON.stringify(outputData);
                this.submit();
            }).catch((error) => {
                console.log('Saving failed: ', error);
            });
        });
    </script>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>