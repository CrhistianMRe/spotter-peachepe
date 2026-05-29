# Admin Guide

## Introduction

This document describes how to configure, maintain, and manage the Spotter application.

Spotter is a lightweight workout tracking application developed using PHP and MariaDB for Raspberry Pi Zero 2W.

The system is intended for local network usage and educational purposes.

---

# System Requirements

## Hardware

Recommended hardware platform:

- Raspberry Pi Zero 2W

## Software

Required software:

- Linux-based operating system
- Apache2
- PHP 8
- MariaDB
- Git

Required PHP extensions:

- mysqli
- pdo_mysql

---

# Application Structure

```
public/
    Public routes and pages

private/
    Shared PHP logic
    Database connection
    Validation helpers
    Templates

sql/
    Database schema

assets/
    Static assets
```

---

# Database Administration

## Database Name

```
spotter
```

## Main Tables

- exercise
- workout
- workout_set
- body_part
- exercise_body_part

## Importing Database Schema

Open MariaDB:

```
sudo mariadb
```

Run the schema file:

```
SOURCE /path/to/spotter.sql;
```

---

# Database User Configuration

Example database user configuration:

```sñ
CREATE USER 'spotter'@'localhost' IDENTIFIED BY 'your_password';

GRANT ALL PRIVILEGES ON spotter.* TO 'spotter'@'localhost';

FLUSH PRIVILEGES;
```

---

# Application Configuration

Database credentials are configured in:

```
private/db.php
```

Example:

```
$host = 'localhost';
$dbname = 'spotter';
$username = 'spotter';
$password = 'your_password';
```

---

# Apache Configuration

The application should be placed inside the Apache web directory or configured through a Virtual Host.

Example deployment path:

```
/var/www/html/spotter
```

The public entry point is:

```
public/
```

Restart Apache after configuration changes:

```
sudo service apache2 restart
```

---

# Updating the Application

To update the application:

```
git pull
```

If database schema changes are introduced:

1. Backup the database
2. Apply required schema modifications manually
3. Verify application functionality

---

# Backup Recommendations

Regular backups are recommended for the MariaDB database.

Example backup command:

```
mysqldump -u spotter -p spotter > backup.sql
```

Restore backup:

```
mysql -u spotter -p spotter < backup.sql
```

---

# Security Notes

The application uses:

- Prepared SQL statements
- Server-side validation
- Public/private directory separation

The application does not implement authentication because it is intended for local single-user operation.

---

# Maintenance Notes

Administrators should periodically:

- Verify Apache service availability
- Verify MariaDB service availability
- Backup the database
- Pull repository updates
- Check PHP error logs if issues occur

Example Apache error log location:

```
/var/log/apache2/error.log
```

---

# Troubleshooting

## Database Connection Errors

Verify:

- MariaDB is running
- Credentials in `private/db.php` are correct
- PHP MySQL extensions are installed

## Apache Errors

Check:

```
sudo tail -n 50 /var/log/apache2/error.log
```

## PHP Module Verification

Verify MySQL PHP modules:

```
php -m | grep mysql
```

Expected modules:

- mysqli
- mysqlnd
- pdo_mysql

---

# Repository Management

Source code is managed through GitHub.
