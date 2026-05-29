<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$errors = [];

$set_id = $_GET['set_id'] ?? null;

if (!is_positive_integer($set_id)) {
    die('Invalid set ID.');
}

$stmt = $pdo->prepare("
    SELECT *
    FROM workout_set
    WHERE id = :id
");

$stmt->execute([
    ':id' => $set_id
]);

$set = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$set) {
    die('Workout set not found.');
}

$rep_amount = $set['rep_amount'];
$weight_amount = $set['weight_amount'];
$to_failure = $set['to_failure'];
$workout_id = $set['workout_id'];

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
            UPDATE workout_set
            SET
                rep_amount = :rep_amount,
                weight_amount = :weight_amount,
                to_failure = :to_failure
            WHERE id = :id
        ");

        $stmt->execute([
            ':rep_amount' => $rep_amount,
            ':weight_amount' => $weight_amount,
            ':to_failure' => $to_failure,
            ':id' => $set_id
        ]);

        redirect("workout_sets.php?workout_id=$workout_id");
    }
}

require_once '../private/templates/header.php';

?>

<a href="workout_sets.php?workout_id=<?= $workout_id ?>" class="back-link">
    Back to Workout Sets
</a>

<h1>Edit Workout Set</h1>

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
            value="<?= escape($rep_amount) ?>"
            min="1"
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
            value="<?= escape($weight_amount) ?>"
            step="0.01"
            min="0"
        >

    </div>

    <div class="form-group">

        <label for="to_failure">
            To Failure
        </label>

        <select id="to_failure" name="to_failure">

            <option
                value="0"
                <?= !$to_failure ? 'selected' : '' ?>
            >
                No
            </option>

            <option
                value="1"
                <?= $to_failure ? 'selected' : '' ?>
            >
                Yes
            </option>

        </select>

    </div>

    <div class="form-actions">

        <button type="submit" class="btn btn-primary">
            Save Changes
        </button>

    </div>

</form>

</div>

<?php require_once '../private/templates/footer.php'; ?>
