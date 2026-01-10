# 👤 User Profile System

## 1. Overview
The Profile system allows authenticated users to manage their own account credentials without needing direct database access.

## 2. Features
*   **Update Info**: Change Display Name and Email Address.
*   **Change Password**: Securely update login credentials.
*   **Avatar Upload**: Upload a profile picture (JPG, PNG, GIF, WEBP).
*   **Validation**: Checks for email uniqueness and password confirmation matching.

## 3. Security
*   **Password Hashing**: All new passwords are hashed using `password_hash()` (Bcrypt/Argon2) before storage.
*   **Session Protection**: Users can only edit their own ID (retrieved from `$_SESSION['user_id']`).
*   **File Security**: Avatars are stored in the secure `uploads/` directory, protected by `.htaccess` to prevent script execution.

## 4. Usage
1.  Log in to the Admin Dashboard.
2.  Click **My Profile** in the sidebar.
3.  Update fields or upload a new **Profile Picture**.
4.  Click **Save Changes**.

## 5. Future Improvements
*   **2FA**: Two-Factor Authentication settings.
*   **Bio/Social Links**: Additional profile fields.

## 6. Technical Details (Avatars)
*   **Storage**: Files are saved in `uploads/{year}/{month}/`.
*   **Database**: The relative path is stored in the `users` table (`avatar` column).
*   **Naming**: Files are renamed to `avatar_{user_id}_{timestamp}.ext` to prevent collisions.