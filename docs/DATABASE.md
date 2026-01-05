# 🗄️ Database Architecture

This document outlines the schema design for Core CMS.

> **Note:** For the raw SQL queries used to create these tables, refer to [`../db/schema.sql`](../db/schema.sql).

---

## 1. Core System

### `users`
Stores administrators and editors.
*   **Primary Key:** `id`
*   **Security:** Passwords must be hashed using `password_hash()` (Bcrypt/Argon2).
*   **Roles:** Currently supports 'admin' (full access). Future: 'editor', 'author'.

### `posts`
The central content table. We use a **Polymorphic** approach here to keep the DB light.
*   **Primary Key:** `id`
*   **Foreign Key:** `author_id` -> `users.id`
*   **Key Column:** `post_type`
    *   `'post'`: Standard blog entries.
    *   `'page'`: Static content (About, Contact).
*   **Status:** `'published'`, `'draft'`, `'archived'`.

### `options`
Key-value storage for site-wide settings (Site Title, Timezone, etc.).
*   **Structure:** `option_name` (Unique String) | `option_value` (Text).

---

## 2. Navigation System

### `menus`
Containers for navigation lists (e.g., "Main Header", "Footer Links").
*   **Columns:** `id`, `name`, `slug`.

### `menu_items`
The actual links inside a menu.
*   **Foreign Key:** `menu_id` -> `menus.id`
*   **Hierarchy:** `parent_id` allows for dropdowns/nested links.
*   **Ordering:** `sort_order` (Integer) determines display position.

---

## 3. Plugins (Event Planner)

### `events`
Stores event-specific data. This is separate from `posts` to allow for specific columns like dates and capacity without bloating the main content table.
*   **Columns:** `start_date`, `end_date`, `location`, `capacity`.
*   **Future:** Could link to `users` for an attendee list table (`event_attendees`).