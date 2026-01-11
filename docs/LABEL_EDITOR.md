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