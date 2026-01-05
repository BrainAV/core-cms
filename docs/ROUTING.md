# 🛣️ Routing & Pretty URLs

## 1. The Concept
"Pretty URLs" turn ugly query strings (e.g., `index.php?page=about`) into clean, readable paths (e.g., `/about`). This improves SEO and user experience.

## 2. The Mechanism (`.htaccess`)
Core CMS uses an Apache `.htaccess` file to intercept all incoming requests.

### How it works:
1.  **Interception**: The server checks if the requested file (e.g., `assets/images/logo.png`) actually exists on the disk.
2.  **Passthrough**: If the file exists, the server delivers it directly.
3.  **Rewrite**: If the file does *not* exist (e.g., `/my-blog-post`), the server internally redirects the request to `index.php`.

### The Code
```apache
RewriteEngine On
RewriteBase /

# If the request is not a real file or directory...
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# ...send it to index.php
RewriteRule ^(.*)$ index.php [QSA,L]
```

## 3. PHP Handling (`index.php`)
Once `index.php` receives the request, it acts as the "Front Controller."

1.  **Capture**: We inspect `$_SERVER['REQUEST_URI']`.
2.  **Parse**: We break the path into segments (e.g., `/post/my-slug`).
3.  **Dispatch**:
    *   `/admin` -> Loads admin dashboard.
    *   `/post/{slug}` -> Queries the database for a post with that slug.
    *   `/` -> Loads the homepage.

## 4. Testing & Troubleshooting

### How to Test
1.  Ensure the `.htaccess` file exists in your root directory.
2.  Visit a non-existent URL like `/testing-123`.
3.  **Success**: If you see your Homepage (or whatever `index.php` outputs), the routing is working.
4.  **Failure**: If you see a standard Apache/Browser "404 Not Found" page, `mod_rewrite` is likely disabled or ignored.

### Common Issues
*   **404 Not Found**:
    *   Ensure `mod_rewrite` is enabled in Apache.
    *   Ensure `AllowOverride All` is set in your Apache config (`httpd.conf`) for your web directory.
*   **500 Internal Server Error**: Usually a syntax error in `.htaccess`. Check your server error logs.
*   **Subdirectories**: If installed in a subfolder (e.g., `example.com/cms/`), you may need to change `RewriteBase /` to `RewriteBase /cms/`.
*   **Nginx/IIS**: This guide assumes Apache. Other servers require different configuration files.