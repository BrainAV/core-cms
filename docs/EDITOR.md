# 📝 Editor Strategy: Block-Based (Editor.js)

## 1. The Decision
We have chosen **Editor.js** as the content editor for Core CMS.

### Why?
1.  **Clean Data**: It saves content as structured JSON, not messy HTML blobs. This makes it API-ready and AI-friendly.
2.  **Modern UI**: Block-based editing is the industry standard (Notion, WordPress Gutenberg).
3.  **Markdown Support**: It supports "Markdown Shortcuts" (typing `# ` auto-converts to a Header), satisfying power users.

## 2. Architecture

### Database
*   The `posts` table's `post_content` column (LONGTEXT) will store the **JSON string**.

### Admin (Writing)
*   We replace the `<textarea>` in `admin/post-edit.php` with the Editor.js UI.
*   On form submit, JavaScript serializes the blocks to JSON and puts it into a hidden input field.

### Frontend (Reading)
*   We need a PHP helper function `render_content($json)` in `includes/functions.php`.
*   This function iterates through the JSON blocks and outputs semantic HTML (`<h1>`, `<p>`, `<img>`).

## 3. Markdown Integration
The user requested a "Markdown Block".
*   **Native**: Editor.js handles standard Markdown syntax (headers, lists, bold, code) via auto-conversion.
*   **Raw HTML/Markdown**: We can include the `Raw` tool for pasting pure HTML or Markdown snippets if needed.

## 4. Implementation Steps
1.  **Assets**: Download/Link Editor.js and essential tools (Header, List, Image, Quote).
2.  **Admin UI**: Update `admin/post-edit.php`.
3.  **Renderer**: Create the PHP rendering logic.