# 🖼️ Media System

## 1. Overview
The Media System allows administrators to upload, manage, and insert files (images, documents) into content.

## 2. Technology Stack
*   **Handling**: Native PHP `$_FILES` processing.
*   **Storage**: Local server filesystem (`/uploads` directory).
*   **Database**: Metadata stored in a `media` table (filename, path, type).

## 3. Upload Process
1.  **User Action**: Admin selects a file in `admin/media.php`.
2.  **Validation**:
    *   Check file size (limit defined in `config.php` or PHP ini).
    *   Check file type (Allowlist: JPG, PNG, GIF, WEBP, PDF).
    *   Sanitize filename to remove special characters.
3.  **Storage**:
    *   File is moved to `uploads/{year}/{month}/` to keep folders organized and prevent filesystem limits.
    *   Unique filename generated if a collision occurs.
4.  **Database**:
    *   Record inserted into `media` table.

## 4. Security Measures
*   **No Execution**: An `.htaccess` file in `/uploads` prevents PHP script execution (critical for security).
*   **MIME Check**: Verify actual file content, not just the extension.

## 5. Database Schema
*(To be added in Phase 4)*

```sql
CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
```

## 6. Roadmap & Future Features

### Phase 4.x: Metadata Editing
*   **Goal**: Allow SEO and accessibility improvements.
*   **Database**: Add `alt_text`, `title`, and `description` columns to the `media` table.
*   **UI**: Create `admin/media-edit.php` to update these fields.

### Phase 6: Image Manipulation
*   **Goal**: Basic image editing within the CMS.
*   **Features**: Crop, Resize, Rotate.
*   **Tech**: Use PHP GD Library or ImageMagick.
```