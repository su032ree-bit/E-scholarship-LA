<?php
session_start();
include '../include/config.php';

// ตั้งค่า Header ให้ตอบกลับเป็น JSON
header('Content-Type: application/json');

// ตรวจสอบว่าเป็นการส่งข้อมูลแบบ POST และมีรหัสผ่านส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password'])) {
    
    $admin_id = 1; // หรือ $_SESSION['admin_id'];
    $input_password = $_POST['current_password'];

    // ดึงรหัสผ่านจริงจากฐานข้อมูล
    $sql = "SELECT ad_pass FROM tb_admin WHERE ad_id = '$admin_id'";
    $result = mysqli_query($connect1, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $admin_data = mysqli_fetch_assoc($result);
        $correct_password = $admin_data['ad_pass'];

        // เปรียบเทียบรหัสผ่าน
        if ($input_password === $correct_password) {
            // ถ้าถูกต้อง, ส่ง {"correct": true} กลับไป
            echo json_encode(['correct' => true]);
        } else {
            // ถ้าไม่ถูกต้อง, ส่ง {"correct": false} กลับไป
            echo json_encode(['correct' => false]);
        }
    } else {
        // กรณีไม่พบ admin ID
        echo json_encode(['correct' => false, 'error' => 'Admin not found']);
    }
} else {
    // กรณีมีการเรียกไฟล์โดยตรงหรือไม่มีข้อมูลส่งมา
    echo json_encode(['correct' => false, 'error' => 'Invalid request']);
}

exit(); // จบการทำงานทันที
?>