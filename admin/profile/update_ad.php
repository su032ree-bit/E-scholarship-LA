<?php
session_start();
include '../../include/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = mysqli_real_escape_string($connect1, $_POST['fullname']);
    $username = mysqli_real_escape_string($connect1, $_POST['username']);
    $contact_number = mysqli_real_escape_string($connect1, $_POST['contact_number']);
    
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    $admin_id = 1;

    // --- 2. ตรรกะการเปลี่ยนรหัสผ่าน ---
    if (!empty($new_password)) {
        // [Server-side check] รหัสใหม่ไม่ตรงกัน
        if ($new_password !== $confirm_new_password) {
            // กรณีนี้ JavaScript ควรดักได้ก่อนแล้ว
            header("Location: ../system/admin.php?error=confirm_password");
            exit();
        }
        
        // ตรวจสอบว่ากรอกรหัสผ่านเดิมหรือไม่
        if (empty($current_password)) {
             // [แก้ไข] เปลี่ยนเป็น URL Parameter
             header("Location: ../system/admin.php?error=current_password_required");
             exit();
        }

        // ดึงรหัสผ่านปัจจุบันจากฐานข้อมูลเพื่อตรวจสอบ
        $sql_check_pass = "SELECT ad_pass FROM tb_admin WHERE ad_id = '$admin_id'";
        $result_pass = mysqli_query($connect1, $sql_check_pass);
        $admin_data_pass = mysqli_fetch_assoc($result_pass);
        
        // [แก้ไข] เปลี่ยนเป็น URL Parameter
        if ($current_password != $admin_data_pass['ad_pass']) {
            header("Location: ../system/admin.php?error=current_password");
            exit();
        }
    }
    
    // --- 3. อัปเดตฐานข้อมูล ---
    $sql_update = "UPDATE tb_admin SET 
                    ad_name = '$fullname', 
                    ad_user = '$username', 
                    ad_tel = '$contact_number' ";

    if (!empty($new_password)) {
        $sql_update .= ", ad_pass = '$new_password' ";
    }

    $sql_update .= " WHERE ad_id = '$admin_id'";

    if (mysqli_query($connect1, $sql_update)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'บันทึกข้อมูลสำเร็จแล้ว'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . mysqli_error($connect1)];
    }
    
    // กลับไปยังหน้าเดิม (แบบไม่มี error)
    header("Location: ../system/admin.php");
    exit();

} else {
    header("Location: ../system/admin.php");
    exit();
}
?>