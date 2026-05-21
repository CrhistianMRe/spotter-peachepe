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

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $stmt = $pdo->prepare("
            DELETE FROM exercise
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        redirect('exercises.php');

    } catch (PDOException $e) {

        $error = '
            Cannot delete exercise.
            It may already be used in workouts.
        ';
    }
}

require_once '../private/templates/header.php';

?>

<h1>Delete Exercise</h1>

<p>
    <a href="exercises.php">
        Back to Exercise Library
    </a>
</p>

<?php if ($error): ?>

    <p>
        <?= escape($error) ?>
    </p>

<?php endif; ?>

<p>

    Are you sure you want to delete:

    <strong>
        <?= escape($exercise['name']) ?>
    </strong>

    ?

</p>

<form method="POST">

    <button type="submit">
        Delete Exercise
    </button>

</form>

<?php require_once '../private/templates/footer.php'; ?>
