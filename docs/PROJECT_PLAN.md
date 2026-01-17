# Project Plan: Core CMS (Event Planner Edition)

## 1. Vision & Goals

* **Lightweight Core:** The base system should be fast and have zero bloat.
* **Modular Growth:** Features (like comments or the **Event Planner**) are added as separate modules/plugins.
* **Security First:** Use PDO for all DB interactions and modern password hashing.
* **AI-Native:** Future-proof architecture designed to integrate LLMs for content and site management.
* **Developer Friendly:** Clean code that Gemini can easily interpret and extend.

## 2. Technical Architecture

* **Language:** PHP 8.3+
* **Database:** MySQL (MariaDB)
* **Frontend:** Clean HTML5/CSS3 (initially no heavy frameworks).
* **Pattern:** Procedural or Simple OOP (to keep entry barrier low).

### 2.1 Templating Strategy
*   **Engine:** Native PHP (no Twig/Blade initially to keep it light).
*   **Global Variables:** `$site_title`, `$site_desc`, `$current_user` loaded from `options` table.
*   **Structure:** `templates/header.php`, `templates/footer.php`, `templates/page.php`.
*   **Menus:** Dynamic injection based on database entries.

### 2.2 Configuration Strategy
*   **Timezone:** Enforce strict timezone settings (e.g., `date_default_timezone_set()`) in a global config to ensure Event times are accurate.
*   **Paths:** Define `ROOT_PATH` and `BASE_URL` constants to prevent broken links in subdirectories.
*   **Error Handling:** Strict separation between Development (Show Errors) and Production (Log Errors).

### 2.3 Folder Structure
```text
/core-cms
├── /admin            # Admin Dashboard files
├── /assets           # CSS, JS, Images
├── /config           # Database & Site settings
├── /docs             # Manuals & Planning
│   └── README.md     # Documentation Index
├── /db               # Database files (schema.sql)
├── /includes         # Reusable PHP logic (functions, classes)
├── /plugins          # Modular extensions (Event Planner lives here)
├── /templates        # Frontend layout files
├── /uploads          # User uploaded media (Images, etc.)
├── .htaccess         # URL rewriting (Pretty URLs)
└── index.php         # Main entry point
```

## 3. Development Phases

### Phase 1: Foundation (The Skeleton)

* [x] Setup Git repository and `.gitignore`.
* [x] Define `db/schema.sql` (Users, Posts, Options, Events).
* [x] Create `config/db.php` (The PDO connection handler).
* [x] Build a basic `index.php` to display "Latest Posts."

### Phase 2: The Command Center (Admin MVP)

* [x] **Authentication:** Secure Login/Logout system.
* [x] **Dashboard:** A simple landing page for logged-in admins.
* [x] **CRUD System:**
    * [x] Create new posts (Classic Editor initially).
    * [x] Read/List existing posts.
    * [x] Update (Edit) posts.
    * [x] Delete (Archive) posts.
* [x] **Documentation:** Draft `docs/ADMIN_MANUAL.md` covering Login and Dashboard.

### Phase 3: URL & Experience (Routing)

* [x] Implement "Pretty URLs" via `.htaccess` (e.g., `/post/my-title` instead of `post.php?id=1`).
* [x] Basic Theming: Separate the logic from the HTML (header.php, footer.php).
* [x] **Menu System:** Database-driven navigation menus.
* [x] **Breadcrumbs:** Helper function to display path hierarchy.

### Phase 4: Expansion (The "Drupal-Lite" Phase)

* [x] **Categories/Tags:** One-to-many relationships.
* [x] **Media Manager:** Simple image uploading for posts.
* [x] **User Roles:** Editor vs. Admin permissions.
* [x] **Advanced Editor:** Implement **Editor.js** (JSON storage) with Markdown shortcuts.
* [x] **Theme Manager:** Ability to switch between different template sets.
* [x] **User Profile:** Allow users to manage their own settings (Name, Email, Password).
* [x] **Site Identity:** Manage Site Title, Logo URL, and Footer Content via Settings.
* [x] **Scroll to Top:** Configurable button (Position, Style, Color) to scroll to top.
* [x] **Label Editor:** Customize UI text strings.
* [x] **Menu Enhancements:** Link by ID and manual reordering.
* [x] **Dynamic Sitemap:** Auto-generate sitemap.xml for SEO.

### Phase 5: AI Integration (Corex Intelligence)

* [x] **Admin Copilot:** An admin-side helper to draft posts or summarize data.
* [ ] **Frontend Assistant:** A chat widget that uses the configured AI Provider (Gemini, OpenAI, Local) to answer visitor questions.
* [ ] **Generative UI (Experimental):** An API endpoint allowing AI to generate and save HTML/CSS layouts to a `templates/custom` folder ("Coding itself").

### Phase 6: Plugins & Expansion (Event Planner)

* [ ] **Plugin Architecture:** Create a simple hook system in `/includes` to allow plugins to inject content.
* [ ] **Event Data:** Build the `events` table and the admin interface to manage them within `/plugins/event-planner`.
* [ ] **Frontend Display:** Create a specific page or shortcode to list upcoming events.
* [ ] **Event Agent:** Allow the AI Assistant to query the `events` table and "book" spots via function calling.
* [ ] **Documentation:** Add "Event Management" section to `docs/ADMIN_MANUAL.md`.

### Phase 7: Commerce, IoT & Plugins
* [ ] **Contact Form:** Simple form builder with email notifications.

---

## 4. Database Roadmap

| Table | Purpose | Key Columns |
| --- | --- | --- |
| **users** | Admin access | `id`, `user_email`, `user_pass`, `display_name`, `role` |
| **posts** | Blog & Pages | `id`, `author_id`, `post_title`, `post_slug`, `post_content`, `post_status`, `post_type` ('post'/'page'), `created_at` |
| **options** | Site config | `option_id`, `option_name`, `option_value` |
| **menus** | Menu Containers | `id`, `name`, `slug` (e.g., 'main-nav') |
| **menu_items** | Links | `id`, `menu_id`, `label`, `url`, `parent_id`, `sort_order` |
| **events** | Event Plugin | `id`, `title`, `start_date`, `end_date`, `location`, `capacity`, `status` |

---

## 5. Maintenance & Workflow

* **Branching:** Use feature branches (e.g., `feature-login`) before merging to `main`.
* **Documentation:** Update the `/docs` folder as new modules are created.
* **Gemini Prompts:** Use the plan to keep Gemini on track.
