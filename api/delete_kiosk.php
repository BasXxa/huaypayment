<?php
include '../config/config.php';

header('Content-Type: application/json');

// ตรวจสอบว่ารับข้อมูล POST หรือไม่
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['kiosk_code'])) {
    $kiosk_code = $data['kiosk_code'];

    // เริ่มการ transaction
    $pdo->beginTransaction();

    try {
        // ลบข้อมูลจากตาราง installments ที่เกี่ยวข้องกับตู้
        $sqlInstallments = "DELETE FROM installments WHERE kiosk_id = (SELECT id FROM kiosks WHERE kiosk_code = ?)";
        $stmtInstallments = $pdo->prepare($sqlInstallments);
        $stmtInstallments->execute([$kiosk_code]);

        // ลบข้อมูลตู้จากฐานข้อมูล
        $sqlKiosk = "DELETE FROM kiosks WHERE kiosk_code = ?";
        $stmtKiosk = $pdo->prepare($sqlKiosk);
        $stmtKiosk->execute([$kiosk_code]);

        // ตรวจสอบว่ามีการลบข้อมูลจากตาราง kiosks หรือไม่
        if ($stmtKiosk->rowCount() > 0) {
            // ถ้าสำเร็จ ให้ commit transaction
            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'ลบตู้และข้อมูลการผ่อนสำเร็จ']);
        } else {
            // ถ้าไม่พบข้อมูลตู้ ยกเลิก transaction
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูลตู้']);
        }
    } catch (Exception $e) {
        // เกิดข้อผิดพลาด ยกเลิก transaction
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
?>
