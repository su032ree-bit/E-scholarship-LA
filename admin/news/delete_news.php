<?php
session_start();
include '../../include/config.php';

// --- 1. ตรวจสอบว่ามี ID ส่งมาหรือไม่ ---
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ไม่ได้ระบุ ID ของข่าวที่ต้องการลบ'];
    header('Location: ../news/news.php');
    exit();
}

// --- 2. รับและป้องกันข้อมูล ID ---
$news_id = mysqli_real_escape_string($connect1, $_GET['id']);
$upload_dir = '../../uploads/';
$filename_to_delete = null;

// --- 3. เริ่มกระบวนการลบข้อมูล (ใช้ Transaction) ---
mysqli_begin_transaction($connect1);

try {
    // 3.1 ค้นหาชื่อไฟล์ที่ต้องลบจากเซิร์ฟเวอร์ก่อน
    $sql_find_file = "SELECT filenab FROM tb_files WHERE idnews = '$news_id' LIMIT 1";
    $result_file = mysqli_query($connect1, $sql_find_file);
    if ($result_file && mysqli_num_rows($result_file) > 0) {
        $file_data = mysqli_fetch_assoc($result_file);
        $filename_to_delete = $file_data['filenab'];
    }

    // 3.2 ลบข้อมูลไฟล์ออกจากตาราง tb_files (ถ้ามี)
    $sql_delete_file_record = "DELETE FROM tb_files WHERE idnews = '$news_id'";
    if (!mysqli_query($connect1, $sql_delete_file_record)) {
        throw new Exception("เกิดข้อผิดพลาดในการลบข้อมูลไฟล์: " . mysqli_error($connect1));
    }

    // 3.3 ลบข้อมูลข่าวหลักออกจากตาราง tb_news
    $sql_delete_news = "DELETE FROM tb_news WHERE idnews = '$news_id'";
    if (!mysqli_query($connect1, $sql_delete_news)) {
        throw new Exception("เกิดข้อผิดพลาดในการลบข่าว: " . mysqli_error($connect1));
    }

    // ถ้า query ทั้งหมดสำเร็จ
    if (mysqli_commit($connect1)) {
        // --- 4. ลบไฟล์ออกจากเซิร์ฟเวอร์ (ทำหลังจาก commit สำเร็จแล้ว) ---
        if ($filename_to_delete !== null) {
            $file_path = $upload_dir . $filename_to_delete;
            if (file_exists($file_path)) {
                unlink($file_path); // คำสั่งลบไฟล์
            }
        }
        $_SESSION['message'] = ['type' => 'success', 'text' => 'ลบข่าวประชาสัมพันธ์สำเร็จ!'];
    } else {
         throw new Exception("เกิดข้อผิดพลาดในการยืนยันการลบข้อมูล (Commit failed)");
    }

} catch (Exception $e) {
    // หากมีข้อผิดพลาดเกิดขึ้น ให้ย้อนกลับการกระทำทั้งหมด
    mysqli_rollback($connect1);
    $_SESSION['message'] = ['type' => 'error', 'text' => $e->getMessage()];
}

// --- 5. กลับไปยังหน้า news.php ---
header('Location: ../news/news.php');
exit();

?>