# Core CMS

[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Database](https://img.shields.io/badge/Database-MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE.md)
[![Status](https://img.shields.io/badge/Status-Phase_6:_Plugins-purple?style=for-the-badge)](.github/ROADMAP.md)
[![Demo](https://img.shields.io/badge/Demo-Live_Showcase-success?style=for-the-badge&logo=firefox&logoColor=white)](https://core-cms.brainav.ca/)
[![Production](https://img.shields.io/badge/Production-BrainAV.ca-blue?style=for-the-badge&logo=google-chrome&logoColor=white)](https://brainav.ca)

Core CMS is a modern, lightweight, and **AI-Native** Content Management System built with PHP 8.3. It is designed for speed, modularity, and a premium writing experience.

### 🌐 Live Sites
*   **Organization Site**: [BrainAV.ca](https://brainav.ca) (Production)
*   **CMS Showcase**: [core-cms.brainav.ca](https://core-cms.brainav.ca) (Live Demo)

## 🚀 Features
*   **AI-Powered (Corex Intelligence)**: Built-in Admin Copilot for drafting, summarizing, and SEO generation.
*   **Block Editor (Gutenberg-Quest)**: Modern, JSON-based content editing with support for multi-column layouts and block alignment.
*   **Lightweight Core**: Fast, secure, and bloat-free foundation.
*   **Modular Architecture**: Features are added as plugins (Event Planner, etc.).
*   **Media Library**: Secure file uploads with metadata editing (Alt text, captions).
*   **Role-Based Access**: Granular permissions for Admins, Editors, and Subscribers.
*   **Theme System**: Switchable designs with a custom Theme Manager.
*   **SEO Ready**: Pretty URLs, custom slugs, and auto-generated XML Sitemap.
*   **Identity & Labels**: Manage site branding and UI text without touching code.

## 📂 Documentation
*   **Roadmap**: [View Active Roadmap](.github/ROADMAP.md)
*   **Architecture**: [Project Plan](docs/PROJECT_PLAN.md)
*   **Database**: [Schema Dictionary](docs/DATABASE.md)
*   **Docs Index**: [Table of Contents](docs/README.md)
*   **Developers**: [Contributing Guide](.github/CONTRIBUTING.md) & [Style Guide](.github/STYLE_GUIDE.md)

## 🛠️ Installation

1.  **Clone the repository**:
    ```bash
    git clone https://github.com/BrainAV/core-cms.git
    ```
2.  **Server Setup (CPanel/FTP)**:
    *   Create a MySQL Database and User in CPanel.
    *   Import `db/schema.sql` via phpMyAdmin.
    *   Upload files to your `public_html` folder.
3.  **Configuration**:
    *   Rename `config/db_creds-sample.php` to `config/db_creds.php`.
    *   Edit the file with your database credentials.
4.  **Installation**:
    *   Navigate to `yourdomain.com/install_admin.php`.
    *   Create your admin account.
    *   **Important:** Delete `install_admin.php` after use.

## 📜 License
MIT License. See LICENSE.md.
