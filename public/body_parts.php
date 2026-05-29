<?php

require_once '../private/db.php';
require_once '../private/helpers.php';

$stmt = $pdo->query("
    SELECT id, name
    FROM body_part
    ORDER BY name
");

$body_parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../private/templates/header.php';

?>

<h1>Body Parts</h1>

<p>
    <a href="body_part_create.php" class="btn btn-primary">
        Add Body Part
    </a>
</p>

<div class="table-wrap">

    <table>

        <thead>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Exercises</th>
                <th>Actions</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($body_parts as $body_part): ?>

            <tr>

                <td>
                    <?= $body_part['id'] ?>
                </td>

                <td>
                    <?= escape($body_part['name']) ?>
                </td>

                <td>

                    <a href="body_part_exercises.php?body_part_id=<?= $body_part['id'] ?>">

                        View Exercises

                    </a>

                </td>

                <td>

                    <a href="body_part_delete.php?id=<?= $body_part['id'] ?>">

                        Delete

                    </a>

                </td>

            </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php require_once '../private/templates/footer.php'; ?>
