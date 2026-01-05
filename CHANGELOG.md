# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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