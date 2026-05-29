<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$id = $_GET['id'] ?? null;

if (!is_positive_integer($id)) {
    require_once '../private/error.php';
    render_error('Invalid workout ID.');
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

$stmt->execute([':id' => $id]);

$workout = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$workout) {
    require_once '../private/error.php';
    render_error('Workout not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        DELETE FROM workout_set
        WHERE workout_id = :id
    ");

    $stmt->execute([':id' => $id]);

    $stmt = $pdo->prepare("
        DELETE FROM workout
        WHERE id = :id
    ");

    $stmt->execute([':id' => $id]);

    redirect('workouts.php');
}

require_once '../private/templates/header.php';

?>

<a href="workouts.php" class="back-link">Back to Workout History</a>

<h1>Delete Workout</h1>

<div class="confirm-card">

    <p class="confirm-message">
        Are you sure you want to delete the workout
        <strong><?= escape($workout['exercise_name']) ?></strong>
        on <?= escape($workout['workout_date']) ?>?
        All sets will also be deleted.
    </p>

    <form method="POST">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger">Delete Workout</button>
            <a href="workouts.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

</div>

<?php require_once '../private/templates/footer.php'; ?>
