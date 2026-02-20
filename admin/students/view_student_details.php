<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
include '../../include/config.php';

// 1. รับค่า ID นักศึกษา
$st_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($st_id <= 0) die("ไม่พบข้อมูลนักศึกษา");

// 2. ดึงข้อมูลประเภททุน
$scholarship_options = [];
$sql_types = "SELECT st_name_1, st_name_2, st_name_3 FROM tb_year WHERE y_id = 1";
$res_types = mysqli_query($connect1, $sql_types);
$d_types = mysqli_fetch_assoc($res_types);
$scholarship_options[1] = $d_types['st_name_1'] ?? 'ทุนประเภทที่ 1';
$scholarship_options[2] = $d_types['st_name_2'] ?? 'ทุนประเภทที่ 2';
$scholarship_options[3] = $d_types['st_name_3'] ?? 'ทุนประเภทที่ 3';

// 3. ดึงข้อมูลนักศึกษา
$sql = "SELECT s.*, p.g_program, t.tc_name 
        FROM tb_student s 
        LEFT JOIN tb_program p ON s.st_program = p.g_id 
        LEFT JOIN tb_teacher t ON s.id_teacher = t.tc_id 
        WHERE s.st_id = '$st_id'";
$result = mysqli_query($connect1, $sql);
$student = mysqli_fetch_assoc($result);

if (!$student) die("ไม่พบข้อมูลนักศึกษาในระบบ");

// --- 4. Logic การแกะข้อมูล (Parsing) ---
function splitData($data) { return explode('|-o-|', $data ?? ''); }
function parseParent($data) {
    $p = explode('|-o-|', $data ?? '');
    return [
        'name' => $p[0] ?? '-', 'age' => $p[1] ?? '-', 'status' => $p[2] ?? '',
        'job' => $p[3] ?? '-', 'income' => $p[4] ?? '-', 'work' => $p[5] ?? '-', 'tel' => $p[6] ?? '-'
    ];
}

$father = parseParent($student['st_father']);
$mother = parseParent($student['st_mother']);
$guardian = parseParent($student['st_guardian']);
$parents_status_raw = array_filter(splitData($student['st_family_status']));

$siblings = [];
if (!empty($student['st_siblings'])) {
    foreach (explode('|-o-|', $student['st_siblings']) as $row) {
        $parts = explode(':', $row);
        if (!empty($parts[0])) {
            $siblings[] = ['name' => $parts[0], 'edu' => $parts[1] ?? '-', 'work' => $parts[2] ?? '-', 'income' => $parts[3] ?? '0'];
        }
    }
}

$formatted_dob = '-';
if ($student['st_birthday'] && $student['st_birthday'] != '0000-00-00') {
    $date_obj = DateTime::createFromFormat('Y-m-d', $student['st_birthday']);
    if ($date_obj) $formatted_dob = $date_obj->format('d/m/') . ($date_obj->format('Y') + 543);
}

$current_date_th = date("j") . " " . [1=>"ม.ค.",2=>"ก.พ.",3=>"มี.ค.",4=>"เม.ย.",5=>"พ.ค.",6=>"มิ.ย.",7=>"ก.ค.",8=>"ส.ค.",9=>"ก.ย.",10=>"ต.ค.",11=>"พ.ย.",12=>"ธ.ค."][(int)date("n")] . " " . (date("Y") + 543);

// --- 5. ฟังก์ชันช่วยแสดงไฟล์เอกสาร (ปรับปรุงขนาดมาตรฐาน) ---
function renderFilePreview($filename) {
    if (!$filename) return '<div class="no-file-box">ไม่ได้แนบเอกสาร</div>';
    $path = "../../images/student/" . $filename;
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    // กำหนดโครงสร้างที่มีคลาสควบคุมขนาดไว้อย่างชัดเจน
    $html = '<div class="fixed-preview-container shadow-sm border">';
    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $html .= '<img src="' . $path . '" class="standard-fit-view">';
    } elseif ($ext == 'pdf') {
        $html .= '<embed src="' . $path . '#toolbar=0" type="application/pdf" class="standard-fit-view">';
    } else {
        $html .= '<div class="p-4 text-center">ไม่รองรับพรีวิว (' . $ext . ') <br><a href="' . $path . '" target="_blank" class="btn btn-sm btn-primary">เปิดไฟล์ภายนอก</a></div>';
    }
    $html .= '</div>';
    return $html;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดนักศึกษา - <?php echo $student['st_firstname']; ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/bg/head_01.png">
    
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        /* สไตล์เฉพาะที่ใช้ร่วมกับฟังก์ชัน renderFilePreview */
        .fixed-preview-container {
            width: 100%;
            height: 700px; /* กำหนดความสูงมาตรฐานเท่ากันทุกรูป */
            margin: 20px 0;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-radius: 8px;
        }
        .standard-fit-view {
            width: 100%;
            height: 100%;
            object-fit: contain; /* บังคับให้รูปไม่ล้นและคงสัดส่วนเดิมภายในพื้นที่ 700px */
            border: none;
        }
        .no-file-box {
            width: 100%; height: 100px; display: flex; justify-content: center; align-items: center;
            background-color: #fff5f5; color: #d9534f; border: 1px dashed #d9534f; border-radius: 8px;
        }
        
        /* สไตล์ Header และ Tab */
        .photo-box-view {
            position: absolute; top: 0; right: 0;
            width: 110px; height: 140px;
            border: 1.5px solid #333; padding: 2px; background: #fff;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        }
        .photo-box-view img { width: 100%; height: 100%; object-fit: cover; }
        .tab-section { display: none; }
        .tab-section.active { display: block; }
        @media (max-width: 1024px) { .photo-box-view { position: relative; margin: 0 auto 20px; top: auto; right: auto; } }
    </style>
</head>

<body>
    <div class="sticky-header-wrapper">
        <?php include('../../include/navbar.php'); ?>
        <?php include('../../include/status_bar.php'); ?>
    </div>

    <div class="container-fluid dashboard-container">
        <div class="row g-4">
            <!-- Sidebar (20%) -->
            <div class="col-12 col-sidebar-20">
                <?php include '../../include/sidebar.php'; ?>
            </div>

            <!-- Content (80%) -->
            <div class="col-12 col-main-80">
                <main class="main-content shadow-sm">
                    
                    <div class="mb-4 no-print">
                        <a href="student_data.php?type=<?php echo $student['st_type']; ?>" class="btn btn-secondary rounded-pill px-4 shadow-sm">
                            <i class="fa-solid fa-arrow-left me-2"></i> กลับหน้ารายการ
                        </a>
                    </div>

                    <div class="header-content position-relative text-center mb-5">
                        <h4 class="fw-bold m-0">ใบสมัครขอรับทุนการศึกษา <?php echo htmlspecialchars($scholarship_options[$student['st_type']] ?? ''); ?></h4>
                        <h5 class="fw-bold mt-2">คณะศิลปศาสตร์ มหาวิทยาลัยสงขลานครินทร์</h5>
                        <p class="text-muted small mt-2">เรียกดูเมื่อ: <?php echo $current_date_th; ?> | <?php echo date("H:i"); ?> น.</p>
                        <div class="photo-box-view">
                            <img src="../../images/student/<?php echo $student['st_image']; ?>" onerror="this.src='../../assets/images/bg/no-profile.png'">
                        </div>
                    </div>

                    <div class="student-info-highlight shadow-sm mb-5">
                        <span><strong>ชื่อ-สกุล:</strong> <?php echo ($student['st_sex'] == 1 ? 'นาย' : 'นางสาว') . $student['st_firstname'] . ' ' . $student['st_lastname']; ?></span>
                        <span class="ms-md-4"><strong>รหัสนักศึกษา:</strong> <?php echo $student['st_code']; ?></span><br>
                        <span><strong>GPAX:</strong> <?php echo $student['st_score']; ?></span>
                        <span class="ms-md-4"><strong>สาขาวิชา:</strong> <?php echo $student['g_program']; ?></span>
                    </div>

                    <!-- Tabs Menu -->
                    <ul class="nav-tabs-app no-print">
                        <li class="nav-item"><a href="javascript:void(0)" onclick="openTab(event, 'personal')" class="nav-link-custom active">ข้อมูลส่วนตัว</a></li>
                        <li class="nav-item"><a href="javascript:void(0)" onclick="openTab(event, 'family')" class="nav-link-custom inactive">ข้อมูลครอบครัว</a></li>
                        <li class="nav-item"><a href="javascript:void(0)" onclick="openTab(event, 'reason')" class="nav-link-custom inactive">เหตุผลการขอทุน</a></li>
                        <li class="nav-item"><a href="javascript:void(0)" onclick="openTab(event, 'document')" class="nav-link-custom inactive">เอกสารแนบ</a></li>
                    </ul>

                    <div class="tab-content pt-2 px-2">
                        <!-- 1. ข้อมูลส่วนตัว -->
                        <div id="personal" class="tab-section active">
                            <div class="section-header-app">ข้อมูลพื้นฐานนักศึกษา</div>
                            <div class="indent-app">
                                <div class="row mb-3 align-items-center"><label class="col-md-3 fw-bold text-muted">วันเกิด:</label><div class="col-md-9"><div class="form-control-static"><?php echo $formatted_dob; ?></div></div></div>
                                <div class="row mb-3 align-items-center"><label class="col-md-3 fw-bold text-muted">อายุ:</label><div class="col-md-9 d-flex align-items-center gap-2"><div class="form-control-static text-center" style="width: 80px;"><?php echo $student['st_age']; ?></div><span>ปี</span></div></div>
                                <div class="row mb-3 align-items-center"><label class="col-md-3 fw-bold text-muted">ที่อยู่:</label><div class="col-md-9"><div class="form-control-static"><?php echo $student['st_address1']; ?></div></div></div>
                                <div class="row mb-3 align-items-center"><label class="col-md-3 fw-bold text-muted">โทรศัพท์:</label><div class="col-md-9"><div class="form-control-static"><?php echo $student['st_tel1']; ?></div></div></div>
                            </div>
                        </div>

                        <!-- 2. ข้อมูลครอบครัว -->
                        <div id="family" class="tab-section">
                            <?php $ps = [['t' => '1. ข้อมูลบิดา', 'd' => $father], ['t' => '2. ข้อมูลมารดา', 'd' => $mother], ['t' => '3. ข้อมูลผู้ปกครอง', 'd' => $guardian]];
                            foreach ($ps as $p): $d = $p['d']; ?>
                                <div class="section-header-app"><?php echo $p['t']; ?></div>
                                <div class="indent-app">
                                    <div class="row mb-3 align-items-center"><label class="col-md-3 fw-bold text-muted">ชื่อ-สกุล:</label><div class="col-md-9"><div class="form-control-static"><?php echo $d['name']; ?></div></div></div>
                                    <div class="row mb-3 align-items-center"><label class="col-md-3 fw-bold text-muted">อาชีพ / รายได้:</label><div class="col-md-9 d-flex gap-3 align-items-center"><div class="form-control-static" style="flex: 2;"><?php echo $d['job']; ?></div><div class="form-control-static text-end" style="flex: 1;"><?php echo number_format((float)$d['income']); ?></div><span>บาท</span></div></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- 3. เหตุผล -->
                        <div id="reason" class="tab-section">
                            <div class="section-header-app">เหตุผลความจำเป็น</div>
                            <div class="content-display-gray shadow-sm border p-4" style="background: #fcfcfc;">
                                <?php echo nl2br(htmlspecialchars($student['st_note'] ?: 'ไม่พบข้อมูลเหตุผล')); ?>
                            </div>
                        </div>

                        <!-- 4. เอกสารแนบ -->
                        <div id="document" class="tab-section">
                            <div class="section-header-app">เอกสารแนบ 1: บัตรนักศึกษา/Transcript</div>
                            <?php echo renderFilePreview($student['st_doc']); ?>
                            
                            <div class="section-header-app mt-5">เอกสารแนบ 2: รายได้/สมุดบัญชี</div>
                            <?php echo renderFilePreview($student['st_doc1']); ?>
                            
                            <div class="section-header-app mt-5">เอกสารแนบ 3: ภาพถ่ายบ้าน</div>
                            <?php echo renderFilePreview($student['st_doc2']); ?>
                        </div>
                    </div>

                    <div class="text-center mt-5 no-print border-top pt-4">
                        <button onclick="window.print()" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm border-0">
                            <i class="fa-solid fa-print me-2"></i> พิมพ์ใบสมัครและเอกสารทั้งหมด
                        </button>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <?php include '../../include/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openTab(evt, tabName) {
            const sects = document.querySelectorAll(".tab-section");
            const links = document.querySelectorAll(".nav-tabs-app a");
            sects.forEach(s => { s.classList.remove("active"); s.style.display = "none"; });
            links.forEach(l => { l.classList.replace("active", "inactive"); });
            const activeSec = document.getElementById(tabName);
            activeSec.style.display = "block"; activeSec.classList.add("active");
            evt.currentTarget.classList.replace("inactive", "active");
        }
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const menuHeader = document.querySelector('.sidebar .menu-header');
            if (menuHeader) { menuHeader.addEventListener('click', () => { if(window.innerWidth <= 1024) sidebar.classList.toggle('is-open'); }); }
        });
    </script>
</body>
</html>