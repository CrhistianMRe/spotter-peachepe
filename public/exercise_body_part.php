<?php

require_once '../private/db.php';
require_once '../private/helpers.php';
require_once '../private/validation.php';

$errors = [];

$exercise_id = $_GET['exercise_id'] ?? null;

if (!is_positive_integer($exercise_id)) {
    require_once '../private/error.php';
    render_error('Invalid exercise ID.');
}

$stmt = $pdo->prepare("
    SELECT * FROM exercise WHERE id = :id
");
$stmt->execute([':id' => $exercise_id]);
$exercise = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exercise) {
    require_once '../private/error.php';
    render_error('Exercise not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $body_part_id = $_POST['body_part_id'] ?? '';

    if (!is_positive_integer($body_part_id)) {
        $errors[] = 'Please select a valid body part.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO exercise_body_part (exercise_id, body_part_id)
                VALUES (:exercise_id, :body_part_id)
            ");
            $stmt->execute([
                ':exercise_id' => $exercise_id,
                ':body_part_id' => $body_part_id
            ]);
            redirect("exercise_body_part.php?exercise_id=$exercise_id");
        } catch (PDOException $e) {
            $errors[] = 'Relationship already exists.';
        }
    }
}

$stmt = $pdo->query("
    SELECT id, name FROM body_part ORDER BY name
");
$body_parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT body_part.id, body_part.name
    FROM exercise_body_part
    JOIN body_part ON exercise_body_part.body_part_id = body_part.id
    WHERE exercise_body_part.exercise_id = :exercise_id
    ORDER BY body_part.name
");
$stmt->execute([':exercise_id' => $exercise_id]);
$assigned_body_parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../private/templates/header.php';

?>

<h1>Exercise Body Parts</h1>

<p>Exercise: <strong><?= escape($exercise['name']) ?></strong></p>

<p>
    <a href="exercises.php">Back to Exercises</a>
</p>

<?php if (!empty($errors)): ?>
<ul>
    <?php foreach ($errors as $error): ?>
    <li><?= escape($error) ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<h2>Assign Body Part</h2>

<form method="POST">
    <p>
        <label>Body Part</label>
        <br>
        <select name="body_part_id">
            <option value="">Select Body Part</option>
            <?php foreach ($body_parts as $body_part): ?>
            <option value="<?= $body_part['id'] ?>"><?= escape($body_part['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <button type="submit">Assign Body Part</button>
</form>

<h2>Assigned Body Parts</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Name</th>
    </tr>
    <?php foreach ($assigned_body_parts as $body_part): ?>
    <tr>
        <td><?= $body_part['id'] ?></td>
        <td><?= escape($body_part['name']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require_once '../private/templates/footer.php'; ?>
