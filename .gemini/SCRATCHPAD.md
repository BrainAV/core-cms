# 📝 Developer Scratchpad

Use this file for:
- Temporary to-do lists for the current coding session.
- Brainstorming logic before writing code.
- Storing error messages or debug output.

## 🚧 Current Session: Phase 5 (AI Integration)

*   [x] **Step 1:** Dynamic Sitemap (XML).
*   [x] **Step 2:** Define AI Configuration (Provider Select, API Key, Base URL).
*   [x] **Step 3:** Create `includes/ai.php` (AI Service with Drivers: Gemini, OpenAI, Local).
*   [x] **Step 4:** Build "Admin Copilot" (Drafting assistant in Post Editor).
*   [x] **Fix:** Improved AI response parsing to handle mixed text/JSON output.

## 🧪 AI Testing Plan (Admin Copilot)
*   [ ] **Test 1: Headers & Structure**
    *   Prompt: "Write an article with H2 and H3 headers."
    *   Verify: JSON structure in DB, rendering in Editor.js.
*   [ ] **Test 2: Lists (Ordered & Unordered)**
    *   Prompt: "List 5 fruits." and "Steps to tie a shoe."
    *   Verify: `list` block type, `style` property.
*   [ ] **Test 3: Long Content (Pagination/Chattiness)**
    *   Prompt: "Write a long story about bubblegum."
    *   Verify: Does it duplicate text? Does it break JSON syntax in the middle?
*   [ ] **Test 4: Links & Formatting**
    *   Prompt: "Write a paragraph with bold text and a link to google.com."
    *   Verify: Does it use `<b>` or `**`? Does it use `<a>` or ``?

## � Brainstorming / Notes

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
