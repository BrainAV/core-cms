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
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE menu_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$menu['id']]);
    $items = $stmt->fetchAll();

    if (!$items) return;

    // 3. Build Tree
    $tree = [];
    $ref = [];
    foreach ($items as $d) {
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