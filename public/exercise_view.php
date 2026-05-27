<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$id = $_GET['id'] ?? null;

if (!is_positive_integer($id)) {
    die('Invalid exercise ID.');
}

$stmt = $pdo->prepare("
    SELECT *
    FROM exercise
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$exercise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exercise) {
    die('Exercise not found.');
}

$stmt = $pdo->prepare("
    SELECT
        body_part.id,
        body_part.name
    FROM exercise_body_part
    JOIN body_part
        ON exercise_body_part.body_part_id = body_part.id
    WHERE exercise_body_part.exercise_id = :exercise_id
    ORDER BY body_part.name
");

$stmt->execute([
    ':exercise_id' => $id
]);

$body_parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT
        id,
        workout_date,
        workout_length
    FROM workout
    WHERE exercise_id = :exercise_id
    ORDER BY workout_date DESC
");

$stmt->execute([
    ':exercise_id' => $id
]);

$workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_workouts = count($workouts);

require_once '../private/templates/header.php';

?>

<a href="exercises.php" class="back-link">
    Back to Exercise Library
</a>

<h1><?= escape($exercise['name']) ?></h1>

<div class="table-wrap">

    <table>

        <tr>
            <th>Description</th>

            <td>

                <?= escape($exercise['description'] ?? 'No description') ?>

            </td>
        </tr>

        <tr>
            <th>Weight Required</th>

            <td>

                <?= $exercise['weight_required'] ? 'Yes' : 'No' ?>

            </td>
        </tr>

        <tr>
            <th>Total Workouts</th>

            <td>

                <?= $total_workouts ?>

            </td>
        </tr>

    </table>

</div>

<h2>Body Parts</h2>

<div class="table-wrap">

    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>

        <?php foreach ($body_parts as $body_part): ?>

        <tr>

            <td>
                <?= $body_part['id'] ?>
            </td>

            <td>
                <?= escape($body_part['name']) ?>
            </td>

        </tr>

        <?php endforeach; ?>

    </table>

</div>

<h2>Workout History</h2>

<div class="table-wrap">

    <table>

        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Length</th>
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
                <?= escape($workout['workout_length']) ?>
                minutes
            </td>

        </tr>

        <?php endforeach; ?>

    </table>

</div>

<?php require_once '../private/templates/footer.php'; ?>
