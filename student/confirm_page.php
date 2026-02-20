<?php
session_start();
include '../include/config.php'; 

$session_st_code = $_SESSION['student_id'] ?? '';

if ($session_st_code == '') {
    header("Location: index.php"); 
    exit();
}

// --- 1. ส่วนบันทึกข้อมูลและอัปโหลดไฟล์จากหน้า apply_document.php ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['advisor_name'])) {
    $advisor_name = mysqli_real_escape_string($connect1, $_POST['advisor_name']);
    
    // หา tc_id
    $res_tc = mysqli_query($connect1, "SELECT tc_id FROM tb_teacher WHERE tc_name = '$advisor_name'");
    $row_tc = mysqli_fetch_assoc($res_tc);
    $tc_id = $row_tc['tc_id'] ?? 0;

    $upload_dir = "../images/student/";
    $update_fields = ["id_teacher = '$tc_id'"];

    // จัดการอัปโหลดไฟล์ 3 ไฟล์
    $file_map = [
        'doc_std_card' => 'st_doc', 
        'doc_transcript' => 'st_doc1', 
        'doc_activity' => 'st_doc2'
    ];

    foreach ($file_map as $input_name => $db_column) {
        if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
            $ext = strtolower(pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION));
            $new_filename = time() . "_" . $input_name . "." . $ext;
            if (move_uploaded_file($_FILES[$input_name]['tmp_name'], $upload_dir . $new_filename)) {
                $update_fields[] = "$db_column = '$new_filename'";
            }
        }
    }

    // อัปเดตข้อมูลและตั้งสถานะ st_confirm = 1 ทันทีเมื่อมาถึงหน้านี้ (เนื่องจากยอมรับเงื่อนไขมาแล้ว)
    $sql_final_doc = "UPDATE tb_student SET " . implode(', ', $update_fields) . ", st_confirm = 1 WHERE st_code = '$session_st_code'";
    mysqli_query($connect1, $sql_final_doc);
}

// --- 2. ดึงสถานะปัจจุบันเพื่อแสดงปุ่ม ---
$is_submitted = false;
$is_approved = false;
$student = [];

if (isset($connect1)) {
    // ดึงข้อมูลชื่อทุน
    $sql_types = "SELECT st_name_1, st_name_2, st_name_3 FROM tb_year WHERE y_id = 1";
    $result_types = mysqli_query($connect1, $sql_types);
    $scholarship_options = [];
    if ($result_types && mysqli_num_rows($result_types) > 0) {
        $data_types = mysqli_fetch_assoc($result_types);
        $scholarship_options[1] = $data_types['st_name_1'];
        $scholarship_options[2] = $data_types['st_name_2'];
        $scholarship_options[3] = $data_types['st_name_3'];
    }

    // ดึงข้อมูลนักศึกษา
    $sql = "SELECT s.*, t.tc_name, p.g_program 
            FROM tb_student s 
            LEFT JOIN tb_teacher t ON s.id_teacher = t.tc_id
            LEFT JOIN tb_program p ON s.st_program = p.g_id
            WHERE s.st_code = '$session_st_code'";
    
    $result = mysqli_query($connect1, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $student = $row;
        $student['prefix']     = ($row['st_sex'] == 1) ? 'นาย' : 'นางสาว';
        $student['firstname']  = $row['st_firstname'];
        $student['lastname']   = $row['st_lastname'];
        $student['gpa']        = $row['st_score']; 
        $student['major_name'] = $row['g_program'] ?? 'ไม่ระบุสาขา'; 
        $student['advisor']    = $row['tc_name'] ?? 'ยังไม่ได้ระบุ';
        $student['scholarship_name'] = $scholarship_options[$row['st_type']] ?? 'ไม่ระบุประเภททุน';
        
        if ($row['st_confirm'] == 1) { $is_submitted = true; }
        if ($row['st_activate'] == 1) { $is_approved = true; }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการส่งเอกสาร - PSU E-Scholarship</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/bg/head_01.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/global2.css">
    <link rel="stylesheet" href="../assets/css/layout.css">
    <link rel="stylesheet" href="../assets/css/navigation.css">
    <link rel="stylesheet" href="../assets/css/ui-elements.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    <link rel="stylesheet" href="../assets/css/tables.css">
    <link rel="stylesheet" href="../assets/css/pages.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* สไตล์ปุ่ม รอยืนยัน สีเหลือง และกดไม่ได้ */
        .btn-status-warning {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #ffffff !important;
            cursor: not-allowed !important;
            font-weight: 500;
        }

        /* สไตล์ปุ่ม ได้รับการพิจารณาแล้ว สีเขียว และกดไม่ได้ */
        .btn-status-success {
            background-color: #198754 !important;
            border-color: #198754 !important;
            color: white !important;
            cursor: not-allowed !important;
        }

        /* ตกแต่ง Checkbox เมื่อถูก Disable */
        .form-check-input:disabled {
            opacity: 1;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .confirm-checkbox-group label {
            cursor: default !important;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<div class="sticky-header-wrapper">
    <?php include('../include/navbar.php'); ?>
    <?php include('../include/status_bar.php'); ?>
</div>

<div class="confirm-page-wrapper">
    <div class="confirm-card-container">
        <div class="confirm-main-title">ทำความเข้าใจรายละเอียดข้างท้ายใบสมัครโดยละเอียด และปฏิบัติตามอย่างเคร่งครัด</div>

        <div class="confirm-instruction-box shadow-sm">
            <span class="confirm-section-header">หมายเหตุ</span>
            <ul class="confirm-list-unstyled">
                <li>1. นักศึกษาสั่งพิมพ์หนังสือรับรองการขอรับทุน เพื่อเสนอให้อาจารย์ที่ปรึกษาลงนาม และส่งคืนงานกิจการนักศึกษา เพื่อยืนยันการสมัครในระบบต่อไป</li>
                <li>2. นักศึกษาสามารถเข้าตรวจสอบสถานะการส่งใบสมัคร กรณีส่งเอกสารครบระบบจะแสดงผลเป็น "เป็นสีเขียว" พร้อมสถานะ "ได้รับการยืนยันแล้ว รอการสัมภาษณ์"</li>
                <li>3. นักศึกษาที่ขอรับทุนการศึกษา ติดตามการประกาศรายชื่อผู้มีสิทธิ์สอบสัมภาษณ์ได้ที่เว็บไซต์ งานกิจการนักศึกษา (iw2.libarts.psu.ac.th/student) หรือเว็บไซต์ระบบรับสมัครทุนฯ</li>
            </ul>

            <span class="confirm-section-header mt-4">ข้อปฏิบัติการเข้าสัมภาษณ์</span>
            <ul class="confirm-list-unstyled">
                <li>1. นักศึกษาต้องแต่งกายด้วยชุดนักศึกษาที่ถูกต้องตามระเบียบของมหาวิทยาลัยสงขลานครินทร์เท่านั้น</li>
                <li>2. นักศึกษาต้องมาถึงสถานที่สอบสัมภาษณ์ก่อนเวลาอย่างน้อย 15 - 30 นาที</li>
                <li>3. การเรียงลำดับก่อน - หลัง ผู้เข้าสัมภาษณ์ โดยการหยิบบัตรคิว</li>
                <li>4. กรณีนักศึกษามาไม่ทันเวลาการสัมภาษณ์ และนักศึกษาคนอื่นสัมภาษณ์ครบทุกคนแล้ว นักศึกษาจะถูกตัดสิทธิ์ทันที</li>
                <li>5. การพิจารณาของคณะกรรมการการสอบสัมภาษณ์ถือเป็นสิ้นสุด</li>
            </ul>

            <div class="confirm-checkbox-group">
                <!-- ติ๊ก Checked และ Disable ไว้เลยตามที่สั่งมาครับ -->
                <input type="checkbox" id="accept_terms" class="form-check-input" checked disabled>
                <label for="accept_terms">
                    ยอมรับเงื่อนไขทุกประการ
                </label>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
            
            <!-- ปุ่มยกเลิก สีแดง ตามเดิม -->
            <button type="button" class="btn btn-danger rounded-pill px-4" onclick="window.location.href='apply_document.php'">ยกเลิก</button>
            
            <?php if ($is_submitted): ?>
                
                <?php if ($is_approved): ?>
                    <!-- ปุ่มได้รับการพิจารณาแล้ว สีเขียว กดไม่ได้ -->
                    <button type="button" class="btn btn-status-success rounded-pill px-4" disabled>
                        <i class="fa-solid fa-circle-check me-1"></i> ได้รับการพิจารณาแล้ว
                    </button>
                <?php else: ?>
                    <!-- ปุ่มรอยืนยัน สีเหลือง กดไม่ได้ ตามความต้องการล่าสุดครับ -->
                    <button type="button" class="btn btn-status-warning rounded-pill px-4" disabled>
                        <i class="fa-solid fa-clock me-1"></i> รอยืนยัน
                    </button>
                <?php endif; ?>
                
                <!-- ปุ่มพิมพ์เอกสาร -->
                <a href="print_scholarship.php?student_id=<?php echo $student['st_id']; ?>" target="_blank" class="btn btn-app-print rounded-pill px-4 shadow-sm text-decoration-none">
                    <i class="fa-solid fa-print me-1"></i> สั่งพิมพ์เอกสาร
                </a>

            <?php else: ?>
                <!-- กรณีไม่มีสถานะ (เผื่อไว้) -->
                <button type="button" class="btn btn-status-warning rounded-pill px-4" disabled>รอยืนยัน</button>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>