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

<a href="workouts.php" class="back-link">
    Back to Workout History
</a>

<div class="page-header">

    <h1>

        <?= escape($workout['exercise_name']) ?>

    </h1>

    <span style="color: var(--text-muted); font-size: 0.9rem;">

        <?= escape($workout['workout_date']) ?>

    </span>

</div>

<h2>Add Set</h2>

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

            <label for="rep_amount">
                Reps
            </label>

            <input
                type="number"
                id="rep_amount"
                name="rep_amount"
                min="1"
                required
            >

        </div>

        <div class="form-group">

            <label for="weight_amount">
                Weight (kg)
            </label>

            <input
                type="number"
                id="weight_amount"
                name="weight_amount"
                step="0.01"
                min="0"
                required
            >

        </div>

        <div class="form-group">

            <label for="to_failure">
                To Failure
            </label>

            <select id="to_failure" name="to_failure">

                <option value="0">
                    No
                </option>

                <option value="1">
                    Yes
                </option>

            </select>

        </div>

        <div class="form-actions">

            <button type="submit" class="btn btn-primary">

                Add Set

            </button>

        </div>

    </form>

</div>

<h2>Sets</h2>

<div class="table-wrap">

    <table>

        <thead>

            <tr>
                <th>#</th>
                <th>Reps</th>
                <th>Weight</th>
                <th>To Failure</th>
                <th>Actions</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($sets as $i => $set): ?>

            <tr>

                <td>
                    <?= $i + 1 ?>
                </td>

                <td>
                    <?= escape($set['rep_amount']) ?>
                </td>

                <td>
                    <?= escape($set['weight_amount']) ?> kg
                </td>

                <td>

                    <?php if ($set['to_failure']): ?>

                        <span class="badge badge-yes">
                            Yes
                        </span>

                    <?php else: ?>

                        <span class="badge badge-no">
                            No
                        </span>

                    <?php endif; ?>

                </td>

                <td>

                    <div class="actions">

                        <a
                            href="workout_set_edit.php?set_id=<?= $set['id'] ?>"
                            class="action-link edit"
                        >

                            Edit

                        </a>

                        <a
                            href="workout_set_delete.php?set_id=<?= $set['id'] ?>&workout_id=<?= $workout_id ?>"
                            class="action-link delete"
                        >

                            Delete

                        </a>

                    </div>

                </td>

            </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php require_once '../private/templates/footer.php'; ?>
