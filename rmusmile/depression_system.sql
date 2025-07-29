-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 18, 2025 at 03:38 PM
-- Server version: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `depression_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `affiliations`
--

CREATE TABLE `affiliations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `affiliations`
--

INSERT INTO `affiliations` (`id`, `name`) VALUES
(3, 'มหาวิทยาลัยราชภัฏมหาสารคาม'),
(4, 'มหาวิทยาลัยมหาสารคาม'),
(5, 'โรงเรียนสาธิต มหาวิทยาลัยราชภัฏมหาสารคาม');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `q1` int(11) DEFAULT NULL,
  `q2` int(11) DEFAULT NULL,
  `q3` int(11) DEFAULT NULL,
  `q4` int(11) DEFAULT NULL,
  `q5` int(11) DEFAULT NULL,
  `q6` int(11) DEFAULT NULL,
  `q7` int(11) DEFAULT NULL,
  `q8` int(11) DEFAULT NULL,
  `q9` int(11) DEFAULT NULL,
  `total_score` int(11) DEFAULT NULL,
  `danger_level` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `user_id`, `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `q8`, `q9`, `total_score`, `danger_level`, `created_at`) VALUES
(1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'ปกติ', '2025-05-11 20:29:09'),
(2, 1, 1, 1, 3, 1, 2, 2, 1, 2, 1, 13, 'นิดหน่อย', '2025-05-11 20:29:42'),
(7, 16, 1, 1, 1, 1, 1, 1, 1, 1, 1, 8, '', '2025-05-11 22:03:42'),
(11, 17, 3, 3, 3, 3, 3, 3, 3, 3, 3, 24, 'รุนแรงมาก', '2025-05-11 22:18:21'),
(12, 17, 2, 2, 2, 2, 2, 2, 2, 2, 2, 16, 'ค่อนข้างมาก', '2025-05-11 22:18:49'),
(13, 17, 1, 1, 1, 1, 1, 1, 1, 1, 1, 8, 'เล็กน้อย', '2025-05-11 22:19:07'),
(14, 17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'น้อยมาก', '2025-05-11 22:19:23'),
(15, 17, 1, 1, 1, 1, 1, 1, 1, 1, 1, 8, 'เล็กน้อย', '2025-05-11 22:20:56'),
(16, 17, 2, 1, 1, 1, 1, 1, 1, 1, 1, 9, 'ปานกลาง', '2025-05-11 22:21:18'),
(17, 16, 3, 3, 3, 3, 3, 3, 3, 3, 3, 24, 'รุนแรงมาก', '2025-05-16 17:12:10'),
(18, 23, 0, 1, 0, 1, 0, 1, 0, 0, 0, 3, 'น้อยมาก', '2025-05-18 20:39:49');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `login_time` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `ip_address`, `role`, `login_time`) VALUES
(1, 19, '127.0.0.1', 'superadmin', '2025-05-16 22:34:55'),
(2, 21, '127.0.0.1', 'admin', '2025-05-16 22:35:15'),
(3, 20, '127.0.0.1', 'doctor', '2025-05-16 22:35:44'),
(4, 19, '127.0.0.1', 'superadmin', '2025-05-16 22:36:08'),
(5, 19, '127.0.0.1', 'superadmin', '2025-05-16 22:48:11'),
(6, 19, '127.0.0.1', 'superadmin', '2025-05-16 23:03:27'),
(7, 16, '127.0.0.1', 'user', '2025-05-16 23:03:49'),
(8, 19, '127.0.0.1', 'superadmin', '2025-05-17 12:31:03'),
(9, 17, '127.0.0.1', 'user', '2025-05-17 12:50:02'),
(10, 17, '127.0.0.1', 'user', '2025-05-17 12:54:14'),
(11, 17, '127.0.0.1', 'user', '2025-05-17 13:07:31'),
(12, 22, '127.0.0.1', 'user', '2025-05-17 13:23:52'),
(13, 19, '127.0.0.1', 'superadmin', '2025-05-18 20:28:45'),
(14, 22, '127.0.0.1', 'user', '2025-05-18 20:30:59'),
(15, 23, '127.0.0.1', 'user', '2025-05-18 20:39:14'),
(16, 23, '127.0.0.1', 'user', '2025-05-18 20:47:51'),
(17, 23, '127.0.0.1', 'user', '2025-05-18 20:48:14'),
(18, 23, '127.0.0.1', 'user', '2025-05-18 20:58:02'),
(19, 23, '127.0.0.1', 'user', '2025-05-18 20:59:05'),
(20, 21, '127.0.0.1', 'admin', '2025-05-18 21:07:29'),
(21, 20, '127.0.0.1', 'doctor', '2025-05-18 21:09:24'),
(22, 19, '127.0.0.1', 'superadmin', '2025-05-18 21:12:46'),
(23, 21, '127.0.0.1', 'admin', '2025-05-18 21:24:06'),
(24, 21, '127.0.0.1', 'admin', '2025-05-18 21:32:41'),
(25, 19, '127.0.0.1', 'superadmin', '2025-05-18 21:59:54'),
(26, 21, '127.0.0.1', 'admin', '2025-05-18 22:05:42'),
(27, 21, '127.0.0.1', 'admin', '2025-05-18 22:07:35'),
(28, 21, '127.0.0.1', 'admin', '2025-05-18 22:07:51'),
(29, 21, '127.0.0.1', 'admin', '2025-05-18 22:07:52'),
(30, 19, '127.0.0.1', 'superadmin', '2025-05-18 22:23:10'),
(31, 19, '127.0.0.1', 'superadmin', '2025-05-18 22:36:42');

-- --------------------------------------------------------

--
-- Table structure for table `patients_status`
--

CREATE TABLE `patients_status` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `current_status` enum('ปกติ','รอรักษา','รักษาแล้ว','ไม่สามารถติดต่อได้') DEFAULT 'รอรักษา',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patients_status`
--

INSERT INTO `patients_status` (`id`, `user_id`, `current_status`, `updated_at`) VALUES
(1, 16, 'รอรักษา', '2025-05-16 17:12:52'),
(2, 16, 'รอรักษา', '2025-05-16 21:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_text`) VALUES
(1, '1. เบื่อ ไม่สนใจอยากทำอะไร'),
(2, '2. ไม่สบายใจ ซึมเศร้า ท้อแท้'),
(3, '3. หลับยาก หรือหลับๆ ตื่นๆ หรือหลับมากไป'),
(4, '4. เหนื่อยง่าย หรือ ไม่ค่อยมีแรง'),
(5, '5. เบื่ออาหาร หรือกินมากเกินไป'),
(6, '6. รู้สึกไม่ดีกับตัวเอง คิดว่าตัวเองล้มเหลว หรือทำให้ตนเองหรือครอบครัวผิดหวัง'),
(7, '7. สมาธิไม่ดีเวลาทำอะไร เช่น ดูโทรทัศน์ ฟังวิทยุ หรือทำงานที่ต้องใช้ความตั้งใจ'),
(8, '8. พูดช้า ทำอะไรช้าลง จนคนอื่นสังเกตเห็นได้ หรือกระสับกระส่ายไม่สามารถอยู่นิ่งได้เหมือนที่เคยเป็น'),
(9, '9. คิดทำร้ายตนเอง หรือคิดว่าถ้าตายไปคงจะดี');

-- --------------------------------------------------------

--
-- Table structure for table `sub_affiliations`
--

CREATE TABLE `sub_affiliations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `affiliation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sub_affiliations`
--

INSERT INTO `sub_affiliations` (`id`, `name`, `affiliation_id`) VALUES
(5, 'คณะวิทยาการจัดการ', 3),
(6, 'คณะวิทยาศาสตร์และเทคโนโลยี', 3),
(7, 'คณะเทคโนโลยีสารสนเทศ', 3),
(8, 'คณะครุศาสตร์', 3),
(9, 'คณะนิติศาสตร์', 3),
(10, 'คณะมนุษย์ศาสตร์และสังคมศาสตร์', 3);

-- --------------------------------------------------------

--
-- Table structure for table `treatments`
--

CREATE TABLE `treatments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `details` text NOT NULL,
  `treated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `treatments`
--

INSERT INTO `treatments` (`id`, `user_id`, `doctor_id`, `details`, `treated_at`) VALUES
(1, 16, 18, 'ทดสอบสถานะการรักษาเบื่องต้น', '2025-05-16 17:13:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `citizen_id` char(13) DEFAULT NULL,
  `affiliation_id` int(11) DEFAULT NULL,
  `sub_affiliation_id` int(11) DEFAULT NULL,
  `family_status` enum('บิดามารดาอยู่ด้วยกัน','บิดามารดาหย่าร้าง','บิดาเสียชีวิต','มารดาเสียชีวิต','บิดาและมารดาเสียชีวิต') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `financial_status` enum('พอใช้','ไม่พอใช้') DEFAULT NULL,
  `drug_use` enum('ใช้','ไม่ใช้') DEFAULT NULL,
  `has_support` enum('มี','ไม่มี') DEFAULT NULL,
  `role` enum('user','doctor','admin','superadmin') DEFAULT 'user',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `phone`, `email`, `password`, `citizen_id`, `affiliation_id`, `sub_affiliation_id`, `family_status`, `financial_status`, `drug_use`, `has_support`, `role`, `created_at`) VALUES
(2, 'test', '0123456789', 'test@test.com', '$2y$10$QMT5ZVj5PecFC.1McQOa/usNkUZu7o1HTwUQEeoxa.No035oI4Jj2', '1234567890123', 1, 1, '', 'ไม่พอใช้', 'ไม่ใช้', 'ไม่มี', 'user', '2025-05-10 20:10:40'),
(16, 'TEST01', '0123456789', 'user@test.com', '$2y$10$4sJdOVAnXPtNUYgeU7UG4eG3vs3YM8C34YrMu9y3g2uBlcZDTsKES', '0123456789012', 1, 1, '', 'พอใช้', 'ใช้', 'มี', 'user', '2025-05-11 20:41:16'),
(17, 'user02', '0610825954', 'user02@test.com', '$2y$10$Q7Y72gw38L4UiqzJaAvIjuBM1Z1jUN0m7JHFNx5CquKdMkTcXWhMm', '1661230331654', 1, 1, 'บิดามารดาอยู่ด้วยกัน', 'พอใช้', 'ไม่ใช้', 'ไม่มี', 'user', '2025-05-11 22:07:58'),
(18, 'doctor', '0123456789', 'doctor@test.com', '$2y$10$qmeY4U.pIkg17kj35jaDZ.KKRV31BhB3lWRiaV2yWCUFdKD8eEMbG', '0123456789012', 1, 1, '', 'พอใช้', 'ใช้', 'มี', 'doctor', '2025-05-16 17:09:40'),
(19, 'super', '0123456789', 'super@test.com', '$2y$10$PUvJZ7YiHcXqdxQkf6hC5ugPIXo8L6pQskwkwgonwdrM95RjPxgoS', '0123456789012', 1, 1, '', 'พอใช้', 'ใช้', 'มี', 'superadmin', '2025-05-16 21:29:20'),
(20, 'ฟ้าลดา', NULL, 'doctor02@test.com', '$2y$10$qhQfrjNKIprUYfw7LgTrBOnEiYHhITwsWpJb.//tpXfOXyfB.duLq', NULL, 1, 1, NULL, NULL, NULL, NULL, 'doctor', '2025-05-16 21:35:58'),
(21, 'admin', NULL, 'admin@test.com', '$2y$10$SYrmJm4Qlfq1iXzUZ7rEpOUXjJTzPK0RkwEQpnn31agpuELMWqXoy', NULL, 1, 1, NULL, NULL, NULL, NULL, 'admin', '2025-05-16 21:41:15'),
(22, 'a1', '0123456789', 'a1@test.com', '$2y$10$ozBYDjJ7OuH.NAQRSf3gvOM/n8MSaQyLqnTyyho4oL5umvnXZEoba', '0123456789012', 1, 1, '', 'พอใช้', 'ไม่ใช้', 'ไม่มี', 'user', '2025-05-17 13:23:47'),
(23, 'sittisak maneerak', '0610825954', 'sittisakbaba@gmail.com', '$2y$10$M7puWkoekhVLvX6RovnQd.cYX9wuMhMpJwyr6ZOIbJPOF3NEls1Cq', '1234567890123', 1, 1, 'บิดามารดาหย่าร้าง', 'พอใช้', 'ไม่ใช้', 'ไม่มี', 'user', '2025-05-18 20:39:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `affiliations`
--
ALTER TABLE `affiliations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients_status`
--
ALTER TABLE `patients_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_affiliations`
--
ALTER TABLE `sub_affiliations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `affiliations`
--
ALTER TABLE `affiliations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `patients_status`
--
ALTER TABLE `patients_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sub_affiliations`
--
ALTER TABLE `sub_affiliations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
