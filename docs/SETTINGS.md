# ⚙️ Global Settings

## 1. Overview
Core CMS uses the `options` database table to store site-wide configuration. These settings are managed via `admin/settings.php`.

## 2. Available Options

### Site Identity
*   `site_title`: The name of the website (displayed in `<title>` and Header).
*   `site_logo`: URL to the logo image (relative or absolute).
*   `site_footer_text`: HTML content displayed in the footer.

### Breadcrumbs
*   `breadcrumbs_enabled`: Boolean (`1` or `0`).
*   `breadcrumbs_separator`: Character between links (e.g., `>`).
*   `breadcrumbs_home_text`: Label for the root link.

### Scroll to Top
*   `scroll_top_enabled`: Boolean (`1` or `0`).
*   `scroll_top_position`: CSS positioning logic (e.g., `bottom-right`, `bottom-left`).
*   `scroll_top_shape`: Button style (`square`, `rounded`, `circle`).
*   `scroll_top_bg_color`: Hex code for background.
*   `scroll_top_icon_color`: Hex code for the arrow.

## 3. Developer Usage
To retrieve a setting in your code (e.g., in a template or plugin), use the helper function:

```php
// get_option($key, $default_value)
$title = get_option('site_title', 'My Website');
```