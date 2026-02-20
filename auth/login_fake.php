<?php
session_start();

// ตรวจสอบว่ามีการกดเลือกกรรมการมาหรือไม่
if (isset($_GET['login_as'])) {
    $id = (int)$_GET['login_as'];
    $name = $_GET['name'];

    // --- สร้าง Session จำลอง (Mock Session) ---
    // ตัวแปรเหล่านี้คือตัวแปรที่หน้า save_score.php และ teacher.php ต้องการ
    
    $_SESSION['id_teacher'] = $id;    // สำคัญ: ใช้ตรวจสอบสิทธิ์ใน save_score.php
    $_SESSION['user_id'] = $id;       // สำรอง: บางระบบใช้ user_id
    $_SESSION['committee_id'] = $id;  // ใช้ใน teacher.php เพื่อดึงคะแนนของคนนี้
    $_SESSION['name_mem'] = $name;    // ใช้แสดงชื่อมุมขวาบน หรือข้อความต้อนรับ
    
    // สถานะอื่นๆ (ถ้ามี)
    $_SESSION['status_user'] = 'teacher'; 

    // เด้งไปหน้าหลักของอาจารย์
    header("Location: teacher.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จำลองการเข้าสู่ระบบ (Fake Login)</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        h2 { color: #003c71; margin-bottom: 20px; }
        p { color: #666; margin-bottom: 30px; }
        
        .btn-login {
            display: block;
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            text-decoration: none;
            color: white;
            border-radius: 6px;
            font-weight: 500;
            transition: opacity 0.2s;
            box-sizing: border-box;
        }
        .btn-login:hover { opacity: 0.9; }
        
        /* สีแยกตามคน */
        .btn-1 { background-color: #007bff; }
        .btn-2 { background-color: #28a745; }
        .btn-3 { background-color: #17a2b8; }
        
        .note {
            margin-top: 20px;
            font-size: 0.8rem;
            color: #dc3545;
            background: #ffe6e6;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>จำลองการล็อกอิน</h2>
        <p>คลิกเลือกกรรมการเพื่อทดสอบระบบให้คะแนน</p>

        <!-- ข้อมูลจาก DB ที่คุณให้มา (tb_teacher) -->
        
        <!-- 1. คุณจอมใจ (ID 73) -->
        <a href="?login_as=73&name=คุณจอมใจ สุทธินนท์" class="btn-login btn-1">
            <i class="fa-solid fa-user"></i> ล็อกอินเป็น <strong>คุณจอมใจ</strong> (ID: 73)
        </a>

        <!-- 2. ดร.วิฑูรย์ (ID 99) -->
        <a href="?login_as=99&name=ดร.วิฑูรย์ เมตตาจิตร" class="btn-login btn-2">
            <i class="fa-solid fa-user"></i> ล็อกอินเป็น <strong>ดร.วิฑูรย์</strong> (ID: 99)
        </a>

        <!-- 3. เฉลิมวุฒิ (ID 100) -->
        <a href="?login_as=100&name=คุณเฉลิมวุฒิ วิจิตร" class="btn-login btn-3">
            <i class="fa-solid fa-user"></i> ล็อกอินเป็น <strong>คุณเฉลิมวุฒิ</strong> (ID: 100)
        </a>

        <div class="note">
            * ไฟล์นี้ใช้สำหรับทดสอบ (Test) เท่านั้น <br>
            เมื่อใช้งานจริงต้องลบไฟล์นี้ออก หรือใช้งานผ่านระบบ Login เดิม
        </div>
    </div>

</body>
</html>