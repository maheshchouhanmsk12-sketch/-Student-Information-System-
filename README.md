# Student Information System (SIS) - Deployment & Setup Guide

A web-based **Student Information System** built using PHP, MySQL, Bootstrap 4, and AdminLTE 3.

---

## 🚀 How to Deploy on Render (Step-by-Step for Beginners)

Because Render does not provide native PHP runtime or native MySQL, this project is pre-configured with **Docker** and connects to any free cloud MySQL database.

### Step 1: Create a Free Cloud MySQL Database
Choose any of these free MySQL providers:
- **TiDB Cloud (Serverless)**: [https://tidbcloud.com](https://tidbcloud.com) (Free 5GB MySQL-compatible database)
- **Clever Cloud**: [https://www.clever-cloud.com](https://www.clever-cloud.com) (Free MySQL add-on)
- **Aiven**: [https://aiven.io](https://aiven.io) (Free trial MySQL)

After creating your database, note down:
- `Host` (e.g., `gateway01.us-east-1.prod.aws.tidbcloud.com`)
- `Port` (usually `4000` for TiDB or `3306` for standard MySQL)
- `Database Name` (e.g., `sis_db` or your custom DB name)
- `User` (e.g., `root` or generated user)
- `Password`

---

### Step 2: Push this Project to GitHub

1. Create a new repository on your GitHub account (e.g. `sis-project`).
2. Run these commands in your project terminal:
   ```bash
   git add .
   git commit -m "Prepare SIS for Render deployment with Docker"
   git branch -M main
   git remote add origin https://github.com/<YOUR_USERNAME>/sis-project.git
   git push -u origin main
   ```

---

### Step 3: Create a Web Service on Render

1. Go to [https://dashboard.render.com](https://dashboard.render.com).
2. Click **New +** -> **Web Service**.
3. Select **Build and deploy from a Git repository** and connect your `sis-project` repository.
4. Settings:
   - **Name**: `student-information-system` (or anything you like)
   - **Region**: Closest to you (e.g., Singapore / Oregon / Frankfurt)
   - **Environment**: `Docker` (Render will automatically detect the `Dockerfile`)
   - **Plan**: `Free`
5. Scroll down to **Environment Variables** and add:
   - `DB_SERVER` = *(Your cloud MySQL Host)*
   - `DB_USERNAME` = *(Your cloud MySQL User)*
   - `DB_PASSWORD` = *(Your cloud MySQL Password)*
   - `DB_NAME` = *(Your cloud MySQL Database Name)*
   - `DB_PORT` = *(Your cloud MySQL Port, e.g. 3306 or 4000)*
6. Click **Create Web Service**.

---

### Step 4: One-Click Database Setup

Once Render deploys your app:
1. Open your Render live URL:
   `https://<your-service-name>.onrender.com/db_setup.php`
2. You will see the **Database Setup & Health Check** page.
3. Click the button: **"Import Database Now"**.
4. That's it! Your tables, courses, departments, and default admin will be automatically created.
5. Click **Proceed to Admin Login** or go to `https://<your-service-name>.onrender.com/admin/login.php`.

---

## 🔑 Default Login Credentials

- **URL**: `https://<your-app>.onrender.com/admin/login.php`
- **Username**: `admin`
- **Password**: `admin123`

---

## 💻 Running Locally (Optional via XAMPP)

1. Download & Install [XAMPP](https://www.apachefriends.org/).
2. Copy the `sis` folder into `C:/xampp/htdocs/sis`.
3. Open XAMPP Control Panel and start **Apache** & **MySQL**.
4. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin) in your browser.
5. Create a new database named `sis_db`.
6. Click **Import** and choose `database/sis_db.sql`.
7. Open [http://localhost/sis](http://localhost/sis) in your browser.
