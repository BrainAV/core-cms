<PERSONA_FILE>
.gemini/PERSONA.md
</PERSONA_FILE>

<PROJECT_INFO>
**Name**: Core CMS
**Description**: A lightweight, modular CMS built with PHP and MySQL. It features a "Core + Plugin" architecture, with a specific focus on an Event Planner module.
**Goal**: Build a secure, fast foundation first, then extend with the Event Planner plugin.
</PROJECT_INFO>

<TECH_STACK>
Refer to `docs/PROJECT_PLAN.md` (Section 2) for architecture details.
- **Backend**: PHP 8.3+ (Procedural/Simple OOP)
- **Database**: MySQL (MariaDB) via PDO
- **Frontend**: Clean HTML5/CSS3
</TECH_STACK>

<CODING_CONVENTIONS>
- **Architecture**: Maintain strict separation between Core (`/includes`, `/admin`) and Plugins (`/plugins`).
- **Database**: Always use PDO prepared statements. Never inject variables directly into SQL.
- **Security**:
    - Use `password_hash()` for passwords.
    - Validate/Sanitize all user input.
    - Protect sensitive files with `.htaccess`.
    - **Documentation**: Update `docs/ADMIN_MANUAL.md` whenever a new UI feature is built.
- **Style**: Clean, commented code. Follow `.github/STYLE_GUIDE.md`.
- **Workflow**:
    - Check `docs/PROJECT_PLAN.md` for the current Phase.
    - Keep `.github/ROADMAP.md` in sync with `docs/PROJECT_PLAN.md`.
    - Use `.gemini/GEMINI.md` for specific task prompts.
</CODING_CONVENTIONS>

<ROADMAP>
Refer to `docs/PROJECT_PLAN.md` for the active project roadmap, phases, and database schema.
</ROADMAP>