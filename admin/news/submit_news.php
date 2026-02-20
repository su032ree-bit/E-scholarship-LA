<?php
session_start();
include '../../include/config.php';

// ตรวจสอบว่าเป็นการส่งข้อมูลแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../news/news.php');
    exit();
}

// --- 1. รับและป้องกันข้อมูลจากฟอร์ม ---
$title = mysqli_real_escape_string($connect1, $_POST['news_title']);
$details = mysqli_real_escape_string($connect1, $_POST['news_details']);
$file_display_name = mysqli_real_escape_string($connect1, $_POST['news_file_name']);

$new_server_filename = null; // ตัวแปรเก็บชื่อไฟล์ใหม่บนเซิร์ฟเวอร์

// --- 2. จัดการการอัปโหลดไฟล์ (ถ้ามี) ---
// ตรวจสอบว่ามีไฟล์ถูกส่งมาและไม่มีข้อผิดพลาด
if (isset($_FILES['news_file']) && $_FILES['news_file']['error'] === UPLOAD_ERR_OK) {
    
    // แก้ไข Path ให้ถอยออกไป 1 ชั้น เพื่อหาโฟลเดอร์ uploads ที่อยู่ใน Root
    $upload_dir = '../../uploads/'; 
    
    // ตรวจสอบว่ามีโฟลเดอร์หรือไม่ ถ้าไม่มีให้สร้างขึ้นใหม่พร้อมกำหนดสิทธิ์
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $original_filename = basename($_FILES['news_file']['name']);
    
    // สร้างชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน เพื่อป้องกันการเขียนทับ
    $new_server_filename = time() . "_" . $original_filename;
    $target_path = $upload_dir . $new_server_filename;

    // ย้ายไฟล์ไปยังโฟลเดอร์ uploads
    if (!move_uploaded_file($_FILES['news_file']['tmp_name'], $target_path)) {
        // หากย้ายไฟล์ไม่สำเร็จ
        $_SESSION['message'] = ['type' => 'error', 'text' => 'ไม่สามารถอัปโหลดไฟล์ได้: ตรวจสอบสิทธิ์ของโฟลเดอร์ uploads'];
        header('Location: ../news/news.php');
        exit();
    }
}

// --- 3. บันทึกข้อมูลลงฐานข้อมูล (ใช้ Transaction เพื่อความปลอดภัย) ---
mysqli_begin_transaction($connect1);

try {
    // 3.1 เพิ่มข้อมูลหลักในตาราง tb_news
    $sql_news = "INSERT INTO tb_news (titlenews, detailnews, datenews, typenews) VALUES ('$title', '$details', NOW(), 1)";
    if (!mysqli_query($connect1, $sql_news)) {
        throw new Exception("เกิดข้อผิดพลาดในการบันทึกข่าว: " . mysqli_error($connect1));
    }

    // 3.2 ดึง ID ของข่าวที่เพิ่งสร้าง
    $last_news_id = mysqli_insert_id($connect1);

    // 3.3 ถ้ามีการอัปโหลดไฟล์ ให้บันทึกข้อมูลไฟล์ในตาราง tb_files
    if ($new_server_filename !== null && !empty($file_display_name)) {
        $sql_file = "INSERT INTO tb_files (namefile, filenab, idnews) VALUES ('$file_display_name', '$new_server_filename', '$last_news_id')";
        if (!mysqli_query($connect1, $sql_file)) {
            throw new Exception("เกิดข้อผิดพลาดในการบันทึกข้อมูลไฟล์: " . mysqli_error($connect1));
        }
    }

    // ถ้าทุกอย่างสำเร็จ
    mysqli_commit($connect1);
    $_SESSION['message'] = ['type' => 'success', 'text' => 'เพิ่มข่าวประชาสัมพันธ์สำเร็จ!'];

} catch (Exception $e) {
    // หากมีข้อผิดพลาดเกิดขึ้น ให้ย้อนกลับการกระทำทั้งหมด
    mysqli_rollback($connect1);

    // ลบไฟล์ที่เพิ่งอัปโหลดไป ถ้ามี
    if ($new_server_filename !== null && file_exists($upload_dir . $new_server_filename)) {
        unlink($upload_dir . $new_server_filename);
    }
    
    $_SESSION['message'] = ['type' => 'error', 'text' => $e->getMessage()];
}

// --- 4. กลับไปยังหน้า news.php ---
header('Location: ../news/news.php');
exit();
?>