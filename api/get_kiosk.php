<?php
require '../config/config.php';

$userPhone = $_SESSION['user_phone'];

$sql = "SELECT * FROM kiosks WHERE kiosk_account = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userPhone]);
$kiosks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>