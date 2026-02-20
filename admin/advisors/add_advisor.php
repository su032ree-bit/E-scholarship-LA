<?php
session_start();
include '../../include/config.php';

// ตรวจสอบว่าเป็นการส่งข้อมูลแบบ POST และมีข้อมูลชื่อมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['advisor_name'])) {

    // 1. รับและป้องกันข้อมูล
    $advisor_name = mysqli_real_escape_string($connect1, $_POST['advisor_name']);

    // 2. สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูล โดยกำหนด tc_type = 4 (อาจารย์ที่ปรึกษา)
    $sql = "INSERT INTO tb_teacher (tc_name, tc_type) VALUES ('$advisor_name', 4)";

    // 3. ประมวลผลและสร้างข้อความแจ้งเตือน
    if (mysqli_query($connect1, $sql)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'เพิ่มข้อมูลอาจารย์ที่ปรึกษาสำเร็จ!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล: ' . mysqli_error($connect1)];
    }

} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'กรุณากรอกชื่อ-สกุล'];
}

// 4. กลับไปยังหน้า advisors.php
header('Location: ../../admin/advisors/advisors.php');
exit();
?>