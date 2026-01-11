# Contributing Guide

Thank you for your interest in contributing to **Core CMS**! This guide provides instructions for developers (both human and AI) to ensure consistency, security, and modularity.

## Project Vision

*   **Lightweight Core**: The base system must remain fast and bloat-free.
*   **Modular Growth**: All non-core features (like the Event Planner) must be built as **Plugins**.
*   **Security First**: Strict adherence to PDO prepared statements and input sanitization.

## Tech Stack

*   **Backend**: PHP 8.3+ (Procedural Core with OOP Helpers).
*   **Database**: MySQL / MariaDB (via PDO).
*   **Frontend**: Clean HTML5, CSS3 (No heavy frameworks initially).
*   **Architecture**: Custom "Core + Plugins" system.

## AI Collaboration

*   **Primary Assistant**: Google Gemini.
*   **Context**: Always refer to the `.gemini/` directory for the Persona (`PERSONA.md`), System Context (`CONTEXT.md`), and Task Prompts (`GEMINI.md`).
*   **Workflow**: Use `.gemini/SCRATCHPAD.md` for brainstorming before writing code.

## Development Workflow

1.  **Initial Setup**:
    *   Clone the repository.
    *   Create `config/db_creds.php` from the sample file.
    *   Import `db/schema.sql` into your local database.
2.  **Branching**: Use feature branches (e.g., `feature/login-system`, `plugin/event-planner`).
3.  **Documentation**:
    *   Update `docs/PROJECT_PLAN.md` when completing phases.
    *   Ensure `.github/ROADMAP.md` stays in sync with `docs/PROJECT_PLAN.md`.
    *   Update `docs/ADMIN_MANUAL.md` when adding UI features.
    *   Update `CHANGELOG.md` for notable changes.
    *   Refer to `docs/THEME_BUILDER.md` when creating or modifying themes.

## Coding Standards

### Repository Structure
*   `/admin`: Protected dashboard files.
*   `/includes`: Core PHP logic (DB connection, helper functions).
*   `/plugins`: Standalone modules (e.g., `/plugins/event-planner`).
*   `/templates`: Frontend views.
*   `/db`: Database schemas (See `docs/DATABASE.md` for dictionary).

### Security Guidelines
*   **SQL**: NEVER inject variables directly into SQL strings. Always use PDO Prepared Statements.
*   **Passwords**: Use `password_hash()` and `password_verify()`.
*   **Input**: Sanitize all `$_POST` and `$_GET` data.
*   **Access Control**: Ensure all admin pages check for a valid session.

### Style Guide
*   **Full Standards**: Refer to [`STYLE_GUIDE.md`](STYLE_GUIDE.md) for detailed rules.
*   **PHP**: Follow PSR-12 where possible. Use descriptive variable names (`$user_id` not `$u`).
*   **HTML**: Semantic tags (`<header>`, `<main>`, `<footer>`).
*   **CSS**: Keep it simple. Use CSS variables for theming.
*   **Files**: Server files are `lowercase.ext`; Documentation is `UPPERCASE.md`.

## Commit Messages
Follow Conventional Commits:
*   `feat:` (New feature or plugin)
*   `fix:` (Bug fix)
*   `docs:` (Documentation update)
*   `refactor:` (Code cleanup)
*   `style:` (Formatting, missing semi-colons, etc.)

## Release Process

1.  **Update Changelog**: Move items from `[Unreleased]` to the new version number.
2.  **Tag Release**: Use semantic versioning (e.g., `v1.0.0`).

---

*Last Updated: Phase 2 (Admin MVP)*