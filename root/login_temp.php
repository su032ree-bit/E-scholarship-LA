<?php
session_start();
include '../include/config.php'; 

// --- ตรวจสอบสิทธิ์ (ถ้ามีการ Login ค้างไว้แล้ว) ---
// 1. เช็คนักศึกษา
if (isset($_SESSION['student_id'])) {
    $session_st_code = mysqli_real_escape_string($connect1, $_SESSION['student_id']);
    $sql_check = "SELECT st_confirm FROM tb_student WHERE st_code = '$session_st_code'";
    $result_check = mysqli_query($connect1, $sql_check);
    if ($result_check && mysqli_num_rows($result_check) > 0) {
        $row_check = mysqli_fetch_assoc($result_check);
        if ($row_check['st_confirm'] == 1) {
            header("Location: confirm_page.php");
        } else {
            header("Location: apply_form.php");
        }
        exit(); 
    }
}
// 2. เช็คอาจารย์
if (isset($_SESSION['id_teacher'])) {
    header("Location: ../admin/advisors/teacher.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - ระบบสมัครทุนการศึกษา</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body.login-combined {
            background: linear-gradient(135deg, #003c71 0%, #1a2a3a 100%);
            display: flex; justify-content: center; align-items: center; min-height: 100vh; font-family: 'Prompt', sans-serif;
            margin: 0;
        }
        .login-card {
            background: white; padding: 0; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); width: 100%; max-width: 420px; overflow: hidden;
        }
        .login-header { padding: 30px 20px 10px 20px; text-align: center; }
        .login-header img { width: 130px; margin-bottom: 10px; }
        
        /* สไตล์แท็บสลับโหมด */
        .login-tabs { display: flex; background: #f8f9fa; border-bottom: 1px solid #eee; }
        .tab-btn {
            flex: 1; padding: 15px; border: none; background: none; cursor: pointer;
            font-family: 'Prompt'; font-size: 15px; font-weight: 600; color: #999;
            transition: 0.3s; border-bottom: 3px solid transparent;
        }
        .tab-btn.active { color: #003c71; border-bottom: 3px solid #003c71; background: white; }
        .tab-btn i { margin-right: 8px; }

        .login-body { padding: 30px; }
        .form-group { text-align: left; margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 8px; color: #555; font-size: 14px; }
        .form-group input {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-family: 'Prompt'; transition: 0.3s;
        }
        .form-group input:focus { border-color: #003c71; outline: none; box-shadow: 0 0 0 3px rgba(0,60,113,0.1); }
        
        .btn-login {
            width: 100%; padding: 13px; background: #003c71; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-family: 'Prompt'; font-weight: 600; margin-top: 10px; transition: 0.3s;
        }
        .btn-login:hover { background: #002a50; transform: translateY(-2px); }
        
        .error-msg { color: #d9534f; background: #f2dede; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #ebccd1; }
        
        /* ซ่อนฟอร์มที่ไม่ได้เลือก */
        .login-form { display: none; }
        .login-form.active { display: block; animation: fadeIn 0.4s ease; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .regis-link { display: block; margin-top: 25px; color: #003c71; text-decoration: none; font-size: 14px; text-align: center; }
        .regis-link:hover { text-decoration: underline; }
    </style>
</head>
<body class="login-combined">

<div class="login-card">
    <div class="login-header">
        <img src="../assets/images/bg/update-09.png" alt="Logo">
        <h2 style="font-size: 1.4rem; color: #333; margin: 10px 0;">ระบบรับสมัครทุนการศึกษา</h2>
    </div>

    <!-- ส่วนของแท็บสลับ -->
    <div class="login-tabs">
        <button class="tab-btn active" onclick="switchTab('student', this)">
            <i class="fa-solid fa-user-graduate"></i> นักศึกษา
        </button>
        <button class="tab-btn" onclick="switchTab('teacher', this)">
            <i class="fa-solid fa-user-tie"></i> อาจารย์ / กรรมการ
        </button>
    </div>

    <div class="login-body">
        
        <!-- ส่วนแจ้งเตือน Error -->
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['login_error_tc'])): ?>
            <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $_SESSION['login_error_tc']; unset($_SESSION['login_error_tc']); ?></div>
        <?php endif; ?>

        <!-- ฟอร์มนักศึกษา -->
        <div id="student-form" class="login-form active">
            <form action="check_login_temp.php" method="POST">
                <div class="form-group">
                    <label><i class="fa-solid fa-id-card"></i> รหัสนักศึกษา</label>
                    <input type="text" name="st_code" placeholder="เช่น 6411110xxx" required>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-key"></i> รหัสผ่าน</label>
                    <input type="password" name="st_pass" placeholder="กรอกรหัสผ่าน" required>
                </div>
                <button type="submit" class="btn-login">เข้าสู่ระบบนักศึกษา</button>
                <a href="regis.php" class="regis-link">ยังไม่มีบัญชีนักศึกษา? สมัครสมาชิกที่นี่</a>
            </form>
        </div>

        <!-- ฟอร์มอจารย์ -->
        <div id="teacher-form" class="login-form">
            <form action="check_login_temp.php" method="POST">
                <div class="form-group">
                    <label><i class="fa-solid fa-user"></i> ชื่อผู้ใช้งาน (Username)</label>
                    <input type="text" name="tc_user" placeholder="กรอก Username อาจารย์" required>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-key"></i> รหัสผ่าน</label>
                    <input type="password" name="tc_pass" placeholder="กรอกรหัสผ่าน" required>
                </div>
                <button type="submit" class="btn-login" style="background: #1a2a3a;">เข้าสู่ระบบอาจารย์</button>
                <div style="height: 45px;"></div> <!-- เว้นระยะให้เท่ากับฟอร์มนักศึกษา -->
            </form>
        </div>

    </div>
</div>

<script>
    function switchTab(mode, btn) {
        // 1. เปลี่ยนสถานะปุ่มแท็บ
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // 2. สลับการแสดงผลฟอร์ม
        document.querySelectorAll('.login-form').forEach(f => f.classList.remove('active'));
        if (mode === 'student') {
            document.getElementById('student-form').classList.add('active');
        } else {
            document.getElementById('teacher-form').classList.add('active');
        }
    }
</script>

</body>
</html>