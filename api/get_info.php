<?php
require_once '../config/config.php';


try {

    $stmt = $pdo->prepare("SELECT sales_phone, admin_phone FROM kiosks WHERE owner_phone = :user_phone");
    $stmt->bindParam(':user_phone', $_SESSION['user_phone']);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result !== false) {
        $sales_phone = $result['sales_phone'];
        $admin_phone = $result['admin_phone'];
    } else {
        $sales_phone = "ไม่มีข้อมูล";
        $admin_phone = "ไม่มีข้อมูล";
    }

} catch (PDOException $e) {
    echo "การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage();
}
?>