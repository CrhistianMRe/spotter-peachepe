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

$stmt->execute([':id' => $body_part_id]);

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

$stmt->execute([':body_part_id' => $body_part_id]);

$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../private/templates/header.php';

?>

<a href="body_parts.php" class="back-link">Back to Body Parts</a>

<h1>Exercises — <?= escape($body_part['name']) ?></h1>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Weight Required</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($exercises as $exercise): ?>
            <tr>
                <td><?= $exercise['id'] ?></td>
                <td><?= escape($exercise['name']) ?></td>
                <td>
                    <?php if ($exercise['weight_required']): ?>
                        <span class="badge badge-yes">Yes</span>
                    <?php else: ?>
                        <span class="badge badge-no">No</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../private/templates/footer.php'; ?>
