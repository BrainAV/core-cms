# 🔌 Plugin: Event Planner

## 1. Overview
The **Event Planner** is the flagship plugin for Core CMS. It transforms the simple blog engine into a management system for events, vendors, and clients.

**[View Active Roadmap](EVENT_PLANNER_ROADMAP.md)**

## 2. Architecture
*   **Location**: `/plugins/event-planner/`
*   **File Structure**:
    *   `install.php`: Database setup.
    *   `admin/`:
        *   `menu.php`: Returns the sidebar link definition.
        *   `events.php`: The main list view.
        *   `event-edit.php`: The editor form.
*   **Database**: Uses the `events` table (and potentially `bookings` in the future).
*   **Integration**: Hooks into the Core CMS routing and admin menu.

## 3. User Roles & Permissions
*(Moved from Scratchpad)*

We need to expand the core `users` system to handle specific event-based roles:
*   **Client**: The user planning the event (e.g., the Bride/Groom or Corporate Planner).
*   **Vendor**: Service providers looking for work (e.g., DJ, Caterer, Photographer).
*   **Admin**: The site owner managing the platform.

## 4. Feature Wishlist
*   **Vendor Marketplace**: Allow Clients to search for Vendors.
*   **Data Mining**:
    *   "Subscribe to Newsletter" checkbox on registration.
    *   User comments on posts/events for engagement.

## 5. Database Schema (Current)
Defined in `plugins/event-planner/install.php` (removed from core schema):
*   `id` (INT)
*   `title` (VARCHAR)
*   `start_date` (DATETIME)
*   `end_date` (DATETIME)
*   `location` (VARCHAR)
*   `capacity` (INT)
*   `status` (VARCHAR)