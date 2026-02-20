<?php
session_start();
include '../../include/config.php';

$id = (int)$_GET['id'];

if ($id > 0) {
    // 1. ดึง st_type มาก่อนเพื่อใช้กระโดดกลับ
    $res = mysqli_query($connect1, "SELECT st_type FROM tb_student WHERE st_id = '$id'");
    $row = mysqli_fetch_assoc($res);
    $type = $row['st_type'];

    // 2. ลบข้อมูลจากตารางที่เกี่ยวข้องทั้งหมด (ตามโครงสร้างฐานข้อมูลที่คุณให้มา)
    mysqli_query($connect1, "DELETE FROM tb_parent WHERE id_student = '$id'");
    mysqli_query($connect1, "DELETE FROM tb_relatives WHERE id_student = '$id'");
    mysqli_query($connect1, "DELETE FROM tb_bursary WHERE id_student = '$id'");
    mysqli_query($connect1, "DELETE FROM tb_scores WHERE st_id = '$id'");
    mysqli_query($connect1, "DELETE FROM tb_activity WHERE id_student = '$id'");
    
    // 3. ลบตัวนักศึกษา
    mysqli_query($connect1, "DELETE FROM tb_student WHERE st_id = '$id'");

    header("Location: ../students/student_data.php?type=$type&msg=deleted");
}