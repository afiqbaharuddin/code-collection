# Atlantis Asia Blog

## Technology Stack

- **Frontend**: Bootstrap 5, jQuery, Font Awesome
- **Backend**: PHP (Vanilla)
- **Database**: MySQL
- **Server**: XAMPP (Apache + MySQL)

## Installation & Configuration

### Step 1: Clone or Download the Project

Place the `atlantis-blog` folder in your XAMPP htdocs directory:

### Step 2: Start XAMPP Services

### Step 3: Create Database

#### Option A: Using phpMyAdmin

Import the `database.sql` to your phpmyadmin.

#### Option B: Using MySQL Command Line

```bash
mysql -u root -p < c:\xampp\htdocs\atlantis-blog\database.sql
```

### Step 4: Configure Database Connection

Open `config.php` and update the database credentials if needed:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'atlantis_blog');
```

### Step 5: Access the Application

Open your web browser and navigate to:

- **Blog Homepage**: `http://localhost/atlantis-blog/`
- **Admin Panel**: `http://localhost/atlantis-blog/admin.php`

## Default Admin Credentials

```
Username: admin
Password: admin123
```
