<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
include '../../include/config.php'; 

// 1. ตรวจสอบสิทธิ์การเข้าใช้งาน (ต้องเป็นอาจารย์ที่ล็อกอินแล้ว)
if (!isset($_SESSION['id_teacher'])) {
    echo "<script>
        alert('กรุณาเข้าสู่ระบบก่อนทำการบันทึกคะแนน');
        window.location.href = '../login.php'; 
    </script>";
    exit();
}

$committee_id = $_SESSION['id_teacher']; 

// 2. รับค่าจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // รับค่าและป้องกัน SQL Injection
    $st_id     = mysqli_real_escape_string($connect1, $_POST['student_id']); // Primary Key (st_id)
    $score_val = mysqli_real_escape_string($connect1, $_POST['score']);      // คะแนน (100, 80, 50, 0)
    $comment   = mysqli_real_escape_string($connect1, $_POST['comment']);
    $now       = date("Y-m-d H:i:s");

    // ตรวจสอบข้อมูลเบื้องต้น
    if (empty($st_id)) {
        echo "<script>alert('ไม่พบรหัสนักศึกษาในระบบ'); window.history.back();</script>";
        exit();
    }
    
    if ($score_val === "") {
        echo "<script>alert('กรุณาเลือกผลการให้คะแนน'); window.history.back();</script>";
        exit();
    }

    // 3. ตรวจสอบว่าเคยให้คะแนนไปแล้วหรือไม่
    // (ใช้ st_id และ tc_id เป็นตัวกำหนดความยูนีค)
    $check_sql = "SELECT sco_id FROM tb_scores WHERE st_id = '$st_id' AND tc_id = '$committee_id'";
    $check_result = mysqli_query($connect1, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // === กรณีเคยให้แล้ว -> อัปเดตข้อมูลเดิม (หรือแจ้งเตือนตามนโยบายของคุณ) ===
        // ในที่นี้ขออัปเดตเพื่อให้คะแนนล่าสุดถูกบันทึก
        $sql = "UPDATE tb_scores 
                SET scores = '$score_val', 
                    sco_comment = '$comment', 
                    sco_date = '$now' 
                WHERE st_id = '$st_id' AND tc_id = '$committee_id'";
        $msg = "อัปเดตผลการประเมินเรียบร้อยแล้ว";
    } else {
        // === ยังไม่เคยให้ -> บันทึกข้อมูลใหม่ (INSERT) ===
        $sql = "INSERT INTO tb_scores (tc_id, scores, sco_comment, sco_date, sco_status, st_id) 
                VALUES ('$committee_id', '$score_val', '$comment', '$now', 1, '$st_id')";
        $msg = "บันทึกผลการประเมินเรียบร้อยแล้ว";
    }

    // 4. ประมวลผลคำสั่ง SQL
    if (mysqli_query($connect1, $sql)) {
        echo "<script>
            alert('$msg');
            window.location.href = '../../admin/advisors/give_score.php?student_id=$st_id'; 
        </script>";
    } else {
        echo "<script>
            alert('เกิดข้อผิดพลาดในการบันทึก: " . mysqli_error($connect1) . "');
            window.history.back();
        </script>";
    }

} else {
    // ถ้าเข้าหน้านี้โดยไม่ได้กด Submit Form
    header("Location: ../../admin/advisors/teacher.php");
    exit();
}
?>