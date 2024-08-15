<?php
header('Content-Type: application/json');
require '../config/config.php';

// เริ่มเซสชัน
session_start();

// ตรวจสอบว่ามีค่า 'phone' ในเซสชันหรือไม่
if (!isset($_SESSION['user_phone'])) {
    echo json_encode(['status' => 'error', 'message' => 'Session phone is not set']);
    exit;
}

// ดึงค่า phone จากเซสชัน
$kiosk_account = $_SESSION['user_phone'];

$response = ['status' => 'error', 'message' => 'An error occurred'];

function thai_month($month)
{
    $thai_month_arr = [
        "01" => "มกราคม",
        "02" => "กุมภาพันธ์",
        "03" => "มีนาคม",
        "04" => "เมษายน",
        "05" => "พฤษภาคม",
        "06" => "มิถุนายน",
        "07" => "กรกฎาคม",
        "08" => "สิงหาคม",
        "09" => "กันยายน",
        "10" => "ตุลาคม",
        "11" => "พฤศจิกายน",
        "12" => "ธันวาคม"
    ];
    return isset($thai_month_arr[$month]) ? $thai_month_arr[$month] : '';
}

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $required_fields = [
        'ownerName', 'ownerPhone', 'salesPhone', 'adminPhone', 'kioskCode',
        'ownerAddress', 'salesName', 'kioskPrice', 'paymentMethod',
        'installmentMonths', 'zeroPercentMonths', 'interestPercent'
    ];

    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO kiosks (
            kiosk_account, owner_phone, sales_phone, admin_phone, kiosk_code, owner_name, owner_address, sales_name,
            kiosk_price, down_payment, payment_method, monthly_fixed_payment, monthly_percentage_payment, installment_months,
            installment_zero_percent_months, installment_interest_percent
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $kiosk_account,          // ค่าของ kiosk_account มาจากเซสชัน
        $data['ownerPhone'],
        $data['salesPhone'],
        $data['adminPhone'],
        $data['kioskCode'],
        $data['ownerName'],
        $data['ownerAddress'],
        $data['salesName'],
        $data['kioskPrice'],
        isset($data['downPayment']) && $data['downPayment'] != '' ? $data['downPayment'] : 0,
        $data['paymentMethod'],
        isset($data['monthlyFixedPayment']) && $data['monthlyFixedPayment'] != '' ? $data['monthlyFixedPayment'] : 0,
        isset($data['monthlyPercentagePayment']) && $data['monthlyPercentagePayment'] != '' ? $data['monthlyPercentagePayment'] : 0,
        $data['installmentMonths'],
        $data['zeroPercentMonths'],
        $data['interestPercent']
    ]);

    $kiosk_id = $pdo->lastInsertId();
    $kiosk_number = $data['kioskCode'];  // ดึง kiosk_number จาก kioskCode

    $kioskPrice = $data['kioskPrice'];
    $downPayment = isset($data['downPayment']) && $data['downPayment'] != '' ? $data['downPayment'] : 0;
    $interestPercent = $data['interestPercent'];
    $installmentMonths = $data['installmentMonths'];
    $zeroPercentMonths = $data['zeroPercentMonths'];

    // คำนวณยอดค้างชำระที่ต้องผ่อน
    $remaining_balance = $kioskPrice - $downPayment;
    $monthly_payment = $remaining_balance / $installmentMonths;

    for ($i = 0; $i < $installmentMonths; $i++) {
        $month_date = date('Y-m-d', strtotime("+$i month"));
        $interest_amount = 0;

        if ($i >= $zeroPercentMonths) {
            $interest_amount = $remaining_balance * ($interestPercent / 100);
        }

        $remaining_amount = $monthly_payment + $interest_amount;

        if ($month_date !== false) {
            $day = date('d', strtotime($month_date));
            $month = thai_month(date('m', strtotime($month_date)));
            $year = date('Y', strtotime($month_date));
            $month_date_thai = "$day-$month-$year";
        } else {
            $month_date_thai = null;
        }

        $status = 'pending';

        $stmt = $pdo->prepare("
            INSERT INTO installments (
                kiosk_id, month_date, installment_no, status, remaining_amount, owner_name, owner_phone, kiosk_account, kiosk_number
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $kiosk_id,
            $month_date_thai,
            $i + 1,
            $status,
            $remaining_amount,
            $data['ownerName'],
            $data['ownerPhone'],
            $kiosk_account,  // kiosk_account
            $kiosk_number    // kiosk_number
        ]);
    }

    $pdo->commit();

    $response = ['status' => 'success', 'message' => 'เพิ่มตู้และการผ่อนชำระสำเร็จแล้ว'];
} catch (Exception $e) {
    $pdo->rollBack();
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
