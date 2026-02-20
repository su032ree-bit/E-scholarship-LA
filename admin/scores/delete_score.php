<?php
session_start();
include '../../include/config.php';

// รับค่า type ของทุน เพื่อใช้ในการ redirect กลับไปหน้าเดิมที่ถูกต้อง
$scholarship_type = isset($_GET['type']) ? '&type=' . (int)$_GET['type'] : '';

// ตรวจสอบว่ามี ID ของนักศึกษา (st_id) ส่งมาหรือไม่
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $student_id = mysqli_real_escape_string($connect1, $_GET['id']);

    // สร้างคำสั่ง SQL เพื่อลบข้อมูลคะแนนทั้งหมดที่เกี่ยวข้องกับ st_id นี้
    $sql_delete = "DELETE FROM tb_scores WHERE st_id = '$student_id'";

    if (mysqli_query($connect1, $sql_delete)) {
        // [ปรับปรุง] อัปเดตคะแนนใน tb_student ให้เป็น 0 ด้วย
        $sql_update_student = "UPDATE tb_student SET sum_score = 0, st_average = 0.00 WHERE st_id = '$student_id'";
        mysqli_query($connect1, $sql_update_student);

        $_SESSION['message'] = ['type' => 'success', 'text' => 'ลบข้อมูลคะแนนสำเร็จ!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . mysqli_error($connect1)];
    }

} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ไม่ได้ระบุ ID ของข้อมูลที่ต้องการลบ'];
}

// กลับไปยังหน้า scholarship_scores.php พร้อมกับ type เดิม
header('Location: ../scores/scholarship_scores.php?' . $scholarship_type);
exit();
?>