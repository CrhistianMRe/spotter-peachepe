<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$exercise_id = $_GET['exercise_id'] ?? null;
$body_part_id = $_GET['body_part_id'] ?? null;

if (
    !is_positive_integer($exercise_id)
    || !is_positive_integer($body_part_id)
) {
    die('Invalid relationship.');
}

$stmt = $pdo->prepare("
    DELETE FROM exercise_body_part
    WHERE
        exercise_id = :exercise_id
        AND body_part_id = :body_part_id
");

$stmt->execute([
    ':exercise_id' => $exercise_id,
    ':body_part_id' => $body_part_id
]);

redirect("exercise_body_part.php?exercise_id=$exercise_id");
