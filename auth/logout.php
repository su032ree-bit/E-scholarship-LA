<?php
// 1. เริ่ม Session เพื่อเข้าถึงข้อมูลปัจจุบัน
session_start();

// 2. ล้างค่าตัวแปร Session ทั้งหมด
session_unset();

// 3. ทำลาย Session ทิ้ง
session_destroy();

// 4. แจ้งเตือนและเด้งกลับไปหน้า Login
// (เปลี่ยน 'login.php' เป็นชื่อไฟล์หน้าเข้าสู่ระบบของคุณ ถ้าชื่ออื่น)
echo "<script>
    alert('ออกจากระบบเรียบร้อยแล้ว');
    window.location.href = 'login.php'; 
</script>";

exit();
?>