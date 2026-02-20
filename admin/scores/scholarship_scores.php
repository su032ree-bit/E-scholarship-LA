<?php
session_start();
include '../../include/config.php';

$page_title = "ข้อมูลคะแนน";
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

// --- 2. ดึงข้อมูลคะแนนนักศึกษาตามประเภททุนที่เลือก ---
$scores = [];
if ($scholarship_id > 0) {
    // ใช้คำสั่ง SQL เพื่อดึงคะแนนและเฉลี่ยจาก tb_student
    $sql_scores = "SELECT st_id, st_firstname, st_lastname, sum_score, st_average, st_confirm
                    FROM tb_student
                    WHERE st_type = '$scholarship_id' AND sum_score >= 0
                    ORDER BY st_average DESC";
    $result_scores = mysqli_query($connect1, $sql_scores);
    if ($result_scores) {
        while ($row = mysqli_fetch_assoc($result_scores)) {
            $scores[] = $row;
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
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/bg/head_01.png">

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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                    <?php
                    if (isset($_SESSION['message'])) {
                        $bg_color = $_SESSION['message']['type'] == 'success' ? '#28a745' : '#dc3545';
                        echo '<div id="notification-message" class="alert border-0 shadow-sm" style="background-color:' . $bg_color . '; color: white; transition: opacity 0.5s ease;">';
                        echo htmlspecialchars($_SESSION['message']['text']);
                        echo '</div>';
                        unset($_SESSION['message']);
                    }
                    ?>

                    <h1 class="content-header fw-bold">
                        <?php echo htmlspecialchars($page_title . ' [' . $scholarship_title . ']'); ?>
                    </h1>

                    <div class="action-bar-wrapper">
                        <form action="../students/student_data.php" method="get" class="d-flex gap-2">
                            <input type="hidden" name="type" value="<?php echo $scholarship_id; ?>">
                            <input type="text" name="search_id" class="form-control" style="width: 250px;" placeholder="ใส่รหัสนักศึกษา" value="<?php echo isset($_GET['search_id']) ? htmlspecialchars($_GET['search_id']) : ''; ?>">
                            <button type="submit" class="btn-search-custom">ค้นหา</button>
                        </form>
                        <a href="../scores/download_scores.php?type=<?php echo $scholarship_id; ?>" class="btn-export-pill">Export</a>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">ลำดับ</th>
                                    <th>ชื่อ-สกุล ผู้ขอรับทุนการศึกษา</th>
                                    <th style="width: 150px;">คะแนนเฉลี่ย</th>
                                    <th class="text-center" style="width: 150px;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($scores)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted fw-medium">
                                            <?php
                                            if ($scholarship_id == 0) {
                                                echo 'กรุณาเลือกประเภททุนจากเมนูด้านข้าง';
                                            } else {
                                                echo 'ไม่พบข้อมูลคะแนนในระบบ';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($scores as $index => $score): ?>
                                        <tr id="score-row-<?php echo $score['st_id']; ?>">
                                            <td class="text-center fw-medium"><?php echo $index + 1; ?>.</td>
                                            <td class="fw-medium"><?php echo htmlspecialchars($score['st_firstname'] . ' ' . $score['st_lastname']); ?></td>
                                            <td class="fw-normal text-black"><?php echo htmlspecialchars(number_format($score['st_average'], 2)); ?></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <!-- ดูรายละเอียดคะแนน - ส่งค่า type ไปด้วยเพื่อให้ย้อนกลับถูกหน้า -->
                                                    <a href="view_score_details.php?id=<?php echo $score['st_id']; ?>&type=<?php echo $scholarship_id; ?>" class="btn-outline-circle btn-outline-view" title="ดูรายละเอียดคะแนน">
                                                        <i class="fas fa-search" style="font-size: 14px;"></i>
                                                    </a>

                                                    <?php if ($score['st_confirm'] == 1): ?>
                                                        <!-- อนุมัติแล้ว: แสดงแม่กุญแจ -->
                                                        <span class="btn-outline-circle btn-outline-lock" title="อนุมัติแล้ว ไม่สามารถลบได้">
                                                            <i class="fas fa-lock" style="font-size: 14px;"></i>
                                                        </span>
                                                    <?php else: ?>
                                                        <!-- ยังไม่อนุมัติ: ลบได้ -->
                                                        <a href="../scores/delete_score.php?id=<?php echo $score['st_id']; ?>&type=<?php echo $scholarship_id; ?>" class="btn-outline-circle btn-outline-delete btn-delete" title="ลบข้อมูลคะแนน">
                                                            <i class="fas fa-trash-alt" style="font-size: 14px;"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="pt-4 text-center text-muted fw-bold">ทั้งหมด <?php echo count($scores); ?> รายการ</td>
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
            if (sidebar) {
                const menuHeader = sidebar.querySelector('.sidebar .menu-header');
                if (menuHeader) {
                    menuHeader.addEventListener('click', function() {
                        if (window.innerWidth <= 1024) {
                            sidebar.classList.toggle('is-open');
                        }
                    });
                }
                const submenuToggles = sidebar.querySelectorAll('.has-submenu > a');
                submenuToggles.forEach(toggle => {
                    toggle.addEventListener('click', function(event) {
                        if (window.innerWidth <= 1024) {
                            event.preventDefault();
                            const parentLi = this.parentElement;
                            parentLi.classList.toggle('submenu-open');
                        }
                    });
                });
            }

            // ---- ส่วนจัดการแถบแจ้งเตือน ----
            const notification = document.getElementById('notification-message');
            if (notification) {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 500);
                }, 5000);
            }

            // ---- ส่วนจัดการ SweetAlert2 สำหรับปุ่มลบ ----
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const deleteUrl = this.href;
                    Swal.fire({
                        title: 'ยืนยันการลบ',
                        text: "คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลคะแนนทั้งหมดของนักศึกษาคนนี้?",
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
                });
            });
        });
    </script>
</body>

</html>