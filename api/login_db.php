<?php
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];

    if ($phone) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        $user = $stmt->fetch();

        if ($user) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['sales_phone'] = $user['salesphone'];
            $_SESSION['admin_phone'] = $user['adminphone'];
            $_SESSION['user_firstname'] = $user['firstname'];
            $_SESSION['user_lastname'] = $user['lastname'];
            $_SESSION['user_subdistrict'] = $user['subdistrict'];
            $_SESSION['user_district'] = $user['district'];
            $_SESSION['user_address'] = $user['address'];
            $_SESSION['user_province'] = $user['province'];
            echo 'เข้าสู่ระบบสำเร็จ';
        } else {
            echo 'เบอร์มือถือนี้ไม่มีในระบบ';
        }
    } else {
        echo 'กรุณากรอกเบอร์มือถือ';
    }
}
?>
