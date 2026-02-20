<?php
session_start();
include '../../include/config.php';

$page_title = "ข้อมูลนักศึกษา";
$scholarship_title = "กรุณาเลือกประเภททุน";
$scholarship_id = isset($_GET['type']) ? (int)$_GET['type'] : 0;

// --- 1. ดึงข้อมูลชื่อทุนจาก tb_year ---
if ($scholarship_id >= 1 && $scholarship_id <= 3) {
    $column_name = "st_name_" . $scholarship_id;
    $sql_title = "SELECT `$column_name` FROM tb_year WHERE y_id = 1";
    $result_title = mysqli_query($connect1, $sql_title);
    if ($result_title && mysqli_num_rows($result_title) > 0) {
        $data_title = mysqli_fetch_row($result_title);
        $scholarship_title = !empty($data_title[0]) ? $data_title[0] : "ทุนประเภทที่ {$scholarship_id}";
    }
}

// --- 2. ดึงข้อมูลนักศึกษาตามประเภททุนที่เลือก ---
$students = [];
if ($scholarship_id > 0) {
    $sql_students = "SELECT 
                        s.st_id, 
                        s.st_firstname, 
                        s.st_lastname, 
                        s.st_code,
                        s.st_confirm,
                        p.g_program
                    FROM 
                        tb_student AS s
                    LEFT JOIN 
                        tb_program AS p ON s.st_program = p.g_id
                    WHERE 
                        s.st_type = '$scholarship_id'";

    if (isset($_GET['search_id']) && !empty($_GET['search_id'])) {
        $search_id = mysqli_real_escape_string($connect1, $_GET['search_id']);
        $sql_students .= " AND s.st_code LIKE '%$search_id%'";
    }

    $sql_students .= " ORDER BY s.st_firstname ASC";

    $result_students = mysqli_query($connect1, $sql_students);
    if ($result_students) {
        while ($row = mysqli_fetch_assoc($result_students)) {
            $students[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo $page_title; ?></title>
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

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="sticky-header-wrapper">
        <?php include('../../include/navbar.php'); ?>
        <?php include('../../include/status_bar.php'); ?>
    </div>

    <div class="container-fluid dashboard-container">
        <div class="row g-4">
            <!-- Sidebar Column - 20% width -->
            <div class="col-12 col-sidebar-20">
                <?php include '../../include/sidebar.php'; ?>
            </div>

            <!-- Main Content Column - 80% width -->
            <div class="col-12 col-main-80">
                <main class="main-content">
                    <h1 class="content-header fw-bold">
                        <?php echo htmlspecialchars($page_title . ' [' . $scholarship_title . ']'); ?>
                    </h1>

                    <div class="action-bar-wrapper">
                        <form action="../students/student_data.php" method="get" class="d-flex gap-2">
                            <input type="hidden" name="type" value="<?php echo $scholarship_id; ?>">
                            <input type="text" name="search_id" class="form-control" style="width: 250px;" placeholder="ใส่รหัสนักศึกษา" value="<?php echo isset($_GET['search_id']) ? htmlspecialchars($_GET['search_id']) : ''; ?>">
                            <button type="submit" class="btn-search-custom">ค้นหา</button>
                        </form>
                        <a href="../students/export_students.php?type=<?php echo $scholarship_id; ?>" class="btn-export-pill">Export</a>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 70px;">ลำดับ</th>
                                    <th style="width: 250px;">ชื่อ-สกุล</th>
                                    <th style="width: 150px;">รหัสนักศึกษา</th>
                                    <th>สาขาวิชา</th>
                                    <th class="text-center" style="width: 150px;">สถานะ</th>
                                    <th class="text-center" style="width: 200px;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($students)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted fw-medium">
                                            <?php
                                            if ($scholarship_id == 0) {
                                                echo 'กรุณาเลือกประเภททุนจากเมนูด้านข้าง';
                                            } else {
                                                echo 'ไม่พบข้อมูลนักศึกษาในระบบ';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($students as $index => $student): ?>
                                        <tr>
                                            <td class="text-center fw-medium"><?php echo $index + 1; ?>.</td>
                                            <td class="fw-medium"><?php echo htmlspecialchars($student['st_firstname'] . ' ' . $student['st_lastname']); ?></td>
                                            <td class="fw-medium"><?php echo htmlspecialchars($student['st_code']); ?></td>
                                            <td class="fw-medium"><?php echo htmlspecialchars($student['g_program'] ?: 'N/A'); ?></td>
                                            <td class="text-center">
                                                <?php if ($student['st_confirm'] == 1): ?>
                                                    <span class="badge-custom badge-success-light"><i class="fa-solid fa-check"></i> อนุมัติแล้ว</span>
                                                <?php else: ?>
                                                    <span class="badge-custom badge-warning-light"><i class="fa-solid fa-clock"></i> รอดำเนินการ</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <!-- ดูรายละเอียด -->
                                                    <a href="../students/view_student_details.php?id=<?php echo $student['st_id']; ?>" class="btn-outline-circle btn-outline-view" title="ดูรายละเอียด"><i class="fas fa-search"></i></a>
                                                    
                                                    <!-- บันทึก PDF -->
                                                    <a href="../students/save_student_pdf.php?id=<?php echo $student['st_id']; ?>" class="btn-outline-circle btn-outline-pdf" title="บันทึกเป็น PDF"><i class="fas fa-file-pdf"></i></a>

                                                    <?php if ($student['st_confirm'] != 1): ?>
                                                        <!-- ยังไม่อนุมัติ: ใช้งานได้ปกติ -->
                                                        <a href="../students/edit_student.php?id=<?php echo $student['st_id']; ?>" class="btn-outline-circle btn-outline-edit" title="แก้ไขข้อมูล"><i class="fas fa-pencil-alt"></i></a>
                                                        <a href="../students/delete_student.php?id=<?php echo $student['st_id']; ?>" class="btn-outline-circle btn-outline-delete btn-delete" title="ลบข้อมูล"><i class="fas fa-trash-alt"></i></a>
                                                        <a href="../students/approve_student.php?id=<?php echo $student['st_id']; ?>" class="btn-outline-circle btn-outline-approve btn-approve" title="กดเพื่ออนุมัติ"><i class="fas fa-check-circle"></i></a>
                                                    <?php else: ?>
                                                        <!-- อนุมัติแล้ว: ปิดการทำงานปุ่มเหล่านี้ -->
                                                        <span class="btn-outline-circle disabled" title="อนุมัติแล้ว ไม่สามารถแก้ไขได้"><i class="fas fa-pencil-alt"></i></span>
                                                        <span class="btn-outline-circle disabled" title="อนุมัติแล้ว ไม่สามารถลบได้"><i class="fas fa-trash-alt"></i></span>
                                                        <span class="btn-outline-circle disabled" title="อนุมัติเรียบร้อยแล้ว"><i class="fas fa-check-circle"></i></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="pt-4 text-center text-muted fw-bold">ทั้งหมด <?php echo count($students); ?> รายการ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <?php include '../../include/footer.php'; ?>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ---- ส่วนจัดการ Sidebar ----
            const sidebar = document.querySelector('.sidebar');
            const menuHeader = document.querySelector('.sidebar .menu-header');
            if (menuHeader && sidebar) {
                menuHeader.addEventListener('click', function() {
                    if (window.innerWidth <= 1024) {
                        sidebar.classList.toggle('is-open');
                    }
                });
            }
            const submenuToggles = document.querySelectorAll('.sidebar .has-submenu > a');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(event) {
                    if (window.innerWidth <= 1024) {
                        event.preventDefault();
                        const parentLi = this.parentElement;
                        parentLi.classList.toggle('submenu-open');
                    }
                });
            });

            // ---- ส่วนจัดการ SweetAlert2 สำหรับปุ่ม "อนุมัติ" และ "ลบ" ----
            const tableBody = document.querySelector('.data-table tbody');
            if (tableBody) {
                tableBody.addEventListener('click', function(event) {
                    const button = event.target.closest('a.btn-outline-circle');
                    if (!button || button.classList.contains('disabled')) return;

                    // ---- จัดการปุ่มลบ ----
                    if (button.classList.contains('btn-delete')) {
                        event.preventDefault();
                        const deleteUrl = button.href;
                        Swal.fire({
                            title: 'ยืนยันการลบ',
                            text: "คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนักศึกษานี้?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#32a838ff',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ยืนยัน',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = deleteUrl;
                            }
                        });
                    }

                    // ---- จัดการปุ่มอนุมัติ ----
                    if (button.classList.contains('btn-approve')) {
                        event.preventDefault();
                        const approveUrl = button.href;

                        Swal.fire({
                            title: 'ยืนยันการอนุมัติ',
                            text: "คุณต้องการอนุมัตินักศึกษาคนนี้ใช่หรือไม่?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'อนุมัติทันที',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = approveUrl;
                            }
                        });
                    }
                });
            }
        });
    </script>
</body>

</html>