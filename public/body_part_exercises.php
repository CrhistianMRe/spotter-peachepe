<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$body_part_id = $_GET['body_part_id'] ?? null;

if (!is_positive_integer($body_part_id)) {
    die('Invalid body part ID.');
}

$stmt = $pdo->prepare("
    SELECT *
    FROM body_part
    WHERE id = :id
");

$stmt->execute([
    ':id' => $body_part_id
]);

$body_part = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$body_part) {
    die('Body part not found.');
}

$stmt = $pdo->prepare("
    SELECT
        exercise.id,
        exercise.name,
        exercise.weight_required
    FROM exercise_body_part
    JOIN exercise
        ON exercise_body_part.exercise_id = exercise.id
    WHERE exercise_body_part.body_part_id = :body_part_id
    ORDER BY exercise.name
");

$stmt->execute([
    ':body_part_id' => $body_part_id
]);

$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../private/templates/header.php';

?>

<h1>

Exercises for

<?= escape($body_part['name']) ?>

</h1>

<p>
    <a href="body_parts.php">
        Back to Body Parts
    </a>
</p>

<table border="1" cellpadding="5">

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Weight Required</th>
    </tr>

    <?php foreach ($exercises as $exercise): ?>

    <tr>

        <td>
            <?= $exercise['id'] ?>
        </td>

        <td>
            <?= escape($exercise['name']) ?>
        </td>

        <td>
            <?= $exercise['weight_required'] ? 'Yes' : 'No' ?>
        </td>

    </tr>

    <?php endforeach; ?>

</table>

<?php require_once '../private/templates/footer.php'; ?>
