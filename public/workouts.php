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

<div class="page-header">
    <h1>Workout History</h1>
    <a href="workout_create.php" class="btn btn-primary">+ Add Workout</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Exercise</th>
                <th>Length (minutes)</th>
                <th>Sets</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workouts as $workout): ?>
            <tr>
                <td><?= $workout['id'] ?></td>
                <td><?= escape($workout['workout_date']) ?></td>
                <td><?= escape($workout['exercise_name']) ?></td>
                <td><?= escape($workout['workout_length']) ?></td>
                <td>
                    <a href="workout_sets.php?workout_id=<?= $workout['id'] ?>" class="action-link view">
                        View Sets
                    </a>
                </td>
                <td>
                    <div class="actions">
                        <a href="workout_edit.php?id=<?= $workout['id'] ?>" class="action-link edit">Edit</a>
                        <a href="workout_delete.php?id=<?= $workout['id'] ?>" class="action-link delete">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../private/templates/footer.php'; ?>
