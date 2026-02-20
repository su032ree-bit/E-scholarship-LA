<?php
session_start();
include '../include/config.php';

// --- 1. ตรวจสอบว่ามี ID ส่งมาหรือไม่ ---
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ไม่ได้ระบุ ID ของปัญหาที่ต้องการลบ'];
    header('Location: issue.php');
    exit();
}

// --- 2. รับและป้องกันข้อมูล ID ---
$issue_id = mysqli_real_escape_string($connect1, $_GET['id']);

// --- 3. ลบข้อมูลออกจากตาราง tb_issue ---
$sql = "DELETE FROM tb_issue WHERE issue_id = '$issue_id'";

if (mysqli_query($connect1, $sql)) {
    // ตรวจสอบว่ามีการลบข้อมูลเกิดขึ้นจริง
    if (mysqli_affected_rows($connect1) > 0) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'ลบรายการแจ้งปัญหาสำเร็จ!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'ไม่พบรายการปัญหาที่ต้องการลบ (ID: '.$issue_id.')'];
    }
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . mysqli_error($connect1)];
}

// --- 4. กลับไปยังหน้า issue.php ---
header('Location: issue.php');
exit();
?>