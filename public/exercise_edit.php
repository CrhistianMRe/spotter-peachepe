<?php

require_once '../private/db.php';
require_once '../private/validation.php';
require_once '../private/helpers.php';

$errors = [];

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

$name = $exercise['name'];
$description = $exercise['description'];
$weight_required = $exercise['weight_required'];
$image_url = $exercise['image_url'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = sanitize_string($_POST['name'] ?? '');
    $description = sanitize_string($_POST['description'] ?? '');
    $weight_required = $_POST['weight_required'] ?? '0';
    $image_url = sanitize_string($_POST['image_url'] ?? '');

    if (!is_not_empty($name)) {
        $errors[] = 'Exercise name is required.';
    }

    if (!is_valid_boolean($weight_required)) {
        $errors[] = 'Invalid weight requirement value.';
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            UPDATE exercise
            SET
                name = :name,
                description = :description,
                weight_required = :weight_required,
                image_url = :image_url
            WHERE id = :id
        ");

        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':weight_required' => $weight_required,
            ':image_url' => $image_url,
            ':id' => $id
        ]);

        redirect('exercises.php');
    }
}

require_once '../private/templates/header.php';

?>

<h1>Edit Exercise</h1>

<p>
    <a href="exercises.php">
        Back to Exercise Library
    </a>
</p>

<?php if (!empty($errors)): ?>

    <ul>

        <?php foreach ($errors as $error): ?>

            <li><?= escape($error) ?></li>

        <?php endforeach; ?>

    </ul>

<?php endif; ?>

<form method="POST">

    <p>

        <label>
            Exercise Name
        </label>

        <br>

        <input
            type="text"
            name="name"
            value="<?= escape($name) ?>"
        >

    </p>

    <p>

        <label>
            Description
        </label>

        <br>

        <textarea
            name="description"
        ><?= escape($description) ?></textarea>

    </p>

    <p>

        <label>
            Image URL
        </label>

        <br>

        <input
            type="text"
            name="image_url"
            value="<?= escape($image_url) ?>"
        >

    </p>

    <p>

        <label>
            Weight Required
        </label>

        <br>

        <select name="weight_required">

            <option
                value="0"
                <?= $weight_required ? '' : 'selected' ?>
            >
                No
            </option>

            <option
                value="1"
                <?= $weight_required ? 'selected' : '' ?>
            >
                Yes
            </option>

        </select>

    </p>

    <button type="submit">
        Update Exercise
    </button>

</form>

<?php require_once '../private/templates/footer.php'; ?>
