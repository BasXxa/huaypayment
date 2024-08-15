<?php

$title = 'HUAY | PAYMENT';

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'huay_payment';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}