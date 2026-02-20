<?php
session_start();
include '../../include/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../settings/scholarship_types.php');
    exit();
}

// 1. รับข้อมูลจากฟอร์ม
$scholarship_names = $_POST['scholarship_name'];
$closed_scholarships = isset($_POST['close_scholarship']) ? $_POST['close_scholarship'] : [];
// [ใหม่] รับข้อมูลวันที่
$start_dates = $_POST['start_date'];
$end_dates = $_POST['end_date'];

// 2. เตรียมข้อมูลสำหรับอัปเดต
$update_fields = [];
for ($i = 1; $i <= 3; $i++) {
    $name = mysqli_real_escape_string($connect1, $scholarship_names[$i]);
    $status = isset($closed_scholarships[$i]) ? 1 : 0;
    
    // [ใหม่] จัดการข้อมูลวันที่
    // ถ้า checkbox 'ปิดรับสมัคร' ไม่ถูกติ๊ก ให้ใช้ค่าวันที่จากฟอร์ม, ถ้าถูกติ๊ก ให้เก็บเป็น NULL
    $start_date = ($status == 0 && !empty($start_dates[$i])) ? "'" . mysqli_real_escape_string($connect1, $start_dates[$i]) . "'" : "NULL";
    $end_date = ($status == 0 && !empty($end_dates[$i])) ? "'" . mysqli_real_escape_string($connect1, $end_dates[$i]) . "'" : "NULL";

    $update_fields[] = "st_name_$i = '$name'";
    $update_fields[] = "st_$i = '$status'";
    $update_fields[] = "date_start_$i = $start_date";
    $update_fields[] = "date_end_$i = $end_date";
}

// 3. สร้างคำสั่ง SQL
$sql = "UPDATE tb_year SET " . implode(", ", $update_fields) . " WHERE y_id = 1";

// 4. ประมวลผลและส่งข้อความแจ้งเตือน
if (mysqli_query($connect1, $sql)) {
    $_SESSION['message'] = ['type' => 'success', 'text' => 'บันทึกข้อมูลประเภททุนสำเร็จ!'];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . mysqli_error($connect1)];
}

header('Location: ../settings/scholarship_types.php');
exit();
?>