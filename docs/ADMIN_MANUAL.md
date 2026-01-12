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
    *   **Reordering**: You can manually update the order numbers in the list view.

---

## 4. Site Settings

Navigate to **Site Settings** to configure global options.
*   **Breadcrumbs**: Enable or disable the path navigation (e.g., `Home > Page`).
    *   You can customize the **Separator** (e.g., `>`) and the **Home Text**.
*   **Site Identity**:
    *   **Site Title**: The name displayed in the browser tab and header.
    *   **Logo URL**: Path to your logo image (e.g., `/uploads/2026/01/logo.png`).
    *   **Footer Text**: Custom text or HTML for the site footer.
*   **Scroll to Top**:
    *   Enable a "Back to Top" button and customize its position, shape, and colors.

---

## 5. Organizing Content (Categories & Tags)

*   **Categories**: Group your posts into broad topics (e.g., "News", "Tutorials").
*   **Tags**: Label your posts with specific keywords (e.g., "PHP", "Security").

---

## 6. Media Library

Navigate to **Media Library** to manage files.
*   **Upload**: Supports Images (JPG, PNG, GIF, WEBP) and PDFs.
*   **Manage**: View uploaded files or delete them.
*   **Usage**: Copy the file URL to use in Posts or Settings.
*   **Edit**: Click "Edit" to add Alt Text, Titles, and Descriptions for SEO.

---

## 7. User Profile

Click **My Profile** in the sidebar to:
*   Update your **Display Name** or **Email**.
*   Change your **Password**.
*   Upload a **Profile Picture** (Avatar).

---

## 8. Theme Manager

*(Admins Only)*
Navigate to **Theme Manager** to switch the visual design of your site.
*   **Activate**: Click to instantly apply a new theme from the `/themes` directory.

---

## 9. Label Editor

*(Admins Only)*
Navigate to **Label Editor** to customize text strings without coding.
*   **Key**: The identifier used in the code (e.g., `read_more_btn`).
*   **Value**: The text you want to display (e.g., "Continue Reading").

---

## 10. AI Copilot

*(Requires AI Configuration in Settings)*

The **Admin Copilot** helps you draft content directly in the post editor.
1.  Go to **Manage Posts** > **Add/Edit**.
2.  Click the **✨ AI Assist** button above the content editor.
3.  Type a prompt (e.g., "Write an outline for a blog post about coffee").
4.  Click **Generate**. The AI will insert formatted blocks (Headers, Lists, Paragraphs) into your post.

---

## 11. Event Planner (Plugin)

*(Coming in Phase 5)*

*   **Adding Events**: Setting dates, locations, and capacity.
*   **Managing Attendees**: Viewing who has signed up.

---

*Last Updated: v0.3.0 (Phase 5 AI Integration)*