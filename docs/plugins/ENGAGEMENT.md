# 🔌 Plugin: Engagement Pack

## 1. Overview
The **Engagement Pack** is designed to "level up" the Core CMS from a static publishing engine to a dynamic community platform. It bridges the gap between the Base Model and the complex Event Planner.

## 2. Purpose
*   **Keep Core Light**: Moves frontend user management and comments out of the main kernel.
*   **Data Collection**: Acts as the first step in gathering user data (leads) before they become Event Clients.

## 3. Features
*   **Frontend Authentication**:
    *   Registration Form (with "Subscribe to Newsletter" checkbox).
    *   Login/Logout for non-admin users.
    *   Profile Management.
*   **Comment System**:
    *   Allow logged-in users to comment on Posts.
    *   Moderation queue in Admin.
*   **Newsletter Integration**:
    *   Simple database collection of emails.
    *   Export to CSV for external tools (Mailchimp, etc.).

## 4. Database Requirements
*   **`comments` table**: `id`, `post_id`, `user_id`, `content`, `status`, `created_at`.
*   **`users` table extension**: Add `newsletter_opt_in` (BOOLEAN).