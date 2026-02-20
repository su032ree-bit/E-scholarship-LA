<?php
session_start();
include '../include/config.php';

// --- 1. ดึงข้อมูลประเภททุน ---
$scholarship_options = [];
if (isset($connect1)) {
    $sql_types = "SELECT st_name_1, st_1, st_name_2, st_2, st_name_3, st_3 FROM tb_year WHERE y_id = 1";
    $result_types = mysqli_query($connect1, $sql_types);
    if ($result_types && mysqli_num_rows($result_types) > 0) {
        $data_types = mysqli_fetch_assoc($result_types);
        if (!empty($data_types['st_name_1'])) $scholarship_options[1] = $data_types['st_name_1'];
        if (!empty($data_types['st_name_2'])) $scholarship_options[2] = $data_types['st_name_2'];
        if (!empty($data_types['st_name_3'])) $scholarship_options[3] = $data_types['st_name_3'];
    }
}

// --- 2. ดึงข้อมูลสาขาวิชา ---
$major_options = [];
if (isset($connect1)) {
    $sql_majors = "SELECT g_id, g_program FROM tb_program ORDER BY g_program ASC";
    $result_majors = mysqli_query($connect1, $sql_majors);
    if ($result_majors) {
        while ($row = mysqli_fetch_assoc($result_majors)) {
            $major_options[$row['g_id']] = $row['g_program'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครทุนการศึกษา - คณะศิลปศาสตร์</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="sticky-header-wrapper">
        <?php include('../include/navbar.php'); ?>
        <?php include('../include/status_bar.php'); ?>
    </div>

    <div class="container py-5">
        <div class="regis-container-card mx-auto shadow border p-4 p-md-5" style="max-width: 850px; background: #fff; border-radius: 15px;">

            <div class="text-center mb-5" id="header-container">
                <h1 id="scholarship-title-header" class="fw-bold" style="font-size: 24px; color: #333;">
                    กรุณาเลือกประเภททุนการศึกษาที่ต้องการสมัคร
                    <span class="error-mark" id="mark-scholarship"><i class="fa-solid fa-circle-exclamation"></i></span>
                </h1>
            </div>

            <form id="regisForm" action="../admin/students/submit_regis.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" novalidate>
                <input type="hidden" name="scholarship_type" id="hidden-scholarship-type" value="">

                <!-- ชื่อ-สกุล -->
                <div class="row mb-4" id="group-name">
                    <div class="col-md-2 pt-2">
                        <label class="regis-label fw-bold">ชื่อ-สกุล <span class="text-danger">*</span> <span class="error-mark"><i class="fa-solid fa-circle-exclamation"></i></span></label>
                    </div>
                    <div class="col-md-10">
                        <div class="row g-2">
                            <div class="col-sm-3">
                                <div class="regis-custom-select-wrapper">
                                    <select name="title" style="display: none;">
                                        <option value="" disabled selected>คำนำหน้า</option>
                                        <option value="นาย">นาย</option>
                                        <option value="นางสาว">นางสาว</option>
                                        <option value="นาง">นาง</option>
                                    </select>
                                    <div class="regis-custom-select-trigger">
                                        <span>คำนำหน้า</span>
                                        <i class="fa-solid fa-chevron-down text-muted" style="font-size: 12px;"></i>
                                    </div>
                                    <div class="regis-custom-options">
                                        <div class="regis-custom-option" data-value="นาย">นาย</div>
                                        <div class="regis-custom-option" data-value="นางสาว">นางสาว</div>
                                        <div class="regis-custom-option" data-value="นาง">นาง</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <input type="text" name="firstname" class="form-control" placeholder="ชื่อ" required oninput="checkInput(this)">
                            </div>
                            <div class="col-sm">
                                <input type="text" name="lastname" class="form-control" placeholder="นามสกุล" required oninput="checkInput(this)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- เกรดเฉลี่ย -->
                <div class="mb-4" id="group-gpa">
                    <label for="gpa" class="regis-label fw-bold mb-2">เกรดเฉลี่ย <span class="text-danger">*</span> <span class="error-mark"><i class="fa-solid fa-circle-exclamation"></i></span></label>
                    <input type="text" id="gpa" name="gpa" class="form-control" placeholder="เกรดเฉลี่ยสะสม (เช่น 3.50)" required oninput="checkInput(this)">
                </div>

                <!-- รหัสนักศึกษา -->
                <div class="mb-4" id="group-student-id">
                    <label for="student-id" class="regis-label fw-bold mb-2">รหัสนักศึกษา <span class="text-danger">*</span> <span class="error-mark"><i class="fa-solid fa-circle-exclamation"></i></span></label>
                    <input type="text" id="student-id" name="student_id" class="form-control" placeholder="กรอกรหัสนักศึกษา" required oninput="checkInput(this)">
                </div>

                <!-- สาขาวิชา -->
                <div class="mb-4" id="group-major">
                    <label for="major" class="regis-label fw-bold mb-2">สาขาวิชา <span class="text-danger">*</span> <span class="error-mark"><i class="fa-solid fa-circle-exclamation"></i></span></label>
                    <div class="regis-custom-select-wrapper">
                        <select id="major" name="major" style="display: none;">
                            <option value="" disabled selected>เลือกสาขาวิชา</option>
                            <?php foreach ($major_options as $id => $name): ?>
                                <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="regis-custom-select-trigger">
                            <span>เลือกสาขาวิชา</span>
                            <i class="fa-solid fa-chevron-down text-muted" style="font-size: 12px;"></i>
                        </div>
                        <div class="regis-custom-options">
                            <?php foreach ($major_options as $id => $name): ?>
                                <div class="regis-custom-option" data-value="<?= $id ?>"><?= htmlspecialchars($name) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- E-mail -->
                <div class="mb-4" id="group-email">
                    <label for="email" class="regis-label fw-bold mb-2">E-mail <span class="text-danger">*</span> <span class="error-mark"><i class="fa-solid fa-circle-exclamation"></i></span></label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="รหัสนักศึกษา@psu.ac.th" required oninput="checkInput(this)">
                </div>

                <!-- ภาพประจำตัว -->
                <div class="mb-4" id="group-profile-pic">
                    <label class="regis-label fw-bold mb-2">ภาพประจำตัว <span class="text-danger">*</span> <span class="error-mark"><i class="fa-solid fa-circle-exclamation"></i></span></label>
                    <div class="d-flex align-items-center mb-2">
                        <input type="file" id="profile-pic" name="profile_pic" accept=".jpg, .jpeg" style="display:none;">
                        <label for="profile-pic" class="regis-custom-file-btn">Choose File</label>
                        <span id="file-name-info" class="regis-file-info-text">No file chosen</span>
                    </div>
                    <p class="regis-note-small">
                        <span class="text-decoration-underline">หมายเหตุ</span> เครื่องแบบนักศึกษาหน้าตรงเท่านั้น
                        <span class="text-danger fw-bold">(สามารถบันทึกภาพได้จากระบบ SIS)</span> หากเป็นภาพถ่ายเอง ระบบจะลบข้อมูลทันที
                    </p>
                </div>

                <!-- รหัสผ่าน -->
                <div class="mb-4" id="group-password">
                    <label class="regis-label fw-bold mb-2">รหัสผ่าน <span class="text-danger">*</span> <span class="error-mark"><i class="fa-solid fa-circle-exclamation"></i></span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="รหัสผ่าน" required oninput="checkInput(this)">
                </div>

                <!-- เงื่อนไขการสมัคร -->
                <div class="mb-4" id="group-conditions">
                    <div class="regis-conditions-box">
                        <h5 class="fw-bold">เงื่อนไขการสมัคร <span class="error-mark"><i class="fa-solid fa-circle-exclamation"></i></span></h3>
                            <ol class="text-muted">
                                <li>ผู้สมัครต้องมีสถานะกำลังศึกษาอยู่ในคณะศิลปศาสตร์</li>
                                <li>ผู้สมัครต้องไม่มีประวัติการกระทำผิดทางวินัยนักศึกษา</li>
                                <li>ผู้สมัครต้องแต่งกายชุดนักศึกษาเรียบร้อย</li>
                                <li>ผู้สมัครต้องเข้ามายืนยันใบสมัครทางอีเมลภายใน 1 วัน</li>
                                <li>ระบบจะลบข้อมูลการสมัครทุกภาคการศึกษา</li>
                            </ol>
                            <label class="regis-accept-label mt-3 fw-medium">
                                <input type="checkbox" name="accept_conditions" id="accept_conditions" class="form-check-input" required onchange="checkCheckbox(this)">
                                <span class="ms-2">ข้าพเจ้ายอมรับเงื่อนไข</span>
                            </label>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="../root/index.php" class="btn btn-regis-cancel shadow-sm text-decoration-none">ยกเลิกการสมัคร</a>
                    <button type="submit" class="btn btn-regis-submit shadow-sm border-0">ส่งใบสมัคร</button>
                </div>
            </form>
        </div>
    </div>

    <?php include('../include/footer.php'); ?>

    <script>
        // --- ฟังก์ชันตรวจจับการพิมพ์เพื่อลบ Error ---
        function checkInput(el) {
            if (el.value.trim() !== "") el.closest('[id^="group-"]').classList.remove('has-error');
        }

        function checkCheckbox(el) {
            if (el.checked) el.closest('#group-conditions').classList.remove('has-error');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // 1. จัดการ Custom Dropdown
            const wrappers = document.querySelectorAll('.regis-custom-select-wrapper');
            wrappers.forEach(wrapper => {
                const trigger = wrapper.querySelector('.regis-custom-select-trigger');
                const options = wrapper.querySelectorAll('.regis-custom-option');
                const select = wrapper.querySelector('select');

                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    wrappers.forEach(w => {
                        if (w !== wrapper) w.classList.remove('open');
                    });
                    wrapper.classList.toggle('open');
                });

                options.forEach(opt => {
                    opt.addEventListener('click', () => {
                        const val = opt.getAttribute('data-value');
                        select.value = val;
                        trigger.querySelector('span').textContent = opt.textContent;
                        wrapper.classList.remove('open');
                        const parentGroup = wrapper.closest('[id^="group-"]');
                        if (parentGroup) parentGroup.classList.remove('has-error');
                    });
                });
            });

            window.addEventListener('click', () => {
                wrappers.forEach(w => w.classList.remove('open'));
            });

            // 2. จัดการไฟล์อัปโหลด
            const fileInput = document.getElementById('profile-pic');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
                    document.getElementById('file-name-info').textContent = fileName;
                    if (this.files[0]) this.closest('#group-profile-pic').classList.remove('has-error');
                });
            }

            // 3. ตรวจจับการเปลี่ยนประเภททุนจาก Status Bar อัตโนมัติ
            const hiddenScholarship = document.getElementById('hidden-scholarship-type');
            const headerContainer = document.getElementById('header-container');
            const observer = new MutationObserver(() => {
                if (hiddenScholarship.value !== "" && hiddenScholarship.value !== "0") {
                    headerContainer.classList.remove('has-error');
                }
            });
            if (hiddenScholarship) observer.observe(hiddenScholarship, {
                attributes: true
            });
        });

        // --- ฟังก์ชันตรวจสอบฟอร์ม (Validation) ---
        function validateForm() {
            let isValid = true;
            let errorMessage = 'กรุณากรอกข้อมูลให้ครบถ้วนในช่องที่มีเครื่องหมาย (!)';

            // ล้าง Error เก่าก่อน
            document.querySelectorAll('.has-error').forEach(el => el.classList.remove('has-error'));

            const studentId = document.getElementById("student-id").value.trim();

            // เช็คประเภททุน
            if (document.getElementById('hidden-scholarship-type').value === "") {
                document.getElementById('header-container').classList.add('has-error');
                isValid = false;
            }
            // เช็คชื่อ-สกุล
            if (document.querySelector('select[name="title"]').value === "" ||
                document.querySelector('input[name="firstname"]').value.trim() === "" ||
                document.querySelector('input[name="lastname"]').value.trim() === "") {
                document.getElementById('group-name').classList.add('has-error');
                isValid = false;
            }
            // เช็คเกรด
            const gpa = document.getElementById("gpa").value.trim();
            if (gpa === "" || !/^\d\.\d{2}$/.test(gpa)) {
                document.getElementById('group-gpa').classList.add('has-error');
                isValid = false;
                if (gpa !== "") errorMessage = 'รูปแบบเกรดเฉลี่ยไม่ถูกต้อง (เช่น 3.50)';
            }
            // เช็ครหัสนักศึกษา
            if (studentId === "") {
                document.getElementById('group-student-id').classList.add('has-error');
                isValid = false;
            }
            // เช็คสาขา
            if (document.getElementById('major').value === "") {
                document.getElementById('group-major').classList.add('has-error');
                isValid = false;
            }
            // เช็คอีเมล
            const email = document.getElementById("email").value.trim();
            if (email === "" || email !== studentId + "@psu.ac.th") {
                document.getElementById('group-email').classList.add('has-error');
                isValid = false;
                if (email !== "" && studentId !== "") errorMessage = 'อีเมลต้องเป็น รหัสนักศึกษา@psu.ac.th';
            }
            // เช็คไฟล์
            if (!document.getElementById('profile-pic').value) {
                document.getElementById('group-profile-pic').classList.add('has-error');
                isValid = false;
            }
            // เช็ครหัสผ่าน
            if (document.getElementById("password").value === "") {
                document.getElementById('group-password').classList.add('has-error');
                isValid = false;
            }
            // เช็คยอมรับเงื่อนไข
            if (!document.getElementById("accept_conditions").checked) {
                document.getElementById('group-conditions').classList.add('has-error');
                isValid = false;
            }

            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ข้อมูลไม่ถูกต้อง',
                    text: errorMessage,
                    confirmButtonColor: '#003c71'
                });
                return false;
            }
            return true;
        }
    </script>
</body>

</html>