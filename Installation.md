# Installation Guide

## Requirements

Before installing Spotter, make sure the following software is installed on the system:

- Apache2
- PHP 8 with MySQL support
- MariaDB
- Git

Recommended hardware platform:

- Raspberry Pi Zero 2W

## Clone Repository

Clone the repository into the web server directory:

```
git clone https://github.com/CrhistianMRe/spotter-peachepe.git
```

Enter the project directory:

```
cd spotter-peachepe
```

## Configure Apache

Place the project inside the Apache web directory or configure a Virtual Host.

Example deployment path:

```
/var/www/html/spotter
```

The public entry point of the application is:

```
public/
```

## Install PHP MySQL Extension

Install MySQL support for PHP:

```
sudo apt install php-mysql
```

Restart Apache after installation:

```
sudo service apache2 restart
```

## Create Database

Open MariaDB:

```
sudo mariadb
```

Create the database and tables using the SQL schema:

```
SOURCE /path/to/spotter.sql;
```

Example:

```
SOURCE /home/pi/spotter-peachepe/sql/spotter.sql;
```

## Create Database User

Create a MariaDB user for the application:

```
CREATE USER 'spotter'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON spotter.* TO 'spotter'@'localhost';
FLUSH PRIVILEGES;
```

## Configure Database Connection

Edit:

```
private/db.php
```

Update database credentials:

```
$host = 'localhost';
$dbname = 'spotter';
$username = 'spotter';
$password = 'your_password';
```

## Access Application

Start Apache and open the application in a browser:

```
http://localhost/spotter/public
```

Or through the Raspberry Pi local network IP address.

## Verify Installation

The following pages should work correctly:

- Dashboard
- Exercise Library
- Workout History
- Body Parts

If all pages load correctly and database operations work, the installation was successful.
