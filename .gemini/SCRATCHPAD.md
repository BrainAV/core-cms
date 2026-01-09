# 📝 Developer Scratchpad

Use this file for:
- Temporary to-do lists for the current coding session.
- Brainstorming logic before writing code.
- Storing error messages or debug output.

## 🚧 Current Session: Phase 3 (Routing & Experience)

*   [x] **Routing & Theming**: Completed (v0.1.0).
*   [x] **Step 1:** Create `admin/menus.php` (Manage Menu Containers).
*   [x] **Step 2:** Create `admin/menu-edit.php` (Manage Menu Items).
*   [x] **Step 3:** Update `templates/header.php` to render dynamic menus.
*   [x] **Step 4:** Create `admin/settings.php` and `render_breadcrumbs()` helper.

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



When uploading files from this repository to your live web server / site. A file exclusion list consists of the following:
\System Volume Information\
\$Recycle.Bin\
\RECYCLE?\
\Recovery\
*\thumbs.db
.git\
.gemini\
.github\
db\
docs\
.gitignore
.editorconfig
LICENSE
README.md
CHANGELOG.md
*\db_creds-sample.php

Also these folders are from cpanel if you are syncing the web folder and can be ignored as well:
.well-known\*
cgi-bin\
