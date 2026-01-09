# 📝 Developer Scratchpad

Use this file for:
- Temporary to-do lists for the current coding session.
- Brainstorming logic before writing code.
- Storing error messages or debug output.

## 🚧 Current Session: Phase 4 (Expansion)

*   [ ] **Step 1:** Create `categories` and `post_tags` tables (Database Update).
*   [ ] **Step 2:** Update `admin/post-edit.php` to save Categories/Tags.
*   [ ] **Step 3:** Create `admin/media.php` (Basic File Upload).

## 🧠 Brainstorming / Notes

*   **Editor Strategy:** User likes WP Block Editor. For MVP, stick to HTML `<textarea>`. In Phase 4, investigate **Editor.js** to store content as JSON blocks.
*   **User Management:**
    *   *Note: Event Roles (Vendor/Client) moved to `docs/plugins/EVENT_PLANNER.md`.*
    *   Add global setting: `users_can_register` (Boolean).
    *   *Note: Engagement ideas moved to `docs/plugins/ENGAGEMENT.md`.*
    *   *Note: Branding & Philosophy moved to `docs/BRANDING.md`.*
*   **Plugin Architecture:**
    *   **Discovery:** Folder existence in `/plugins/` defines availability.
    *   **Business Model:** Engagement Pack = Free; Event Planner = Paid Upgrade.
    *   **Installation Strategy (Non-Destructive):**
        *   **Concept:** "Soft Installation" via Database flags, not file writes.
        *   **Discovery:** `scandir('/plugins')` to find available modules.
        *   **Activation:** Toggle button in Admin -> Updates `options` table (`active_plugins` JSON).
        *   **Loading:** `index.php` fetches `active_plugins` and includes them dynamically.
        *   **Setup:** Check for `install.php` on activation and run it once to set up DB tables.
    *   **Admin Organization (The "Proxy" Pattern):**
        *   **Isolation:** Plugin admin files stay in `plugins/{slug}/admin/`.
        *   **Menu Registration:** Plugins provide a `menu.php` returning link data.
        *   **Routing:** Admin sidebar dynamically includes these links.
        *   **Access:** Admin pages are accessed via `plugins/{slug}/admin/{page}.php` (with auth checks).
*   **Hardware / IoT Vision:**
    *   **Local Host:** Run Core CMS on Raspberry Pi 5 for in-store management.
    *   **Digital Signage:** Use CMS as a headless content server for store displays.
    *   **Hotspot:** Manage guest WiFi login pages via the CMS.
*   **Content Hierarchy:**
    *   **Concept:** Pages/Posts can have children (Sub-pages).
    *   **Requirement:** Add `parent_id` to `posts` table.
    *   **Feature:** Breadcrumbs generator (e.g., Home > Parent > Child).
