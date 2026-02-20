<?php
session_start();
include '../../include/config.php';

// ตรวจสอบว่ามี target ส่งมาหรือไม่
if (!isset($_GET['target'])) {
    header('Location: clear_data.php');
    exit();
}

$target = $_GET['target'];

// ใช้ Transaction เพื่อให้แน่ใจว่าทุกคำสั่งสำเร็จทั้งหมด หรือไม่สำเร็จเลย
mysqli_begin_transaction($connect1);

try {
    switch ($target) {
        case 'students_all':
            // ล้างข้อมูลนักศึกษา, ประวัติทุน, กิจกรรม, ผู้ปกครอง, ญาติ
            mysqli_query($connect1, "TRUNCATE TABLE tb_student");
            mysqli_query($connect1, "TRUNCATE TABLE tb_bursary");
            mysqli_query($connect1, "TRUNCATE TABLE tb_activity");
            mysqli_query($connect1, "TRUNCATE TABLE tb_parent");
            mysqli_query($connect1, "TRUNCATE TABLE tb_relatives");
            mysqli_query($connect1, "TRUNCATE TABLE tb_scores");
            $_SESSION['message'] = ['type' => 'success', 'text' => 'ล้างข้อมูลนักศึกษาและประวัติทั้งหมดสำเร็จ!'];
            break;

        case 'personnel_all':
            // ลบข้อมูลเฉพาะ คณะกรรมการ (type 5) และ อาจารย์ที่ปรึกษา (type 4)
            mysqli_query($connect1, "DELETE FROM tb_teacher WHERE tc_type = 4 OR tc_type = 5");
            $_SESSION['message'] = ['type' => 'success', 'text' => 'ล้างข้อมูลคณะกรรมการและอาจารย์ที่ปรึกษาสำเร็จ!'];
            break;

        case 'majors':
            // ล้างข้อมูลสาขาวิชา
            mysqli_query($connect1, "TRUNCATE TABLE tb_program");
            $_SESSION['message'] = ['type' => 'success', 'text' => 'ล้างข้อมูลสาขาวิชาสำเร็จ!'];
            break;
            
        case 'news':
            // ล้างข้อมูลข่าวสารและไฟล์แนบ
            mysqli_query($connect1, "TRUNCATE TABLE tb_news");
            mysqli_query($connect1, "TRUNCATE TABLE tb_files");
            // *** หมายเหตุ: โค้ดส่วนนี้ยังไม่ได้ลบไฟล์จริงออกจากโฟลเดอร์ uploads ***
            $_SESSION['message'] = ['type' => 'success', 'text' => 'ล้างข้อมูลข่าวสารสำเร็จ!'];
            break;

        case 'all':
             // ล้างข้อมูลเกือบทั้งหมด ยกเว้น admin และการตั้งค่าหลัก
            mysqli_query($connect1, "TRUNCATE TABLE tb_student");
            mysqli_query($connect1, "TRUNCATE TABLE tb_bursary");
            mysqli_query($connect1, "TRUNCATE TABLE tb_activity");
            mysqli_query($connect1, "TRUNCATE TABLE tb_parent");
            mysqli_query($connect1, "TRUNCATE TABLE tb_relatives");
            mysqli_query($connect1, "TRUNCATE TABLE tb_scores");
            mysqli_query($connect1, "DELETE FROM tb_teacher WHERE tc_type != 1"); // ลบ teacher ทุกประเภท ยกเว้น admin (ถ้ามี)
            mysqli_query($connect1, "TRUNCATE TABLE tb_program");
            mysqli_query($connect1, "TRUNCATE TABLE tb_news");
            mysqli_query($connect1, "TRUNCATE TABLE tb_files");
            mysqli_query($connect1, "TRUNCATE TABLE tb_ban");
            $_SESSION['message'] = ['type' => 'success', 'text' => 'ล้างข้อมูลทั้งหมดของระบบสำเร็จ!'];
            break;

        default:
            $_SESSION['message'] = ['type' => 'error', 'text' => 'เป้าหมายการล้างข้อมูลไม่ถูกต้อง'];
            break;
    }

    // ถ้าทุกอย่างสำเร็จ ให้ commit
    mysqli_commit($connect1);

} catch (Exception $e) {
    // ถ้ามีข้อผิดพลาด ให้ rollback
    mysqli_rollback($connect1);
    $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการล้างข้อมูล: ' . $e->getMessage()];
}

// กลับไปหน้า clear_data.php
header('Location: ../system/clear_data.php');
exit();
?>