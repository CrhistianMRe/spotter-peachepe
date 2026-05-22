# Spotter

Spotter is a lightweight workout logging web application developed in PHP with a MariaDB relational database.

The project is designed to run on a Raspberry Pi Zero 2W using Apache and PHP without server-side or client-side frameworks.

<details>
<summary>🐬 MariaDB</summary>

![DB ER-diagram](https://raw.githubusercontent.com/CrhistianMRe/spotter-peachepe/main/MamitaDebe.svg)

</details>


## Project Structure

.
├── assets/
├── private/
├── public/
├── sql/
├── CONTRIBUTORS.md
└── README.md

### Folder Responsibilities

#### `public/`

Browser-accessible PHP pages.

Examples:

* exercise CRUD pages
* workout pages
* statistics pages

Apache serves files from this folder.

---

#### `private/`

Internal backend PHP files.

Examples:

* database connection
* validation helpers
* reusable functions
* templates

Files in this folder should not be directly accessible from the browser.

---

#### `assets/`

Static assets.

Examples:

* CSS
* images

---

#### `sql/`

Database schema and seed data.

Contains:

* table creation SQL
* initial exercise data

## Current Features

* PHP project structure
* MariaDB database integration
* PDO database connection
* Exercise CRUD functionality
* Reusable validation and helper functions

## Technologies Used

* PHP 8
* MariaDB
* Apache2
* GitHub
* Raspberry Pi Zero 2W

## Development Notes

* Keep backend PHP logic inside `private/`
* Keep browser-accessible pages inside `public/`
* Use prepared statements for database queries

### (PUTOELQUELOLEA) 
1. FALTA ENDPOINT DE **GET ALL BODY PARTS LIST** 
2. FALTA ENDPOINT DE **GET ALL EXERCISES BY BODY_PART ID** 

