<?php

$dbname = 'spotter';
$username = 'spotter';
$password = '12345678';

try {
    $pdo = new PDO(
        "mysql:unix-socket=/run/mysql/mysqld.sock;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
