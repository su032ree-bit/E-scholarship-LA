<?php
session_start();
include '../../include/config.php';

$st_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($st_id > 0) {
    // อัปเดต st_activate เป็น 1 (หมายถึงอนุมัติ/พิจารณาแล้ว)
    $sql = "UPDATE tb_student SET st_confirm = 1 WHERE st_id = '$st_id'";
    
    if (mysqli_query($connect1, $sql)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'อนุมัติคำขอรับทุนเรียบร้อยแล้ว'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการอนุมัติ'];
    }
}

// ย้อนกลับไปหน้าเดิม (ดึงค่า st_type กลับไปด้วยเพื่อให้เมนูยังค้างอยู่ที่เดิม)
$res = mysqli_query($connect1, "SELECT st_type FROM tb_student WHERE st_id = '$st_id'");
$row = mysqli_fetch_assoc($res);
header("Location: ../students/student_data.php?type=" . $row['st_type']);
exit();
?>