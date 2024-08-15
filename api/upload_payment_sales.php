<?php
header('Content-Type: application/json');


require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kiosk_number = $_POST['kiosk_number'];
    $month_date = $_POST['month_date'];
    $installment_no = $_POST['installment_no'];

    if (isset($_FILES['slip']) && $_FILES['slip']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['slip']['tmp_name'];
        $originalFileName = $_FILES['slip']['name'];
        $uploadDir = '../image/sales/';
        $dest_path = $uploadDir . $originalFileName;

        $sqlCheck = "SELECT * FROM payment_slips WHERE kiosk_number = ? AND installment_no = ?";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([$kiosk_number, $installment_no]);

        if ($stmtCheck->rowCount() > 0) {
            $existingSlip = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            if ($existingSlip['slip_path'] === $originalFileName) {
                echo json_encode(['success' => false, 'error' => 'Slip with this name already exists for the given kiosk number and installment number.']);
                exit;
            }
        }

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $sql = "INSERT INTO payment_slips (kiosk_number, month_date, installment_no, slip_path) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$kiosk_number, $month_date, $installment_no, $originalFileName]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error uploading the file.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No file uploaded or there was an upload error.']);
    }
}
?>
