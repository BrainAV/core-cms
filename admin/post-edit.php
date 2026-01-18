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
    'is_home' => 0,
    'created_at' => date('Y-m-d H:i:s')
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
    $created_at = $_POST['created_at'];
    $author_id = $_SESSION['user_id'];

    // Auto-generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }

    // Ensure Slug Uniqueness
    $base_slug = $slug;
    $counter = 1;
    while (true) {
        if ($id) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE post_slug = ? AND id != ?");
            $stmt->execute([$slug, $id]);
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE post_slug = ?");
            $stmt->execute([$slug]);
        }

        if ($stmt->fetchColumn() > 0) {
            $slug = $base_slug . '-' . $counter;
            $counter++;
        } else {
            break;
        }
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
                $stmt = $pdo->prepare("UPDATE posts SET post_title = ?, post_slug = ?, post_content = ?, post_status = ?, post_type = ?, is_home = ?, created_at = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $content, $status, $type, $is_home, $created_at, $id]);
            } else {
                // INSERT new post
                $stmt = $pdo->prepare("INSERT INTO posts (author_id, post_title, post_slug, post_content, post_status, post_type, is_home, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$author_id, $title, $slug, $content, $status, $type, $is_home, $created_at]);
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

        /* Media Modal Styles */
        #media-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 9999; }
        .media-modal-content { background: white; padding: 25px; border-radius: 12px; width: 80%; max-width: 900px; max-height: 80vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        .media-grid-picker { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 20px; }
        .media-picker-item { cursor: pointer; border: 2px solid transparent; transition: all 0.2s; border-radius: 6px; overflow: hidden; height: 120px; background: #eee; }
        .media-picker-item:hover { border-color: #007bff; transform: scale(1.05); }
        .media-picker-item img { width: 100%; height: 100%; object-fit: cover; }
        
        #media-close-btn { float: right; cursor: pointer; font-size: 1.5em; color: #666; }
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
                    <label>
                        Content 
                        <?php if (get_option('ai_enabled', '0') === '1'): ?>
                        <button type="button" id="ai-open-btn" style="background:none; border:none; color:#007bff; cursor:pointer; font-size:0.9em; font-weight:bold;">✨ AI Assist</button>
                        <?php endif; ?>
                        <button type="button" id="media-open-btn" style="background:none; border:none; color:#28a745; cursor:pointer; font-size:0.9em; font-weight:bold; margin-left: 10px;">🖼️ Media Library</button>
                    </label>
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
                    <label for="created_at">Publish Date</label>
                    <input type="datetime-local" id="created_at" name="created_at" value="<?php echo date('Y-m-d\TH:i', strtotime($post['created_at'])); ?>">
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

    <?php if (get_option('ai_enabled', '0') === '1'): ?>
    <!-- AI Modal -->
    <div id="ai-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; width:90%; max-width:500px; padding:20px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.2);">
            <h3 style="margin-top:0;">🤖 Admin Copilot</h3>
            <p style="font-size:0.9em; color:#666;">Ask AI to draft content, generate an outline, or fix grammar.</p>
            <textarea id="ai-prompt" style="width:100%; height:100px; padding:10px; border:1px solid #ccc; border-radius:4px; font-family:inherit; margin-bottom:15px; box-sizing:border-box;" placeholder="e.g. Write an intro paragraph about the benefits of meditation..."></textarea>
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" class="btn" style="background-color:#6c757d;" onclick="document.getElementById('ai-modal').style.display='none'">Cancel</button>
                <button type="button" class="btn" id="ai-generate-btn">Generate</button>
            </div>
            <div id="ai-loading" style="display:none; margin-top:10px; color:#007bff; font-weight:bold;">✨ Thinking...</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Media Modal -->
    <div id="media-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div class="media-modal-content">
            <span id="media-close-btn">&times;</span>
            <h2 style="margin-top: 0;">🖼️ Select Media Library Image</h2>
            <p style="color: #666; font-size: 0.9em;">Click an image to insert it into the editor.</p>
            <div id="media-grid-picker" class="media-grid-picker">
                <!-- Data loaded via JS -->
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-text-alignment-blocktune@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@calumk/editorjs-columns@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-text-color-plugin@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-inline-link@latest"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        // Safely define tools to prevent crashes if a CDN fails
        const tools = {};
        
        // 1. Alignment Block Tune
        if (typeof AlignmentBlockTune !== 'undefined') {
            tools.alignment = {
                class: AlignmentBlockTune,
                config: {
                    default: 'left',
                    blocks: {
                        header: 'left',
                        paragraph: 'left'
                    }
                },
            };
        }

        // 2. Main Tools
        if (typeof Header !== 'undefined') {
            tools.header = { 
                class: Header, 
                inlineToolbar: ['link', 'color', 'marker', 'bold', 'italic'], 
                tunes: ['alignment'],
                config: { placeholder: 'Header' } 
            };
        }

        if (typeof Paragraph !== 'undefined') {
            tools.paragraph = {
                class: Paragraph,
                inlineToolbar: ['link', 'color', 'marker', 'bold', 'italic'],
                tunes: ['alignment']
            };
        }

        if (typeof List !== 'undefined') tools.list = { class: List, inlineToolbar: true };
        if (typeof Quote !== 'undefined') tools.quote = { class: Quote, inlineToolbar: true };
        if (typeof Delimiter !== 'undefined') tools.delimiter = Delimiter;
        if (typeof RawTool !== 'undefined') tools.raw = RawTool;
        if (typeof CodeTool !== 'undefined') tools.code = CodeTool;
        
        const colorConfig = {
            colorCollections: ['#FF1300', '#EC78FF', '#191105', '#DCC9B6', '#72635B', '#413B37', '#000000', '#28a745', '#007bff'],
            defaultColor: '#FF1300',
            type: 'text', 
            customPicker: true 
        };

        if (typeof Marker !== 'undefined') {
            tools.marker = {
                class: Marker,
                shortcut: 'CMD+SHIFT+M',
            };
        }

        if (typeof ColorPlugin !== 'undefined') {
            tools.color = {
                class: ColorPlugin,
                config: colorConfig
            };
        }

        const linkConfig = {
            showTargetToggle: true,
            defaultTarget: '_self'
        };

        if (typeof InlineLink !== 'undefined') {
            tools.link = {
                class: InlineLink,
                config: linkConfig
            };
        }

        if (typeof ImageTool !== 'undefined') {
            tools.image = {
                class: ImageTool,
                config: { endpoints: { byFile: 'api/upload.php' } }
            };
        }
        
        // 3. Columns Internal Tools (A separate object to avoid circular references)
        const columnTools = {};
        
        // Add Alignment, Color, Link to columnTools so nested blocks can find them
        if (typeof AlignmentBlockTune !== 'undefined') {
            columnTools.alignment = {
                class: AlignmentBlockTune,
                config: { default: 'left', blocks: { header: 'left', paragraph: 'left' } }
            };
        }

        if (typeof ColorPlugin !== 'undefined') {
            columnTools.color = {
                class: ColorPlugin,
                config: colorConfig
            };
        }

        if (typeof Marker !== 'undefined') {
            columnTools.marker = Marker;
        }
        
        if (typeof InlineLink !== 'undefined') {
            columnTools.link = {
                class: InlineLink,
                config: linkConfig
            };
        }

        if (typeof Header !== 'undefined') {
            columnTools.header = {
                class: Header,
                inlineToolbar: ['link', 'color', 'marker', 'bold', 'italic'],
                tunes: ['alignment'],
                config: { placeholder: 'Column Header' }
            };
        }
        if (typeof List !== 'undefined') columnTools.list = List;
        if (typeof ImageTool !== 'undefined') {
            columnTools.image = {
                class: ImageTool,
                config: { endpoints: { byFile: 'api/upload.php' } }
            };
        }
        if (typeof Paragraph !== 'undefined') {
            columnTools.paragraph = {
                class: Paragraph,
                inlineToolbar: ['link', 'color', 'marker', 'bold', 'italic'],
                tunes: ['alignment']
            };
        }

        // 4. Register Columns Tool
        if (typeof editorjsColumns !== 'undefined') {
            tools.columns = {
                class: editorjsColumns,
                config: {
                    EditorJsLibrary: EditorJS,
                    tools: columnTools
                }
            };
        }

        const editor = new EditorJS({
            holder: 'editorjs',
            placeholder: 'Let`s write an awesome story!',
            tools: tools,
            inlineToolbar: ['link', 'color', 'marker', 'bold', 'italic'],
            data: <?php echo $editor_data; ?>
        });

        // --- Media Library Picker Logic ---
        function openMediaLibrary(callback) {
            const modal = document.getElementById('media-modal');
            const grid = document.getElementById('media-grid-picker');
            modal.style.display = 'flex';
            grid.innerHTML = '<div style="padding: 20px;">Loading your library...</div>';

            fetch('api/media-list.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        grid.innerHTML = '';
                        if (data.items.length === 0) {
                            grid.innerHTML = '<div style="padding: 20px; grid-column: 1/-1; text-align: center;">No images found in library.</div>';
                        }
                        data.items.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'media-picker-item';
                            div.innerHTML = `<img src="../${item.file_path}" title="${item.file_name}">`;
                            div.onclick = () => {
                                callback(`../${item.file_path}`);
                                modal.style.display = 'none';
                            };
                            grid.appendChild(div);
                        });
                    } else {
                        grid.innerHTML = '<div style="padding: 20px; color: red;">Failed to load media.</div>';
                    }
                });
        }

        // Trigger Media Library
        const mediaOpenBtn = document.getElementById('media-open-btn');
        if (mediaOpenBtn) {
            mediaOpenBtn.addEventListener('click', () => {
                openMediaLibrary((url) => {
                    editor.blocks.insert('image', {
                        file: { url: url },
                        caption: ''
                    });
                });
            });
        }

        document.getElementById('media-close-btn').addEventListener('click', () => {
            document.getElementById('media-modal').style.display = 'none';
        });

        // Intercept Form Submit
        document.getElementById('post-form').addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Saving Editor.js content...');
            editor.save().then((outputData) => {
                console.log('Content captured:', outputData);
                document.getElementById('post_content_input').value = JSON.stringify(outputData);
                console.log('Hidden field updated. Submitting form...');
                this.submit();
            }).catch((error) => {
                console.error('Saving failed:', error);
                alert('Failed to save content. Check console.');
            });
        });

        // AI Copilot Logic
        const aiOpenBtn = document.getElementById('ai-open-btn');
        if (aiOpenBtn) {
            aiOpenBtn.addEventListener('click', () => {
                document.getElementById('ai-modal').style.display = 'flex';
                document.getElementById('ai-prompt').focus();
            });

            document.getElementById('ai-generate-btn').addEventListener('click', () => {
                const prompt = document.getElementById('ai-prompt').value;
                const loading = document.getElementById('ai-loading');
                
                if(!prompt) return;
                
                loading.style.display = 'block';
                
                fetch('api/ai.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({prompt: prompt})
                })
                .then(res => res.json())
                .then(data => {
                    loading.style.display = 'none';
                    if(data.success) {
                    try {
                        // Try to parse the AI response as JSON blocks
                        const blocks = JSON.parse(data.text);
                        if (Array.isArray(blocks)) {
                            blocks.forEach(block => {
                                editor.blocks.insert(block.type, block.data);
                            });
                        } else {
                            editor.blocks.insert('paragraph', {text: data.text});
                        }
                    } catch (e) {
                        // Fallback: Insert as plain text if JSON parsing fails
                        editor.blocks.insert('paragraph', {text: data.text});
                    }
                        document.getElementById('ai-modal').style.display = 'none';
                        document.getElementById('ai-prompt').value = '';
                    } else {
                        alert('AI Error: ' + data.error);
                    }
                })
                .catch(err => {
                    loading.style.display = 'none';
                    alert('Network Error');
                });
            });
        }
    }); // End DOMContentLoaded
</script>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>