<?php
session_start();
include '../../include/config.php';

// ตรวจสอบว่าเป็น POST request และมีข้อมูลครบหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['advisor_id']) && !empty($_POST['advisor_name'])) {

    // 1. รับและป้องกันข้อมูล
    $advisor_id = mysqli_real_escape_string($connect1, $_POST['advisor_id']);
    $advisor_name = mysqli_real_escape_string($connect1, $_POST['advisor_name']);

    // 2. สร้างคำสั่ง SQL เพื่ออัปเดต
    $sql = "UPDATE tb_teacher SET tc_name = '$advisor_name' WHERE tc_id = '$advisor_id'";

    // 3. ประมวลผลและสร้างข้อความแจ้งเตือน
    if (mysqli_query($connect1, $sql)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'อัปเดตข้อมูลอาจารย์สำเร็จ!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการอัปเดต: ' . mysqli_error($connect1)];
    }

} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ข้อมูลไม่ครบถ้วน'];
}

// 4. กลับไปยังหน้า advisors.php
header('Location: ../../admin/advisors/advisors.php');
exit();
?>