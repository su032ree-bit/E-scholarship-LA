<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
include '../include/config.php';

// --- 1. รับค่ารหัสนักศึกษาจาก URL ---
$get_student_id = isset($_GET['student_id']) ? mysqli_real_escape_string($connect1, $_GET['student_id']) : '';

// --- 2. ดึงข้อมูลจากฐานข้อมูลแบบเชื่อมตาราง (Join) ---
$sql = "SELECT s.*, p.g_program, t.tc_name, y.y_year, y.st_name_1, y.st_name_2, y.st_name_3 
        FROM tb_student s
        LEFT JOIN tb_program p ON s.st_program = p.g_id
        LEFT JOIN tb_teacher t ON s.id_teacher = t.tc_id
        LEFT JOIN tb_year y ON y.y_id = 1
        WHERE s.st_id = '$get_student_id' OR s.st_code = '$get_student_id' LIMIT 1";

$result = mysqli_query($connect1, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("<script>alert('ไม่พบข้อมูลนักศึกษาในระบบ'); window.close();</script>");
}

// --- 3. จัดการข้อมูลให้เข้ากับรูปแบบเดิม ---
// ตรวจสอบชื่อทุนตามประเภทที่สมัคร (st_type)
$scholar_type = "";
if ($row['st_type'] == 1) $scholar_type = $row['st_name_1'];
elseif ($row['st_type'] == 2) $scholar_type = $row['st_name_2'];
elseif ($row['st_type'] == 3) $scholar_type = $row['st_name_3'];

$data = [
    'student_id'    => $row['st_code'],
    'prefix'        => ($row['st_sex'] == 1) ? 'นาย' : 'นางสาว',
    'firstname'     => $row['st_firstname'],
    'lastname'      => $row['st_lastname'],
    'major'         => $row['g_program'] ?? 'ไม่ได้ระบุสาขา',
    'gpa'           => $row['st_score'],
    'advisor_name'  => $row['tc_name'] ?? 'ไม่ได้ระบุอาจารย์ที่ปรึกษา',
    'scholar_type'  => $scholar_type,
    'year'          => $row['y_year'] ?? (date("Y") + 543),
    'reason'        => $row['st_note'],
    'image_url'     => (!empty($row['st_image'])) ? '../images/student/' . $row['st_image'] : ''
];

$full_name = $data['prefix'] . $data['firstname'] . ' ' . $data['lastname'];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>พิมพ์หนังสือรับรองทุน - <?php echo $data['student_id']; ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/bg/head_01.png">

    <!-- เปลี่ยนจาก Prompt เป็น Sarabun -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS หลักของระบบ (ถ้ามีผลกระทบกับความสวยงามของการพิมพ์ แนะนำให้สร้าง style เฉพาะในหน้านี้) -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/global.css">

    <style>
        @media print {
            body {
                background: none !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 2.5cm;
                /* ระยะขอบกระดาษมาตรฐาน */
            }
        }

        html,
        body {
            font-family: 'Sarabun', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #525659;
        }

        .content-body {
            font-size: 16pt;
            /* ขนาดตัวอักษรมาตรฐานเอกสารไทย */
            margin-top: 30px;
            text-align: justify;
            text-justify: inter-character;
        }

        /* --- การตั้งค่าหน้ากระดาษ A4 --- */
        @page {
            size: A4;
            margin: 0;
        }

        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding: 1.5cm 2cm 2cm 2cm;
            /* ปรับ Padding ให้สมดุล */
            margin: 100px auto;
            background: white;
            position: relative;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            color: #000;
            overflow: hidden;
            /* ป้องกันเนื้อหาแลบออกขอบ */
        }

        /* --- หัวกระดาษ --- */
        .header-section {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
            /* ระยะห่างจากเส้นคั่นลงมาหาเนื้อหา */
        }

        .logo {
            width: 65px;
            height: auto;
        }

        .header-text {
            padding-left: 15px;
        }

        .header-text h2 {
            margin: 0;
            font-size: 18pt;
            font-weight: 700;
            line-height: 1.1;
        }

        .header-text p {
            margin: 3px 0 0 0;
            font-size: 11pt;
            color: #333;
        }

        /* --- จัดระเบียบรูปถ่าย (แก้ไขตำแหน่งที่เกิน) --- */
        .photo-frame {
            position: absolute;
            top: 5cm;
            /* ขยับลงมาให้พ้นเส้นหัวกระดาษพอดี */
            right: 2cm;
            /* ชิดขอบขวาตาม Padding ของหน้า */
            width: 3cm;
            /* ขนาดมาตรฐาน 1 นิ้วครึ่ง */
            height: 4cm;
            border: 1px solid #000;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            font-size: 9pt;
            color: #666;
            text-align: center;
            line-height: 1.4;
        }

        /* --- ส่วนหัวข้อเรื่อง (หลบรูปถ่าย) --- */
        .title-block {
            width: 65%;
            /* จำกัดความกว้างเพื่อไม่ให้ข้อความไปทับรูปทางขวา */
            margin-bottom: 45px;
            margin-top: 60px;
            text-align: center;
        }

        .title-block h3 {
            font-size: 15pt;
            font-weight: 700;
            margin: 0 0 5px 0;
            line-height: 1.3;
        }

        .scholar-label {
            font-size: 14pt;
            font-weight: 600;
        }

        .underline-bold {
            text-decoration: underline;
            font-weight: 700;
        }

        /* --- เนื้อหาหลัก --- */
        .content-area {
            font-size: 14pt;
            line-height: 2.8;
            text-align: justify;
            clear: both;
            /* เคลียร์ float ถ้ามี */
        }

        .indent {
            margin-left: 60px;
        }

        .val-dotted {
            display: inline-block;
            border-bottom: 1px dotted #000;
            padding: 0 8px;
            font-weight: 700;
            text-align: center;
            min-width: 60px;
            line-height: 1.2;
        }

        /* --- ส่วนเหตุผล (จัดกรอบให้สวยงาม) --- */
        .reason-section {
            margin-top: 35px;
        }

        .reason-header {
            font-weight: 700;
            display: block;
            text-align: center;
            margin-bottom: 12px;
            text-decoration: underline;
            font-size: 14pt;
        }

        .reason-content {
            display: flex;
            /* จัดเรียงข้อความและเส้นให้อยู่แนวนอนเดียวกัน */
            align-items: baseline;
            /* ให้ฐานของเส้นตรงกับฐานของตัวอักษร */
            padding: 20px;
            min-height: 150px;
            font-size: 13pt;
            line-height: 1.8;
            text-indent: 50px;
            border-radius: 5px;
        }

        /* --- ส่วนลงนาม --- */
        .signature-section {
            margin-top: 70px;
            display: flex;
            justify-content: space-around;
        }

        .sig-box {
            text-align: center;
            width: 40%;
        }

        .sig-line {
            flex-grow: 1;
            /* ให้เส้นประยืดออกไปจนเต็มพื้นที่ที่เหลือ */
            border-bottom: 1px dotted #000;
            /* กำหนดลักษณะเส้นประ */
            margin-left: 10px;
            /* ระยะห่างระหว่างข้อความกับเส้น */
            height: 1px;
            /* ความสูงของตัวเส้น */
        }

        /* --- ตั้งค่าสำหรับการพิมพ์ --- */
        @media print {
            body {
                background: none;
            }

            .page {
                margin: 0;
                box-shadow: none;
                border: none;
            }

            .no-print {
                display: none;
            }
        }

        .btn-print {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 25px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 50px;
            font-family: 'Sarabun';
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            z-index: 100;
        }
    </style>
</head>

<body>

    <button class="btn-print no-print" onclick="window.print()">
        <i class="fa fa-print"></i> พิมพ์เอกสารหนังสือรับรอง
    </button>

    <div class="page">
        <!-- Header -->
        <div class="header-section">
            <img src="../assets/images/bg/head_01.png" class="logo" alt="Logo">
            <div class="header-text">
                <h2>คณะศิลปศาสตร์ มหาวิทยาลัยสงขลานครินทร์</h2>
                <p>Faculty of Liberal Arts, Prince of Songkla University, Hat Yai, Songkhla 90110, Thailand</p>
            </div>
        </div>

        <!-- กรอบรูปถ่าย (ล็อกตำแหน่งขวาบน ไม่ให้เกินขอบ) -->
        <div class="photo-frame">
            <?php if (!empty($data['image_url'])): ?>
                <img src="<?php echo $data['image_url']; ?>" alt="Student Photo">
            <?php else: ?>
                <div class="photo-placeholder">รูปถ่ายนักศึกษา<br>1 นิ้ว</div>
            <?php endif; ?>
        </div>

        <!-- Title Section -->
        <div class="title-block">
            <h3>หนังสือรับรองการขอรับทุนการศึกษาของคณะศิลปศาสตร์</h3>
            <div class="scholar-label">ประเภท <span class="underline-bold"><?php echo $data['scholar_type']; ?></span></div>
            <div style="font-weight: 600; margin-top: 5px;">ปีการศึกษา <?php echo $data['year']; ?></div>
        </div>

        <!-- Main Content -->
        <div class="content-area">
            <span class="indent">ข้าพเจ้า</span>
            <span class="val-dotted" style="min-width: 225px;"><?php echo $data['advisor_name']; ?></span>
            อาจารย์ที่ปรึกษาผู้สมัครขอรับทุนการศึกษา ขอรับรองว่า
            <span class="val-dotted" style="min-width: 205px;"><?php echo $full_name; ?></span>
            รหัสนักศึกษา <span class="val-dotted" style="min-width: 145px;"><?php echo $data['student_id']; ?></span>
            สาขาวิชา <span class="val-dotted" style="min-width: 200px;"><?php echo $data['major']; ?></span>
            เป็นผู้ที่มีความประพฤติดี ขาดแคลนทุนทรัพย์ ตามข้อมูลที่ได้แสดงไว้ในใบสมัครทุกประการ และเป็นบุคคลที่สมควรได้รับทุนการศึกษาในครั้งนี้
        </div>

        <!-- Reason Area -->
        <div class="reason-section">
            <span class="reason-header">เหตุผลการขอรับทุน</span>
            <div class="reason-content"><?php echo nl2br(htmlspecialchars($data['reason'])); ?><div class="sig-line"></div>
            </div>
        </div>

        <!-- Signatures Area -->
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-line"></div>
                <strong>(ลงนามผู้สมัคร)</strong>
            </div>
            <div class="sig-box">
                <div class="sig-line"></div>
                <strong>(ลงนามอาจารย์ที่ปรึกษา)</strong>
            </div>
        </div>

    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>