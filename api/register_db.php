<?php
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $salesphone = $_POST['salesphone'];
    $adminphone = $_POST['adminphone'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $subdistrict = $_POST['subdistrict'];
    $district = $_POST['district'];
    $address = $_POST['address'];
    $province = $_POST['province'];

    if ($phone && $salesphone && $adminphone && $firstname && $lastname && $subdistrict && $district && $address && $province) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        $user = $stmt->fetch();

        if ($user) {
            echo 'เบอร์มือถือนี้ถูกใช้ไปแล้ว';
        } else {
            // เพิ่มคอมม่าหลัง adminphone
            $stmt = $pdo->prepare("INSERT INTO users (phone, salesphone, adminphone, firstname, lastname, subdistrict, district, address, province) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$phone, $salesphone, $adminphone, $firstname, $lastname, $subdistrict, $district, $address, $province])) {
                echo 'สมัครสมาชิกสำเร็จ';
            } else {
                echo 'เกิดข้อผิดพลาดในการสมัครสมาชิก';
            }
        }
    } else {
        echo 'กรุณากรอกข้อมูลให้ครบถ้วน';
    }
}
