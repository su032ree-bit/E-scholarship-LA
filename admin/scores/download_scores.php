<?php
session_start();
include '../../include/config.php';

$scholarship_id = isset($_GET['type']) ? (int)$_GET['type'] : 0;

// 1. ดึงชื่อทุน
$scholarship_title = "คะแนนรวม";
if ($scholarship_id >= 1 && $scholarship_id <= 3) {
    $column_name = "st_name_" . $scholarship_id;
    $sql_title = "SELECT `$column_name` FROM tb_year WHERE y_id = 1";
    $result_title = mysqli_query($connect1, $sql_title);
    if ($result_title && mysqli_num_rows($result_title) > 0) {
        $data_title = mysqli_fetch_row($result_title);
        $scholarship_title = !empty($data_title[0]) ? $data_title[0] : "ทุนประเภทที่ " . $scholarship_id;
    }
}

// 2. ดึงรายชื่อกรรมการทั้งหมดที่มีคะแนนในทุนประเภทนี้ (เพื่อสร้างหัวตาราง)
$teachers = [];
$sql_teachers = "SELECT DISTINCT t.tc_id, t.tc_name 
                 FROM tb_scores s 
                 JOIN tb_teacher t ON s.tc_id = t.tc_id 
                 JOIN tb_student st ON s.st_id = st.st_id 
                 WHERE st.st_type = '$scholarship_id'
                 ORDER BY t.tc_id ASC";
$res_teachers = mysqli_query($connect1, $sql_teachers);
while ($row_t = mysqli_fetch_assoc($res_teachers)) {
    $teachers[$row_t['tc_id']] = $row_t['tc_name'];
}

// 3. ดึงข้อมูลนักศึกษา
$students = [];
$sql_students = "SELECT st_id, st_firstname, st_lastname, st_average 
                 FROM tb_student 
                 WHERE st_type = '$scholarship_id' AND sum_score >= 0 
                 ORDER BY st_average DESC";
$res_students = mysqli_query($connect1, $sql_students);
while ($row_s = mysqli_fetch_assoc($res_students)) {
    $students[] = $row_s;
}

// --- ส่วนการตั้งค่าสำหรับการดาวน์โหลดเป็น Excel ---
$filename = "คะแนน_" . $scholarship_title . "_" . date('Ymd_His') . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
// -------------------------------------------
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        /* สไตล์สำหรับตารางใน Excel */
        .header-title {
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
        }
        .subheader-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            color: #123985;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            background-color: #3b7ddd;
            color: #ffffff;
            border: 0.5pt solid #000000;
            padding: 10px;
            font-weight: bold;
        }
        td {
            border: 0.5pt solid #000000;
            padding: 8px;
            text-align: center;
        }
        .name-cell {
            text-align: left;
        }
        .avg-cell {
            background-color: #e3edf7;
            font-weight: bold;
            color: #0000ff;
        }
        .teacher-header {
            background-color: #f8f9fa;
            color: #333;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td colspan="<?php echo count($teachers) + 3; ?>" class="header-title">รายงานคะแนนผลการคัดเลือกทุนการศึกษา</td>
        </tr>
        <tr>
            <td colspan="<?php echo count($teachers) + 3; ?>" class="subheader-title">ประเภท: <?php echo $scholarship_title; ?></td>
        </tr>
        <tr>
            <td colspan="<?php echo count($teachers) + 3; ?>"></td>
        </tr>
        <thead>
            <tr>
                <th style="width: 50px;">ลำดับ</th>
                <th style="width: 250px;">ชื่อ-นามสกุล</th>
                <th style="width: 120px;">คะแนนเฉลี่ยรวม</th>
                <?php foreach ($teachers as $tc_name): ?>
                    <th style="width: 180px;" class="teacher-header"><?php echo $tc_name; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="<?php echo count($teachers) + 3; ?>">ไม่พบข้อมูลคะแนน</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $index => $st): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td class="name-cell"><?php echo $st['st_firstname'] . " " . $st['st_lastname']; ?></td>
                        <td class="avg-cell"><?php echo number_format($st['st_average'], 2); ?></td>
                        
                        <?php 
                        foreach ($teachers as $tc_id => $tc_name): 
                            $st_id = $st['st_id'];
                            $sql_score = "SELECT scores FROM tb_scores WHERE st_id = '$st_id' AND tc_id = '$tc_id' LIMIT 1";
                            $res_score = mysqli_query($connect1, $sql_score);
                            $score_data = mysqli_fetch_assoc($res_score);
                            $val = ($score_data) ? $score_data['scores'] : "-";
                        ?>
                            <td><?php echo $val; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>