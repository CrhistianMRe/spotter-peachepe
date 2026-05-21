<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$id = $_GET['id'] ?? null;

if (!is_positive_integer($id)) {
    die('Invalid workout ID.');
}

$stmt = $pdo->prepare("
    SELECT workout.id,
           exercise.name AS exercise_name,
           workout.workout_date
    FROM workout
    JOIN exercise
        ON workout.exercise_id = exercise.id
    WHERE workout.id = :id
");

$stmt->execute([
    ':id' => $id
]);

$workout = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$workout) {
    die('Workout not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        DELETE FROM workout_set
        WHERE workout_id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $stmt = $pdo->prepare("
        DELETE FROM workout
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    redirect('workouts.php');
}

require_once '../private/templates/header.php';

?>

<h1>Delete Workout</h1>

<p>
    <a href="workouts.php">
        Back to Workout History
    </a>
</p>

<p>

Are you sure you want to delete:

<strong>

<?= escape($workout['exercise_name']) ?>

</strong>

on

<?= escape($workout['workout_date']) ?>

?

</p>

<form method="POST">

    <button type="submit">
        Delete Workout
    </button>

</form>

<?php require_once '../private/templates/footer.php'; ?>
