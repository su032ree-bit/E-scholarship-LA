<?php
session_start();
include '../../include/config.php';

// ตรวจสอบว่าเป็นการส่งข้อมูลแบบ POST และมีข้อมูลชื่อสาขามาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['major_name'])) {

    // 1. รับและป้องกันข้อมูล
    $major_name = mysqli_real_escape_string($connect1, $_POST['major_name']);

    // 2. สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูล
    $sql = "INSERT INTO tb_program (g_program) VALUES ('$major_name')";

    // 3. ประมวลผลและสร้างข้อความแจ้งเตือน
    if (mysqli_query($connect1, $sql)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'เพิ่มสาขาวิชา "' . htmlspecialchars($major_name) . '" สำเร็จ!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล: ' . mysqli_error($connect1)];
    }

} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'กรุณากรอกชื่อสาขาวิชา'];
}

// 4. กลับไปยังหน้า majors.php
header('Location: ../settings/majors.php');
exit();
?>