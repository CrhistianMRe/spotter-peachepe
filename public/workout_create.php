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

<a href="workouts.php" class="back-link">Back to Workout History</a>

<h1>Create Workout</h1>

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
            <label for="exercise_id">Exercise</label>
            <select id="exercise_id" name="exercise_id">
                <option value="">Select Exercise</option>
                <?php foreach ($exercises as $exercise): ?>
                <option value="<?= $exercise['id'] ?>"><?= escape($exercise['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="workout_date">Date</label>
            <input type="date" id="workout_date" name="workout_date" value="<?= escape($date) ?>">
        </div>

        <div class="form-group">
            <label for="workout_length">Length (minutes)</label>
            <input type="number" id="workout_length" name="workout_length" value="<?= escape($length) ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Workout</button>
            <a href="workouts.php" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>

<?php require_once '../private/templates/footer.php'; ?>
