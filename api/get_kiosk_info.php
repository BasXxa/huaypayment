<?php
include '../config/config.php';

header('Content-Type: application/json');

try {

    // รับค่า kiosk_code จาก GET request
    $kiosk_code = $_GET['kiosk_code'];

    // เตรียมและดำเนินการคำสั่ง SQL
    $stmt = $pdo->prepare("SELECT * FROM kiosks WHERE kiosk_code = :kiosk_code");
    $stmt->bindParam(':kiosk_code', $kiosk_code, PDO::PARAM_STR);
    $stmt->execute();

    // ดึงข้อมูลผลลัพธ์
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // ส่งข้อมูลในรูปแบบ JSON
    echo json_encode($data);

} catch (PDOException $e) {
    // ส่งข้อผิดพลาดในรูปแบบ JSON
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    // ส่งข้อผิดพลาดทั่วไปในรูปแบบ JSON
    echo json_encode(['error' => 'General error: ' . $e->getMessage()]);
}
