<?php
session_start();
include '../../include/config.php';

// ตรวจสอบว่าเป็น POST request และมีข้อมูลครบ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    !empty($_POST['committee_username']) && 
    !empty($_POST['committee_password']) && 
    !empty($_POST['committee_fullname'])) {

    // 1. รับและป้องกันข้อมูล
    $username = mysqli_real_escape_string($connect1, $_POST['committee_username']);
    $password = mysqli_real_escape_string($connect1, $_POST['committee_password']);
    $fullname = mysqli_real_escape_string($connect1, $_POST['committee_fullname']);

    // (แนะนำ) ในระบบจริงควร hash รหัสผ่านก่อนเก็บ
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 2. สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูล โดยกำหนด tc_type = 5 (คณะกรรมการ)
    $sql = "INSERT INTO tb_teacher (tc_user, tc_pass, tc_name, tc_type) 
            VALUES ('$username', '$password', '$fullname', 5)";

    // 3. ประมวลผลและสร้างข้อความแจ้งเตือน
    if (mysqli_query($connect1, $sql)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'เพิ่มข้อมูลคณะกรรมการสำเร็จ!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล: ' . mysqli_error($connect1)];
    }

} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'กรุณากรอกข้อมูลให้ครบถ้วน'];
}

// 4. กลับไปยังหน้า committees.php
header('Location: ../committees/committees.php');
exit();
?>