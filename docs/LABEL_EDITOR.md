# 🏷️ Label Editor

## 1. Overview
The Label Editor allows Administrators to customize text strings in the user interface without modifying code. This is useful for:
*   **Localization**: Translating hardcoded English text.
*   **Branding**: Changing generic terms (e.g., "Posts" -> "Articles").
*   **A/B Testing**: Tweaking call-to-action buttons.

## 2. How it Works
Labels are stored in the `options` database table with a `label_` prefix.
*   **Key**: The unique identifier used in the code (e.g., `read_more_btn`).
*   **Value**: The text to display (e.g., "Continue Reading").

## 3. Managing Labels (Admin)
1.  Navigate to **Label Editor** in the Admin Dashboard (Admins only).
2.  **Add Label**:
    *   **Key**: Enter the identifier provided by the theme developer (e.g., `contact_submit`).
    *   **Value**: Enter the text you want to appear.
3.  **Edit/Delete**: Use the list view to modify existing overrides.

## 4. Developer Usage
To make your theme or plugin compatible with the Label Editor, replace hardcoded strings with the `get_label()` helper function.

### Function Signature
```php
/**
 * Get a localized label/text string.
 * @param string $key The label key.
 * @param string $default The default text if no override exists.
 */
function get_label($key, $default)
```

### Example
**Before:**
```php
<button>Submit</button>
```

**After:**
```php
<button><?php echo get_label('contact_submit_btn', 'Submit'); ?></button>
```

## 5. Scope & Limitations
### Can I use labels inside the Block Editor (Editor.js)? 
No. The Label Editor is designed for PHP Templates (the structural parts of your theme like headers, footers, and layout buttons).
* Content (Editor.js): This is static text saved to the database for a specific post. 
* Interface (Labels): This is dynamic text loaded by the theme files.
If you need a button inside a blog post, use the Raw HTML block in Editor.js.

## 6. The Override Pattern (Best Practice)

A powerful way to use the Label Editor is to combine it with default settings or hardcoded values. This creates a "Hierarchy of Needs" for your text.

### Example: Footer Text (footer_text) and (site_footer_text)
In `templates/footer.php`, we can check for a label first, and if it doesn't exist, fall back to the value from **Site Settings**.

```php
// 1. Get the default from Site Settings (or a hardcoded string)
$default_text = get_option('site_footer_text', '&copy; 2026 Core CMS');

// 2. Check if a Label exists to override it
// If 'footer_text' label exists, it wins. If not, $default_text is used.
$footer_text = get_label('footer_text', $default_text);

echo $footer_text;
```

### Why is this powerful?
1.  **Safety**: You can experiment with changing text in the Label Editor. If you delete the label, the site instantly reverts to the safe default.
2.  **Separation**: Keep structural defaults in Settings/Code, and use Labels for specific tweaks or translations.