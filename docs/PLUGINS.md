# 🔌 Plugin Architecture

## 1. Philosophy: Learned Skills
In the **Human CMS** analogy, the Core is the Brain (Logic) and CNS. **Plugins are Learned Skills**.
Just as learning to play the piano doesn't change your DNA, a plugin shouldn't hack the Core files. It adds new capabilities on top of the existing system.

*   **Core**: Handles routing, database connection, and basic page rendering.
*   **Plugins**: Handle specialized tasks (Events, E-commerce, Forums).

## 2. Directory Structure
All plugins reside in the `/plugins` directory. Each plugin gets its own folder.

```text
/plugins
└── /event-planner          # The Plugin Slug
    ├── init.php            # Entry point (Hooks & Logic)
    ├── install.php         # Database setup (Run on activation)
    ├── /admin              # Admin interface files
    │   ├── menu.php        # Defines sidebar links
    │   └── events.php      # Main admin view
    └── /templates          # Frontend views (optional)
```

## 3. The "Proxy" Pattern
To keep the Admin Panel (`/admin`) clean, we don't put plugin files directly in it. Instead, we use a "Proxy" approach:

1.  **Menu Registration**: The plugin provides a `menu.php` that returns an array of links.
2.  **Dynamic Inclusion**: The Core Admin sidebar loops through active plugins and includes their menu links.
3.  **Routing**: When you click a plugin link (e.g., `/admin/plugins/event-planner/events.php`), the Core authenticates the user and then loads the file from the plugin directory.

## 4. Development Guidelines

### A. Isolation
*   **Namespaces**: Use PHP namespaces or unique prefixes (e.g., `ep_get_events`) to avoid collisions with Core functions.
*   **Database**: Create separate tables (e.g., `events`, `bookings`) rather than adding columns to the `posts` table.

### B. Hooks (Future Phase)
We will implement a simple Hook system (`do_action`, `add_filter`) in `includes/functions.php`.
*   **Action**: "Do something here" (e.g., `after_post_save`).
*   **Filter**: "Modify this data" (e.g., `the_content`).

### C. Activation
1.  **Discovery**: The system scans `/plugins` for folders.
2.  **Activation**: Admin toggles "Active".
3.  **Installation**: If `install.php` exists, it runs once to create DB tables.
4.  **Loading**: `index.php` checks the `active_plugins` option and includes `init.php` for each active plugin.

## 5. Example: Event Planner
See `docs/plugins/EVENT_PLANNER.md` for the specific implementation of our first plugin.