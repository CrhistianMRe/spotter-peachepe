<?php

require_once '../private/db.php';
require_once '../private/helpers.php';

$total_exercises = $pdo->query("
    SELECT COUNT(*) FROM exercise
")->fetchColumn();

$total_workouts = $pdo->query("
    SELECT COUNT(*) FROM workout
")->fetchColumn();

$total_sets = $pdo->query("
    SELECT COUNT(*) FROM workout_set
")->fetchColumn();

$total_body_parts = $pdo->query("
    SELECT COUNT(*) FROM body_part
")->fetchColumn();

$average_workout_length = $pdo->query("
    SELECT AVG(workout_length)
    FROM workout
")->fetchColumn();

$stmt = $pdo->query("
    SELECT
        exercise.name,
        COUNT(workout.id) AS workout_count
    FROM workout
    JOIN exercise
        ON workout.exercise_id = exercise.id
    GROUP BY exercise.id
    ORDER BY workout_count DESC
    LIMIT 1
");

$most_used_exercise = $stmt->fetch(PDO::FETCH_ASSOC);

require_once '../private/templates/header.php';

?>

<h1>Dashboard</h1>

<div class="table-wrap">
    <table class="detail-table">
        <tbody>
            <tr>
                <th>Total Exercises</th>
                <td><?= $total_exercises ?></td>
            </tr>
            <tr>
                <th>Total Workouts</th>
                <td><?= $total_workouts ?></td>
            </tr>
            <tr>
                <th>Total Workout Sets</th>
                <td><?= $total_sets ?></td>
            </tr>
            <tr>
                <th>Total Body Parts</th>
                <td><?= $total_body_parts ?></td>
            </tr>
            <tr>
                <th>Average Workout Length</th>
                <td><?= round($average_workout_length ?? 0, 2) ?> minutes</td>
            </tr>
            <tr>
                <th>Most Used Exercise</th>
                <td>
                    <?php if ($most_used_exercise): ?>
                        <?= escape($most_used_exercise['name']) ?>
                        <span style="color: var(--text-muted); font-size: 0.8rem;">
                            (<?= $most_used_exercise['workout_count'] ?> workouts)
                        </span>
                    <?php else: ?>
                        <span style="color: var(--text-muted);">No workout data</span>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php

$stmt = $pdo->query("
    SELECT
        workout.id,
        workout.workout_date,
        workout.workout_length,
        exercise.name AS exercise_name
    FROM workout
    JOIN exercise
        ON workout.exercise_id = exercise.id
    ORDER BY workout.workout_date DESC, workout.id DESC
    LIMIT 5
");

$recent_workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Recent Workouts</h2>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Exercise</th>
                <th>Date</th>
                <th>Length</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recent_workouts as $workout): ?>
            <tr>
                <td><?= $workout['id'] ?></td>
                <td><?= escape($workout['exercise_name']) ?></td>
                <td><?= escape($workout['workout_date']) ?></td>
                <td><?= escape($workout['workout_length']) ?> min</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../private/templates/footer.php'; ?>
