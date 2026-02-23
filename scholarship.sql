-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2026 at 03:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scholarship`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_activity`
--

CREATE TABLE `tb_activity` (
  `act_id` int(10) NOT NULL,
  `act_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_student` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_activity`
--

INSERT INTO `tb_activity` (`act_id`, `act_name`, `id_student`) VALUES
(1, '', 2),
(2, '', 2),
(3, '', 2),
(4, '', 3),
(5, '', 3),
(6, '', 3),
(7, '', 3),
(8, '', 3),
(9, '', 3),
(10, '', 5),
(11, '', 5),
(12, '', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `ad_id` int(10) NOT NULL,
  `ad_user` varchar(100) NOT NULL,
  `ad_pass` varchar(100) NOT NULL,
  `ad_name` varchar(255) NOT NULL,
  `ad_tel` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`ad_id`, `ad_user`, `ad_pass`, `ad_name`, `ad_tel`) VALUES
(1, 'admin', '1234', 'งานพัฒนานักศึกษาและศิษย์เก่าสัมพันธ์', '074286655');

-- --------------------------------------------------------

--
-- Table structure for table `tb_ban`
--

CREATE TABLE `tb_ban` (
  `id_ban` int(10) NOT NULL,
  `code_student` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `date_ban` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_ban`
--

INSERT INTO `tb_ban` (`id_ban`, `code_student`, `date_start`, `date_end`, `date_ban`) VALUES
(226, '6511110058', '2023-06-12', '2025-10-06', '2023-06-12'),
(227, '6511110005', '2023-06-12', '2025-10-06', '2023-06-12'),
(230, '6411110156', '2023-06-12', '2025-10-06', '2023-06-12'),
(231, '6411110133', '2023-06-12', '2025-10-06', '2023-06-12'),
(232, '6411110113', '2023-06-12', '2025-10-06', '2023-06-12'),
(265, '6511110047', '2024-06-27', '2025-10-31', '2024-06-27'),
(268, '6411110275', '2024-10-24', '2025-10-31', '2024-10-24'),
(269, '6411110358', '2024-10-24', '2025-10-31', '2024-10-24'),
(254, '6411110271', '2023-10-06', '2025-10-06', '2023-10-06'),
(241, '6511110027', '2023-06-12', '2025-10-06', '2023-06-12'),
(242, '6411110354', '2023-06-12', '2025-10-06', '2023-06-12'),
(267, '6411110072', '2024-10-24', '2025-10-31', '2024-10-24'),
(263, '6511110200', '2024-06-27', '2025-10-31', '2024-06-27'),
(246, '6411110083', '2023-06-12', '2025-10-06', '2023-06-12'),
(253, '6411110233', '2023-10-06', '2025-10-06', '2023-10-06'),
(258, '6511110249', '2023-10-06', '2025-10-06', '2023-10-06'),
(260, '6511110119', '2023-10-06', '2025-10-06', '2023-10-06'),
(270, '6511110206', '2024-10-24', '2025-10-31', '2024-10-24'),
(271, '6611110330', '2024-10-24', '2025-10-31', '2024-10-24');

-- --------------------------------------------------------

--
-- Table structure for table `tb_bursary`
--

CREATE TABLE `tb_bursary` (
  `bur_id` int(10) NOT NULL,
  `bur_year` int(5) NOT NULL,
  `bur_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `bur_quantity` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_student` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_bursary`
--

INSERT INTO `tb_bursary` (`bur_id`, `bur_year`, `bur_name`, `bur_quantity`, `id_student`) VALUES
(1, 2567, 'ผลการเรียนดีและะขาดแคลนทุนทรัพย์', '2000', 2),
(5, 2566, 'ทุนทำงานแลกเปลี่ยน', '5000', 3),
(4, 2565, 'ทุนทำงานแลกเปลี่ยน', '5000', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tb_files`
--

CREATE TABLE `tb_files` (
  `idfile` int(10) NOT NULL,
  `namefile` varchar(250) NOT NULL,
  `filenab` varchar(200) NOT NULL,
  `idnews` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_files`
--

INSERT INTO `tb_files` (`idfile`, `namefile`, `filenab`, `idnews`) VALUES
(36, 'เอกสารประกาศ', '1770260583_แบบฟอร์มทำบัตรประจำตัวเจ้าหน้าที่ของรัฐ.pdf', 362),
(37, 'เอกสารประกาศ', '1770775196_แบบฟอร์มทำบัตรประจำตัวเจ้าหน้าที่ของรัฐ.pdf', 363);

-- --------------------------------------------------------

--
-- Table structure for table `tb_issue`
--

CREATE TABLE `tb_issue` (
  `issue_id` int(11) NOT NULL,
  `issue_topic` varchar(255) NOT NULL,
  `issue_details` text NOT NULL,
  `student_id` int(10) NOT NULL,
  `issue_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_issue`
--

INSERT INTO `tb_issue` (`issue_id`, `issue_topic`, `issue_details`, `student_id`, `issue_date`) VALUES
(2, 'อัปโหลดไฟล์ภาพประจำตัวไม่ได้', 'ตอนสมัครทุน พยายามอัปโหลดรูปนักศึกษา (.jpg) แต่ระบบไม่ตอบสนอง กดปุ่ม Choose File แล้วเลือกรูป แต่ชื่อไฟล์ไม่ขึ้น และไม่สามารถกดส่งใบสมัครได้ค่ะ', 3, '2025-12-18 14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_member`
--

CREATE TABLE `tb_member` (
  `id_mem` int(10) NOT NULL,
  `no_mem` int(2) NOT NULL,
  `name_mem` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sur_mem` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `programe` int(10) NOT NULL,
  `class_mem` int(2) NOT NULL,
  `tea_mem` int(10) NOT NULL,
  `address_mem` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tel_mem` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email_mem` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `score_mem` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `com_mem` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `eng_mem` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `doc_mem` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date_mem` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_mem_date`
--

CREATE TABLE `tb_mem_date` (
  `id_date` int(10) NOT NULL,
  `date_date` int(3) NOT NULL,
  `date_time` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_mem` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_news`
--

CREATE TABLE `tb_news` (
  `idnews` int(10) NOT NULL,
  `titlenews` varchar(250) NOT NULL,
  `detailnews` text NOT NULL,
  `datenews` datetime NOT NULL,
  `typenews` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_news`
--

INSERT INTO `tb_news` (`idnews`, `titlenews`, `detailnews`, `datenews`, `typenews`) VALUES
(356, 'เพิ่มเทสๆ', '111111111', '2026-01-22 09:04:33', 1),
(363, 'ข่าวใหม่', 'ทดสอบ', '2026-02-11 08:59:56', 1),
(362, 'รับสมัครทุน', 'กรุณาแนบเอกสารตามรายละเอียดที่แนบ', '2026-02-05 10:03:03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_parent`
--

CREATE TABLE `tb_parent` (
  `parent_id` int(10) NOT NULL,
  `parent_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent_age` int(3) NOT NULL,
  `parent_status` int(2) NOT NULL,
  `parent_career` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent_revenue` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent_workplace` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent_tel` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_student` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_parent`
--

INSERT INTO `tb_parent` (`parent_id`, `parent_name`, `parent_age`, `parent_status`, `parent_career`, `parent_revenue`, `parent_workplace`, `parent_tel`, `id_student`) VALUES
(1, 'นายสมชาย จันทร์เเดง', 48, 1, 'ขับรถJCB', '5000', 'บริษัท เอสเอ็นปิโตเลียมจำกัด', '0824320497', 2),
(2, 'นางสาวมะลิสา จันทร์เเดง', 42, 1, '-', '-', '-', '0628355694', 2),
(3, 'นางสมศรี จันทร์แดง', 68, 0, '-', '-', '0843124513', '', 2),
(4, 'MOHDReduan Bin MOHDArof', 76, 1, 'ข้าราชการบำนาญ', '20000', 'ประเทศมาเลเซีย', '-', 3),
(5, 'ประภาส ม่วงแสง', 56, 1, 'แม่บ้าน', '0', 'บ้าน', '0989046526', 3),
(6, '', 0, 0, '', '', '', '', 3),
(7, 'นายเกรียงศักดิ์ อึังพงศ์ภัค', 57, 1, 'เกษตรกรรับจ้างเลี้ยงกุ้ง', '-', '-', '0869637475', 5),
(8, 'นางสาวรวิวรรณ ไชยกุล', 50, 1, 'พนักงานโรงเเรม', '25,000-30,000', 'โรงเเรมเรดเเพลนเนต หาดใหญ่', '0818971779', 5),
(9, 'นายเกรียงศักดิ์ อึังพงศ์ภัค', 57, 0, 'เกษตรกรรับจ้างเลี้ยงกุ้ง', '-', '-', '0869637475', 5),
(33, 'นูรี', 30, 1, 'เซล', '5000', 'ห้าง', '0817357224', 24),
(31, 'แบดิง', 60, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0650521112', 24),
(32, 'อายีย๊ะ', 52, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0612542221', 24),
(34, 'แบดิง', 60, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0650521112', 12),
(35, 'อายีย๊ะ', 52, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0612542221', 12),
(36, 'นูรี', 30, 1, 'เซล', '10000', 'ห้าง', '0817357224', 12),
(45, 'นูรี', 30, 1, 'เซล', '12000', 'ห้าง', '0817357224', 30),
(44, 'อายีย๊ะ', 52, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0612542221', 30),
(43, 'แบดิง', 60, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0650521112', 30),
(123, 'นูรี', 30, 1, 'เซล', '300', 'ห้าง', '0817357224', 35),
(121, 'k', 60, 1, '??????', '200', 'หน้าซอย', '0650521112', 35),
(122, 'อายีย๊ะ', 0, 0, 'ค้าขาย', '100', 'หน้าซอย', '0612542221', 35),
(116, 'อายีย๊ะ', 52, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0612542221', 33),
(115, 'แบดิง', 60, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0650521112', 33),
(117, 'นูรี', 30, 1, 'เซล', '20000', 'ห้าง', '0817357224', 33),
(118, 'แบดิง', 60, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0650521112', 34),
(119, 'อายีย๊ะ', 52, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0612542221', 34),
(120, 'นูรี', 30, 1, 'เซล', '20000', 'ห้าง', '0817357224', 34),
(128, 'อายีย๊ะ อาแว', 52, 1, 'ค้าขาย', '5000', 'หน้าซอย', '0612542221', 36),
(127, '', 0, 0, '-', '-', '-', '-', 36),
(129, 'นูรี ยาการียา', 30, 1, 'เซล', '20000', 'ห้าง', '0817357224', 36),
(162, 'นูรี ยาการียา', 31, 1, 'เซล', '10000', 'ห้าง', '0817357225', 43),
(161, 'อายีย๊ะ อาแว', 52, 1, 'ค้าขาย', '1000', 'หน้าซอย', '0612542221', 43),
(160, 'แบดิง ยาการียา', 60, 1, 'ค้าขาย', '1000', 'หน้าซอย', '0650521112', 43);

-- --------------------------------------------------------

--
-- Table structure for table `tb_program`
--

CREATE TABLE `tb_program` (
  `g_id` int(10) NOT NULL,
  `g_program` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_program`
--

INSERT INTO `tb_program` (`g_id`, `g_program`) VALUES
(8, 'สาขาวิชาชุมชนศึกษาเพื่อการพัฒนาที่ยั่งยืน'),
(2, 'สาขาวิชาภาษาอังกฤษ'),
(5, 'สาขาวิชาภาษาจีน'),
(6, 'สาขาวิชาภาษาไทยประยุกต์'),
(7, 'สาขาวิชาการจัดการอุตสาหกรรมการบินและการบริการ');

-- --------------------------------------------------------

--
-- Table structure for table `tb_relatives`
--

CREATE TABLE `tb_relatives` (
  `re_id` int(10) NOT NULL,
  `re_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ra_edu` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ra_workplace` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ra_revenue` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_student` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_relatives`
--

INSERT INTO `tb_relatives` (`re_id`, `re_name`, `ra_edu`, `ra_workplace`, `ra_revenue`, `id_student`) VALUES
(1, 'เด็กชายสิรภัทร จันทร์เเดง', 'โรงเรียนปากพะยูนพิทยาคาร', '', '-', 2),
(2, 'นายบูรพา อึังพงศ์ภัค', 'จุฬาลงกรณ์มหาวิทยาลัย', '-', '-', 5),
(3, 'นายบริราช อึ้งพงศ์ภัค', 'จุฬาลงกรณ์มหาวิทยาลัย', '-', '-', 5),
(13, 'ก', '-', 'ห้าง', '10000', 24),
(12, 'น', '-', 'ห้าง', '5000', 24),
(15, 'น', '-', 'ห้าง', '10000', 30),
(47, 'นูรี', '-', 'ห้าง', '300', 35),
(46, 'นูรี', '-', 'ห้าง', '300', 35),
(44, 'นูรี', '-', 'ห้าง', '20000', 33),
(45, 'นูรี', '-', 'ห้าง', '20000', 34),
(48, 'นูรี ยากรียา', '-', 'ห้าง', '10000', 36),
(59, 'นูรี ยากรียา', '-', 'ห้าง', '10000', 43);

-- --------------------------------------------------------

--
-- Table structure for table `tb_scores`
--

CREATE TABLE `tb_scores` (
  `sco_id` int(10) NOT NULL,
  `tc_id` int(10) NOT NULL,
  `scores` float NOT NULL,
  `sco_comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sco_date` datetime NOT NULL,
  `sco_status` int(2) NOT NULL,
  `st_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_scores`
--

INSERT INTO `tb_scores` (`sco_id`, `tc_id`, `scores`, `sco_comment`, `sco_date`, `sco_status`, `st_id`) VALUES
(17, 73, 80, '', '2026-02-20 09:50:10', 1, 30),
(14, 73, 80, '', '2026-01-30 14:29:29', 1, 33),
(13, 73, 0, '', '2026-01-29 10:45:39', 1, 34),
(9, 73, 60, '', '2026-01-19 16:21:09', 1, 24),
(10, 73, 2, '', '2026-01-19 10:28:17', 1, 4),
(6, 72, 3.5, '', '2025-12-17 07:17:38', 1, 4),
(7, 73, 80, '', '2025-12-17 14:11:38', 1, 12),
(8, 72, 2.5, '', '2025-12-17 08:12:06', 1, 3),
(4, 73, 85, 'ผลการเรียนดีมาก', '2025-12-11 11:00:00', 1, 3),
(5, 99, 91, 'มีความสามารถพิเศษด้านภาษา\nควรได้รับการสนับสนุน', '2025-12-11 11:05:00', 1, 3),
(15, 72, 60, '', '2026-01-30 15:02:27', 1, 24),
(16, 73, 0, '', '2026-02-19 14:14:04', 1, 35),
(18, 72, 50, '', '2026-02-20 09:52:03', 1, 30),
(19, 124, 100, '', '2026-02-20 09:52:32', 1, 30),
(20, 124, 100, 'เริ่ดเลยล่ะ', '2026-02-23 08:40:02', 1, 24);

-- --------------------------------------------------------

--
-- Table structure for table `tb_student`
--

CREATE TABLE `tb_student` (
  `st_id` int(10) NOT NULL,
  `st_sex` int(2) NOT NULL,
  `st_firstname` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_lastname` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_score` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_code` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_program` int(10) NOT NULL,
  `st_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_image` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_pass` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_birthday` date NOT NULL,
  `st_age` int(2) NOT NULL,
  `st_address1` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_address2` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_tel1` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_tel2` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_family_status` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_borrow_money` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_received` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_job` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_current_job` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_peripeteia` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_solutions` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_history_bursary` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_note` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_doc` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_doc1` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_doc2` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_teacher` int(10) NOT NULL,
  `st_date_regis` datetime NOT NULL,
  `st_date_send` datetime NOT NULL,
  `st_activate` int(2) NOT NULL,
  `st_confirm` int(2) NOT NULL,
  `md5_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sum_score` int(10) NOT NULL,
  `st_average` float(7,2) NOT NULL,
  `st_type` int(2) NOT NULL,
  `st_father` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_mother` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_guardian` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_siblings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_history_detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_student`
--

INSERT INTO `tb_student` (`st_id`, `st_sex`, `st_firstname`, `st_lastname`, `st_score`, `st_code`, `st_program`, `st_email`, `st_image`, `st_pass`, `st_birthday`, `st_age`, `st_address1`, `st_address2`, `st_tel1`, `st_tel2`, `st_family_status`, `st_borrow_money`, `st_received`, `st_job`, `st_current_job`, `st_peripeteia`, `st_solutions`, `st_history_bursary`, `st_note`, `st_doc`, `st_doc1`, `st_doc2`, `id_teacher`, `st_date_regis`, `st_date_send`, `st_activate`, `st_confirm`, `md5_code`, `sum_score`, `st_average`, `st_type`, `st_father`, `st_mother`, `st_guardian`, `st_siblings`, `st_history_detail`) VALUES
(24, 1, 'อับดุลเลาะห์', 'ดอเล๊าะ', '3.56', '106765025', 6, 'abdullah.m@psu.ac.th', '1767775358_695e1c7ed7898.jpg', '1234', '2026-01-08', 22, '12 สิโรรส 2 ', '91 มุสลิมบำรุง', '0635851106', 'abdullah.m@psu.ac.th', '|-o-||-o-|3|-o-||-o-|', '1|-o-||-o-|36000', '1|-o-||-o-||-o-|4|-o-||-o-|4000', '|-o-|2|-o-||-o-|', '|-o-|2|-o-|ติดเรียน', '1|-o-||-o-|ค้าขายไม่ดี', '|-o-||-o-|3|-o-||-o-||-o-|', '2', 'เนื่องจากในปัจจุบันครอบครัวของข้าพเจ้าประสบปัญหาด้านสภาวะเศรษฐกิจ (ระบุเหตุผลเพิ่มเติม เช่น รายได้ไม่แน่นอน หรือมีภาระค่าใช้จ่ายสูง) ทำให้รายได้หลักของครอบครัวไม่เพียงพอต่อการส่งเสียค่าธรรมเนียมการศึกษาและค่าใช้จ่ายที่เกี่ยวเนื่องกับการเรียนได้อย่างเต็มที่ แม้ข้าพเจ้าจะมีความพยายามในการช่วยแบ่งเบาภาระด้วยการ (ระบุสิ่งที่ทำ เช่น ทำงานพิเศษ หรือประหยัดค่าใช้จ่าย) แต่ยังคงมีความกังวลว่าปัจจัยด้านทุนทรัพย์อาจกลายเป็นอุปสรรคต่อการศึกษาในระยะยาว\r\n\r\nข้าพเจ้าจึงมีความประสงค์ขอรับทุนการศึกษาในครั้งนี้ เพื่อนำไปใช้เป็นแรงขับเคลื่อนในการเรียนโดยไม่ต้องพะวงถึงภาระค่าใช้จ่ายของทางบ้าน ข้าพเจ้าขอให้คำมั่นสัญญาว่าจะตั้งใจศึกษาเล่าเรียนอย่างเต็มกำลังความสามารถ รักษาผลการเรียนให้อยู่ในเกณฑ์ดี และใช้ทุนการศึกษาที่ได้รับให้เกิดประโยชน์สูงสุด เพื่อที่ในอนาคตข้าพเจ้าจะได้นำความรู้ความสามารถที่ได้เล่าเรียนมาไปประกอบอาชีพที่มั่นคงและกลับมาสร้างประโยชน์ให้กับสังคมต่อไป', '1767843496_card.pdf', '1768790307_9021.png', '1768790307_7016.jpeg', 123, '2026-01-07 15:42:38', '2026-01-19 09:54:35', 1, 1, '', 220, 73.33, 1, 'แบดิง|-o-|60|-o-|1|-o-|ค้าขาย|-o-|5000|-o-|หน้าซอย|-o-|0650521112', 'อายีย๊ะ|-o-|52|-o-|1|-o-|ค้าขาย|-o-|5000|-o-|หน้าซอย|-o-|0612542221', 'นูรี|-o-|30|-o-|1|-o-|เซล|-o-|12000|-o-|ห้าง|-o-|0817357224', 'น:-:ห้าง:5000|-o-|ก:-:ห้าง:10000', ''),
(30, 2, 'ฮาวานี', 'หะยีมะสะแม', '4.00', '106765033', 2, 'ateez81seonghwa@gmail.com', '1768880323_696ef8c3d0a51.jpg', '1234', '1998-02-10', 25, 'บนดิน ใต้ฟ้า ใต้หลังคา', 'บ้าน 30 ชั้น รวยปะละ บ้านใครไม่รู้ แต่ว่า', '0223568790', 'ateez81seonghwa@gmail.com', '1|-o-||-o-||-o-||-o-|', '1|-o-||-o-|30000', '1|-o-||-o-||-o-|4|-o-||-o-|4000', '|-o-|2|-o-||-o-|', '1|-o-||-o-|หาเงิน', '1|-o-||-o-|ค้าขายไม่ดี', '|-o-||-o-|3|-o-||-o-||-o-|', '2', 'จำเป็นมาก', '1768881646_1198.png', '1768881646_9789.pdf', '1768881646_8328.jpeg', 126, '2026-01-20 10:38:43', '2026-01-20 11:12:06', 1, 0, '', 230, 76.67, 1, '|-o-|-|-o-||-o-|-|-o-|-|-o-|-|-o-|-', '|-o-|-|-o-||-o-|-|-o-|-|-o-|-|-o-|-', '|-o-|-|-o-|1|-o-|-|-o-|-|-o-|-|-o-|-', '', ''),
(43, 2, 'ฟิตเราะห์', 'โต๊ะบาโก', '3.89', '5511112546', 7, '5511112546@psu.ac.th', '1771561407_6997e1bf4f85e.jpg', '1234', '2026-02-01', 22, 'บนดิน ใต้ฟ้า ใต้หลังคา', '91 มุสลิมบำรุง', '0223568790', '5511112546@psu.ac.th', '1|-o-||-o-||-o-||-o-|', '1|-o-||-o-|3000000', '1|-o-||-o-||-o-|4|-o-||-o-|4000', '|-o-|2|-o-||-o-|', '|-o-|2|-o-|-', '|-o-|2|-o-|', '|-o-||-o-|3|-o-|', '2', 'มีความยากลำบากในการใช้ชีวิตด้วยจำนวนเงินอันน้อยนิดที่มีกินไปแต่ละวัน', '1771571521_8985.pdf', '1771571521_9394.pdf', '1771571521_2137.pdf', 120, '2026-02-20 11:23:27', '2026-02-20 14:20:43', 1, 0, '', 0, 0.00, 3, 'แบดิง ยาการียา|-o-|60|-o-|1|-o-|ค้าขาย|-o-|1000|-o-|หน้าซอย|-o-|0650521112', 'อายีย๊ะ อาแว|-o-|52|-o-|1|-o-|ค้าขาย|-o-|1000|-o-|หน้าซอย|-o-|0612542221', 'นูรี ยาการียา|-o-|31|-o-|1|-o-|เซล|-o-|10000|-o-|ห้าง|-o-|0817357225', 'นูรี ยากรียา:-:ห้าง:10000', ''),
(35, 2, 'ฮาวานี', 'นะจ้ะ', '3.86', '6711112547', 7, '6711112546@psu.ac.th', '1770001029_6980128584a02.jpg', '1234', '2002-09-06', 28, '12 สิโรรส 2 ', '91 มุสลิมบำรุง', '0635851106', '6711112546@psu.ac.th', '|-o-|2|-o-|3|-o-||-o-|', '|-o-||-o-|', '|-o-||-o-||-o-||-o-||-o-|', '|-o-||-o-||-o-|', '|-o-||-o-|', '|-o-||-o-|', '|-o-||-o-||-o-||-o-||-o-|', '2', 'พ่อเป็นกระเทย\r\nแม่เป็นทอม\r\nฉันลูกใคร\r\nช่วยหาคำตอบหน่อย\r\nใครคลอดฉัน', '1770001588_1752.jpg', '1770001588_2590.jpeg', '1770001588_3046.jfif', 96, '2026-02-02 09:57:09', '2026-02-02 10:06:31', 1, 1, '', 0, 0.00, 1, 'k|-o-|60|-o-|1|-o-|??????|-o-|200|-o-|หน้าซอย|-o-|0650521112', 'อายีย๊ะ|-o-|0|-o-|0|-o-|ค้าขาย|-o-|100|-o-|หน้าซอย|-o-|0612542221', 'นูรี|-o-|30|-o-|1|-o-|เซล|-o-|300|-o-|ห้าง|-o-|0817357224', '', ''),
(42, 1, 'ฮาซัน', 'ยาการียา', '4.00', '5811112546', 5, '5811112546@psu.ac.th', '1771489479_6996c8c7c463e.jpg', '1234', '0000-00-00', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '2026-02-19 15:24:39', '0000-00-00 00:00:00', 0, 0, '', 0, 0.00, 3, NULL, NULL, NULL, NULL, NULL),
(41, 2, 'ซูรีย์', 'ยาการียา', '4.00', '6911112546', 2, '6911112546@psu.ac.th', '1771487580_6996c15c69b75.JPG', '1234', '2024-03-24', 22, '12 สิโรรส 2 ', '91 มุสลิมบำรุง', '0635851106', '6911112546@psu.ac.th', '', '', '', '', '', '', '', '', '', '', '', '', 0, '2026-02-19 14:53:00', '0000-00-00 00:00:00', 0, 0, '', 0, 0.00, 3, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_teacher`
--

CREATE TABLE `tb_teacher` (
  `tc_id` int(10) NOT NULL,
  `tc_user` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tc_pass` varchar(199) CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
  `tc_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tc_type` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_teacher`
--

INSERT INTO `tb_teacher` (`tc_id`, `tc_user`, `tc_pass`, `tc_name`, `tc_type`) VALUES
(105, '', '', 'ดร.วิฑูรย์ เมตตาจิตร', 4),
(73, 'jomjai.s', '6656', 'จอมใจ  สุทธินนท์', 5),
(114, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.อับดุลเลาะ เจ๊ะหลง', 4),
(72, 'apinya.c', '6656', 'อภิญญา  จงวัฒนไพบูลย์', 5),
(124, 'trithika.n', '6656', 'ไตรถิกา นุ่นเกลี้ยง', 5),
(67, '', '', 'ดร.ณัชรดา  สมสิทธิ์', 4),
(122, '', '', 'ดร.จารุวรรณ ทองเนื้อแข็ง', 4),
(98, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.กฤติยา รัตนกานตะดิลก', 4),
(99, 'vitoon.m', '6656', 'วิฑูรย์ เมตตาจิตร', 5),
(102, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.นิธิตยา สุนทรธรรมนิติ', 4),
(83, '', '', 'ดร.บุษกร โกมลตรี', 4),
(59, '', '', 'รองศาสตราจารย์ ดร.อุทัย  ปริญญาสุทธินันท์', 4),
(58, '', '', 'ดร.สุรพงษ์  ยิ้มละมัย', 4),
(57, '', '', 'รองศาสตราจารย์ ดร.ณฐศร อังศุวิริยะ', 4),
(56, '', '', 'รองศาสตราจารย์ ดร.เกษตรชัย  และหีม', 4),
(54, '', '', 'รองศาสตราจารย์เอมอร  เจียรมาศ', 4),
(120, '', '', 'ดร.อรอุมา จริงจิตร', 4),
(119, '', '', 'ผู้ช่วยศาสตราจารย์กรศักย์ ตันติวิชช์', 4),
(48, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.คมสันต์  วงค์วรรณ์', 4),
(103, '', '', 'อาจารย์พรเทพ งามอร่ามวรางกูร', 4),
(15, '', '', 'รองศาสตราจารย์ ดร.เข็มทอง   สินวงศ์สุวัฒน์ ', 4),
(16, '', '', 'ดร.จอมใจ  สุทธินนท์', 4),
(18, '', '', 'ดร.พนิดา  สุขศรีเมือง', 4),
(19, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.สิตา  มูสิกรังษี', 4),
(20, '', '', 'ดร.อุษา  อินทรักษา', 4),
(21, '', '', 'อาจารย์กรุณา วงศ์ผาสุกโชติ', 4),
(22, '', '', 'ดร.ณัฐพร  แซ่เตีย', 4),
(23, '', '', 'อาจารย์ชุติมา  สว่างวารี', 4),
(100, 'chalermwut.w', '6656', 'เฉลิมวุฒิ วิจิตร', 5),
(25, '', '', 'ดร.บุญทิวา  จันทรเจริญ', 4),
(27, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.สมฤดี  คงพุฒ', 4),
(29, '', '', 'อาจารย์สุกัญญา  ทองดีนอก', 4),
(30, '', '', 'อาจารย์อภิญญา  จงวัฒนไพบูลย์', 4),
(88, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.อัญชนา รักทอง', 4),
(34, '', '', 'อาจารย์ทิศาชล  แซ่ฟุ้ง', 4),
(35, '', '', 'ดร.อุไรวรรณ แซ่อ๋อง', 4),
(37, '', '', 'Mr. Fenglong Zhang', 4),
(38, '', '', 'อาจารย์อรณิช รุ่งภักดีสวัสดิ์', 4),
(39, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.นราธิป  จินดาพิทักษ์', 4),
(116, '', '', 'อาจารย์ศักรินทร์ สวัสดี', 4),
(42, '', '', 'รองศาสตราจารย์ ดร.ปัญญา  เทพสิงห์', 4),
(84, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.จอมขวัญ สุทธินนท์', 4),
(10, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.ไซนี แวมูซอ', 4),
(123, '', '', ' ดร.ธันยากร ตุดเกื้อ', 4),
(111, '', '', 'ดร.ธิติพัทธ์ บุญปก', 4),
(112, '', '', 'ดร.อรกัญญา โรจนวานิชกิจ', 4),
(113, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.อภิชญา แก้วอุทัย', 4),
(89, '', '', 'รองศาสตราจารย์ ดร.วรรณนะ หนูหมื่น', 4),
(80, 'ronnakrit.c', '6656', 'รณกฤต จันทรักษ์', 5),
(125, 'onkanya.r', '6656', 'ดร.อรกัญญา โรจนวานิชกิจ', 5),
(78, 'pichamon.b', '6656', 'พิชามญชุ์ บุญสิทธิ์', 5),
(96, 'patson.j', '6656', 'พธสน ใจห้าว', 5),
(97, '', '', 'ดร.พธสน ใจห้าว', 4),
(106, '', '', 'ดร.ห้าวหาญ ทวีเส้ง', 4),
(117, '', '', 'อาจารย์กานต์พิชชา ดุลยะลา', 4),
(90, '', '', 'ดร.ฐิติวรรณ ชีววิภาส', 4),
(118, '', '', 'ผู้ช่วยศาสตราจารย์ ดร.พิมพวรรณ ใช้พานิช', 4),
(126, 'kanphitcha.d', '6656', 'กานต์พิชชา ดุลยะลา', 5),
(127, 'thitipat.b', '6656', 'ดร.ธิติพัทธ์ บุญปก', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tb_year`
--

CREATE TABLE `tb_year` (
  `y_id` int(2) NOT NULL,
  `y_year` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `y_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_name_1` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_1` int(2) NOT NULL,
  `date_start_1` date DEFAULT NULL,
  `date_end_1` date DEFAULT NULL,
  `st_name_2` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_2` int(2) NOT NULL,
  `date_start_2` date DEFAULT NULL,
  `date_end_2` date DEFAULT NULL,
  `st_name_3` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `st_3` int(2) NOT NULL,
  `date_start_3` date DEFAULT NULL,
  `date_end_3` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_year`
--

INSERT INTO `tb_year` (`y_id`, `y_year`, `y_url`, `st_name_1`, `st_1`, `date_start_1`, `date_end_1`, `st_name_2`, `st_2`, `date_start_2`, `date_end_2`, `st_name_3`, `st_3`, `date_start_3`, `date_end_3`) VALUES
(1, '2569', 'http://localhost/las', 'ทุนสนับสนุนค่าธรรมเนียมการศึกษา', 0, '2026-02-23', NULL, 'เทส', 1, NULL, NULL, 'ทุนนักศึกษาทำงานแลกเปลี่ยน', 0, '2026-01-22', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_activity`
--
ALTER TABLE `tb_activity`
  ADD PRIMARY KEY (`act_id`);

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexes for table `tb_ban`
--
ALTER TABLE `tb_ban`
  ADD PRIMARY KEY (`id_ban`);

--
-- Indexes for table `tb_bursary`
--
ALTER TABLE `tb_bursary`
  ADD PRIMARY KEY (`bur_id`);

--
-- Indexes for table `tb_files`
--
ALTER TABLE `tb_files`
  ADD PRIMARY KEY (`idfile`);

--
-- Indexes for table `tb_issue`
--
ALTER TABLE `tb_issue`
  ADD PRIMARY KEY (`issue_id`);

--
-- Indexes for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`id_mem`);

--
-- Indexes for table `tb_mem_date`
--
ALTER TABLE `tb_mem_date`
  ADD PRIMARY KEY (`id_date`);

--
-- Indexes for table `tb_news`
--
ALTER TABLE `tb_news`
  ADD PRIMARY KEY (`idnews`);

--
-- Indexes for table `tb_parent`
--
ALTER TABLE `tb_parent`
  ADD PRIMARY KEY (`parent_id`);

--
-- Indexes for table `tb_program`
--
ALTER TABLE `tb_program`
  ADD PRIMARY KEY (`g_id`);

--
-- Indexes for table `tb_relatives`
--
ALTER TABLE `tb_relatives`
  ADD PRIMARY KEY (`re_id`);

--
-- Indexes for table `tb_scores`
--
ALTER TABLE `tb_scores`
  ADD PRIMARY KEY (`sco_id`);

--
-- Indexes for table `tb_student`
--
ALTER TABLE `tb_student`
  ADD PRIMARY KEY (`st_id`);

--
-- Indexes for table `tb_teacher`
--
ALTER TABLE `tb_teacher`
  ADD PRIMARY KEY (`tc_id`);

--
-- Indexes for table `tb_year`
--
ALTER TABLE `tb_year`
  ADD PRIMARY KEY (`y_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_activity`
--
ALTER TABLE `tb_activity`
  MODIFY `act_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `ad_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_ban`
--
ALTER TABLE `tb_ban`
  MODIFY `id_ban` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT for table `tb_bursary`
--
ALTER TABLE `tb_bursary`
  MODIFY `bur_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_files`
--
ALTER TABLE `tb_files`
  MODIFY `idfile` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tb_issue`
--
ALTER TABLE `tb_issue`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `id_mem` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_mem_date`
--
ALTER TABLE `tb_mem_date`
  MODIFY `id_date` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_news`
--
ALTER TABLE `tb_news`
  MODIFY `idnews` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=366;

--
-- AUTO_INCREMENT for table `tb_parent`
--
ALTER TABLE `tb_parent`
  MODIFY `parent_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `tb_program`
--
ALTER TABLE `tb_program`
  MODIFY `g_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_relatives`
--
ALTER TABLE `tb_relatives`
  MODIFY `re_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `tb_scores`
--
ALTER TABLE `tb_scores`
  MODIFY `sco_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_student`
--
ALTER TABLE `tb_student`
  MODIFY `st_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tb_teacher`
--
ALTER TABLE `tb_teacher`
  MODIFY `tc_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `tb_year`
--
ALTER TABLE `tb_year`
  MODIFY `y_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
