<?php
// คำนวณหาเส้นทางไปสู่โฟลเดอร์หลัก (Root) โดยเช็คจากตำแหน่งของโฟลเดอร์ assets
$to_root = "";
if (file_exists('assets/images/bg/update-09.png')) {
    $to_root = "./";
} elseif (file_exists('../assets/images/bg/update-09.png')) {
    $to_root = "../";
} elseif (file_exists('../../assets/images/bg/update-09.png')) {
    $to_root = "../../";
} else {
    // กรณีที่เรียกจากโฟลเดอร์อื่น ให้ลองถอยกลับไป 1-2 ชั้นเป็นพื้นฐาน
    $to_root = "../"; 
}
?>
<nav class="navbar">
    <!-- ส่วนโลโก้: ปรับเส้นทางโดยใช้ $to_root เพื่อให้เรียกรูปได้จากทุกโฟลเดอร์ -->
    <a href="<?php echo $to_root; ?>admin/system/admin.php" class="navbar-brand p-0">
        <img src="<?php echo $to_root; ?>assets/images/bg/update-09.png" alt="PSU E-Scholarship Logo" class="navbar-logo">
    </a>

    <!-- ส่วนปุ่มติดตามข่าวสาร -->
    <a href="https://www.facebook.com/pages/งานกิจการนักศึกษา-คณะศิลปศาสตร์-มอ/374074482603129" target="_blank" class="social-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/>
        </svg>
        <span>ติดตามข่าวสาร</span>
    </a>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. จัดการย่อ-ขยาย Navbar และ Status Bar เมื่อ Scroll
    const navbar = document.querySelector('.navbar');
    const statusBar = document.querySelector('.user-status-bar');

    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.innerWidth > 767) {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                    if (statusBar) statusBar.classList.add('bar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                    if (statusBar) statusBar.classList.remove('bar-scrolled');
                }
            }
        });
    }

    // 2. จัดการ Sidebar (สลับคลาส is-open และจัดการเมนูย่อยบนมือถือ)
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        const menuHeader = sidebar.querySelector('.menu-header');
        const hasSubmenuLinks = sidebar.querySelectorAll('.has-submenu > a');

        // คลิกที่หัวเมนูเพื่อเปิด/ปิด Sidebar (Mobile)
        if (menuHeader) {
            menuHeader.addEventListener('click', function() {
                sidebar.classList.toggle('is-open');
                // ปิดเมนูย่อยทั้งหมดหาก Sidebar ถูกปิด
                if (!sidebar.classList.contains('is-open')) {
                    sidebar.querySelectorAll('.submenu-open').forEach(li => li.classList.remove('submenu-open'));
                }
            });
        }

        // จัดการคลิกเมนูที่มีเมนูย่อย
        hasSubmenuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 1024) {
                    const listItem = link.closest('li');
                    
                    // ถ้า Sidebar เปิดอยู่ ให้แสดงเมนูย่อยแทนการเปลี่ยนหน้า
                    if (sidebar.classList.contains('is-open')) {
                        e.preventDefault();
                        listItem.classList.toggle('submenu-open');
                        
                        // ปิดเมนูย่อยอื่นๆ ที่เปิดค้างไว้
                        listItem.parentNode.querySelectorAll('.has-submenu').forEach(item => {
                            if (item !== listItem) item.classList.remove('submenu-open');
                        });
                    }
                }
            });
        });
    }

    // 3. จัดการแสดงชื่อไฟล์เมื่อเลือกรูปโปรไฟล์ (ถ้ามี)
    const fileInput = document.getElementById('profile-pic');
    const fileNameDisplay = document.getElementById('file-name-info');
    if(fileInput && fileNameDisplay) {
        fileInput.addEventListener('change', function() {
            fileNameDisplay.textContent = (this.files && this.files.length > 0) ? this.files[0].name : 'No file chosen';
        });
    }
});
</script>