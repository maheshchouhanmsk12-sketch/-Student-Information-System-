# Student Information System (SIS)

A complete web-based **Student Information System** built using PHP, MySQL (Aiven Cloud Ready), Bootstrap 4, and AdminLTE 3.

---

## 🌐 Live Application Link

- **Live URL:** [https://student-information-system-5wqk.onrender.com/](https://student-information-system-5wqk.onrender.com/)
- **Admin Login:** [https://student-information-system-5wqk.onrender.com/admin/login.php](https://student-information-system-5wqk.onrender.com/admin/login.php)
- **Database Setup & Health Check:** [https://student-information-system-5wqk.onrender.com/db_setup.php](https://student-information-system-5wqk.onrender.com/db_setup.php)

---

## 🔑 Admin Login Credentials

| Role | Username | Password | Access URL |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | [Open Login Page](https://student-information-system-5wqk.onrender.com/admin/login.php) |

---

## 🛠️ Technology Stack & Deployment Architecture

- **Backend:** PHP 8.2 (Object-Oriented + Procedural with `mysqli`)
- **Hosting Platform:** [Render](https://render.com) (Docker Container Web Service)
- **Database:** [Aiven MySQL](https://aiven.io) (Cloud MySQL with SSL/TLS encryption enabled)
- **Frontend Framework:** AdminLTE 3, Bootstrap 4, FontAwesome 5, jQuery, DataTables, SweetAlert2
- **Dynamic Port & Routing:** Dynamic Render `$PORT` binding via Apache startup script (`start.sh`)

---

## ⚙️ Environment Variables (Render Web Service)

The following Environment Variables are configured in the Render Dashboard:

| Variable | Description | Example Value |
| :--- | :--- | :--- |
| `DB_SERVER` | Aiven Cloud MySQL Host | `mysql-xxxx.aivencloud.com` |
| `DB_PORT` | Aiven Cloud MySQL Port | `26284` |
| `DB_USERNAME` | MySQL Username | `avnadmin` |
| `DB_PASSWORD` | MySQL Password | *(Your Aiven Password)* |
| `DB_NAME` | Database Name | `defaultdb` |
| `DB_SSL` | Enable SSL encryption | `true` |

---

## 🚀 How to Run Locally (via XAMPP)

1. Download and install [XAMPP](https://www.apachefriends.org/).
2. Copy the `sis` folder into your XAMPP web root: `C:/xampp/htdocs/sis`.
3. Start **Apache** and **MySQL** in the XAMPP Control Panel.
4. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
5. Create a new database named `sis_db`.
6. Import `sis/database/sis_db.sql`.
7. Open [http://localhost/sis](http://localhost/sis) in your browser.
8. Login with `admin` / `admin123`.

---

## 📁 Repository Structure

```text
├── Dockerfile                  # Production Docker container configuration (PHP 8.2 + Apache)
├── render.yaml                 # Render Blueprint deployment definition
├── start.sh                    # Dynamic Apache PORT startup script
├── test_aiven.php              # Aiven MySQL CLI connection verification tool
├── README.md                   # Project documentation & credentials
└── sis/                        # Main Application Code
    ├── admin/                  # Admin Dashboard, Student management, Departments, Courses
    ├── classes/                # Business logic (DBConnection.php, Login.php, SystemSettings.php)
    ├── database/               # Database SQL schema (sis_db.sql)
    ├── db_setup.php            # 1-Click Database Setup & Importer tool for Cloud DBs
    ├── dist/                   # AdminLTE 3 CSS, JS & theme assets
    ├── plugins/                # Bootstrap, jQuery, FontAwesome, DataTables libraries
    ├── uploads/                # User avatars, logos, and system images
    ├── initialize.php          # Dynamic configuration & environment variables loader
    └── index.php               # Main landing redirect
```
