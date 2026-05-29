<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$id = $_GET['id'] ?? null;

if (!is_positive_integer($id)) {
    die('Invalid body part ID.');
}

$stmt = $pdo->prepare("
    SELECT *
    FROM body_part
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$body_part = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$body_part) {
    die('Body part not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        DELETE FROM exercise_body_part
        WHERE body_part_id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $stmt = $pdo->prepare("
        DELETE FROM body_part
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    redirect('body_parts.php');
}

require_once '../private/templates/header.php';

?>

<a href="body_parts.php" class="back-link">
    Back to Body Parts
</a>

<h1>Delete Body Part</h1>

<p>

Are you sure you want to delete:

<strong>

<?= escape($body_part['name']) ?>

</strong>

?

</p>

<form method="POST">

    <button type="submit" class="btn btn-danger">

        Delete Body Part

    </button>

</form>

<?php require_once '../private/templates/footer.php'; ?>
