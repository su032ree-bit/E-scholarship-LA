<?php
// 1. เชื่อมต่อฐานข้อมูล
session_start();
include '../../include/config.php';

// 2. ดึงข้อมูลข่าวทั้งหมด โดยเรียงจากวันที่ล่าสุดไปเก่าสุด
$sql = "SELECT idnews, titlenews, datenews FROM tb_news ORDER BY datenews DESC";
$result = mysqli_query($connect1, $sql);

include '../../include/header.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ข่าวประชาสัมพันธ์</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/bg/head_01.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- เรียกไฟล์ CSS ให้เหมือน admin.php ทุกประการ -->
    <link rel="stylesheet" href="../../assets/css/global2.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/navigation.css">
    <link rel="stylesheet" href="../../assets/css/ui-elements.css">
    <link rel="stylesheet" href="../../assets/css/forms.css">
    <link rel="stylesheet" href="../../assets/css/tables.css">
    <link rel="stylesheet" href="../../assets/css/pages.css">
    
    <!-- Google Fonts (Prompt) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* --- เพิ่มเติมเพื่อแก้ปัญหาตามรูปภาพ --- */
        
        /* 1. จัดระเบียบ Body ให้ Footer อยู่ล่างสุดเสมอ */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .dashboard-container {
            flex: 1 0 auto; /* ดันเนื้อหาให้ขยายพื้นที่เพื่อให้ footer อยู่ล่างสุด */
        }

        .site-footer {
            flex-shrink: 0;
        }

        /* 2. แก้ไขลูกศร Main Menu จม สำหรับโหมด Responsive (iPad/Mobile) */
        @media (max-width: 1024px) {
            .sidebar .menu-header {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                line-height: 1 !important;
                height: auto !important;
                padding: 15px 20px !important;
            }
            .sidebar .menu-header::after {
                float: none !important;
                margin-top: 0 !important;
                position: static !important;
            }
        }
    </style>
</head>
<body>

    <div class="sticky-header-wrapper">
        <?php include('../../include/navbar.php'); ?>
        <?php include('../../include/status_bar.php'); ?>
    </div>

    <div class="container-fluid dashboard-container">
        <div class="row g-4">
            <!-- Sidebar Column (20%) -->
            <div class="col-12 col-sidebar-20">
                <?php include '../../include/sidebar.php'; ?>
            </div>

            <!-- Main Content Column (80%) -->
            <div class="col-12 col-main-80">
                <main class="main-content shadow-sm">

                    <?php
                    if (isset($_SESSION['message'])) {
                        $bg_color = $_SESSION['message']['type'] == 'success' ? '#28a745' : '#dc3545';
                        echo '<div id="notification-message" class="alert border-0 shadow-sm" style="background-color:' . $bg_color . '; color: white; transition: opacity 0.5s ease; opacity: 1;">';
                        echo htmlspecialchars($_SESSION['message']['text']);
                        echo '</div>';
                        unset($_SESSION['message']);
                    }
                    ?>

                    <div class="content-header">
                        <h1 class="m-0 fw-bold" style="font-size: 22px; color: #333;">ข่าวประชาสัมพันธ์</h1>
                        <a href="../news/add_news.php" class="btn-primary-pill text-decoration-none">เพิ่มข่าวประชาสัมพันธ์</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 100px;">ลำดับ</th>
                                    <th>หัวข้อ</th>
                                    <th style="width: 200px;">วันที่</th>
                                    <th class="text-center" style="width: 120px;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // 3. วนลูปแสดงผลข้อมูล
                                if (mysqli_num_rows($result) > 0) {
                                    $i = 1; // ตัวแปรสำหรับนับลำดับ
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                        <tr>
                                            <td class="text-center fw-medium"><?php echo $i; ?></td>
                                            <td class="fw-medium"><?php echo htmlspecialchars($row['titlenews']); ?></td>
                                            <td class="fw-medium">
                                                <?php 
                                                // จัดรูปแบบวันที่เป็น วัน/เดือน/ปี(พ.ศ.)
                                                echo date('d/m/', strtotime($row['datenews'])) . (date('Y', strtotime($row['datenews'])) + 543);
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <!-- ปุ่มไอคอนแก้ไขแบบวงกลม Outline -->
                                                <a href="../news/add_news.php?action=edit&id=<?php echo $row['idnews']; ?>" class="btn-outline-circle btn-outline-edit" title="แก้ไข">
                                                    <i class="fas fa-pencil-alt" style="font-size: 14px;"></i>
                                                </a>
                                                <!-- ปุ่มไอคอนลบแบบวงกลม Outline -->
                                                <a href="../news/delete_news.php?id=<?php echo $row['idnews']; ?>" class="btn-outline-circle btn-outline-delete btn-delete" title="ลบ">
                                                    <i class="fas fa-trash-alt" style="font-size: 14px;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                        $i++; // เพิ่มค่าลำดับ
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center py-5 text-muted">ไม่มีข้อมูลข่าวประชาสัมพันธ์ในขณะนี้</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <?php include '../../include/footer.php'; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- ส่วนจัดการ Sidebar ---
        const sidebar = document.querySelector('.sidebar');
        const menuHeader = document.querySelector('.sidebar .menu-header');

        if (menuHeader && sidebar) {
            menuHeader.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    sidebar.classList.toggle('is-open');
                }
            });
        }

        // --- ส่วนจัดการแถบแจ้งเตือน ---
        const notification = document.getElementById('notification-message');
        if (notification) {
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 500);
            }, 2000);
        }

        // --- ส่วนจัดการ SweetAlert สำหรับการลบ ---
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); 
                const deleteUrl = this.href;

                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "ข้อมูลข่าวนี้จะถูกลบอย่างถาวร!",
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