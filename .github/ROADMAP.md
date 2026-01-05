# 🗺️ Project Roadmap

This document tracks the active development status of Core CMS.

## 🟢 Phase 1: Foundation (The Skeleton)
- [x] Setup Git repository and `.gitignore`.
- [x] Define `db/schema.sql` (Users, Posts/Pages, Menus, Options, Events).
- [x] Create `config/db.php` (PDO connection).
- [x] Build basic `index.php` (Hello World).

## 🟢 Phase 2: Admin MVP
- [x] **Auth**: Secure Login/Logout system.
- [x] **Dashboard**: Admin landing page.
- [x] **CRUD**: Create, Read, Update, Delete Posts.
- [x] **Docs**: Update Admin Manual.

## 🔴 Phase 3: Routing & Experience
- [x] Pretty URLs (`.htaccess`).
- [x] **Router**: Update `index.php` to handle dispatching.
- [x] **404 Page**: Custom "Page Not Found" handler.
- [x] Basic Theming (Header/Footer separation).
- [ ] **Menus**: Database-driven Menu System.

## 🔴 Phase 4: Expansion
- [ ] Categories & Tags.
- [ ] Media Manager (Image Uploads).
- [ ] User Roles.

## 🔴 Phase 5: Event Planner Plugin (Side Quest)
- [ ] Plugin Architecture (Hooks).
- [ ] Event Database Table.
- [ ] Admin Event Management (CRUD).
- [ ] Frontend Event Display.

## 🟣 Phase 6: AI Integration
- [ ] **Frontend Chat**: Visitor Q&A Widget.
- [ ] **Event Agent**: AI Booking via Function Calling.
- [ ] **Admin Copilot**: Content generation helper.
- [ ] **Generative UI**: AI-created layouts (Experimental).

---

## Legend
*   🟢 **Active**: Currently in progress.
*   🟡 **Next Up**: Planned for immediate follow-up.
*   🔴 **Future**: Backlog items.
*   ✅ **Done**: Completed.