<?php

require_once '../private/db.php';
require_once '../private/helpers.php';

$selected_body_part = $_GET['body_part_id'] ?? '';

$stmt = $pdo->query("
    SELECT id, name
    FROM body_part
    ORDER BY name
");

$body_parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (is_numeric($selected_body_part) && $selected_body_part !== '') {

    $stmt = $pdo->prepare("
        SELECT DISTINCT
            exercise.id,
            exercise.name,
            exercise.weight_required
        FROM exercise
        JOIN exercise_body_part
            ON exercise.id = exercise_body_part.exercise_id
        WHERE exercise_body_part.body_part_id = :body_part_id
        ORDER BY exercise.name
    ");

    $stmt->execute([
        ':body_part_id' => $selected_body_part
    ]);

} else {

    $stmt = $pdo->query("
        SELECT
            id,
            name,
            weight_required
        FROM exercise
        ORDER BY name
    ");
}

$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../private/templates/header.php';

?>

<div class="page-header">
    <h1>Exercise Library</h1>
    <a href="exercise_create.php" class="btn btn-primary">+ Add Exercise</a>
</div>

<form method="GET" class="filter-bar">
    <label for="body_part_id">Filter by Body Part</label>
    <select id="body_part_id" name="body_part_id">
        <option value="">All Body Parts</option>
        <?php foreach ($body_parts as $body_part): ?>
        <option
            value="<?= $body_part['id'] ?>"
            <?= $selected_body_part == $body_part['id'] ? 'selected' : '' ?>
        ><?= escape($body_part['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-secondary">Filter</button>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Weight Required</th>
                <th>Body Parts</th>
                <th>Actions</th>
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
                <td>
                    <a href="exercise_body_part.php?exercise_id=<?= $exercise['id'] ?>" class="action-link view">
                        Manage
                    </a>
                </td>
                <td>
                    <div class="actions">
                        <a href="exercise_view.php?id=<?= $exercise['id'] ?>" class="action-link view">View</a>
                        <a href="exercise_edit.php?id=<?= $exercise['id'] ?>" class="action-link edit">Edit</a>
                        <a href="exercise_delete.php?id=<?= $exercise['id'] ?>" class="action-link delete">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../private/templates/footer.php'; ?>
