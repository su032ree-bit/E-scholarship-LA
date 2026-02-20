<?php
session_start();
include '../../include/config.php';

// ตรวจสอบว่าเป็น POST request และมีข้อมูลครบ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_POST['committee_id']) &&
    !empty($_POST['committee_username']) && 
    !empty($_POST['committee_fullname'])) {

    // 1. รับและป้องกันข้อมูล
    $id = mysqli_real_escape_string($connect1, $_POST['committee_id']);
    $username = mysqli_real_escape_string($connect1, $_POST['committee_username']);
    $password = mysqli_real_escape_string($connect1, $_POST['committee_password']);
    $fullname = mysqli_real_escape_string($connect1, $_POST['committee_fullname']);

    // 2. สร้างคำสั่ง SQL เพื่ออัปเดต
    $sql = "UPDATE tb_teacher SET 
                tc_user = '$username', 
                tc_name = '$fullname'";

    // อัปเดตรหัสผ่านเฉพาะเมื่อมีการกรอกรหัสผ่านใหม่เท่านั้น
    if (!empty($password)) {
        // (แนะนำ) ในระบบจริงควร hash รหัสผ่าน
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", tc_pass = '$password'";
    }

    $sql .= " WHERE tc_id = '$id'";

    // 3. ประมวลผลและสร้างข้อความแจ้งเตือน
    if (mysqli_query($connect1, $sql)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'อัปเดตข้อมูลคณะกรรมการสำเร็จ!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการอัปเดต: ' . mysqli_error($connect1)];
    }

} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ข้อมูลไม่ครบถ้วน'];
}

// 4. กลับไปยังหน้า committees.php
header('Location: ../committees/committees.php');
exit();
?>