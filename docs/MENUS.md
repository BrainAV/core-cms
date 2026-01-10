# 🧭 Menu System

## 1. Overview
Core CMS uses a **database-driven menu system**. Instead of hardcoding HTML links in `header.php`, we store navigation structures in the database. This allows admins to manage links without touching code.

## 2. Database Architecture

The system uses two tables (defined in `db/schema.sql`):

### A. `menus` (The Container)
Represents a menu location (e.g., "Main Header", "Footer Links").
*   `id`: Unique ID.
*   `name`: Human-readable name (e.g., "Top Nav").
*   `slug`: Unique identifier used in code (e.g., `main-menu`).

### B. `menu_items` (The Links)
The actual links inside a container.
*   `menu_id`: Links to the parent `menus` table.
*   `label`: The text to display (e.g., "About Us").
*   `url`: The destination (e.g., `/about`).
*   `parent_id`: Used for nested dropdowns (Sub-menus).
*   `sort_order`: Integer to control display order.

## 3. How it Works

### In the Admin
1.  **Create Menu**: Admin creates a container (e.g., "Header").
2.  **Add Items**: Admin adds links to that container.
3.  **Reorder**: Admin sets the `sort_order`.

### On the Frontend
We use a helper function (to be built in Phase 3) to fetch and render the menu by its slug.

```php
// Usage in templates/header.php
// Second parameter allows custom CSS class (default is 'menu')
render_menu('main-menu', 'site-menu');
```

## 4. Future Features
*   **Drag-and-Drop Reordering**: Using JavaScript in the admin.
*   **Auto-Add Pages**: Automatically adding new pages to a menu.
*   **Mega Menus**: Complex nested layouts.