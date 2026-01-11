<?php
/**
 * Core Helper Functions
 */

/**
 * Get an option from the database.
 * 
 * @param string $name The option name.
 * @param string $default Default value if not found.
 * @return string
 */
function get_option($name, $default = '') {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("SELECT option_value FROM options WHERE option_name = ?");
    $stmt->execute([$name]);
    $row = $stmt->fetch();
    return $row ? $row['option_value'] : $default;
}

/**
 * Update (or create) an option in the database.
 * 
 * @param string $name The option name.
 * @param string $value The value to save.
 */
function update_option($name, $value) {
    $pdo = get_db_connection();
    
    // Check if exists
    $exists = get_option($name, null) !== null;
    
    if ($exists) {
        $stmt = $pdo->prepare("UPDATE options SET option_value = ? WHERE option_name = ?");
        $stmt->execute([$value, $name]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO options (option_name, option_value) VALUES (?, ?)");
        $stmt->execute([$name, $value]);
    }
}

/**
 * Delete an option from the database.
 * 
 * @param string $name The option name.
 */
function delete_option($name) {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("DELETE FROM options WHERE option_name = ?");
    $stmt->execute([$name]);
}

/**
 * Get a localized label/text string.
 * 
 * @param string $key The label key (e.g., 'read_more').
 * @param string $default The default text if no override exists.
 * @return string
 */
function get_label($key, $default) {
    $val = get_option('label_' . $key, null);
    return ($val !== null) ? $val : $default;
}

/**
 * Get the path to the active theme directory.
 * 
 * @return string Absolute path to the theme folder (no trailing slash).
 */
function get_theme_path() {
    $theme = get_option('active_theme', 'default');
    
    // If default, use the core templates folder
    if ($theme === 'default' || empty($theme)) {
        return ROOT_PATH . '/templates';
    }
    
    // Otherwise, check themes directory
    $custom_path = ROOT_PATH . '/themes/' . $theme;
    if (is_dir($custom_path)) {
        return $custom_path;
    }
    
    // Fallback
    return ROOT_PATH . '/templates';
}

/**
 * Render a navigation menu by its slug.
 *
 * @param string $slug The menu slug (e.g., 'main-menu').
 * @param string $class Optional CSS class for the <ul>.
 */
function render_menu($slug, $class = 'menu') {
    $pdo = get_db_connection();

    // 1. Get the Menu ID
    $stmt = $pdo->prepare("SELECT id FROM menus WHERE slug = ?");
    $stmt->execute([$slug]);
    $menu = $stmt->fetch();

    if (!$menu) {
        // Menu doesn't exist.
        // In debug mode, we might want to show a message, but for frontend, we stay silent
        // or show a fallback link to Home.
        if (DEBUG_MODE) {
            echo "<!-- Menu '$slug' not found. -->";
        }
        return;
    }

    // 2. Get Menu Items
    // Join with posts table to get dynamic slugs if target_id is set
    $sql = "SELECT mi.*, p.post_slug FROM menu_items mi LEFT JOIN posts p ON mi.target_id = p.id WHERE mi.menu_id = ? ORDER BY mi.sort_order ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$menu['id']]);
    $items = $stmt->fetchAll();

    if (!$items) return;

    // 3. Build Tree
    $tree = [];
    $ref = [];
    foreach ($items as $d) {
        // Dynamic URL override
        if (!empty($d['target_id']) && !empty($d['post_slug'])) {
            // Assuming BASE_URL is defined in config.php
            $d['url'] = BASE_URL . '/' . $d['post_slug'];
        }
        
        $d['children'] = [];
        $ref[$d['id']] = $d;
    }
    foreach ($ref as $id => &$d) {
        if ($d['parent_id']) {
            $ref[$d['parent_id']]['children'][] = &$d;
        } else {
            $tree[] = &$d;
        }
    }

    // 4. Render
    echo "<ul class='" . htmlspecialchars($class) . "'>";
    _render_menu_items($tree);
    echo "</ul>";
}

/**
 * Recursive helper to render menu items
 */
function _render_menu_items($items) {
    foreach ($items as $item) {
        echo "<li>";
        echo "<a href='" . htmlspecialchars($item['url']) . "'>" . htmlspecialchars($item['label']) . "</a>";
        if (!empty($item['children'])) {
            echo "<ul>";
            _render_menu_items($item['children']);
            echo "</ul>";
        }
        echo "</li>";
    }
}

/**
 * Render Breadcrumbs.
 * 
 * Displays path: Home > [Current Page Title]
 * 
 * @param string $current_title The title of the current page.
 */
function render_breadcrumbs($current_title = '') {
    // Check if enabled via Admin > Settings
    if (get_option('breadcrumbs_enabled', '0') !== '1') {
        return;
    }

    $separator = get_option('breadcrumbs_separator', '>');
    $home_text = get_option('breadcrumbs_home_text', 'Home');
    
    echo '<nav aria-label="breadcrumb" class="breadcrumbs">';
    echo '<a href="' . BASE_URL . '">' . htmlspecialchars($home_text) . '</a>';
    
    if ($current_title) {
        echo ' <span class="separator">' . htmlspecialchars($separator) . '</span> ';
        echo '<span class="current">' . htmlspecialchars($current_title) . '</span>';
    }
    echo '</nav>';
}

/**
 * Render Editor.js JSON to HTML.
 *
 * @param string $json_string The JSON string from the database.
 * @return string HTML output.
 */
function render_blocks($json_string) {
    $data = json_decode($json_string, true);

    // Fallback for legacy content (not JSON)
    if (json_last_error() !== JSON_ERROR_NONE || !isset($data['blocks'])) {
        return nl2br(htmlspecialchars($json_string));
    }

    $html = '';
    foreach ($data['blocks'] as $block) {
        switch ($block['type']) {
            case 'header':
                $level = $block['data']['level'] ?? 2;
                $text = $block['data']['text'];
                $html .= "<h{$level}>{$text}</h{$level}>";
                break;
            case 'paragraph':
                $text = $block['data']['text'];
                $html .= "<p>{$text}</p>";
                break;
            case 'list':
                $style = ($block['data']['style'] ?? 'unordered') === 'ordered' ? 'ol' : 'ul';
                $items = $block['data']['items'] ?? [];
                $html .= "<{$style}>";
                foreach ($items as $item) {
                    $html .= "<li>{$item}</li>";
                }
                $html .= "</{$style}>";
                break;
            case 'quote':
                $text = $block['data']['text'];
                $caption = $block['data']['caption'] ?? '';
                $html .= "<blockquote class='editor-quote'>{$text}";
                if ($caption) $html .= "<cite>{$caption}</cite>";
                $html .= "</blockquote>";
                break;
            case 'delimiter':
                $html .= "<hr class='editor-delimiter'>";
                break;
            case 'raw':
                $html .= $block['data']['html'];
                break;
            case 'image':
                $url = $block['data']['file']['url'] ?? '';
                $caption = $block['data']['caption'] ?? '';
                $html .= "<figure class='editor-image'>";
                $html .= "<img src='{$url}' alt='{$caption}'>";
                if ($caption) $html .= "<figcaption>{$caption}</figcaption>";
                $html .= "</figure>";
                break;
            case 'code':
                $code = htmlspecialchars($block['data']['code']);
                $html .= "<pre><code>{$code}</code></pre>";
                break;
            default:
                // Ignore unknown blocks
                break;
        }
    }
    return $html;
}