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

<h1>Create Body Part</h1>

<p>
    <a href="body_parts.php">Back to Body Parts</a>
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
        <label>Body Part Name</label>
        <br>
        <input type="text" name="name" value="<?= escape($name) ?>">
    </p>
    <button type="submit">Create Body Part</button>
</form>

<?php require_once '../private/templates/footer.php'; ?>
