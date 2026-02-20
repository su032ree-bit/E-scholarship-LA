<?php
date_default_timezone_set("Asia/Bangkok");
// 1. เริ่ม Session และเชื่อมต่อฐานข้อมูล
session_start();
include '../../include/config.php';

if (!isset($_SESSION['id_teacher'])) {
    header("Location: ../../root/login_temp.php");
    exit();
}

$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
if ($student_id <= 0) {
    die("ไม่พบรหัสนักศึกษาที่ต้องการตรวจสอบ");
}

// --- ดึงข้อมูลประเภททุน ---
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

$student = null;
$family = [];

if ($student_id > 0) {
    $sql_student = "SELECT s.*, p.g_program, t.tc_name AS advisor_name FROM tb_student AS s
                    LEFT JOIN tb_program AS p ON s.st_program = p.g_id
                    LEFT JOIN tb_teacher AS t ON s.id_teacher = t.tc_id
                    WHERE s.st_id = '$student_id'";
    $result_student = mysqli_query($connect1, $sql_student);

    if ($result_student && mysqli_num_rows($result_student) > 0) {
        $student_data = mysqli_fetch_assoc($result_student);
        $prefix = ($student_data['st_sex'] == 1) ? 'นาย' : (($student_data['st_sex'] == 2) ? 'นางสาว' : '');
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

        function parseFamilyData($data)
        {
            $parts = explode('|-o-|', $data);
            return [
                'name' => $parts[0] ?? '',
                'age' => $parts[1] ?? '',
                'status_id' => $parts[2] ?? '',
                'job' => $parts[3] ?? '',
                'income' => $parts[4] ?? '',
                'workplace' => $parts[5] ?? '',
                'phone' => $parts[6] ?? ''
            ];
        }

        $father = parseFamilyData($student_data['st_father'] ?? '');
        $family['father_name'] = $father['name'];
        $family['father_age'] = $father['age'];
        $family['father_status'] = ($father['status_id'] == '1') ? 'alive' : (($father['status_id'] == '0') ? 'deceased' : '');
        $family['father_job'] = $father['job'];
        $family['father_income'] = $father['income'];
        $family['father_workplace'] = $father['workplace'];
        $family['father_phone'] = $father['phone'];

        $mother = parseFamilyData($student_data['st_mother'] ?? '');
        $family['mother_name'] = $mother['name'];
        $family['mother_age'] = $mother['age'];
        $family['mother_status'] = ($mother['status_id'] == '1') ? 'alive' : (($mother['status_id'] == '0') ? 'deceased' : '');
        $family['mother_job'] = $mother['job'];
        $family['mother_income'] = $mother['income'];
        $family['mother_workplace'] = $mother['workplace'];
        $family['mother_phone'] = $mother['phone'];

        $guardian = parseFamilyData($student_data['st_guardian'] ?? '');
        $family['guardian_name'] = $guardian['name'];
        $family['guardian_age'] = $guardian['age'];
        $family['guardian_job'] = $guardian['job'];
        $family['guardian_income'] = $guardian['income'];
        $family['guardian_workplace'] = $guardian['workplace'];
        $family['guardian_phone'] = $guardian['phone'];

        $family['siblings'] = [];
        if (!empty($student_data['st_siblings'])) {
            $sib_rows = explode('|-o-|', $student_data['st_siblings']);
            foreach ($sib_rows as $row) {
                $sib_parts = explode(':-:', $row);
                if (!empty($sib_parts[0])) {
                    $extra_parts = explode(':', $sib_parts[1] ?? '');
                    $family['siblings'][] = ['name' => $sib_parts[0], 'edu' => '-', 'work' => $extra_parts[0] ?? '-', 'income' => $extra_parts[1] ?? '0'];
                }
            }
        }

        $res_bur = mysqli_query($connect1, "SELECT * FROM tb_bursary WHERE id_student = '$student_id' ORDER BY bur_year DESC");
        $family['history_list'] = [];
        while ($row = mysqli_fetch_assoc($res_bur)) $family['history_list'][] = ['year' => $row['bur_year'], 'name' => $row['bur_name'], 'amount' => $row['bur_quantity']];
        $family['history_scholarship'] = !empty($family['history_list']) ? 'yes' : 'no';

        $family['parents_status'] = [];
        $family_status_parts = array_filter(explode('|-o-|', $student_data['st_family_status']));
        if (in_array('1', $family_status_parts)) $family['parents_status'][] = 'together';
        if (in_array('2', $family_status_parts)) $family['parents_status'][] = 'divorced';
        if (in_array('3', $family_status_parts)) $family['parents_status'][] = 'separated';
        if (in_array('4', $family_status_parts)) $family['parents_status'][] = 'father_died';
        if (in_array('5', $family_status_parts)) $family['parents_status'][] = 'mother_died';

        $loan_parts = explode('|-o-|', $student_data['st_borrow_money']);
        $family['loan_status'] = ($loan_parts[0] == '1') ? 'yes' : 'no';
        $family['loan_amount'] = $loan_parts[2] ?? '';
        $family['loan_no_reason'] = ($family['loan_status'] == 'no') ? ($loan_parts[1] ?? '') : '';

        $expense_parts = array_filter(explode('|-o-|', $student_data['st_received']));
        $family['expense_source'] = [];
        if (in_array('1', $expense_parts)) $family['expense_source'][] = 'father';
        if (in_array('2', $expense_parts)) $family['expense_source'][] = 'mother';
        if (in_array('3', $expense_parts)) $family['expense_source'][] = 'guardian';
        if (in_array('4', $expense_parts)) $family['expense_source'][] = 'loan';
        if (in_array('5', $expense_parts)) $family['expense_source'][] = 'other';
        $family['expense_amount'] = end($expense_parts) ?: '';

        $work_parts = explode('|-o-|', $student_data['st_job']);
        $family['work_history'] = ($work_parts[0] == '1') ? 'yes' : 'no';
        $family['work_history_type'] = $work_parts[1] ?? '';
        $family['work_history_income'] = $work_parts[2] ?? '';

        $curr_work_parts = explode('|-o-|', $student_data['st_current_job']);
        $family['current_work'] = ($curr_work_parts[0] == '1') ? 'yes' : 'no';
        $family['current_work_reason'] = ($family['current_work'] == 'no') ? ($curr_work_parts[1] ?? '') : '';

        $prob_parts = explode('|-o-|', $student_data['st_peripeteia']);
        $family['financial_prob'] = ($prob_parts[0] == '1') ? 'often' : 'not_often';
        $family['financial_prob_reason'] = $prob_parts[2] ?? '';

        $solve_parts = array_filter(explode('|-o-|', $student_data['st_solutions']));
        $family['solve_prob'] = '';
        if (in_array('1', $solve_parts)) $family['solve_prob'] = 'loan_out';
        if (in_array('2', $solve_parts)) $family['solve_prob'] = 'loan_in';
        if (in_array('3', $solve_parts)) $family['solve_prob'] = 'relative';
        if (in_array('4', $solve_parts)) $family['solve_prob'] = 'parttime';
        if (in_array('5', $solve_parts)) $family['solve_prob'] = 'other';
        $family['solve_prob_other'] = ($family['solve_prob'] == 'other') ? end($solve_parts) : '';
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

if ($student === null) die("Error: ไม่พบข้อมูลนักศึกษา");
$current_month_th = [1 => "มกราคม", 2 => "กุมภาพันธ์", 3 => "มีนาคม", 4 => "เมษายน", 5 => "พฤษภาคม", 6 => "มิถุนายน", 7 => "กรกฎาคม", 8 => "สิงหาคม", 9 => "กันยายน", 10 => "ตุลาคม", 11 => "พฤศจิกายน", 12 => "ธันวาคม"][(int)date("n")];
$current_year_th = date("Y") + 543;
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบสมัคร - ข้อมูลครอบครัว</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/bg/head_01.png">

    <!-- Bootstrap 5 -->
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
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

            <div class="text-center mb-5" style="line-height: 2;">
                <span class="mx-2"><strong>ชื่อ:</strong> <?php echo htmlspecialchars($student['prefix'] . $student['firstname'] . " " . $student['lastname']); ?></span>
                <span class="mx-2"><strong>รหัสนักศึกษา:</strong> <?php echo htmlspecialchars($student['id']); ?></span><br>
                <span class="mx-2"><strong>เกรดเฉลี่ย:</strong> <?php echo htmlspecialchars($student['gpa']); ?></span>
                <span class="mx-2"><strong>สาขาวิชา:</strong> <?php echo htmlspecialchars($student['major']); ?></span>
                <span class="mx-2"><strong>อาจารย์ที่ปรึกษา:</strong> <?php echo htmlspecialchars($student['advisor']); ?></span>
            </div>

            <!-- Tabs Menu -->
            <ul class="nav-tabs-app">
                <li class="nav-item"><a href="../advisors/give_score.php?student_id=<?php echo $student_id; ?>" class="nav-link-custom inactive">ข้อมูลส่วนตัว</a></li>
                <li class="nav-item"><a href="../advisors/family.php?student_id=<?php echo $student_id; ?>" class="nav-link-custom active">ข้อมูลครอบครัว</a></li>
                <li class="nav-item"><a href="../advisors/reasons.php?student_id=<?php echo $student_id; ?>" class="nav-link-custom inactive">ระบุเหตุผลการขอทุน</a></li>
                <li class="nav-item"><a href="../advisors/document.php?student_id=<?php echo $student_id; ?>" class="nav-link-custom inactive">หนังสือรับรองและเอกสารแนบ</a></li>
            </ul>

            <div class="px-2">
                <!-- 1. ข้อมูลบิดา -->
                <div class="section-header-app">1. ข้อมูลบิดา</div>
                <div class="indent-app">
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">ชื่อ-สกุล บิดา:</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['father_name']; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">อายุ:</label>
                        <div class="col-sm-9 d-flex align-items-center gap-2">
                            <div class="form-control-static text-center" style="width: 80px;"><?php echo $family['father_age']; ?></div>
                            <span>ปี</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" <?php echo ($family['father_status'] == 'alive') ? 'checked' : 'disabled'; ?>> มีชีวิตอยู่</div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" <?php echo ($family['father_status'] == 'deceased') ? 'checked' : 'disabled'; ?>> ถึงแก่กรรม</div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">อาชีพ:</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['father_job']; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">รายได้ต่อเดือน:</label>
                        <div class="col-sm-9 d-flex align-items-center gap-2">
                            <div class="form-control-static text-end" style="max-width: 250px;"><?php echo $family['father_income']; ?></div>
                            <span>บาท</span>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">สถานที่ทำงาน :</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['father_workplace']; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">หมายเลขโทรศัพท์:</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['father_phone']; ?></div>
                        </div>
                    </div>
                </div>

                <!-- 2. ข้อมูลมารดา -->
                <div class="section-header-app">2. ข้อมูลมารดา</div>
                <div class="indent-app">
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">ชื่อ-สกุล มารดา:</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['mother_name']; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">อายุ:</label>
                        <div class="col-sm-9 d-flex align-items-center gap-2">
                            <div class="form-control-static text-center" style="width: 80px;"><?php echo $family['mother_age']; ?></div>
                            <span>ปี</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" <?php echo ($family['mother_status'] == 'alive') ? 'checked' : 'disabled'; ?>> มีชีวิตอยู่</div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" <?php echo ($family['mother_status'] == 'deceased') ? 'checked' : 'disabled'; ?>> ถึงแก่กรรม</div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">อาชีพ:</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['mother_job']; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">รายได้ต่อเดือน:</label>
                        <div class="col-sm-9 d-flex align-items-center gap-2">
                            <div class="form-control-static text-end" style="max-width: 250px;"><?php echo $family['mother_income']; ?></div>
                            <span>บาท</span>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">สถานที่ทำงาน :</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['mother_workplace']; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">หมายเลขโทรศัพท์:</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['mother_phone']; ?></div>
                        </div>
                    </div>
                </div>

                <!-- 3. ข้อมูลผู้ปกครอง -->
                <div class="section-header-app">3. ข้อมูลผู้ปกครอง</div>
                <div class="indent-app">
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">ชื่อ-สกุล ผู้ปกครอง:</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['guardian_name']; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">อายุ:</label>
                        <div class="col-sm-9 d-flex align-items-center gap-2">
                            <div class="form-control-static text-center" style="width: 80px;"><?php echo $family['guardian_age']; ?></div>
                            <span>ปี</span>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">อาชีพ:</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['guardian_job']; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">รายได้ต่อเดือน:</label>
                        <div class="col-sm-9 d-flex align-items-center gap-2">
                            <div class="form-control-static text-end" style="max-width: 250px;"><?php echo $family['guardian_income']; ?></div>
                            <span>บาท</span>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">สถานที่ทำงาน :</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['guardian_workplace']; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 fw-medium">หมายเลขโทรศัพท์:</label>
                        <div class="col-sm-9">
                            <div class="form-control-static"><?php echo $family['guardian_phone']; ?></div>
                        </div>
                    </div>
                </div>

                <!-- 4. สถานภาพ -->
                <div class="section-header-app">4. สถานภาพของบิดามารดา</div>
                <div class="indent-app mb-4">
                    <div class="form-check form-check-app"><input class="form-check-input" type="checkbox" <?php echo in_array('together', $family['parents_status']) ? 'checked' : 'disabled'; ?>> อยู่ด้วยกัน</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="checkbox" <?php echo in_array('divorced', $family['parents_status']) ? 'checked' : 'disabled'; ?>> หย่าร้าง</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="checkbox" <?php echo in_array('separated', $family['parents_status']) ? 'checked' : 'disabled'; ?>> แยกกันอยู่</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="checkbox" <?php echo in_array('father_died', $family['parents_status']) ? 'checked' : 'disabled'; ?>> บิดาเสียชีวิต</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="checkbox" <?php echo in_array('mother_died', $family['parents_status']) ? 'checked' : 'disabled'; ?>> มารดาเสียชีวิต</div>
                </div>

                <!-- 5. พี่น้อง -->
                <div class="section-header-app">5. จำนวนพี่น้องร่วมบิดามารดา</div>
                <div class="indent-app">
                    <div class="table-responsive">
                        <table class="table-app">
                            <thead>
                                <tr>
                                    <th>ชื่อ-สกุล</th>
                                    <th>สถานศึกษา</th>
                                    <th>สถานที่ทำงาน</th>
                                    <th>รายได้/บาท</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($family['siblings'] as $sibling): ?>
                                    <tr>
                                        <td><?php echo $sibling['name']; ?></td>
                                        <td><?php echo $sibling['edu']; ?></td>
                                        <td><?php echo $sibling['work']; ?></td>
                                        <td class="text-end"><?php echo $sibling['income']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 6. กู้ยืม -->
                <div class="section-header-app">6. นักศึกษากู้ยืมกองทุน กยศ. หรือ กรอ. หรือไม่</div>
                <div class="indent-app">
                    <div class="row mb-3 align-items-center">
                        <div class="col-auto">
                            <div class="form-check"><input class="form-check-input" type="radio" <?php echo ($family['loan_status'] == 'yes') ? 'checked' : 'disabled'; ?>> กู้</div>
                        </div>
                        <div class="col-auto">จำนวน</div>
                        <div class="col-sm-3">
                            <div class="form-control-static text-end"><?php echo $family['loan_amount']; ?></div>
                        </div>
                        <div class="col-auto">บาท/ปี</div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-auto">
                            <div class="form-check"><input class="form-check-input" type="radio" <?php echo ($family['loan_status'] == 'no') ? 'checked' : 'disabled'; ?>> ไม่ได้กู้</div>
                        </div>
                        <div class="col-auto pt-2">สาเหตุ</div>
                        <div class="col-sm-9">
                            <div class="form-control-static bg-light"><?php echo $family['loan_no_reason']; ?></div>
                        </div>
                    </div>
                </div>

                <!-- 7. นักศึกษาได้รับค่าครองชีพจาก -->
                <div class="section-header-app">7. นักศึกษาได้รับค่าครองชีพจาก</div>
                <div class="indent-app mb-4">
                    <div class="form-check form-check-app"><input class="form-check-input" type="checkbox" <?php echo in_array('father', $family['expense_source']) ? 'checked' : 'disabled'; ?>> บิดา</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="checkbox" <?php echo in_array('mother', $family['expense_source']) ? 'checked' : 'disabled'; ?>> มารดา</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="checkbox" <?php echo in_array('guardian', $family['expense_source']) ? 'checked' : 'disabled'; ?>> ผู้ปกครอง</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="checkbox" <?php echo in_array('loan', $family['expense_source']) ? 'checked' : 'disabled'; ?>> กองทุนกู้ยืมเพื่อการศึกษา</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="checkbox" <?php echo in_array('other', $family['expense_source']) ? 'checked' : 'disabled'; ?>> อื่นๆ</div>
                    <div class="row align-items-center mt-2">
                        <div class="col-auto">สามารถเลือกได้มากกว่า 1 ตัวเลือก รวมเดือนละ</div>
                        <div class="col-sm-3">
                            <div class="form-control-static text-end"><?php echo $family['expense_amount']; ?></div>
                        </div>
                        <div class="col-auto">บาท</div>
                    </div>
                </div>

                <!-- 8. นักศึกษาเคยทำงานพิเศษระหว่างที่ศึกษาอยู่หรือไม่ -->
                <div class="section-header-app">8. นักศึกษาเคยทำงานพิเศษระหว่างที่ศึกษาอยู่หรือไม่</div>
                <div class="indent-app mb-4">
                    <div class="row mb-3 align-items-center">
                        <div class="col-auto">
                            <div class="form-check"><input class="form-check-input" type="radio" <?php echo ($family['work_history'] == 'yes') ? 'checked' : 'disabled'; ?>> เคย</div>
                        </div>
                        <div class="col-auto">ระบุประเภท</div>
                        <div class="col-sm-3">
                            <div class="form-control-static"><?php echo $family['work_history_type']; ?></div>
                        </div>
                        <div class="col-auto">รายได้ต่อเดือน:</div>
                        <div class="col-sm-2">
                            <div class="form-control-static text-end"><?php echo $family['work_history_income']; ?></div>
                        </div>
                        <div class="col-auto">บาท</div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" <?php echo ($family['work_history'] == 'no') ? 'checked' : 'disabled'; ?>> ไม่เคย (ข้ามไปทำข้อ 10.)
                    </div>
                </div>

                <!-- 9. ถ้านักศึกษาเคยทำงานพิเศษ ปัจจุบันยังทำอยู่หรือไม่ -->
                <div class="section-header-app">9. ถ้านักศึกษาเคยทำงานพิเศษ ปัจจุบันยังทำอยู่หรือไม่</div>
                <div class="indent-app mb-4">
                    <div class="row mb-3 align-items-center">
                        <div class="col-auto">
                            <div class="form-check"><input class="form-check-input" type="radio" <?php echo ($family['current_work'] == 'yes') ? 'checked' : 'disabled'; ?>> ทำ</div>
                        </div>
                        <div class="col-auto">ระบุประเภท</div>
                        <div class="col-sm-8">
                            <div class="form-control-static"><?php echo $family['work_history_type']; ?></div>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-auto">
                            <div class="form-check"><input class="form-check-input" type="radio" <?php echo ($family['current_work'] == 'no') ? 'checked' : 'disabled'; ?>> ไม่ทำ</div>
                        </div>
                        <div class="col-auto pt-2">เนื่องด้วย</div>
                        <div class="col-sm-8">
                            <div class="form-control-static bg-light"><?php echo $family['current_work_reason']; ?></div>
                        </div>
                    </div>
                </div>

                <!-- 10. ครอบครัวของนักศึกษาประสบปัญหาการเงินบ่อยเพียงใด -->
                <div class="section-header-app">10. ครอบครัวของนักศึกษาประสบปัญหาการเงินบ่อยเพียงใด</div>
                <div class="indent-app mb-4">
                    <div class="row mb-3 align-items-center">
                        <div class="col-auto">
                            <div class="form-check"><input class="form-check-input" type="radio" <?php echo ($family['financial_prob'] == 'often') ? 'checked' : 'disabled'; ?>> บ่อย</div>
                        </div>
                        <div class="col-auto">เนื่องจาก</div>
                        <div class="col-sm-8">
                            <div class="form-control-static bg-light"><?php echo $family['financial_prob_reason']; ?></div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" <?php echo ($family['financial_prob'] == 'not_often') ? 'checked' : 'disabled'; ?>> ไม่บ่อย
                    </div>
                </div>

                <!-- 11. เมื่อครอบครัวมีปัญหาการเงิน ผู้ปกครองหรือนักศึกษา จะแก้ไขด้วยวิธีใด -->
                <div class="section-header-app">11. เมื่อครอบครัวมีปัญหาการเงิน ผู้ปกครองหรือนักศึกษา จะแก้ไขด้วยวิธีใด</div>
                <div class="indent-app mb-4">
                    <div class="form-check form-check-app"><input class="form-check-input" type="radio" <?php echo ($family['solve_prob'] == 'loan_out') ? 'checked' : 'disabled'; ?>> กู้ยืมนอกระบบ</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="radio" <?php echo ($family['solve_prob'] == 'loan_in') ? 'checked' : 'disabled'; ?>> กู้ยืมในระบบ</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="radio" <?php echo ($family['solve_prob'] == 'relative') ? 'checked' : 'disabled'; ?>> ญาติ/เพื่อน</div>
                    <div class="form-check form-check-app"><input class="form-check-input" type="radio" <?php echo ($family['solve_prob'] == 'parttime') ? 'checked' : 'disabled'; ?>> ทำงานพิเศษ</div>
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="form-check"><input class="form-check-input" type="radio" <?php echo ($family['solve_prob'] == 'other') ? 'checked' : 'disabled'; ?>> อื่นๆ</div>
                        </div>
                        <div class="col-auto">ระบุ</div>
                        <div class="col-sm-9">
                            <div class="form-control-static bg-light"><?php echo $family['solve_prob_other']; ?></div>
                        </div>
                    </div>
                </div>

                <!-- 12. ประวัติทุนการศึกษา -->
                <div class="section-header-app">12. นักศึกษาเคยได้รับทุนการศึกษาจากคณะฯ หรือหน่วยงานอื่นๆ หรือไม่</div>
                <div class="indent-app mb-5">
                    <div class="row mb-3">
                        <div class="col-auto">
                            <div class="form-check"><input class="form-check-input" type="radio" <?php echo ($family['history_scholarship'] == 'yes') ? 'checked' : 'disabled'; ?>> เคย (ระบุ 3 ปีย้อนหลัง)</div>
                        </div>
                        <div class="col-auto">
                            <div class="form-check"><input class="form-check-input" type="radio" <?php echo ($family['history_scholarship'] == 'no') ? 'checked' : 'disabled'; ?>> ไม่เคย</div>
                        </div>
                    </div>

                    <?php if ($family['history_scholarship'] == 'yes'): ?>
                        <div class="table-responsive">
                            <table class="table-app">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">ลำดับ</th>
                                        <th style="width: 150px;">เมื่อปี</th>
                                        <th>ชื่อทุน</th>
                                        <th style="width: 200px;">จำนวน (บาท)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($family['history_list'] as $idx => $hist): ?>
                                        <tr>
                                            <td class="text-center"><?php echo $idx + 1; ?>.</td>
                                            <td class="text-center"><?php echo $hist['year']; ?></td>
                                            <td><?php echo $hist['name']; ?></td>
                                            <td class="text-end"><?php echo $hist['amount']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Scoring Area -->
                <form action="../scores/save_score.php" method="POST" class="scoring-panel">
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
    </div>

    <script>
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
        window.onscroll = function() {
            let btn = document.getElementById("scrollTopBtn");
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) btn.style.display = "block";
            else btn.style.display = "none";
        };
    </script>

    <?php include '../../include/footer.php'; ?> 

</body>

</html>