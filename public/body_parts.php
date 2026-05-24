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
    <a href="body_part_create.php">Add Body Part</a>
</p>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Name</th>
    </tr>
    <?php foreach ($body_parts as $body_part): ?>
    <tr>
        <td><?= $body_part['id'] ?></td>
        <td><?= escape($body_part['name']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require_once '../private/templates/footer.php'; ?>
