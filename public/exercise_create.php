<?php

require_once '../private/db.php';
require_once '../private/validation.php';
require_once '../private/helpers.php';

$errors = [];

$name = '';
$description = '';
$weight_required = 0;
$image_url = '';

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
            INSERT INTO exercise (
                name,
                description,
                weight_required,
                image_url
            )
            VALUES (
                :name,
                :description,
                :weight_required,
                :image_url
            )
        ");

        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':weight_required' => $weight_required,
            ':image_url' => $image_url
        ]);

        redirect('exercises.php');
    }
}

require_once '../private/templates/header.php';

?>

<h1>Create Exercise</h1>

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

            <option value="0">
                No
            </option>

            <option value="1">
                Yes
            </option>

        </select>

    </p>

    <button type="submit">
        Create Exercise
    </button>

</form>

<?php require_once '../private/templates/footer.php'; ?>
