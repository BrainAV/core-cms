# 🗺️ Dynamic Sitemap

## 1. Overview
The Dynamic Sitemap is an XML file generated on-the-fly that lists all public URLs on the site. This helps search engines (Google, Bing) crawl and index content efficiently.

## 2. Implementation
*   **Route**: `/sitemap.xml`
*   **Logic**: A script queries the `posts` table for all `published` content.
*   **Output**: Formatted XML following the Sitemap protocol.

## 3. Content Included
*   **Homepage**: `/`
*   **Pages**: Static pages (e.g., `/about`).
*   **Posts**: Blog posts (e.g., `/hello-world`).
*   *(Future)* **Categories**: Taxonomy archives.

## 4. Technical Details
*   **Header**: `Content-Type: application/xml`
*   **Caching**: Currently generated on every request (lightweight). Future versions may cache the output for performance.