<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$errors = [];
$name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = sanitize_string($_POST['name'] ?? '');

    if (!is_not_empty($name)) {
        $errors[] = 'Body part name is required.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO body_part (name)
                VALUES (:name)
            ");
            $stmt->execute([':name' => $name]);
            redirect('body_parts.php');
        } catch (PDOException $e) {
            $errors[] = 'Body part name must be unique.';
        }
    }
}

require_once '../private/templates/header.php';

?>

<a href="body_parts.php" class="back-link">Back to Body Parts</a>

<h1>Create Body Part</h1>

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
            <label for="name">Body Part Name</label>
            <input type="text" id="name" name="name" value="<?= escape($name) ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Body Part</button>
            <a href="body_parts.php" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>

<?php require_once '../private/templates/footer.php'; ?>
