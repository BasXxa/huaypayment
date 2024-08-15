<?php
// ตั้งค่า URL ของ API
$apiUrl = "https://api.slipok.com/api/line/apikey/26449";
$apiKey = "SLIPOK83SA90D";

// ตรวจสอบว่ามีไฟล์แนบมาในคำขอหรือไม่
if (isset($_FILES['files'])) {
    $file = $_FILES['files'];
    
    // สร้างข้อมูล POST
    $postData = [
        'files' => curl_file_create($file['tmp_name'], $file['type'], $file['name']),
        'log' => true // ถ้าต้องการเช็คสลิปซ้ำ
    ];

    // ตรวจสอบว่ามีข้อมูล QR code หรือ URL หรือไม่
    if (!empty($_POST['data'])) {
        $postData['data'] = $_POST['data'];
    }

    if (!empty($_POST['url'])) {
        $postData['url'] = $_POST['url'];
    }

    // เริ่มต้น cURL
    $ch = curl_init($apiUrl);

    // ตั้งค่าต่างๆ ของ cURL
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "x-authorization: $apiKey"
    ]);

    // ส่งคำขอ
    $response = curl_exec($ch);

    // ตรวจสอบข้อผิดพลาด
    if ($response === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        echo $response;
    }

    // ปิดการเชื่อมต่อ cURL
    curl_close($ch);
} else {
    echo "No file uploaded.";
}
?>
