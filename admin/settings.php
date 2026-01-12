<?php
/**
 * Admin: Global Settings
 * 
 * Manage site-wide configuration options.
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Role Check
if (($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

$message = '';

// --- Handle Save ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Site Identity
    update_option('site_title', trim($_POST['site_title']));
    update_option('site_logo', trim($_POST['site_logo']));
    update_option('site_footer_text', trim($_POST['site_footer_text']));

    // Breadcrumb Settings
    update_option('breadcrumbs_enabled', isset($_POST['breadcrumbs_enabled']) ? '1' : '0');
    update_option('breadcrumbs_separator', trim($_POST['breadcrumbs_separator']));
    update_option('breadcrumbs_home_text', trim($_POST['breadcrumbs_home_text']));
    
    // Scroll to Top Settings
    update_option('scroll_top_enabled', isset($_POST['scroll_top_enabled']) ? '1' : '0');
    update_option('scroll_top_position', $_POST['scroll_top_position']);
    update_option('scroll_top_bg_color', $_POST['scroll_top_bg_color']);
    update_option('scroll_top_icon_color', $_POST['scroll_top_icon_color']);
    update_option('scroll_top_shape', $_POST['scroll_top_shape']);

    // AI Configuration
    update_option('ai_enabled', isset($_POST['ai_enabled']) ? '1' : '0');
    update_option('ai_provider', $_POST['ai_provider']);
    update_option('ai_api_key', trim($_POST['ai_api_key']));
    update_option('ai_model', trim($_POST['ai_model']));
    update_option('ai_base_url', trim($_POST['ai_base_url']));

    $message = "Settings saved successfully!";
}

// Fetch Current Values
$site_title = get_option('site_title', 'Core CMS');
$site_logo = get_option('site_logo', '');
$site_footer_text = get_option('site_footer_text', '&copy; ' . date('Y') . ' Core CMS. Built with PHP & Passion.');

$bc_enabled = get_option('breadcrumbs_enabled', '0');
$bc_separator = get_option('breadcrumbs_separator', '>');
$bc_home_text = get_option('breadcrumbs_home_text', 'Home');

$st_enabled = get_option('scroll_top_enabled', '0');
$st_position = get_option('scroll_top_position', 'bottom-right');
$st_bg_color = get_option('scroll_top_bg_color', '#007bff');
$st_icon_color = get_option('scroll_top_icon_color', '#ffffff');
$st_shape = get_option('scroll_top_shape', 'rounded');

$ai_enabled = get_option('ai_enabled', '0');
$ai_provider = get_option('ai_provider', 'gemini');
$ai_api_key = get_option('ai_api_key', '');
$ai_model = get_option('ai_model', 'gemini-1.5-flash');
$ai_base_url = get_option('ai_base_url', '');

// --- Prepare Footer Content for Editor.js ---
$footer_editor_data = '{}';
if (!empty($site_footer_text)) {
    // Check if content is already JSON
    $decoded = json_decode($site_footer_text, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && isset($decoded['blocks'])) {
        $footer_editor_data = $site_footer_text;
    } else {
        // Legacy HTML: Wrap in a Raw block so it's not lost
        $footer_editor_data = json_encode([
            'time' => time() * 1000,
            'blocks' => [[
                'type' => 'raw',
                'data' => ['html' => $site_footer_text]
            ]]
        ]);
    }
}

?>
<?php $page_title = 'Site Settings'; require_once __DIR__ . '/includes/header.php'; ?>
    <style>
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .checkbox-label { display: flex; align-items: center; gap: 10px; font-weight: normal; cursor: pointer; }
        /* Editor.js Overrides */
        #footer-editor { border: 1px solid #ccc; border-radius: 4px; padding: 10px; background: #fff; min-height: 150px; }
        .codex-editor__redactor { padding-bottom: 30px !important; }
    </style>
    <div class="container">
        <form method="post" id="settings-form">
        <div class="card">
            <h3>Site Identity</h3>
                <div class="form-group">
                    <label>Site Title</label>
                    <input type="text" name="site_title" value="<?php echo htmlspecialchars($site_title); ?>" required>
                </div>
                <div class="form-group">
                    <label>Logo URL (Relative or Absolute)</label>
                    <input type="text" name="site_logo" value="<?php echo htmlspecialchars($site_logo); ?>" placeholder="e.g. /assets/images/logo.png">
                </div>
                <div class="form-group">
                    <label>Footer Content</label>
                    <div id="footer-editor"></div>
                    <input type="hidden" name="site_footer_text" id="footer_text_input">
                </div>
        </div>

        <div class="card">
            <h3>Breadcrumbs</h3>
            <?php if ($message): ?><p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
            
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="breadcrumbs_enabled" value="1" <?php echo $bc_enabled === '1' ? 'checked' : ''; ?>>
                        Enable Breadcrumbs
                    </label>
                </div>

                <div class="form-group">
                    <label>Home Link Text</label>
                    <input type="text" name="breadcrumbs_home_text" value="<?php echo htmlspecialchars($bc_home_text); ?>">
                </div>

                <div class="form-group">
                    <label>Separator Character</label>
                    <input type="text" name="breadcrumbs_separator" value="<?php echo htmlspecialchars($bc_separator); ?>" style="width: 50px;">
                </div>

        </div>

        <div class="card">
            <h3>Scroll to Top</h3>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="scroll_top_enabled" value="1" <?php echo $st_enabled === '1' ? 'checked' : ''; ?>>
                    Enable Button
                </label>
            </div>
            
            <div class="form-group">
                <label>Position</label>
                <select name="scroll_top_position">
                    <option value="bottom-right" <?php echo $st_position === 'bottom-right' ? 'selected' : ''; ?>>Bottom Right</option>
                    <option value="bottom-left" <?php echo $st_position === 'bottom-left' ? 'selected' : ''; ?>>Bottom Left</option>
                    <option value="bottom-center" <?php echo $st_position === 'bottom-center' ? 'selected' : ''; ?>>Bottom Center</option>
                </select>
            </div>

            <div class="form-group">
                <label>Shape</label>
                <select name="scroll_top_shape">
                    <option value="square" <?php echo $st_shape === 'square' ? 'selected' : ''; ?>>Square</option>
                    <option value="rounded" <?php echo $st_shape === 'rounded' ? 'selected' : ''; ?>>Rounded</option>
                    <option value="circle" <?php echo $st_shape === 'circle' ? 'selected' : ''; ?>>Circle</option>
                </select>
            </div>

            <div class="form-group">
                <label>Background Color</label>
                <input type="color" name="scroll_top_bg_color" value="<?php echo htmlspecialchars($st_bg_color); ?>" style="height: 40px; width: 60px; padding: 0; border: none;">
            </div>

            <div class="form-group">
                <label>Icon Color</label>
                <input type="color" name="scroll_top_icon_color" value="<?php echo htmlspecialchars($st_icon_color); ?>" style="height: 40px; width: 60px; padding: 0; border: none;">
            </div>

        </div>

        <div class="card">
            <h3>AI Configuration</h3>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="ai_enabled" value="1" <?php echo $ai_enabled === '1' ? 'checked' : ''; ?>>
                    Enable AI Features
                </label>
            </div>
            <div class="form-group">
                <label>Provider</label>
                <select name="ai_provider">
                    <option value="gemini" <?php echo $ai_provider === 'gemini' ? 'selected' : ''; ?>>Google Gemini</option>
                    <option value="openai" <?php echo $ai_provider === 'openai' ? 'selected' : ''; ?>>OpenAI</option>
                    <option value="local" <?php echo $ai_provider === 'local' ? 'selected' : ''; ?>>Local (Ollama/LM Studio)</option>
                </select>
            </div>
            <div class="form-group">
                <label>API Key</label>
                <input type="password" name="ai_api_key" value="<?php echo htmlspecialchars($ai_api_key); ?>" placeholder="e.g. AIzaSy...">
            </div>
            <div class="form-group">
                <label>Model</label>
                <input type="text" name="ai_model" value="<?php echo htmlspecialchars($ai_model); ?>" placeholder="e.g. gemini-1.5-flash">
            </div>
            <div class="form-group">
                <label>Base URL (Optional)</label>
                <input type="text" name="ai_base_url" value="<?php echo htmlspecialchars($ai_base_url); ?>" placeholder="e.g. http://localhost:11434/v1">
                <small style="color: #666;">Required for Local providers.</small>
            </div>

                <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" class="btn">Save Settings</button>
                </div>
        </div>
        </form>
    </div>

    <!-- Editor.js & Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script>

    <script>
        const tools = {};
        if (typeof Header !== 'undefined') tools.header = { class: Header, inlineToolbar: true, config: { placeholder: 'Header' } };
        if (typeof List !== 'undefined') tools.list = { class: List, inlineToolbar: true };
        if (typeof Quote !== 'undefined') tools.quote = { class: Quote, inlineToolbar: true };
        if (typeof Delimiter !== 'undefined') tools.delimiter = Delimiter;
        if (typeof RawTool !== 'undefined') tools.raw = RawTool;
        if (typeof CodeTool !== 'undefined') tools.code = CodeTool;

        const editor = new EditorJS({
            holder: 'footer-editor',
            placeholder: 'Footer content...',
            tools: tools,
            data: <?php echo $footer_editor_data; ?>,
            minHeight: 50
        });

        document.getElementById('settings-form').addEventListener('submit', function(e) {
            e.preventDefault();
            editor.save().then((outputData) => {
                document.getElementById('footer_text_input').value = JSON.stringify(outputData);
                this.submit();
            }).catch((error) => {
                console.log('Saving failed: ', error);
            });
        });
    </script>
</body>

</html>