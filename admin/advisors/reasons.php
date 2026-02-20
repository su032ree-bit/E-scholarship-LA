<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
include '../../include/config.php';

if (!isset($_SESSION['id_teacher'])) {
    header("Location: ../../root/login_temp.php");
    exit();
}

$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
if ($student_id <= 0) die("ไม่พบข้อมูลนักศึกษา");

// --- ดึงชื่อประเภททุน ---
$scholarship_options = [];
if (isset($connect1)) {
    $sql_types = "SELECT st_name_1, st_name_2, st_name_3 FROM tb_year WHERE y_id = 1";
    $result_types = mysqli_query($connect1, $sql_types);
    if ($result_types && mysqli_num_rows($result_types) > 0) {
        $data_types = mysqli_fetch_assoc($result_types);
        if (!empty($data_types['st_name_1'])) $scholarship_options[1] = $data_types['st_name_1'];
        if (!empty($data_types['st_name_2'])) $scholarship_options[2] = $data_types['st_name_2'];
        if (!empty($data_types['st_name_3'])) $scholarship_options[3] = $data_types['st_name_3'];
    }
}

// --- ดึงข้อมูลนักศึกษา ---
$student = null;
$scholarship_reason = "";

if ($student_id > 0) {
    $sql = "SELECT s.*, p.g_program, t.tc_name AS advisor_name FROM tb_student AS s
            LEFT JOIN tb_program AS p ON s.st_program = p.g_id
            LEFT JOIN tb_teacher AS t ON s.id_teacher = t.tc_id
            WHERE s.st_id = '$student_id'";
    $result = mysqli_query($connect1, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $student_data = mysqli_fetch_assoc($result);
        $prefix = ($student_data['st_sex'] == 1) ? 'นาย' : 'นางสาว';
        $student = [
            'id' => $student_data['st_code'],
            'prefix' => $prefix,
            'firstname' => $student_data['st_firstname'],
            'lastname' => $student_data['st_lastname'],
            'gpa' => $student_data['st_score'],
            'major' => $student_data['g_program'] ?: 'N/A',
            'advisor' => $student_data['advisor_name'] ?: 'N/A',
            'scholarship_name' => $scholarship_options[$student_data['st_type']] ?? 'ไม่ระบุประเภททุน',
            'image_url' => !empty($student_data['st_image']) ? '../../images/student/' . $student_data['st_image'] : '../../assets/images/bg/no-profile.png'
        ];
        $scholarship_reason = $student_data['st_note'];
    }
}

$committee_id = $_SESSION['id_teacher'] ?? 0;
$has_scored = false;
$existing_score = "";
$existing_comment = "";
if ($committee_id > 0 && $student_id > 0) {
    $res_check = mysqli_query($connect1, "SELECT * FROM tb_scores WHERE st_id = '$student_id' AND tc_id = '$committee_id'");
    if ($row_score = mysqli_fetch_assoc($res_check)) {
        $has_scored = true;
        $existing_score = $row_score['scores'];
        $existing_comment = $row_score['sco_comment'];
    }
}

$current_month_th = [1 => "มกราคม", 2 => "กุมภาพันธ์", 3 => "มีนาคม", 4 => "เมษายน", 5 => "พฤษภาคม", 6 => "มิถุนายน", 7 => "กรกฎาคม", 8 => "สิงหาคม", 9 => "กันยายน", 10 => "ตุลาคม", 11 => "พฤศจิกายน", 12 => "ธันวาคม"][(int)date("n")];
$current_year_th = date("Y") + 543;
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบสมัคร - ระบุเหตุผลการขอทุน</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/bg/head_01.png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/global2.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">

    <link rel="stylesheet" href="../../assets/css/navigation.css">
    <link rel="stylesheet" href="../../assets/css/ui-elements.css">

    <link rel="stylesheet" href="../../assets/css/forms.css">
    <link rel="stylesheet" href="../../assets/css/tables.css">

    <link rel="stylesheet" href="../../assets/css/pages.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">

    <div class="sticky-header-wrapper">
        <?php include('../../include/navbar.php'); ?>
        <?php include('../../include/status_bar.php'); ?>
    </div>

    <button onclick="scrollToTop()" id="scrollTopBtn" class="scroll-top-btn" title="เลื่อนขึ้นข้างบน">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    <div class="container py-5">
        <div class="app-form-container mx-auto">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <a href="../advisors/teacher.php" class="btn btn-secondary rounded-pill px-4">ย้อนกลับ</a>
                <div class="text-center flex-grow-1">
                    <h5 class="fw-bold mb-1">ใบสมัครขอรับทุนการศึกษา<?php echo htmlspecialchars($student['scholarship_name']); ?></h5>
                    <h5 class="fw-bold mb-1">คณะศิลปศาสตร์ มหาวิทยาลัยสงขลานครินทร์</h5>
                    <h5 class="fw-bold mb-1">ประจำปีการศึกษา <?php echo $current_year_th; ?></h5>
                    <p class="text-muted small mt-2">วันที่ <?php echo date("j"); ?> <?php echo $current_month_th; ?> <?php echo $current_year_th; ?> เวลา <?php echo date("H:i"); ?> น.</p>
                </div>
                <div class="student-photo-wrapper">
                    <img src="<?php echo htmlspecialchars($student['image_url']); ?>" alt="Student">
                </div>
            </div>

            <!-- ข้อมูลสรุปนักศึกษา -->
            <div class="text-center mb-5" style="line-height: 2;">
                <span class="mx-2"><strong>ชื่อ:</strong> <?php echo htmlspecialchars($student['prefix'] . $student['firstname'] . " " . $student['lastname']); ?></span>
                <span class="mx-2"><strong>รหัสนักศึกษา:</strong> <?php echo htmlspecialchars($student['id']); ?></span><br>
                <span class="mx-2"><strong>เกรดเฉลี่ย:</strong> <?php echo htmlspecialchars($student['gpa']); ?></span>
                <span class="mx-2"><strong>สาขาวิชา:</strong> <?php echo htmlspecialchars($student['major']); ?></span>
                <span class="mx-2"><strong>อาจารย์ที่ปรึกษา:</strong> <?php echo htmlspecialchars($student['advisor']); ?></span>
            </div>

            <!-- แถบ Tabs เมนู -->
            <ul class="nav-tabs-app">
                <li class="nav-item"><a href="../advisors/give_score.php?student_id=<?php echo $student_id; ?>" class="nav-link-custom inactive">ข้อมูลส่วนตัว</a></li>
                <li class="nav-item"><a href="../advisors/family.php?student_id=<?php echo $student_id; ?>" class="nav-link-custom inactive">ข้อมูลครอบครัว</a></li>
                <li class="nav-item"><a href="../advisors/reasons.php?student_id=<?php echo $student_id; ?>" class="nav-link-custom active">ระบุเหตุผลการขอทุน</a></li>
                <li class="nav-item"><a href="../advisors/document.php?student_id=<?php echo $student_id; ?>" class="nav-link-custom inactive">หนังสือรับรองและเอกสารแนบ</a></li>
            </ul>

            <!-- เนื้อหาเหตุผล -->
            <div class="px-2">
                <p class="text-dark fw-medium mb-3" style="font-size: 14.5px;">เพื่อให้ข้อมูลเป็นประโยชน์สำหรับการพิจารณาคัดเลือก กรุณาบรรยายโดยละเอียด :</p>

                <div class="content-display-gray shadow-sm">
                    <?php echo !empty($scholarship_reason) ? nl2br(htmlspecialchars($scholarship_reason)) : "<div class='text-center py-5 text-muted'><em>- ไม่ได้ระบุเหตุผลการขอทุน -</em></div>"; ?>
                </div>

                <p class="text-center mt-5 fw-medium" style="font-size: 14px; line-height: 1.6; color: #333;">
                    ข้าพเจ้าขอรับรองว่า ข้อความที่ได้กล่าวมาทั้งหมดในใบสมัครนี้เป็นความจริงทุกประการ หากตรวจสอบพบว่าข้อความข้างต้นไม่เป็นความจริง ข้าพเจ้ายินดีคืนทุนและงดสิทธิ์การรับทุนอื่นๆ ของคณะตลอดสภาพการเป็นนักศึกษา
                </p>
            </div>

            <!-- ส่วนประเมินผล (Scoring Footer) ปรับให้เหมือน document.php -->
            <form action="../scores/save_score.php" method="POST" class="scoring-panel mt-5">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <div class="row g-3">
                    <div class="col-md-8">
                        <textarea name="comment" id="scoreComment" class="form-control w-100 h-100" rows="4" placeholder="เสนอแนะเพิ่มเติม" <?php echo $has_scored ? 'readonly' : ''; ?>><?php echo htmlspecialchars($existing_comment); ?></textarea>
                    </div>
                    <div class="col-md-4 d-flex flex-column justify-content-between align-items-end">
                        <?php if ($has_scored): ?>
                            <div class="text-success fw-bold mb-2"><i class="fa-solid fa-check-circle"></i> คุณได้ให้คะแนนแล้ว</div>
                            <select disabled class="form-select scoring-select-custom bg-light">
                                <option><?php echo ($existing_score >= 1) ? 'ผ่านเกณฑ์' : 'ไม่ผ่านเกณฑ์'; ?></option>
                            </select>
                        <?php else: ?>
                            <select name="score" id="scoreSelect" class="form-select scoring-select-custom mb-3" required>
                                <option value="" disabled selected>ให้คะแนน</option>
                                <option value="100">ผ่านเกณฑ์ดีมาก (100)</option>
                                <option value="80">ผ่านเกณฑ์ (80)</option>
                                <option value="50">เฉียดฉิว (50)</option>
                                <option value="0">ไม่ผ่านเกณฑ์ (0)</option>
                            </select>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-warning rounded-pill px-4 text-white" onclick="document.getElementById('scoreComment').value=''; document.getElementById('scoreSelect').value='';">คืนค่า</button>
                                <button type="submit" class="btn btn-success rounded-pill px-4">ยืนยัน</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include '../../include/footer.php'; ?>

    <script>
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>

</body>

</html>