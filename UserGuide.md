# User Guide

## Introduction

Spotter is a workout tracking web application that allows users to manage exercises, workouts, workout sets, and body part relationships.

The application is designed to run locally on a Raspberry Pi Zero 2W using PHP and MariaDB.

## Accessing the Application

Open a web browser and navigate to:

```
http://localhost/spotter/public
```

Or use the Raspberry Pi local network IP address:

```
http://<raspberry-pi-ip>/spotter/public
```

The application homepage opens the dashboard automatically.

---

# Dashboard

The dashboard displays general statistics about the application, including:

- Total exercises
- Total workouts
- Total workout sets
- Total body parts
- Average workout length
- Most used exercise
- Recent workouts

Use the navigation bar to access other sections of the application.

---

# Exercise Library

The Exercise Library allows users to manage exercises.

Features:

- View all exercises
- Create new exercises
- Edit existing exercises
- Delete exercises
- View exercise details
- Assign body parts to exercises
- Filter exercises by body part

## Creating an Exercise

1. Open the Exercise Library
2. Click "Add Exercise"
3. Fill in the form
4. Submit the form

## Managing Exercise Body Parts

1. Open the Exercise Library
2. Click "Manage Body Parts"
3. Select a body part
4. Click "Assign Body Part"

Assigned body parts can also be removed.

---

# Body Parts

The Body Parts section allows users to:

- Create body parts
- View all body parts
- View exercises associated with a body part

## Creating a Body Part

1. Open the Body Parts page
2. Click "Add Body Part"
3. Enter a body part name
4. Submit the form

---

# Workout History

The Workout History section allows users to:

- Create workouts
- Edit workouts
- Delete workouts
- View workout sets

## Creating a Workout

1. Open Workout History
2. Click "Add Workout"
3. Select an exercise
4. Select the workout date
5. Enter workout length
6. Submit the form

---

# Workout Sets

Workout Sets are attached to workouts and store detailed performance information.

Each set includes:

- Repetitions
- Weight
- Failure status

Users can:

- Add workout sets
- Edit workout sets
- Delete workout sets

## Managing Workout Sets

1. Open Workout History
2. Click "View Sets"
3. Add or manage sets

---

# Filtering Exercises

Exercises can be filtered by body part.

1. Open the Exercise Library
2. Select a body part from the filter dropdown
3. Click "Filter"

The page will display only exercises associated with the selected body part.

---

# Error Handling

The application validates user input before database operations.

Examples of validation:

- Required fields
- Positive numeric values
- Valid relationship assignments
- Duplicate prevention for body part assignments

---

# Notes

- The application is intended for local network usage.
- Authentication is not implemented because the system is designed as a single-user local application.
- All database interactions use prepared statements for security.
