# 🎨 Coding Style Guide

To maintain a clean and secure codebase, please adhere to the following styles.

## 🐘 PHP Standards

### General
*   **Version**: PHP 8.3+.
*   **Indentation**: 4 Spaces.
*   **Tags**: Always use `<?php ?>`. Never use short tags `<? ?>`.

### Naming Conventions
*   **Variables**: `snake_case` (e.g., `$user_id`, `$event_title`).
*   **Functions**: `snake_case` (e.g., `get_db_connection()`).
*   **Classes**: `PascalCase` (e.g., `DatabaseHelper`).
*   **Constants**: `UPPER_CASE` (e.g., `DB_HOST`).

### Security (Critical)
*   **SQL**: ALWAYS use PDO Prepared Statements.
    *   ❌ `query("SELECT * FROM users WHERE id = $id")`
    *   ✅ `prepare("SELECT * FROM users WHERE id = ?")`
*   **Output**: Escape output with `htmlspecialchars()` to prevent XSS.

---

## 🌐 HTML & CSS

### HTML
*   Use semantic tags (`<header>`, `<nav>`, `<main>`, `<footer>`, `<article>`).
*   Indent with 4 spaces.
*   Always include `alt` attributes for images.

### CSS
*   **Naming**: Use BEM (Block Element Modifier) where possible.
    *   `.card` (Block)
    *   `.card__title` (Element)
    *   `.card--featured` (Modifier)
*   **Formatting**:
    ```css
    .selector {
        property: value;
    }
    ```

---

## 🗄️ Database (SQL)
*   **Table Names**: Plural, lowercase (e.g., `users`, `posts`, `events`).
*   **Keywords**: UPPERCASE (e.g., `SELECT * FROM users`).

---

## 📁 File Naming
*   **Directories**: `lowercase/` (e.g., `admin/`, `includes/`).
    *   *Reason*: Consistency across operating systems.
*   **Server Files** (PHP, HTML, CSS, JS): `lowercase.ext` (e.g., `index.php`, `style.css`).
    *   *Reason*: Linux file systems are case-sensitive; this prevents routing errors.
*   **Documentation**: `UPPERCASE.md` (e.g., `README.md`, `PROJECT_PLAN.md`).
    *   *Reason*: Makes documentation stand out visually in file explorers.