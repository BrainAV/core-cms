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

## ✅ Phase 3: Routing & Experience
- [x] Pretty URLs (`.htaccess`).
- [x] **Router**: Update `index.php` to handle dispatching.
- [x] **404 Page**: Custom "Page Not Found" handler.
- [x] Basic Theming (Header/Footer separation).
- [x] **Menus**: Database-driven Menu System.
- [x] **Breadcrumbs**: Helper function and Settings.

## 🟢 Phase 4: Expansion
- [x] Categories & Tags.
- [x] Media Manager (Uploads & Deletion).
- [x] Media Metadata (Alt Text, Captions).
- [x] User Roles.
- [x] Theme Manager.
- [x] Advanced Editor (Editor.js Integration).
- [x] Menu Manager Enhancements (Link by Key/ID).
- [x] Site Identity (Title, Logo, Footer Text).
- [x] Label Editor.
- [x] Scroll to Top (Settings).
- [x] User Profile / Control Panel.
- [x] Dynamic Sitemap (XML).

## ✨ Editor Side Quest (The Gutenberg Path)
- [x] **Base Blocks**: H1-H6, Lists, Quotes, Code, Raw HTML.
- [x] **Media**: Image uploads with caption support.
- [x] **Layout**: Multi-column responsive grids.
- [x] **Flow**: Block Alignment (Left, Center, Right).
- [ ] **Style**: Group/Container blocks with backgrounds.
- [ ] **Data**: Structured Table blocks.

## ✅ Phase 5: AI Integration (Core Intelligence)
- [x] **Admin Copilot**: Content generation helper.
- [x] **AI Refinement**: Improve JSON parsing, handle lists/headers, and reduce hallucinations.
- [ ] **Frontend Chat**: Visitor Q&A Widget.
- [ ] **Generative UI**: AI-created layouts (Experimental).

## 🟢 Phase 6: Plugins & Expansion
- [ ] Plugin Architecture (Hooks).
- [ ] Event Database Table.
- [ ] Admin Event Management (CRUD).
- [ ] Frontend Event Display.
- [ ] **Event Agent**: AI Booking via Function Calling.

## 🔵 Phase 7: Commerce, IoT & Plugins
- [ ] **Store / E-commerce**:
    - [ ] PayPal Standard & Stripe Integration.
    - [ ] Printful Merch connection.
    - [ ] Simple Product Manager (Books, Clothing, Crystals).
    - [ ] **Contact Form Plugin**: Simple form builder with email notifications.
- [ ] **Digital Signage**:
    - [ ] TV/Screen Controller (Headless Mode).
    - [ ] Digital Menus (linked to Products).
    - [ ] WooCommerce API Integration (Stock/Price sync).
- [ ] **Network Management**:
    - [ ] WiFi Hotspot Manager (Captive Portal).
    - [ ] Local Host Optimization (Raspberry Pi 5 support).

---

## Legend
*   🟢 **Active**: Currently in progress.
*   🟡 **Next Up**: Planned for immediate follow-up.
*   🔴 **Future**: Backlog items.
*   ✅ **Done**: Completed.