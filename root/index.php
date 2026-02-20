<?php
include '../include/config.php';

// ดึงข้อมูลข่าวล่าสุด 3 รายการ
$latest_news = [];
if (isset($connect1)) {
    $sql_news = "SELECT idnews, titlenews, datenews FROM tb_news ORDER BY datenews DESC LIMIT 3";
    $result_news = mysqli_query($connect1, $sql_news);
    if ($result_news) {
        while ($row = mysqli_fetch_assoc($result_news)) {
            $latest_news[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - PSU E-Scholarship</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/bg/head_01.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- เชื่อมต่อไฟล์ CSS -->
    <link rel="stylesheet" href="../assets/css/global2.css">
    <link rel="stylesheet" href="../assets/css/layout.css">
    <link rel="stylesheet" href="../assets/css/components.css">
</head>
<body class="login">

<div class="main-wrapper">

    <!-- 1. ส่วนข่าวประชาสัมพันธ์ -->
    <div class="news-box">
        <div class="news-box-header">
            <span>ข่าวประชาสัมพันธ์</span>
            <a href="all_news.php">ทั้งหมด</a>
        </div>
        <div class="news-box-body">
            <?php if (!empty($latest_news)): ?>
                <?php foreach ($latest_news as $index => $news): ?>
                <a href="news_detail.php?id=<?php echo $news['idnews']; ?>" class="news-item">
                    <span class="icon">+</span>
                    <span><?php echo htmlspecialchars($news['titlenews']); ?></span>
                    <?php if ($index == 0): ?>
                        <span class="new-badge">NEW</span>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="news-item px-3 text-muted">ไม่มีข่าวสารล่าสุด</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 2. กล่อง Login คอนเทนเนอร์หลัก -->
    <div class="login-container">
        
        <!-- ฝั่งซ้าย: รูปประกอบและโซเชียล -->
        <div class="info-panel">
            <img src="../assets/images/bg/update-10.png" alt="Student Illustration" class="illustration">
            <div class="info-footer">
                <img src="../assets/images/bg/update-06.png" alt="Faculty Logo" class="faculty-logo">
                <a href="https://www.facebook.com/pages/งานกิจการนักศึกษา-คณะศิลปศาสตร์-มอ/374074482603129" target="_blank" class="social-button-login">
                    <svg viewBox="0 0 24 24" width="20" height="20">
                        <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/>
                    </svg>
                    <span>ติดตามข่าวสาร</span>
                </a>
            </div>
        </div>

        <!-- ฝั่งขวา: ล็อกอิน PSU Passport -->
        <div class="passport-panel">
            <img src="../assets/images/bg/update-09.png" alt="Scholarship Logo" class="scholarship-logo-login">
            
            <div class="user-icon-login">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>

            <div class="title-divider-login">
                <div class="line"></div>
                <span class="text">PSU PASSPORT</span>
                <div class="line"></div>
            </div>

            <div style="width: 100%;">
                <!-- ปรับเปลี่ยนมาใช้คลาส btn-login-pill เพื่อความสวยงามและแม่นยำ -->
                <a href="login_temp.php" class="btn-login-pill text-decoration-none">เข้าสู่ระบบ</a>
            </div>
        </div>
    </div>

    <!-- 3. ส่วนแจ้งปัญหาด้านล่าง -->
    <div class="issue-reporter-login">
        *แจ้งปัญหาการใช้งาน <a href="https://m.me/374074482603129" target="_blank">ที่นี่</a> หรือโทร.074-286656 (ในวันและเวลาทำการ)
    </div>

</div>

</body>
</html>