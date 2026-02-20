<?php
// เช็คชื่อไฟล์ปัจจุบัน
$current_page = basename($_SERVER['PHP_SELF']); 

// --- ดึงข้อมูลเฉพาะประเภททุนที่ "เปิด" อยู่ ---
$open_scholarship_options = [];
if (isset($connect1)) {
    $sql_types = "SELECT st_name_1, st_1, st_name_2, st_2, st_name_3, st_3 FROM tb_year WHERE y_id = 1";
    $result_types = mysqli_query($connect1, $sql_types);
    if ($result_types && mysqli_num_rows($result_types) > 0) {
        $data_types = mysqli_fetch_assoc($result_types);
        if (!empty($data_types['st_name_1']) && $data_types['st_1'] == 0) $open_scholarship_options[1] = $data_types['st_name_1'];
        if (!empty($data_types['st_name_2']) && $data_types['st_2'] == 0) $open_scholarship_options[2] = $data_types['st_name_2'];
        if (!empty($data_types['st_name_3']) && $data_types['st_3'] == 0) $open_scholarship_options[3] = $data_types['st_name_3'];
    }
}

// กำหนดชื่อแสดงผล
if (isset($_SESSION['tc_name'])) {
    $display_name = $_SESSION['tc_name'];
} elseif (isset($_SESSION['st_name'])) {
    $display_name = $_SESSION['st_name'];
} else {
    $display_name = 'User';
}

$is_regis_page = ($current_page == 'regis.php');

// รายการหน้าที่ไม่ให้แสดงตัวเลือกทุน
$no_selector_pages = [
    'apply_form.php', 'apply_fam.php', 'apply_reasons.php', 'apply_document.php', 'confirm_page.php',
    'teacher.php', 'give_score.php', 'family.php', 'reasons.php', 'document.php', 'admin.php', 
    'news.php', 'issue.php', 'academic_year.php', 'scholarship_types.php', 'majors.php', 
    'advisors.php', 'committees.php', 'susp_std.php', 'student_data.php', 'scholarship_scores.php', 
    'clear_data.php', 'add_news.php', 'susp_std_add.php', 'issue_view.php', 'view_student_details.php', 'view_score_details.php', 'edit_student.php'
];
?>

<div class="user-status-bar">
    <div class="user-status-content <?php echo $is_regis_page ? 'center-mode' : ''; ?>">
        
        <!-- ส่วนเลือกทุน -->
        <?php if (!in_array($current_page, $no_selector_pages)): ?>
        <div class="scholarship-selector-group">
            <div class="regis-custom-select-wrapper" id="customScholarship">
                <div class="regis-custom-select-trigger" id="scholarshipTrigger">
                    <span>-- กรุณาเลือกประเภททุน --</span>
                    <i class="fa-solid fa-chevron-down text-muted" style="font-size: 12px;"></i>
                </div>
                <div class="regis-custom-options" id="scholarshipOptions">
                    <div class="regis-option disabled" data-value="">-- กรุณาเลือกประเภททุน --</div>
                    <?php
                    if (!empty($open_scholarship_options)) {
                        foreach ($open_scholarship_options as $id => $name) {
                            echo '<div class="regis-option" data-value="' . $id . '">' . htmlspecialchars($name) . '</div>';
                        }
                    } else {
                        echo '<div class="regis-option disabled">-- ไม่มีทุนที่เปิดรับสมัคร --</div>';
                    }
                    ?>
                </div>
                <input type="hidden" name="scholarship_type" id="scholarship_type_value">
            </div>
        </div>
        <?php else: ?>
            <div class="scholarship-selector-group"></div>
        <?php endif; ?>

        <!-- ส่วนของ ชื่อผู้ใช้ และ ออกจากระบบ -->
        <?php if (!$is_regis_page): ?>
            <?php $first_char = mb_substr($display_name, 0, 1, 'UTF-8'); ?>
            <div class="user-info-actions">
                <div class="user-avatar-circle">
                    <?php echo htmlspecialchars($first_char); ?>
                </div>
                <span class="user-name"><?php echo htmlspecialchars($display_name); ?></span>
                <a href="javascript:void(0);" onclick="confirmLogout();" class="logout-link">
                    <i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'ยืนยันการออกจากระบบ?',
        text: "คุณต้องการออกจากระบบใช่หรือไม่",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745', 
        cancelButtonColor: '#d33',
        confirmButtonText: 'ออกจากระบบ',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../../root/logout.php';
        }
    })
}

document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.getElementById('customScholarship');
    if (wrapper) {
        const trigger = document.getElementById('scholarshipTrigger');
        const optionsContainer = document.getElementById('scholarshipOptions');
        const hiddenInput = document.getElementById('scholarship_type_value');
        const options = optionsContainer.querySelectorAll('.regis-option');
        const formHeaderTitle = document.getElementById('scholarship-title-header');
        const formHiddenType = document.getElementById('hidden-scholarship-type');

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            optionsContainer.classList.toggle('show');
        });

        options.forEach(option => {
            option.addEventListener('click', function() {
                if (this.classList.contains('disabled')) return;
                const value = this.getAttribute('data-value');
                const text = this.textContent;

                trigger.querySelector('span').textContent = text;
                hiddenInput.value = value;

                if (formHeaderTitle) {
                    formHeaderTitle.textContent = (value !== "") ? "กรอกข้อมูลการสมัคร " + text : "กรุณาเลือกประเภททุนการศึกษาที่ต้องการสมัคร";
                }
                if (formHiddenType) formHiddenType.value = value;

                options.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                optionsContainer.classList.remove('show');
            });
        });

        window.addEventListener('click', function(e) {
            if (!wrapper.contains(e.target)) optionsContainer.classList.remove('show');
        });
    }
});
</script>