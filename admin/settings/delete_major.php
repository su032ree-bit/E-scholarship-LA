<?php
session_start();
include '../../include/config.php';

// ตรวจสอบว่ามี ID ส่งมาหรือไม่
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    // 1. รับและป้องกันข้อมูล ID
    $major_id = mysqli_real_escape_string($connect1, $_GET['id']);

    // 2. สร้างคำสั่ง SQL เพื่อลบข้อมูล
    $sql = "DELETE FROM tb_program WHERE g_id = '$major_id'";

    // 3. ประมวลผลและสร้างข้อความแจ้งเตือน
    if (mysqli_query($connect1, $sql)) {
        if (mysqli_affected_rows($connect1) > 0) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'ลบสาขาวิชาสำเร็จ!'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'ไม่พบสาขาวิชาที่ต้องการลบ'];
        }
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . mysqli_error($connect1)];
    }

} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ไม่ได้ระบุ ID ของสาขาที่ต้องการลบ'];
}

// 4. กลับไปยังหน้า majors.php
header('Location: ../settings/majors.php');
exit();
?>