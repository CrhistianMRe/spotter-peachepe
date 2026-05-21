<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$errors = [];

$date = '';
$length = '';

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
            INSERT INTO workout (
                workout_date,
                workout_length,
                exercise_id
            )
            VALUES (
                :workout_date,
                :workout_length,
                :exercise_id
            )
        ");

        $stmt->execute([
            ':workout_date' => $date,
            ':workout_length' => $length,
            ':exercise_id' => $exercise_id
        ]);

        redirect('workouts.php');
    }
}

require_once '../private/templates/header.php';

?>

<h1>Create Workout</h1>

<p>
    <a href="workouts.php">
        Back to Workout History
    </a>
</p>

<?php if (!empty($errors)): ?>

    <ul>

        <?php foreach ($errors as $error): ?>

            <li><?= escape($error) ?></li>

        <?php endforeach; ?>

    </ul>

<?php endif; ?>

<form method="POST">

    <p>

        <label>
            Exercise
        </label>

        <br>

        <select name="exercise_id">

            <option value="">
                Select Exercise
            </option>

            <?php foreach ($exercises as $exercise): ?>

                <option value="<?= $exercise['id'] ?>">

                    <?= escape($exercise['name']) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </p>

    <p>

        <label>
            Workout Date
        </label>

        <br>

        <input
            type="date"
            name="workout_date"
            value="<?= escape($date) ?>"
        >

    </p>

    <p>

        <label>
            Workout Length (minutes)
        </label>

        <br>

        <input
            type="number"
            name="workout_length"
            value="<?= escape($length) ?>"
        >

    </p>

    <button type="submit">
        Create Workout
    </button>

</form>

<?php require_once '../private/templates/footer.php'; ?>
