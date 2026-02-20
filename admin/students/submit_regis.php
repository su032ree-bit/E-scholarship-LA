<?php
session_start();
include '../../include/config.php'; 

// --- 1. ตรวจสอบว่าเป็น POST Request ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../student/regis.php');
    exit();
}

// --- 2. รับค่าจากฟอร์ม ---
$st_sex = ($_POST['title'] == 'นาย') ? 1 : 2;
$st_firstname = mysqli_real_escape_string($connect1, $_POST['firstname']);
$st_lastname = mysqli_real_escape_string($connect1, $_POST['lastname']);
$st_score = mysqli_real_escape_string($connect1, $_POST['gpa']);
$st_code = mysqli_real_escape_string($connect1, $_POST['student_id']);
$st_program = (int)$_POST['major'];
$st_email = mysqli_real_escape_string($connect1, $_POST['email']);
$st_pass = mysqli_real_escape_string($connect1, $_POST['password']); 
$st_type = (int)$_POST['scholarship_type']; 

// --- 3. จัดการการอัปโหลดไฟล์รูปภาพ ---
$new_image_filename = null;
$upload_dir = '../../images/student/'; 

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $file_extension = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
    $new_image_filename = time() . "_" . uniqid() . "." . $file_extension;
    $target_path = $upload_dir . $new_image_filename;

    if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_path)) {
        $_SESSION['regis_error'] = "ไม่สามารถย้ายไฟล์ไปยังโฟลเดอร์เป้าหมายได้";
        header('Location: ../../student/regis.php');
        exit();
    }
} else {
    $_SESSION['regis_error'] = "กรุณาแนบไฟล์ภาพประจำตัว";
    header('Location: ../../student/regis.php');
    exit();
}

// --- 4. บันทึกลงฐานข้อมูล (แก้ไข SQL ให้ถูกต้อง) ---
$sql = "INSERT INTO tb_student (
            st_sex, st_firstname, st_lastname, st_score, st_code, 
            st_program, st_email, st_image, st_pass, st_type, 
            st_date_regis, st_activate, st_confirm
        ) VALUES (
            '$st_sex', '$st_firstname', '$st_lastname', '$st_score', '$st_code',
            '$st_program', '$st_email', '$new_image_filename', '$st_pass', '$st_type',
            NOW(), 0, 0
        )";

if (mysqli_query($connect1, $sql)) {
    // แสดงผลด้วย SweetAlert2 แทน Alert ธรรมดา
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <link href='https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500&display=swap' rel='stylesheet'>
        <style>
            * { font-family: 'Prompt', sans-serif; }
        </style>
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'กรุณาเข้าสู่ระบบเพื่อดำเนินการกรอกข้อมูลขอรับทุน',
                icon: 'success',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#003c71', // สีน้ำเงินตามธีมของคุณ
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../../root/index.php';
                }
            });
        </script>
    </body>
    </html>";
    exit();
} else {
    // ลบไฟล์ถ้า DB บันทึกไม่สำเร็จ
    if ($new_image_filename && file_exists($upload_dir . $new_image_filename)) {
        unlink($upload_dir . $new_image_filename);
    }
    // หากเกิด Error ให้เก็บข้อความไว้ใน Session แล้วดีดกลับไปหน้าสมัคร
    $_SESSION['regis_error'] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($connect1);
    header('Location: ../../student/regis.php');
    exit();
}
?>