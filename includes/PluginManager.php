<?php
/**
 * Class: PluginManager
 *
 * Handles the discovery, activation, and integration of plugins.
 * Enforces the "Soft Install" strategy by reading configuration
 * rather than moving files.
 */

class PluginManager {
    private $pdo;
    private $plugins_dir;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->plugins_dir = __DIR__ . '/../plugins';
    }

    /**
     * Get a list of active plugin slugs from the database.
     *
     * @return array ['event-planner', 'engagement']
     */
    public function getActivePlugins() {
        $stmt = $this->pdo->prepare("SELECT option_value FROM options WHERE option_name = 'active_plugins'");
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row && $row['option_value']) {
            return json_decode($row['option_value'], true) ?? [];
        }

        return [];
    }

    /**
     * Scan active plugins for Admin Menu definitions.
     *
     * Looks for: /plugins/{slug}/admin/menu.php
     * Expected Return: ['label' => 'Events', 'url' => '...']
     *
     * @return array List of menu items to inject into the sidebar.
     */
    public function getAdminMenus() {
        $active = $this->getActivePlugins();
        $menus = [];

        foreach ($active as $slug) {
            $menu_file = $this->plugins_dir . '/' . $slug . '/admin/menu.php';
            
            if (file_exists($menu_file)) {
                // The menu.php file should return an array
                $item = include $menu_file;
                
                if (is_array($item)) {
                    $menus[] = $item;
                }
            }
        }

        return $menus;
    }
}