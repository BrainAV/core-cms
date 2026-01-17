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

## 5. Enhancement Plan (Gutenberg-like Features)

To better mimic the WordPress Gutenberg experience, we are implementing the following enhancements:

### 5.1 Block Alignment (Left/Center/Right)
*   **Tool**: `editorjs-text-alignment-blocktune`.
*   **Logic**: This adds a `tunes` property to block data. The PHP renderer will check for `tunes.alignment.alignment` and apply corresponding CSS classes (`text-left`, `text-center`, `text-right`).

### 5.2 Multi-Column Layouts
*   **Tool**: `editorjs-columns`.
*   **Logic**: This introduces a `columns` block type which contains nested block arrays.
*   **Renderer**: `render_blocks()` will be updated to handle recursion for nested columns using CSS Grid or Flexbox for the frontend layout.

### 5.3 Containers & Spacing
*   Future blocks may include "Group" containers to allow background colors or padding on a set of blocks.

## 🗺️ Editor Roadmap

This roadmap tracks the "Gutenberg-Quest" to make Core CMS a powerful visual editor.

### ✅ Phase 1: Foundation
- [x] **Base Blocks**: Headers, Lists, Quotes, Code, and Delimiters.
- [x] **Raw HTML**: Ability to paste custom code snippets.
- [x] **Image Tool**: Multi-media support with dynamic uploads to `/uploads`.
- [x] **AI Copilot**: Direct integration with AIService for content generation.

### ✅ Phase 2: Flow & Layout
- [x] **Alignment**: Support for text-alignment tunes (Left, Center, Right).
- [x] **Columns**: Responsive multi-column layout support (2 and 3 columns).
- [x] **Nested Alignment**: Support for block tunes (alignment) inside nested column blocks.
- [x] **Recursive Rendering**: PHP logic to handle nested blocks within columns.

### 🟡 Phase 3: Advanced Structure (Next Up)
- [ ] **Container/Group Block**: Ability to wrap blocks in a `div` with custom background colors and padding.
- [ ] **Tables**: Support for structured data and pricing tables.
- [ ] **Table of Contents**: Auto-generated TOC based on page headers.

### 🔴 Phase 4: Rich Ecosystem (Future)
- [ ] **Embeds**: Native support for YouTube, Twitter/X, and Instagram.
- [ ] **Reusable Blocks**: Save a group of blocks as a "Template" to reuse on other pages.
- [ ] **Frontend Editor**: (Experimental) Direct block editing on the site frontend.
