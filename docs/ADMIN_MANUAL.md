# 📘 Core CMS: Admin Manual

Welcome to **Core CMS**. This guide will help you manage your website content and events.

---

## 1. Getting Started

### Logging In
1.  Navigate to `/admin` in your browser.
2.  Enter your **Email** and **Password**.
3.  Click **Login**.

> **Note:** If you lose your password, please contact the database administrator as there is currently no self-service reset function.

### The Dashboard
Once logged in, you will see the **Command Center**.
*   **Welcome Card**: Confirms your identity.
*   **Quick Links**: Fast access to manage Posts, Pages, and Settings.

### Logging Out
To securely end your session, click the **Logout** link located in the top header. This will destroy your session and return you to the login screen.

---

## 2. Managing Content (Posts)

### Viewing Posts
Click **Manage Posts** on the Dashboard to see a list of all content. The table shows the Title, Author, Status, and Date.

### Creating & Editing
1.  Click **+ Add New Post** (or **Edit** next to an existing post).
2.  **Title**: The main headline.
3.  **Slug**: The URL-friendly name (e.g., `my-first-post`). Leave blank to auto-generate from the title.
4.  **Content**: The main body text (currently supports HTML).
5.  **Status**: Set to `Published` to make visible, or `Draft` to hide.

### Deleting
Click the red **Delete** button on the posts list. **Warning:** This action is immediate and permanent.

---

## 3. Managing Menus

1.  Navigate to **Navigation Menus** from the Dashboard.
2.  **Create a Menu**: Give it a name (e.g., "Main Header") and click Create.
3.  **Add Links**: Click **Manage Links** next to your new menu.
    *   **Label**: What the user sees (e.g., "Home").
    *   **URL**: Where it goes (e.g., `/` or `/about`).
    *   **Order**: Lower numbers appear first.

---

## 4. Site Settings

Navigate to **Site Settings** to configure global options.
*   **Breadcrumbs**: Enable or disable the path navigation (e.g., `Home > Page`).
    *   You can customize the **Separator** (e.g., `>`) and the **Home Text**.

---

## 5. Organizing Content (Categories & Tags)

*   **Categories**: Group your posts into broad topics (e.g., "News", "Tutorials").
*   **Tags**: Label your posts with specific keywords (e.g., "PHP", "Security").

---

## 6. Event Planner (Plugin)

*(Coming in Phase 5)*

*   **Adding Events**: Setting dates, locations, and capacity.
*   **Managing Attendees**: Viewing who has signed up.

---

*Last Updated: v0.2.0 (Phase 4 Expansion)*