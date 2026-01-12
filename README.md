# Core CMS

[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Database](https://img.shields.io/badge/Database-MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE.md)
[![Status](https://img.shields.io/badge/Status-Phase_5:_Event_Planner-orange?style=for-the-badge)](.github/ROADMAP.md)
[![Demo](https://img.shields.io/badge/Demo-Live_Site-success?style=for-the-badge&logo=firefox&logoColor=white)](https://core-cms.brainav.ca/)

A lightweight, modular Content Management System built with PHP and MySQL. Designed with a "Core + Plugin" architecture, featuring a specialized Event Planner module.

## 🚀 Features
*   **Lightweight Core**: Fast, secure, and bloat-free.
*   **Modular Architecture**: Features are added as plugins.
*   **Block Editor**: Modern, JSON-based content editing (Editor.js).
*   **Media Library**: Secure file uploads and management.
*   **Role-Based Access**: Granular permissions for Admins, Editors, and Subscribers.
*   **Theme System**: Switchable designs with a custom Theme Manager.
*   **Secure**: Built with PDO, prepared statements, and modern password hashing.
*   **Site Identity**: Manage branding (Logo, Title) without code.
*   **Label Editor**: Customize UI text strings for localization or branding.
*   **SEO Friendly**: Built-in routing for pretty URLs.
*   **Dynamic Menus**: Manage navigation links directly from the admin dashboard.

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
