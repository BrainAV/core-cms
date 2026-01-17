# 🎨 Theme System

## 1. Overview
Core CMS supports switching between different visual themes.
*   **Default Theme**: Located in `/templates`.
*   **Custom Themes**: Located in `/themes/{theme-name}`.

## 2. Managing Themes
1.  Go to **Admin Dashboard**.
2.  Click **Theme Manager** (Admins only).
3.  You will see a list of available themes.
4.  Click **Activate** to switch the site's design instantly.

## 3. Creating a Theme
To create a new theme, refer to the [**Theme Builder Guide**](THEME_BUILDER.md) for detailed specifications.

**Quick Start:**
1.  Create a folder in `/themes/` (e.g., `/themes/brainav`).
2.  Create the required files: `header.php`, `footer.php`, `404.php`.
3.  Add your assets: `style.css`, `main.js`, images.

## 4. Theme Structure Example (BrainAV)
The `BrainAV` theme demonstrates a complex setup:
*   **`header.php`**: Contains the HTML `<head>`, Navigation, and a conditional "Hero" section that only appears on the Homepage (`$post['is_home']`).
*   **`footer.php`**: Contains the Footer, Scroll-to-Top logic, and script includes.
*   **`style.css`**: Theme-specific styles.
*   **`main.js`**: Theme-specific interactivity (Mobile menu, Scroll effects).

## 5. How it Works
The system uses the `active_theme` option in the database.

*   **Logic**: `includes/functions.php` -> `get_theme_path()`.
*   **Router**: `index.php` uses this path to `require` the correct template files.

### Example `header.php` for a Custom Theme
```php
<!DOCTYPE html>
<html>
<head>
    <!-- Link to your theme's specific CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/themes/my-theme/style.css">
</head>
...
```