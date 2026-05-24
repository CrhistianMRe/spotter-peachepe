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

<div class="page-header">
    <h1>Body Parts</h1>
    <a href="body_part_create.php" class="btn btn-primary">+ Add Body Part</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($body_parts as $body_part): ?>
            <tr>
                <td><?= $body_part['id'] ?></td>
                <td><?= escape($body_part['name']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../private/templates/footer.php'; ?>
