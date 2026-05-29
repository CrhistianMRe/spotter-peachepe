<?php

require_once '../private/db.php';
require_once '../private/validation.php';
require_once '../private/helpers.php';

$errors = [];

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

<a href="exercises.php" class="back-link">Back to Exercise Library</a>

<h1>Edit Exercise</h1>

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
            <label for="name">Exercise Name</label>
            <input type="text" id="name" name="name" value="<?= escape($name) ?>">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"><?= escape($description) ?></textarea>
        </div>

        <div class="form-group">
            <label for="image_url">Image URL</label>
            <input type="text" id="image_url" name="image_url" value="<?= escape($image_url) ?>">
        </div>

        <div class="form-group">
            <label for="weight_required">Weight Required</label>
            <select id="weight_required" name="weight_required">
                <option value="0" <?= $weight_required ? '' : 'selected' ?>>No</option>
                <option value="1" <?= $weight_required ? 'selected' : '' ?>>Yes</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Exercise</button>
            <a href="exercises.php" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>

<?php require_once '../private/templates/footer.php'; ?>
