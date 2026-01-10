# 🏷️ Categories & Tags

## 1. Overview
Core CMS supports two primary ways to organize content: **Categories** and **Tags**.

### Categories
*   **Hierarchical**: Can have parents and children (e.g., `News > Technology > AI`).
*   **Broad**: Used for broad grouping of topics.
*   **Required**: Ideally, every post should belong to at least one category (defaulting to "Uncategorized").

### Tags
*   **Flat**: No hierarchy. Just keywords.
*   **Specific**: Used for specific details (e.g., `#php`, `#update`, `#v1.0`).
*   **Optional**: Posts don't need tags.

## 2. Database Structure
The system uses a normalized schema with pivot tables to handle relationships.

*   `categories`: Stores category names, slugs, and hierarchy (`parent_id`).
*   `tags`: Stores tag names and slugs.
*   `post_categories`: Pivot table linking Posts to Categories (Many-to-Many).
*   `post_tags`: Pivot table linking Posts to Tags (Many-to-Many).

## 3. Usage
*(Coming in Phase 4)*

1.  **Managing Categories**: Admin > Posts > Categories.
2.  **Assigning to Posts**: In the Post Editor sidebar.
3.  **Frontend**: Clicking a category link (e.g., `/category/news`) will filter posts.