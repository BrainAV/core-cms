# 👥 User Roles & Permissions

## 1. Overview
Core CMS uses a role-based access control (RBAC) system. The `users` table contains a `role` column (VARCHAR) that determines what a user can do.

## 2. Core Roles

### 👑 Administrator (`admin`)
*   **Access**: Full System Access.
*   **Capabilities**:
    *   Manage Settings & Configuration.
    *   Install/Activate Plugins.
    *   Manage Users (Add/Edit/Delete).
    *   Manage Menus & Themes.
    *   Manage Labels (UI Text).
    *   All Editor capabilities.

### ✏️ Editor (`editor`)
*   **Access**: Content Management.
*   **Capabilities**:
    *   Create, Edit, Delete Posts & Pages.
    *   Manage Categories/Tags.
    *   Upload Media.
    *   *Cannot* access Settings, Plugins, or User Management.

### 👤 Subscriber (`subscriber`)
*   **Access**: Frontend Only.
*   **Capabilities**:
    *   Login to the frontend (via Engagement Pack).
    *   Post Comments.
    *   Manage own Profile.
    *   *No Admin Dashboard access (except Profile).*

## 3. Plugin Roles (Event Planner)

These roles are added when the **Event Planner** plugin is active.

### 📅 Client (`client`)
*   **Context**: A user planning an event.
*   **Capabilities**:
    *   Create/Manage their specific Event.
    *   View Vendor proposals.

### 💼 Vendor (`vendor`)
*   **Context**: A service provider (DJ, Caterer).
*   **Capabilities**:
    *   Manage their Vendor Profile.
    *   Bid on Events.

## 4. Technical Implementation

### Database
The `users` table has a `role` column.
*   **Default for new registrations**: `subscriber` (Safe default).
*   **Default for first installer**: `admin`.

### Access Checks (Code Pattern)
Admin pages should check the role stored in the session:
```php
if (!in_array($_SESSION['user_role'], ['admin', 'editor'])) {
    // Redirect non-privileged users
    header("Location: /"); 
    exit;
}
```