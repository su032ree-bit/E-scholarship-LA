<?php
session_start();
session_destroy(); // ลบทุก Session
header("Location: index.php"); // กลับไปหน้าแรก
exit();
?>