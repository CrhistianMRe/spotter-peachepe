<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$errors = [];

$id = $_GET['id'] ?? null;

if (!is_positive_integer($id)) {
    die('Invalid workout ID.');
}

$stmt = $pdo->prepare("
    SELECT *
    FROM workout
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$workout = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$workout) {
    die('Workout not found.');
}

$date = $workout['workout_date'];
$length = $workout['workout_length'];
$exercise_id = $workout['exercise_id'];

$stmt = $pdo->query("
    SELECT id, name
    FROM exercise
    ORDER BY name
");

$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $exercise_id = $_POST['exercise_id'] ?? '';
    $date = sanitize_string($_POST['workout_date'] ?? '');
    $length = sanitize_string($_POST['workout_length'] ?? '');

    if (!is_positive_integer($exercise_id)) {
        $errors[] = 'Please select a valid exercise.';
    }

    if (!is_not_empty($date)) {
        $errors[] = 'Workout date is required.';
    }

    if (!is_positive_integer($length)) {
        $errors[] = 'Workout length must be a positive integer.';
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            UPDATE workout
            SET
                workout_date = :workout_date,
                workout_length = :workout_length,
                exercise_id = :exercise_id
            WHERE id = :id
        ");

        $stmt->execute([
            ':workout_date' => $date,
            ':workout_length' => $length,
            ':exercise_id' => $exercise_id,
            ':id' => $id
        ]);

        redirect('workouts.php');
    }
}

require_once '../private/templates/header.php';

?>

<a href="workouts.php" class="back-link">
    Back to Workout History
</a>

<h1>Edit Workout</h1>

<?php if (!empty($errors)): ?>

<ul class="error-list">

    <?php foreach ($errors as $error): ?>

    <li><?= escape($error) ?></li>

    <?php endforeach; ?>

</ul>

<?php endif; ?>

<div class="form-card">

<form method="POST">

    <div class="form-group">

        <label for="exercise_id">
            Exercise
        </label>

        <select id="exercise_id" name="exercise_id">

            <?php foreach ($exercises as $exercise): ?>

            <option
                value="<?= $exercise['id'] ?>"
                <?= $exercise_id == $exercise['id'] ? 'selected' : '' ?>
            >

                <?= escape($exercise['name']) ?>

            </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="form-group">

        <label for="workout_date">
            Workout Date
        </label>

        <input
            type="date"
            id="workout_date"
            name="workout_date"
            value="<?= escape($date) ?>"
        >

    </div>

    <div class="form-group">

        <label for="workout_length">
            Workout Length (minutes)
        </label>

        <input
            type="number"
            id="workout_length"
            name="workout_length"
            value="<?= escape($length) ?>"
            min="1"
        >

    </div>

    <div class="form-actions">

        <button type="submit" class="btn btn-primary">
            Save Changes
        </button>

    </div>

</form>

</div>

<?php require_once '../private/templates/footer.php'; ?>
