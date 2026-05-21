<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$errors = [];

$workout_id = $_GET['workout_id'] ?? null;

if (!is_positive_integer($workout_id)) {
    die('Invalid workout ID.');
}

$stmt = $pdo->prepare("
    SELECT
        workout.id,
        workout.workout_date,
        exercise.name AS exercise_name
    FROM workout
    JOIN exercise
        ON workout.exercise_id = exercise.id
    WHERE workout.id = :id
");

$stmt->execute([
    ':id' => $workout_id
]);

$workout = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$workout) {
    die('Workout not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $rep_amount = $_POST['rep_amount'] ?? '';
    $weight_amount = $_POST['weight_amount'] ?? '';
    $to_failure = $_POST['to_failure'] ?? '0';

    if (!is_positive_integer($rep_amount)) {
        $errors[] = 'Reps must be a positive integer.';
    }

    if (!is_non_negative_number($weight_amount)) {
        $errors[] = 'Weight must be non-negative.';
    }

    if (!is_valid_boolean($to_failure)) {
        $errors[] = 'Invalid failure value.';
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            INSERT INTO workout_set (
                rep_amount,
                weight_amount,
                to_failure,
                workout_id
            )
            VALUES (
                :rep_amount,
                :weight_amount,
                :to_failure,
                :workout_id
            )
        ");

        $stmt->execute([
            ':rep_amount' => $rep_amount,
            ':weight_amount' => $weight_amount,
            ':to_failure' => $to_failure,
            ':workout_id' => $workout_id
        ]);

        redirect("workout_sets.php?workout_id=$workout_id");
    }
}

$stmt = $pdo->prepare("
    SELECT *
    FROM workout_set
    WHERE workout_id = :workout_id
    ORDER BY id
");

$stmt->execute([
    ':workout_id' => $workout_id
]);

$sets = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../private/templates/header.php';

?>

<h1>

Workout Sets

</h1>

<p>

Workout:

<strong>

<?= escape($workout['exercise_name']) ?>

</strong>

on

<?= escape($workout['workout_date']) ?>

</p>

<p>
    <a href="workouts.php">
        Back to Workouts
    </a>
</p>

<?php if (!empty($errors)): ?>

    <ul>

        <?php foreach ($errors as $error): ?>

            <li><?= escape($error) ?></li>

        <?php endforeach; ?>

    </ul>

<?php endif; ?>

<h2>Add Set</h2>

<form method="POST">

    <p>

        <label>
            Reps
        </label>

        <br>

        <input
            type="number"
            name="rep_amount"
        >

    </p>

    <p>

        <label>
            Weight
        </label>

        <br>

        <input
            type="number"
            step="0.01"
            name="weight_amount"
        >

    </p>

    <p>

        <label>
            To Failure
        </label>

        <br>

        <select name="to_failure">

            <option value="0">
                No
            </option>

            <option value="1">
                Yes
            </option>

        </select>

    </p>

    <button type="submit">
        Add Set
    </button>

</form>

<h2>Existing Sets</h2>

<table border="1" cellpadding="5">

    <tr>
        <th>ID</th>
        <th>Reps</th>
        <th>Weight</th>
        <th>To Failure</th>
    </tr>

    <?php foreach ($sets as $set): ?>

    <tr>

        <td>
            <?= $set['id'] ?>
        </td>

        <td>
            <?= escape($set['rep_amount']) ?>
        </td>

        <td>
            <?= escape($set['weight_amount']) ?>
        </td>

        <td>
            <?= $set['to_failure'] ? 'Yes' : 'No' ?>
        </td>

    </tr>

    <?php endforeach; ?>

</table>

<?php require_once '../private/templates/footer.php'; ?>
