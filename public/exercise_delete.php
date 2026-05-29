<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$id = $_GET['id'] ?? null;

if (!is_positive_integer($id)) {
    require_once '../private/error.php';
    render_error('Invalid exercise ID.');
}

$stmt = $pdo->prepare("
    SELECT *
    FROM exercise
    WHERE id = :id
");

$stmt->execute([':id' => $id]);

$exercise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exercise) {
    require_once '../private/error.php';
    render_error('Exercise not found.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $stmt = $pdo->prepare("
            DELETE FROM exercise
            WHERE id = :id
        ");

        $stmt->execute([':id' => $id]);

        redirect('exercises.php');

    } catch (PDOException $e) {

        $error = 'Cannot delete exercise. It may already be used in workouts.';
    }
}

require_once '../private/templates/header.php';

?>

<a href="exercises.php" class="back-link">Back to Exercise Library</a>

<h1>Delete Exercise</h1>

<div class="confirm-card">

    <?php if ($error): ?>
    <ul class="error-list">
        <li><?= escape($error) ?></li>
    </ul>
    <?php endif; ?>

    <p class="confirm-message">
        Are you sure you want to delete <strong><?= escape($exercise['name']) ?></strong>?
        This action cannot be undone.
    </p>

    <form method="POST">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger">Delete Exercise</button>
            <a href="exercises.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

</div>

<?php require_once '../private/templates/footer.php'; ?>
