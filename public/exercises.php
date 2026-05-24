<?php

require_once '../private/db.php';
require_once '../private/helpers.php';

$stmt = $pdo->query("
    SELECT id, name, weight_required
    FROM exercise
    ORDER BY name
");

$exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../private/templates/header.php';

?>

<div class="page-header">
    <h1>Exercise Library</h1>
    <a href="exercise_create.php" class="btn btn-primary">+ Add Exercise</a>
</div>

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
