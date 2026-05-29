# Spotter

Spotter is a lightweight workout tracking web application developed using pure PHP and MariaDB for Raspberry Pi Zero 2W.

The application allows users to manage exercises, workouts, workout sets, and body part relationships through a relational database structure. The project focuses on server-side PHP development, CRUD operations, and relational database design without using frameworks.

## Features

- Exercise management
  - Create, edit, delete, and view exercises
  - Assign body parts to exercises
  - Filter exercises by body part

- Workout management
  - Create, edit, and delete workouts
  - Track workout duration and exercise history

- Workout set tracking
  - Add, edit, and delete workout sets
  - Record reps, weight, and failure information

- Body part management
  - Create and manage body parts
  - View exercises associated with each body part

- Dashboard and statistics
  - Total exercises, workouts, sets, and body parts
  - Average workout length
  - Most used exercise
  - Recent workouts overview

## Technologies Used

- PHP 8
- MariaDB
- Apache2
- HTML/CSS
- Raspberry Pi Zero 2W
- GitHub

## Database Design

The application uses a relational database structure with:

- One-to-many relationships
  - Workout → Workout Sets

- Many-to-many relationships
  - Exercise ↔ Body Parts

<details>
<summary>🐬 ERD</summary>

![DB ER-diagram](https://raw.githubusercontent.com/CrhistianMRe/spotter-peachepe/main/MamitaDebe.svg)

</details>


Main tables:

- exercise
- workout
- workout_set
- body_part
- exercise_body_part

## Project Structure

```
public/
    Public PHP pages and routes

private/
    Database connection
    Validation helpers
    Shared templates

sql/
    Database schema

assets/
    Static assets
```

## Development Goals

This project was developed to demonstrate:

- Server-side PHP programming
- Relational database integration
- CRUD operations
- SQL joins and aggregation
- Database normalization concepts
- Clean project organization
- Secure database interaction using prepared statements

## Hardware Platform

- Raspberry Pi Zero 2W

## Repository

GitHub repository for source code and documentation:

https://github.com/CrhistianMRe/spotter-peachepe#
