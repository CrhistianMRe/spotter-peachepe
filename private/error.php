<?php

require_once __DIR__ . '/templates/header.php';

function render_error($message)
{
    echo "<h1>Error</h1>";

    echo "<p>" . htmlspecialchars($message) . "</p>";

    require_once __DIR__ . '/templates/footer.php';

    exit;
}
