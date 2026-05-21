<?php

require_once '../private/db.php';
require_once '../private/helpers.php';

$stmt = $pdo->query("
    SELECT
        workout.id,
        workout.workout_date,
        workout.workout_length,
        exercise.name AS exercise_name
    FROM workout
    JOIN exercise
        ON workout.exercise_id = exercise.id
    ORDER BY workout.workout_date DESC
");

$workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../private/templates/header.php';

?>

<h1>Workout History</h1>

<p>
    <a href="workout_create.php">
        Add Workout
    </a>
</p>

<table border="1" cellpadding="5">

    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Exercise</th>
        <th>Length (minutes)</th>
        <th>Sets</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($workouts as $workout): ?>

    <tr>

        <td>
            <?= $workout['id'] ?>
        </td>

        <td>
            <?= escape($workout['workout_date']) ?>
        </td>

        <td>
            <?= escape($workout['exercise_name']) ?>
        </td>

        <td>
            <?= escape($workout['workout_length']) ?>
        </td>

        <td>

            <a href="workout_sets.php?workout_id=<?= $workout['id'] ?>">

                View Sets

            </a>

        </td>

        <td>

            <a href="workout_delete.php?id=<?= $workout['id'] ?>">

                Delete

            </a>

        </td>

    </tr>

    <?php endforeach; ?>

</table>

<?php require_once '../private/templates/footer.php'; ?>
