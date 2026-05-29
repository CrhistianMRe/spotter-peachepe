<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$set_id = $_GET['set_id'] ?? null;
$workout_id = $_GET['workout_id'] ?? null;

if (
    !is_positive_integer($set_id)
    || !is_positive_integer($workout_id)
) {
    die('Invalid set.');
}

$stmt = $pdo->prepare("
    DELETE FROM workout_set
    WHERE id = :id
");

$stmt->execute([
    ':id' => $set_id
]);

redirect("workout_sets.php?workout_id=$workout_id");
