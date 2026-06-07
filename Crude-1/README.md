# ProfileHub - PHP User Profile CRUD System

ProfileHub is a full-featured, modular User Profile CRUD (Create, Read, Update, Delete) web application built using **PHP (Core)** and **MySQL** (using **PDO**). It demonstrates web development best practices, including input validation, sanitization, secure file uploads, cross-site scripting (XSS) prevention, and SQL injection prevention.

## 🚀 Key Features

* **Create Profile**: Register new profiles with an avatar image upload and rich text sections for Descriptions, Experience, and Projects.
* **Dashboard (Read All)**: View a tabular directory of all registered profiles with thumbnails, registration dates, and quick action controls.
* **Detailed View (Read One)**: Display complete user details and render rich text formatting (list, bold, headers) safely.
* **Edit Profile (Update)**: Modify basic user data, replace/keep existing profile photos, and edit rich text fields.
* **Delete Profile (Delete)**: Safe deletion including an interactive, animated confirmation prompt (SweetAlert2) and automatic cleanup of avatar files on the filesystem.
* **Rich Text Editing**: Integrated **Summernote Lite** WYSIWYG editor for professional portfolio formatting.
* **Session-based Flash Messages**: Dynamic notifications confirming actions or detailing form validation errors.

---

## 🛠️ Technology Stack

* **Backend**: PHP 8.x (Core)
* **Database Driver**: PHP Data Objects (PDO) for secure, prepared SQL execution
* **Database**: MySQL 8.x / MariaDB
* **CSS Framework**: Bootstrap 5
* **Rich Text Editor**: Summernote Lite
* **Popups/Confirmations**: SweetAlert2
* **Fonts/Icons**: FontAwesome 6, Google Fonts (Outfit & Inter)

---

## 📁 Project Directory Structure

```text
asg02/
│
├── assets/
│   ├── css/
│   │   └── style.css            # Layout styling, colors, transitions
│   ├── js/
│   │   └── custom.js            # SweetAlert2 delete triggers & photo preview
│   └── uploads/
│       └── profile_images/      # Server directory where uploaded images are saved
│
├── controller/
│   ├── db.php                   # Database connection using PDO instance
│   ├── function.php             # Validation, sanitization, image handlers, flash sessions
│
├── includes/
│   ├── header.php               # HTML Head, CDNs, Nav, Flash message container
│   └── footer.php               # CDNs, script initializers, container closer
│
├── index.php                    # Redirection entrypoint
├── create.php                   # Form UI for adding user profiles
├── store.php                    # Processor for creation
├── dashboard.php                # Table view listing all users
├── profile.php                  # Detail view for single profile
├── edit.php                     # Prefilled editor form UI
├── update.php                   # Processor for profile modifications
├── delete.php                   # Processor for profile deletions
│
├── database.sql                 # MySQL Database initialization schema
└── README.md                    # Project documentation
```

---

## ⚙️ Installation & Setup

1. **Clone/Copy Workspace**: Put this project in your local server directory (e.g. `htdocs/` for XAMPP, or `var/www/html/` for Apache).
2. **Start Services**: Launch your web server (Apache) and Database server (MySQL) from XAMPP or system services.
3. **Database Import**:
   * Open PHPMyAdmin (`http://localhost/phpmyadmin`) or your MySQL client.
   * Create a database named `user_management`.
   * Import the `database.sql` file or run the SQL schema commands:
     ```sql
     CREATE DATABASE IF NOT EXISTS user_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     USE user_management;

     CREATE TABLE IF NOT EXISTS users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(100) NOT NULL,
         email VARCHAR(150) NOT NULL UNIQUE,
         description LONGTEXT NULL,
         experience LONGTEXT NULL,
         projects LONGTEXT NULL,
         profile_image VARCHAR(255) NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
     ) ENGINE=InnoDB;
     ```
4. **Configuration Check**:
   * Open `controller/db.php`.
   * If your MySQL server port, user, or password differs from default settings (Host: `127.0.0.1`, User: `root`, Pass: `""`), update them there.
5. **Run the Application**:
   * Navigate to `http://localhost/asg02` (or the folder path on your local setup) in your web browser.

---

## 🔒 Security Practices Implemented

* **SQL Injection**: All operations use PDO parameterized prepared statements (`execute()`), blocking inputs from altering SQL semantics.
* **XSS (Cross-Site Scripting)**: Default outputs are escaped using `htmlspecialchars()`. In Summernote rich text areas, custom regular expressions strip out harmful elements like `<script>` blocks and arbitrary `onload`/`onerror`/`onclick` event handlers.
* **Secure File Uploads**:
  * Uploaded images are renamed using cryptographically secure random values (`bin2hex(random_bytes(16))`) to block file name collision and directory traversal exploits.
  * Validation rules restrict uploads based on strict file size (maximum 2MB) and extensions (`jpg`, `jpeg`, `png`, `webp`).
  * Validates file MIME types (`mime_content_type()`) to block renaming malicious scripts.
