<?php
include '../config/config.php';

header('Content-Type: application/json');

try {

    $data = json_decode(file_get_contents("php://input"), true);

    $kiosk_name = $data['kioskName'];
    $owner_phone = $data['ownerPhone'];
    $sales_phone = $data['salesPhone'];
    $admin_phone = $data['adminPhone'];
    $owner_address = $data['ownerAddress'];
    $kiosk_code = $data['kioskCode'];

    $query = "UPDATE kiosks SET owner_name = :kiosk_name , owner_phone = :owner_phone, sales_phone = :sales_phone, admin_phone = :admin_phone, owner_address = :owner_address WHERE kiosk_code = :kiosk_code";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':kiosk_name', $kiosk_name, PDO::PARAM_STR);
    $stmt->bindParam(':owner_phone', $owner_phone, PDO::PARAM_STR);
    $stmt->bindParam(':sales_phone', $sales_phone, PDO::PARAM_STR);
    $stmt->bindParam(':admin_phone', $admin_phone, PDO::PARAM_STR);
    $stmt->bindParam(':owner_address', $owner_address, PDO::PARAM_STR);
    $stmt->bindParam(':kiosk_code', $kiosk_code, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "ข้อมูลถูกอัปเดตสำเร็จ"]);
    } else {
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการอัปเดตข้อมูล"]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'General error: ' . $e->getMessage()]);
}
