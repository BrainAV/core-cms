# 🤖 Gemini Playground & Prompt Guide

This file is your **Command Center**. It contains the "Magic Spells" (Prompts) to guide Gemini through the build process defined in `docs/PROJECT_PLAN.md`.

## 🏁 How to Use This Guide
1.  **Copy** the "Context Setter" below.
2.  **Paste** it at the start of every new chat session.
3.  **Select** the specific prompt for the Phase you are working on.

---

## ⚡ Quick Sync & Workflow

### 1. Start of Session (The "Quick Sync")
*Use this to instantly load the project context:*
> "Please read all the project context files (`.gemini/CONTEXT.md`, `.github/ROADMAP.md`, `.github/CONTRIBUTING.md`, `docs/PROJECT_PLAN.md`) to get in sync with the current state of the project."

### 2. Feature Development
> "Let's implement the '[Feature Name]' feature from the roadmap."
> "I have an idea for a new feature: '[Description]'. Please add it as a '[To Do]' item to the roadmap."

### 3. Bug Fixes
> "I've found a bug: [Describe bug]. Please add it to the roadmap as a high-priority fix, and then let's start working on it."

### 4. Documentation
> "Please update the `CHANGELOG.md` to reflect the recent changes we've made."
> "Let's review all the documentation files to ensure they are consistent and up-to-date."
> "Summarize the changes we made in this session for a Git commit message. Follow the Conventional Commits format."

### 5. Release
> "Let's release version vX.X.X. Please update the `CHANGELOG.md` by moving the `[Unreleased]` items to a new `[vX.X.X]` section."

---

## 🧠 Manual Context Setter (Fallback)
*If the Quick Sync doesn't work, paste this:*
> "You are an expert PHP developer building a lightweight, modular CMS called 'Core CMS'.
> **Architecture:** PHP 8.3, MySQL (PDO), Procedural/Simple OOP.
> **Structure:** Core system + Plugins (Event Planner is a plugin).
> **Current Goal:** We are working on Phase 5 (AI Integration).
> **Style:** Secure, clean, and well-commented code."
---

## 📅 Phase 1: The Foundation

### 1. Database Setup
> "Generate the `db/schema.sql` file. We need tables for:
> 1. `users` (id, email, password_hash, role)
> 2. `posts` (id, title, slug, content, author_id, type ['post','page'], created_at)
> 3. `options` (key, value)
> 4. `menus` (id, name, slug)
> 5. `menu_items` (id, menu_id, label, url, parent_id, sort_order)
> 6. `events` (id, title, start_date, end_date, location, capacity)
> Use proper constraints and UTF8mb4."

### 2. Configuration & DB
> "Step 1: Create `config/config.php`. Define `ROOT_PATH`, set `date_default_timezone_set()`, and configure error reporting based on a `DEBUG_MODE` constant.
> Step 2: Create `config/db.php`. Require `config.php`. Create a `get_db_connection()` function using PDO. Use environment variables or a separate `config/db_creds.php` file."

### 3. The Skeleton
> "Create a basic `index.php`. It should:
> 1. Require `config/db.php`.
> 2. Test the database connection.
> 3. Display a simple HTML 'Under Construction' page."

---

## 🔐 Phase 2: Admin & Auth

### 1. Login System
> "Create `admin/login.php`. It needs a secure HTML form. On POST, validate the email/password against the `users` table using `password_verify`. If successful, set `$_SESSION['user_id']`."

### 2. Admin Dashboard
> "Create `admin/index.php`. Add a check at the top: if the user is NOT logged in, redirect to `admin/login.php`. Otherwise, show a dashboard with links to 'Posts' and 'Events'."

### 3. CRUD System (Posts)
> "Create `admin/posts.php`. It should list all posts from the database in a table. Include columns for Title, Author, Status, and Date. Add 'Edit' and 'Delete' buttons."
> "Create `admin/post-edit.php`. It needs a form to Create or Update a post. Fields: Title, Slug, Content (Textarea), Status. Handle the POST request to INSERT or UPDATE the `posts` table."

---

## 🧠 Phase 5: AI Integration (Core Intelligence)

### 1. AI Foundation
> "Create `includes/ai.php`. It should define an `AIService` class that uses a Factory pattern to load drivers for different providers (Gemini, OpenAI, Local/Ollama). It needs to handle API Keys and Base URLs (for local)."

### 2. Admin Copilot
> "Update `admin/post-edit.php` to include an 'AI Assist' button next to the editor. Use JavaScript to send the current title/content to an API endpoint (`admin/api/ai.php`) and insert the generated text into Editor.js."

---

## 🔌 Phase 6: Plugins & Expansion

### 1. Plugin System
> "I want to build the Event Planner as a plugin. Create a folder `plugins/event-planner`. Create a file `plugins/event-planner/init.php` that defines a function `register_event_routes()`."

### 2. Event Management
> "Create a CRUD interface for Events in `plugins/event-planner/admin/events.php`. I need to be able to Add, Edit, and Delete events from the `events` table."

---

## 📝 Playground Notes
*Use this space below to paste code snippets, error logs, or ideas you want to save for later.*