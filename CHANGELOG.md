# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.3.0] - 2026-01-11

### Added
- **Site Identity Settings**:
  - `admin/settings.php`: Added fields for Site Title, Logo URL, and Footer Text.
  - `templates/header.php`: Updated to use dynamic title and logo.
  - `templates/footer.php`: Updated to use dynamic footer text.
  - `docs/SETTINGS.md`: Documentation for global settings.
- **Marketing Assets**:
  - `docs/blog/BLOG_POST_ANNOUNCEMENT.md`: Draft blog post for public release.
  - `docs/SOCIAL_PREVIEW.md`: AI prompt for generating the repo social image.
- **Media Metadata**:
  - `admin/media-edit.php`: Interface to edit Alt Text, Title, and Description.
  - `admin/media.php`: Added "Edit" button to media items.
  - `db/schema.sql`: Added columns to `media` table.
- **Editor.js Image Tool**:
  - `admin/api/upload.php`: API endpoint for handling image uploads from the editor.
  - `admin/post-edit.php`: Configured Image Tool.
- **Scroll to Top**:
  - `admin/settings.php`: Added configuration for position, shape, and color.
  - `templates/footer.php`: Injected button logic.
- **User Roles & Management**:
  - `admin/users.php`, `admin/user-edit.php`: Admin interface to manage users.
  - Implemented Role-Based Access Control (RBAC) for Admin, Editor, and Subscriber.
  - Secured sensitive admin pages (`settings.php`, `menus.php`) against non-admins.
- **Theme System**:
  - `admin/themes.php`: Manager to switch between active themes.
  - `includes/functions.php`: Added `get_theme_path()` for dynamic template loading.
  - `docs/THEMES.md` & `docs/THEME_BUILDER.md`: Documentation for creating themes.
  - `themes/darkmode/`: Added a sample "Dark Mode" theme to verify the system.
- **Label Editor**:
  - `admin/labels.php`: Interface to customize UI text without code changes.
  - `includes/functions.php`: Added `get_label()` helper.
- **Menu Enhancements**:
  - Added manual reordering via sort order inputs.
  - Implemented "Link by ID" to prevent broken links when slugs change.

### Changed
- **Admin Dashboard**: Refactored Quick Links into a modern card grid layout.
- **Content Lists**: Made Post/Page titles clickable for quick frontend preview.
- **Documentation**: Updated `README.md` and `ADMIN_MANUAL.md` with new features.

## [0.2.0] - 2026-01-09

### Added
- **Breadcrumbs System**:
  - `admin/settings.php`: Interface to toggle breadcrumbs and customize separators.
  - `includes/functions.php`: Added `render_breadcrumbs()` and option helpers.
  - `docs/BREADCRUMBS.md`: Documentation.
- **Admin UI Refactoring**:
  - Created `admin/includes/header.php` and `admin/includes/footer.php` for consistent layout.
  - Updated all admin pages to use the shared templates.
  - Fixed session management and path resolution in admin files.
- **Deployment**:
  - Added `docs/DEPLOYMENT.md` with FTP exclusion list.
- **Pages Management**:
  - `admin/pages.php`: Dedicated list view for managing static pages.
  - Updated `admin/post-edit.php` to support both Posts and Pages.
- **Home Page Logic**:
  - Added `is_home` flag to `posts` table.
  - Implemented "Set as Homepage" functionality in Page Editor.
  - Updated `index.php` to render designated static homepage.
- **Menu System Upgrades**:
  - Added support for Sub-menus (nested dropdowns).
  - Added "Add Content" dropdown to easily link existing Posts/Pages.
  - Improved CSS styling for navigation (`.site-menu`).
- **Taxonomy System (Categories & Tags)**:
  - `db/schema.sql`: Added tables for categories, tags, and pivot tables.
  - `admin/categories.php`: Manager for creating, editing, and deleting categories.
  - `admin/post-edit.php`: Added UI to assign categories and tags to posts.
  - `docs/CATEGORIES_TAGS.md`: Documentation for the taxonomy system.
- **Media System**:
  - `admin/media.php`: Library for uploading, viewing, and deleting files.
  - `docs/MEDIA.md`: Documentation and security overview.
  - `uploads/.htaccess`: Security rules to prevent script execution.
- **User Profile System**:
  - `admin/profile.php`: Interface for users to update Name, Email, Password, and Avatar.
  - `docs/PROFILE.md`: Documentation for user settings.
  - `db/schema.sql`: Added `avatar` column to `users` table.
- **Advanced Editor (Editor.js)**:
  - `admin/post-edit.php`: Replaced `<textarea>` with Block Editor (Headers, Lists, Quotes, Code).
  - `includes/functions.php`: Added `render_blocks()` to convert JSON to HTML.
  - `index.php`: Updated frontend to render block content.
  - `assets/css/style.css`: Added styling for code blocks (`pre`, `code`) and editor elements.
  - `docs/EDITOR.md`: Strategy documentation.

### Changed
- `admin/index.php`: Removed placeholder "Manage Events" link.
- `docs/MENUS.md`: Updated documentation to reflect `main-menu` slug usage.
- `.github/ROADMAP.md`: Added "Theme Manager" to Phase 4.

## [0.1.0] - 2026-01-04

### Added
- **Core Foundation**:
  - `db/schema.sql`: Initial database schema for all core tables.
  - `config/config.php`: Global constants and debug mode settings.
  - `config/db.php`: PDO database connection manager.
  - `index.php`: Site entrypoint with database connection test.
- Initial project directory structure.
- `docs/PROJECT_PLAN.md`: Comprehensive roadmap including the Event Planner plugin.
- `docs/README.md`: Documentation index and landing page.
- `docs/ADMIN_MANUAL.md`: Skeleton for the user guide.
- `.gemini/`: Context, Persona, and Prompt guides for AI assistance.
- `.gemini/SCRATCHPAD.md`: Temporary workspace for active development notes.
- `LICENSE.md`: MIT License.
- `.gitignore`: Initial exclusions.
- `config/`: Added `config.php`, `db.php`, and `db_creds-sample.php`.
- `docs/DATABASE.md`: Added database schema dictionary.
- `.github/`: Added `ROADMAP.md`, `STYLE_GUIDE.md`, and `CONTRIBUTING.md`.
- `README.md`: Added main project landing page.
- `admin/login.php`: Initial admin login page with secure authentication (Phase 2).
- `admin/logout.php`: Secure session destruction and redirect.
- **CRUD System**:
  - `admin/posts.php`: List view for posts.
  - `admin/post-edit.php`: Create/Edit form with slug generation.
  - `admin/post-delete.php`: Deletion handler.
- `docs/plugins/`: Added `EVENT_PLANNER.md` and `ENGAGEMENT.md` documentation.
- `install_admin.php`: Setup script to create the initial admin user.
- **Routing System**:
  - `.htaccess`: Apache rewrite rules for Pretty URLs.
  - `templates/404.php`: Custom "Page Not Found" template.
  - `docs/ROUTING.md`: Guide on how routing works.
- **Branding**:
  - `docs/BRANDING.md`: Visual identity guidelines and AI prompts.
- **Menu System**:
  - `admin/menus.php`: Manager for menu containers.
  - `admin/menu-edit.php`: Manager for menu links.
  - `docs/MENUS.md`: Documentation for the menu system.
- `plugins/event-planner/install.php`: Database installer for the Event Planner plugin.

- `docs/USER_ROLES.md`: Defined security roles (Admin, Editor, Subscriber) and plugin roles.

### Changed
- `docs/PROJECT_PLAN.md`: Added Templating and Configuration strategies.
- `.gemini/GEMINI.md`: Added Quick Sync and Workflow prompts.
- **Rebranding**: Renamed project from "Event-Planner CMS" to "**Core CMS**" across all documentation and context files.
- `docs/PROJECT_PLAN.md`: Updated folder structure to `/core-cms` and added Phase 6 (AI Integration).
- `.github/ROADMAP.md`: Added Phase 6 (AI Integration).
- `docs/README.md`: Added "Future Documentation Plan".
- `admin/index.php`: Refactored logout logic to use dedicated `logout.php`.
- `.github/ROADMAP.md`: Marked Auth system as complete.
- `docs/ADMIN_MANUAL.md`: Added instructions for Managing Posts.
- `index.php`: Implemented Front Controller logic for routing.
- `.gemini/`: Renamed `PROMPT.md` to `CONTEXT.md` for clarity.
- `docs/README.md`: Updated documentation index.
- `db/schema.sql`: Removed `events` table (moved to plugin installer).
- `admin/index.php`: Added link to Navigation Menus.
- `docs/plugins/EVENT_PLANNER.md`: Updated schema reference.