# Core CMS

![Your Stats](https://github-readme-stats.vercel.app/api?username=jasonbra1n&show_icons=true&theme=tokyonight)
![Streak](https://github-readme-streak-stats.herokuapp.com/?user=jasonbra1n&theme=tokyonight)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Database](https://img.shields.io/badge/Database-MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE.md)
[![Status](https://img.shields.io/badge/Status-Phase_3:_Routing_&_Experience-success?style=for-the-badge)](.github/ROADMAP.md)

A lightweight, modular Content Management System built with PHP and MySQL. Designed with a "Core + Plugin" architecture, featuring a specialized Event Planner module.

## 🚀 Features
*   **Lightweight Core**: Fast, secure, and bloat-free.
*   **Modular**: Features are added as plugins (e.g., Event Planner).
*   **Secure**: Built with PDO, prepared statements, and modern password hashing.
*   **SEO Friendly**: Built-in routing for pretty URLs.
*   **Dynamic Menus**: Manage navigation links directly from the admin dashboard.

## 📂 Documentation
*   **Roadmap**: [View Active Roadmap](.github/ROADMAP.md)
*   **Architecture**: [Project Plan](docs/PROJECT_PLAN.md)
*   **Database**: [Schema Dictionary](docs/DATABASE.md)
*   **User Guide**: [Admin Manual](docs/ADMIN_MANUAL.md)
*   **Developers**: [Contributing Guide](.github/CONTRIBUTING.md) & [Style Guide](.github/STYLE_GUIDE.md)

## 🛠️ Installation

1.  **Clone the repository**:
    ```bash
    git clone https://github.com/jasonbra1n/core-cms.git
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