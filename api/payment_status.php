<?php
include '../config/config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['installment_no']) && isset($data['kiosk_number'])) {
    $installment_no = $data['installment_no'];
    $kiosk_number = $data['kiosk_number'];

    $sql = "UPDATE installments 
            SET status = 'paid', remaining_amount = 0 
            WHERE installment_no = :installment_no 
              AND kiosk_id = (SELECT id FROM kiosks WHERE kiosk_code = :kiosk_number)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':installment_no', $installment_no, PDO::PARAM_INT);
    $stmt->bindParam(':kiosk_number', $kiosk_number, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปเดตสถานะได้']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
