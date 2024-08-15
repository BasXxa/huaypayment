<?php
include '../config/config.php';

$owner_phone = isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : '';

if ($owner_phone) {
    $sql = "SELECT kiosks.owner_phone, kiosks.kiosk_code, installments.kiosk_id, installments.month_date AS month_year, installments.installment_no, installments.status, installments.remaining_amount 
            FROM installments 
            JOIN kiosks ON installments.kiosk_id = kiosks.id 
            WHERE kiosks.kiosk_account = :owner_phone";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':owner_phone', $owner_phone, PDO::PARAM_STR);
        $stmt->execute();
        $installments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $groupedInstallments = [];
        foreach ($installments as $installment) {
            $kiosk_code = $installment['kiosk_code'];
            if (!isset($groupedInstallments[$kiosk_code])) {
                $groupedInstallments[$kiosk_code] = [];
            }
            $groupedInstallments[$kiosk_code][] = $installment;
        }
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
        exit;
    }
} else {
    $groupedInstallments = [];
}
?>