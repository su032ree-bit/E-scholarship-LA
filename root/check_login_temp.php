<?php
session_start();
include '../include/config.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // --- กรณีที่ 1: การล็อกอินของ "นักศึกษา" ---
    if (isset($_POST['st_code'])) {
        $st_code = mysqli_real_escape_string($connect1, $_POST['st_code']);
        $st_pass = mysqli_real_escape_string($connect1, $_POST['st_pass']);

        $sql_st = "SELECT st_code, st_firstname, st_confirm FROM tb_student WHERE st_code = '$st_code' AND st_pass = '$st_pass' LIMIT 1";
        $res_st = mysqli_query($connect1, $sql_st);

        if ($res_st && mysqli_num_rows($res_st) > 0) {
            $row = mysqli_fetch_assoc($res_st);
            
            // ล้าง Session อาจารย์ออก (ป้องกันสิทธิ์ตีกัน)
            unset($_SESSION['id_teacher']);
            unset($_SESSION['tc_name']);

            // บันทึก Session นักศึกษา
            $_SESSION['student_id'] = $row['st_code'];
            $_SESSION['st_name'] = $row['st_firstname'];

            unset($_SESSION['login_error']); // ล้าง error

            // ตรวจสอบสถานะการส่งใบสมัคร
            if ($row['st_confirm'] == 1) {
                header("Location: ../student/confirm_page.php");
            } else {
                header("Location: ../student/apply_form.php");
            }
            exit();
        } else {
            $_SESSION['login_error'] = "รหัสนักศึกษาหรือรหัสผ่านไม่ถูกต้อง";
            header("Location: ../root/login_temp.php");
            exit();
        }
    }

    // --- กรณีที่ 2: การล็อกอินของ "อาจารย์ / กรรมการ" ---
    elseif (isset($_POST['tc_user'])) {
        $tc_user = mysqli_real_escape_string($connect1, $_POST['tc_user']);
        $tc_pass = mysqli_real_escape_string($connect1, $_POST['tc_pass']);

        $sql_tc = "SELECT tc_id, tc_name, tc_type FROM tb_teacher WHERE tc_user = '$tc_user' AND tc_pass = '$tc_pass' LIMIT 1";
        $res_tc = mysqli_query($connect1, $sql_tc);

        if ($res_tc && mysqli_num_rows($res_tc) > 0) {
            $row_tc = mysqli_fetch_assoc($res_tc);

            // ล้าง Session นักศึกษาออก (ป้องกันสิทธิ์ตีกัน)
            unset($_SESSION['student_id']);
            unset($_SESSION['st_name']);

            // บันทึก Session อาจารย์
            $_SESSION['id_teacher'] = $row_tc['tc_id'];
            $_SESSION['tc_name']    = $row_tc['tc_name'];
            $_SESSION['tc_type']    = $row_tc['tc_type'];

            unset($_SESSION['login_error_tc']); // ล้าง error

            // ส่งไปหน้าหลักของอาจารย์
            header("Location: ../admin/advisors/teacher.php");
            exit();
        } else {
            $_SESSION['login_error_tc'] = "ชื่อผู้ใช้งานหรือรหัสผ่านอาจารย์ไม่ถูกต้อง";
            header("Location: ../root/login_temp.php");
            exit();
        }
    }

} else {
    // หากเข้าหน้านี้โดยตรง
    header("Location: login.php");
    exit();
}