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

<h1>Exercise Library</h1>

<p>
    <a href="exercise_create.php">
        Add Exercise
    </a>
</p>

<table border="1" cellpadding="5">

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Weight Required</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($exercises as $exercise): ?>

    <tr>

        <td>
            <?= $exercise['id'] ?>
        </td>

        <td>
            <?= escape($exercise['name']) ?>
        </td>

        <td>
            <?= $exercise['weight_required'] ? 'Yes' : 'No' ?>
        </td>

        <td>

            <a href="exercise_edit.php?id=<?= $exercise['id'] ?>">
                Edit
            </a>

            |

            <a href="exercise_delete.php?id=<?= $exercise['id'] ?>">
                Delete
            </a>

        </td>

    </tr>

    <?php endforeach; ?>

</table>

<?php require_once '../private/templates/footer.php'; ?>
