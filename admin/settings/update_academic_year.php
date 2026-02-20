<?php
session_start();
include '../../include/config.php';

// ตรวจสอบว่าเป็นการส่งข้อมูลแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../settings/academic_year.php');
    exit();
}

// 1. รับและป้องกันข้อมูลจากฟอร์ม
$academic_year = mysqli_real_escape_string($connect1, $_POST['academic_year']);
$web_url = mysqli_real_escape_string($connect1, $_POST['web_url']);

// 2. สร้างคำสั่ง SQL เพื่ออัปเดตข้อมูล
// เราจะอัปเดตแถวที่มี y_id = 1 เสมอ สำหรับข้อมูลตั้งค่าหลัก
$sql = "UPDATE tb_year SET 
            y_year = '$academic_year', 
            y_url = '$web_url' 
        WHERE y_id = 1";

// 3. ประมวลผลและส่งข้อความแจ้งเตือน
if (mysqli_query($connect1, $sql)) {
    $_SESSION['message'] = ['type' => 'success', 'text' => 'บันทึกข้อมูลปีการศึกษาสำเร็จ!'];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . mysqli_error($connect1)];
}

// 4. กลับไปยังหน้าเดิม
header('Location: ../settings/academic_year.php');
exit();

?>