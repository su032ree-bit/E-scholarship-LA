<?php
session_start();
include '../../include/config.php';

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_id'])) {
    
    $student_ids = $_POST['student_id'];
    $date_start = date('Y-m-d'); // วันที่เริ่มระงับ (วันนี้)
    $date_ban = date('Y-m-d');   // วันที่บันทึกข้อมูล
    
    // กำหนดวันสิ้นสุดการระงับสิทธิ์ 
    // ตัวอย่าง: ระงับ 2 ปีนับจากวันที่เพิ่มข้อมูล (หรือปรับเปลี่ยนตามนโยบาย)
    $date_end = date('Y-m-d', strtotime('+2 years')); 

    $success_count = 0;
    $duplicate_count = 0;
    $error_messages = [];

    // เริ่มต้นกระบวนการบันทึกข้อมูล
    foreach ($student_ids as $st_code) {
        // ทำความสะอาดข้อมูลรหัสนักศึกษา
        $st_code = mysqli_real_escape_string($connect1, trim($st_code));

        if (!empty($st_code)) {
            // 1. ตรวจสอบว่ารหัสนี้ถูกระงับในฐานข้อมูลอยู่แล้วหรือไม่ (Double Check)
            $sql_check = "SELECT id_ban FROM tb_ban WHERE code_student = '$st_code'";
            $result_check = mysqli_query($connect1, $sql_check);

            if (mysqli_num_rows($result_check) == 0) {
                // 2. ถ้ายังไม่มี ให้ทำการบันทึก
                $sql_insert = "INSERT INTO tb_ban (code_student, date_start, date_end, date_ban) 
                               VALUES ('$st_code', '$date_start', '$date_end', '$date_ban')";
                
                if (mysqli_query($connect1, $sql_insert)) {
                    $success_count++;
                } else {
                    $error_messages[] = "ไม่สามารถบันทึกรหัส $st_code ได้: " . mysqli_error($connect1);
                }
            } else {
                $duplicate_count++;
            }
        }
    }

    // กำหนดข้อความแจ้งเตือนกลับไปที่หน้าหลัก
    if ($success_count > 0) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => "บันทึกรหัสนักศึกษาสำเร็จ $success_count รายการ" . ($duplicate_count > 0 ? " (ข้ามรหัสที่ซ้ำ $duplicate_count รายการ)" : "")
        ];
    } else if ($duplicate_count > 0) {
        $_SESSION['message'] = [
            'type' => 'warning',
            'text' => "รหัสนักศึกษาทั้งหมดที่ระบุ มีอยู่ในระบบอยู่แล้ว"
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง"
        ];
    }

} else {
    // กรณีพยายามเข้าถึงไฟล์โดยตรงโดยไม่ผ่านฟอร์ม
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => "การเข้าถึงไม่ถูกต้อง"
    ];
}

// ย้อนกลับไปหน้าแสดงรายชื่อนักศึกษาที่ถูกระงับ
header("Location: ../../student/susp_std.php");
exit();
?>