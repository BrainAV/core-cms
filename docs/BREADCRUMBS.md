# 🍞 Breadcrumbs System

## 1. Overview
Breadcrumbs provide a secondary navigation scheme that reveals the user's location in a website or web application.

**Example:** `Home > Blog > My First Post`

## 2. Configuration
Breadcrumbs are managed via the **Admin Panel**.

1.  Go to **Admin Dashboard**.
2.  Click **Settings** (or navigate to `/admin/settings.php`).
3.  **Enable Breadcrumbs**: Toggle the checkbox.
4.  **Home Text**: Customize the label for the root link (e.g., "Home" or "Start").
5.  **Separator**: Choose the character between items (e.g., `>`, `/`, `»`).

## 3. Developer Usage

To display breadcrumbs in your theme (e.g., `templates/header.php` or `templates/page.php`), use the helper function:

```php
<?php render_breadcrumbs($page_title); ?>
```

The function automatically checks the database settings. If breadcrumbs are disabled in the admin, nothing will render.

## 4. Limitations (Phase 3)
Currently, the breadcrumbs support a **Flat Hierarchy** (`Home > Current Page`).

In **Phase 4**, when we add `parent_id` to the `posts` table, we will update `render_breadcrumbs()` to recursively display the full parent lineage (e.g., `Home > Services > Web Design > Project A`).