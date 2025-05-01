-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 10:40 AM
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
-- Database: `sit-in`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(1020) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123'),
(2, 'admin-536', '123'),
(3, 'admin-542', '123');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `admin_name` varchar(200) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `admin_name`, `post_date`, `message`) VALUES
(2, 'CCS-Admin', '2025-03-04 17:38:40', 'System maintenance is scheduled for this Friday from 8:00 PM to 10:00 PM. Hello World!'),
(3, 'CCS-Admin', '2025-03-04 17:40:12', 'All users must update their profiles with their latest student ID numbers.'),
(4, 'CCS-Admin', '2025-03-04 17:41:05', 'Reminder: All students must log their sit-in sessions properly.'),
(5, 'CCS-Admin', '2025-03-04 17:41:14', 'Sit-in schedules for next week are now available. Book your slots in advance. Thank You.'),
(6, 'CCS-Admin', '2025-03-05 02:52:47', 'Attention students and faculty! 🎉 We are excited to introduce the Sit-in Lab System, designed to streamline the sit-in process for laboratory sessions. Thank u so much'),
(7, 'CCS-Admin', '2025-03-11 04:55:08', 'Goodluck CSS. Thanks'),
(8, 'CCS-Admin', '2025-03-11 11:24:37', 'Attention students and faculty! 🎉 We are thrilled to announce the launch of the Sit-in Lab System, created to simplify and enhance the process of attending laboratory sessions. Thank you for your support!'),
(9, 'CCS-Admin', '2025-03-13 04:03:13', 'GOODLUCK, TIDERTS');

-- --------------------------------------------------------

--
-- Table structure for table `curr_sit_in`
--

CREATE TABLE `curr_sit_in` (
  `sit_in_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `laboratory` varchar(100) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `check_in_time` datetime NOT NULL DEFAULT current_timestamp(),
  `check_out_time` datetime DEFAULT NULL,
  `status` enum('active','completed') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curr_sit_in`
--

INSERT INTO `curr_sit_in` (`sit_in_id`, `user_id`, `laboratory`, `purpose`, `check_in_time`, `check_out_time`, `status`) VALUES
(1, 1, '517', 'Computer Application', '2025-04-29 06:56:33', '2025-04-29 07:00:29', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `lab_computers`
--

CREATE TABLE `lab_computers` (
  `id` int(11) NOT NULL,
  `lab_room` varchar(10) NOT NULL,
  `pc_number` varchar(10) NOT NULL,
  `status` enum('available','used','maintenance') NOT NULL DEFAULT 'available',
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_computers`
--

INSERT INTO `lab_computers` (`id`, `lab_room`, `pc_number`, `status`, `last_updated`) VALUES
(1, '524', '1', 'available', '2025-04-25 15:10:08'),
(2, '524', '2', 'available', '2025-04-25 22:21:25'),
(3, '524', '3', 'available', '2025-04-24 11:41:16'),
(4, '517', '1', 'available', '2025-04-25 22:05:50'),
(5, '517', '2', 'available', '2025-04-25 21:52:47'),
(6, '517', '3', 'available', '2025-04-25 21:52:48'),
(7, '517', '4', 'available', '2025-04-25 22:10:54'),
(8, '517', '5', 'available', '2025-04-24 15:14:17'),
(9, '517', '6', 'available', '2025-04-25 22:13:03'),
(10, '517', '7', 'available', '2025-04-24 12:53:57'),
(11, '517', '8', 'available', '2025-04-25 02:13:15'),
(12, '517', '9', 'available', '2025-04-25 01:44:39'),
(13, '517', '10', 'available', '2025-04-30 16:33:02'),
(14, '517', '11', 'available', '2025-04-30 16:33:03'),
(15, '517', '12', 'available', '2025-04-30 16:33:03'),
(16, '517', '13', 'available', '2025-04-30 16:33:05'),
(17, '517', '14', 'available', '2025-04-30 16:33:05'),
(18, '517', '15', 'available', '2025-04-30 16:33:05'),
(19, '517', '16', 'available', '2025-04-30 16:33:06'),
(20, '517', '17', 'available', '2025-04-30 16:33:07'),
(21, '517', '18', 'available', '2025-04-30 16:33:07'),
(22, '517', '19', 'available', '2025-04-30 16:33:07'),
(23, '517', '20', 'available', '2025-04-30 16:33:08'),
(24, '544', '1', 'available', '2025-04-24 16:21:04'),
(25, '544', '2', 'available', '2025-04-25 22:16:30'),
(26, '544', '3', 'available', '2025-04-25 00:34:03'),
(27, '544', '4', 'available', '2025-04-25 00:34:04'),
(28, '544', '5', 'available', '2025-04-25 00:34:04'),
(29, '544', '6', 'available', '2025-04-25 00:34:05'),
(30, '544', '7', 'available', '2025-04-25 00:34:08'),
(31, '544', '8', 'available', '2025-04-25 00:34:07'),
(32, '544', '9', 'available', '2025-04-25 00:34:08'),
(33, '544', '10', 'available', '2025-04-24 16:21:04'),
(34, '544', '11', 'available', '2025-04-24 16:21:05'),
(35, '544', '12', 'available', '2025-04-24 16:21:05'),
(36, '544', '13', 'available', '2025-04-24 16:21:05'),
(37, '544', '14', 'available', '2025-04-24 16:21:06'),
(38, '544', '15', 'available', '2025-04-24 16:21:06'),
(39, '544', '50', 'available', '2025-04-30 16:33:47'),
(40, '544', '49', 'available', '2025-04-30 16:33:47'),
(41, '544', '48', 'available', '2025-04-30 16:33:49'),
(42, '544', '47', 'available', '2025-04-30 16:33:48'),
(43, '544', '46', 'available', '2025-04-30 16:33:48'),
(44, '544', '42', 'available', '2025-04-30 16:33:50'),
(45, '544', '43', 'available', '2025-04-30 16:33:49'),
(46, '544', '44', 'available', '2025-04-30 16:33:49'),
(47, '544', '45', 'available', '2025-04-30 16:33:48'),
(48, '544', '41', 'available', '2025-04-30 16:33:50'),
(49, '544', '36', 'available', '2025-04-30 16:33:52'),
(50, '544', '37', 'available', '2025-04-30 16:33:51'),
(51, '544', '38', 'available', '2025-04-30 16:33:51'),
(52, '544', '39', 'available', '2025-04-30 16:33:52'),
(53, '544', '40', 'available', '2025-04-30 16:33:53'),
(54, '544', '35', 'available', '2025-04-30 16:33:53'),
(55, '544', '34', 'available', '2025-04-30 16:33:54'),
(56, '544', '33', 'available', '2025-04-30 16:33:54'),
(57, '544', '31', 'available', '2025-04-30 16:33:57'),
(58, '544', '32', 'available', '2025-04-30 16:33:57'),
(59, '544', '26', 'available', '2025-04-24 16:21:22'),
(60, '544', '27', 'maintenance', '2025-04-25 04:29:17'),
(61, '544', '28', 'available', '2025-04-30 16:33:56'),
(62, '544', '29', 'available', '2025-04-30 16:33:58'),
(63, '544', '30', 'available', '2025-04-30 16:33:57'),
(64, '544', '25', 'available', '2025-04-24 16:21:21'),
(65, '544', '24', 'available', '2025-04-24 16:21:21'),
(66, '544', '22', 'available', '2025-04-24 16:21:13'),
(67, '544', '23', 'available', '2025-04-24 16:21:21'),
(68, '544', '21', 'available', '2025-04-24 16:21:13'),
(69, '544', '16', 'available', '2025-04-24 16:21:07'),
(70, '544', '17', 'available', '2025-04-24 16:21:07'),
(71, '544', '18', 'available', '2025-04-24 16:21:08'),
(72, '544', '19', 'available', '2025-04-24 16:21:09'),
(73, '544', '20', 'available', '2025-04-25 02:46:47'),
(74, '517', '25', 'available', '2025-04-30 16:33:12'),
(75, '517', '50', 'available', '2025-04-30 16:33:25'),
(77, '524', '4', 'available', '2025-04-24 16:21:32'),
(78, '524', '5', 'available', '2025-04-24 16:21:34'),
(79, '524', '10', 'available', '2025-04-25 04:28:58'),
(80, '524', '9', 'available', '2025-04-24 16:21:34'),
(81, '517', '40', 'available', '2025-04-30 16:33:19'),
(82, '517', '21', 'available', '2025-04-30 16:33:08'),
(83, '517', '22', 'available', '2025-04-30 16:33:09'),
(84, '517', '23', 'available', '2025-04-30 16:33:09'),
(85, '517', '24', 'available', '2025-04-30 16:33:10'),
(86, '517', '26', 'available', '2025-04-30 16:33:12'),
(87, '517', '27', 'available', '2025-04-30 16:33:12'),
(88, '517', '28', 'available', '2025-04-30 16:33:14'),
(89, '517', '32', 'available', '2025-04-30 16:33:16'),
(90, '517', '31', 'available', '2025-04-30 16:33:15'),
(91, '517', '30', 'available', '2025-04-30 16:33:15'),
(92, '517', '29', 'available', '2025-04-30 16:33:15'),
(93, '517', '33', 'available', '2025-04-30 16:33:17'),
(94, '517', '34', 'available', '2025-04-30 16:33:17'),
(95, '517', '35', 'available', '2025-04-30 16:33:17'),
(96, '517', '36', 'available', '2025-04-30 16:33:18'),
(97, '517', '37', 'available', '2025-04-30 16:33:18'),
(98, '517', '38', 'available', '2025-04-30 16:33:18'),
(99, '517', '39', 'available', '2025-04-30 16:33:19'),
(100, '517', '44', 'available', '2025-04-30 16:33:22'),
(101, '517', '43', 'available', '2025-04-30 16:33:21'),
(102, '517', '42', 'available', '2025-04-30 16:33:21'),
(103, '517', '41', 'available', '2025-04-30 16:33:20'),
(104, '517', '45', 'available', '2025-04-30 16:33:22'),
(105, '517', '47', 'available', '2025-04-30 16:33:23'),
(106, '517', '46', 'available', '2025-04-30 16:33:23'),
(107, '517', '48', 'available', '2025-04-30 16:33:24'),
(108, '517', '49', 'available', '2025-04-30 16:33:25'),
(109, '528', '50', 'available', '2025-04-29 00:34:31'),
(110, '524', '6', 'available', '2025-04-25 14:50:47'),
(111, '524', '7', 'available', '2025-04-25 14:50:47'),
(112, '524', '8', 'available', '2025-04-25 14:50:48'),
(113, '524', '11', 'available', '2025-04-25 14:50:49'),
(114, '524', '12', 'available', '2025-04-25 14:50:49'),
(115, '526', '1', 'available', '2025-04-29 00:34:26'),
(116, '524', '50', 'available', '2025-04-30 16:33:32'),
(117, '524', '49', 'available', '2025-04-30 16:33:31'),
(118, '524', '48', 'available', '2025-04-30 16:33:31'),
(119, '524', '47', 'available', '2025-04-30 16:33:31'),
(120, '524', '46', 'available', '2025-04-30 16:33:30'),
(121, '524', '45', 'available', '2025-04-30 16:33:30'),
(122, '530', '3', '', '2025-05-01 06:35:58'),
(123, '542', '8', '', '2025-05-01 07:05:05'),
(124, '528', '13', '', '2025-05-01 06:48:11'),
(125, '530', '1', '', '2025-05-01 07:18:27'),
(126, '530', '10', '', '2025-05-01 07:37:37'),
(127, '542', '30', '', '2025-05-01 08:20:10');

-- --------------------------------------------------------

--
-- Table structure for table `lab_resources`
--

CREATE TABLE `lab_resources` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `year_level` varchar(50) NOT NULL,
  `course` varchar(50) NOT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `resource_type` varchar(50) NOT NULL DEFAULT 'document'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_resources`
--

INSERT INTO `lab_resources` (`id`, `title`, `description`, `file_path`, `link_url`, `year_level`, `course`, `uploaded_by`, `upload_date`, `is_active`, `resource_type`) VALUES
(0, 'Law 101 ', 'To enhance the knowledge towards law 101.', NULL, 'https://courses.lumenlearning.com/suny-monroe-law101/', '1st Year', 'AB PolSci', 'Admin', '2025-04-10 20:23:30', 1, 'link');

-- --------------------------------------------------------

--
-- Table structure for table `lab_schedules`
--

CREATE TABLE `lab_schedules` (
  `schedule_id` int(11) NOT NULL,
  `lab_room` varchar(10) NOT NULL,
  `schedule_image` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `uploaded_by` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `related_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lab_room` varchar(10) NOT NULL,
  `pc_number` varchar(10) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `reservation_date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time DEFAULT NULL,
  `status` enum('pending','approved','disapproved','completed','cancelled') NOT NULL DEFAULT 'pending',
  `disapproval_reason` varchar(255) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sit_in_ratings`
--

CREATE TABLE `sit_in_ratings` (
  `rating_id` int(11) NOT NULL,
  `sit_in_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sit_in_ratings`
--

INSERT INTO `sit_in_ratings` (`rating_id`, `sit_in_id`, `user_id`, `rating`, `feedback`, `created_at`) VALUES
(1, 17, 1, 5, 'Its very nice and Good', '2025-03-13 12:29:01'),
(2, 16, 1, 4, 'Very good and Nice', '2025-03-13 12:33:11'),
(3, 15, 1, 3, 'Ana alexus', '2025-03-13 12:53:05'),
(4, 9, 7, 5, 'Very Good', '2025-03-13 17:39:37'),
(5, 4, 7, 4, 'Thank u So much', '2025-03-13 17:39:47'),
(6, 6, 7, 3, 'Love it', '2025-03-13 17:41:57'),
(7, 5, 7, 3, 'Great', '2025-03-13 17:42:07'),
(8, 2, 7, 3, 'HAHAHAHAHHA', '2025-03-13 17:42:26'),
(9, 1, 1, 5, 'Very Good!', '2025-04-29 08:37:07');

-- --------------------------------------------------------

--
-- Table structure for table `student_points`
--

CREATE TABLE `student_points` (
  `point_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points_earned` int(11) NOT NULL DEFAULT 1,
  `points_reason` varchar(255) NOT NULL,
  `awarded_by` varchar(100) NOT NULL,
  `awarded_date` datetime NOT NULL DEFAULT current_timestamp(),
  `converted_to_session` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_points`
--

INSERT INTO `student_points` (`point_id`, `user_id`, `points_earned`, `points_reason`, `awarded_by`, `awarded_date`, `converted_to_session`) VALUES
(1, 10, 3, 'Very Good', 'admin', '2025-04-28 23:22:03', 1),
(2, 10, 3, 'Nice', 'admin', '2025-04-28 23:22:30', 1),
(3, 10, 3, 'Effortless', 'admin', '2025-04-28 23:22:58', 1),
(4, 10, 3, 'Nc ka one', 'admin', '2025-04-28 23:24:01', 1),
(5, 4, 1, 'basic', 'admin', '2025-04-28 23:37:53', 1),
(6, 4, 2, 'Nice one', 'admin', '2025-04-28 23:38:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stud_session`
--

CREATE TABLE `stud_session` (
  `id` int(11) NOT NULL,
  `session` int(11) NOT NULL DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stud_session`
--

INSERT INTO `stud_session` (`id`, `session`) VALUES
(1, 27),
(2, 27),
(3, 27),
(4, 30),
(5, 26),
(6, 29),
(7, 25),
(8, 26),
(9, 28),
(10, 30),
(11, 26);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `idno` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `midname` varchar(50) DEFAULT NULL,
  `course` varchar(50) NOT NULL,
  `level` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `profileImg` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `idno`, `lastname`, `firstname`, `midname`, `course`, `level`, `address`, `profileImg`, `email`, `password`) VALUES
(1, '22692693', 'Does', 'John', 'D.', 'BSCS', '1st Year', 'Talisay City', '../uploadimg/profile_6810bb21a463d.jpg', 'doe@gmail.com', 'doe123@J'),
(2, '21864648', 'Smith', 'David', 'C.', 'BSIT', '4th Year', 'Cordova, Lapu-lapu', 'uploadimg/dog.jpg', 'smith@gmail.com', '123'),
(3, '48965754', 'Major', 'Mary', 'L.', 'BSIT', '1st Year', 'Pahina, Cebu City', 'uploadimg/profile_67bd9bc827a4e.jpg', 'mary@gmail.com', '123'),
(4, '22889977', 'Michaels', 'Bron', 'C.', 'BSCS', '1st Year', 'Basak, Pardo', 'uploadimg/panda.jpg', 'mic@gmail.com', '$2y$10$NzuQBLCsFSVIqnOwMimST.Q8T11SH5Nygkeayg.IlvGKbZ2BCvq9W'),
(5, '33669944', 'Tatum', 'Lebron', 'L.', 'BSCompE', '2nd Year', 'Cebu, Boston', 'uploadimg/profile_67bfdabe507b0.jpg', 'tatum@gmail.com', 'Piggy123@'),
(6, '11556677', 'Doncics', 'Maxie', 'D.', 'BSCS', '1st Year', 'Sibonga, Cebu', 'images/person.jpg', 'max@gmail.com', '123'),
(7, '22596886', 'PaldoGodz', 'Rovic', 'T.', 'BSCompE', '2nd Year', 'Pahina, Cebu City', NULL, 'rovic@gmail.com', 'Paldo123@'),
(8, '55442211', 'Steph', 'Kevin', 'B.', 'AB PolSci', '1st Year', 'Mandaue Cebu', NULL, 'step@gmail.com', '123'),
(9, '44883311', 'Byrce', 'Paul', 'P', 'BSEE', '2', 'Cleveland, USA', NULL, 'paul@gmail.com', '123paul@'),
(10, '14556678', 'Bryant', 'Alexander', 'R.', 'BSCrim', '2nd Year', 'Bulacao, Cebu City', NULL, 'alexander@gmail.com', 'Ball@123'),
(11, '99884422', 'Wade', 'Chris', 'J.', 'BSEE', '2nd Year', 'Mambaling, Basak', NULL, 'chris@gmail.com', 'chris@J123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `curr_sit_in`
--
ALTER TABLE `curr_sit_in`
  ADD PRIMARY KEY (`sit_in_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lab_computers`
--
ALTER TABLE `lab_computers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_pc_unique` (`lab_room`,`pc_number`);

--
-- Indexes for table `lab_schedules`
--
ALTER TABLE `lab_schedules`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_read` (`is_read`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `sit_in_ratings`
--
ALTER TABLE `sit_in_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `sit_in_id` (`sit_in_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `student_points`
--
ALTER TABLE `student_points`
  ADD PRIMARY KEY (`point_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stud_session`
--
ALTER TABLE `stud_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `idno` (`idno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `curr_sit_in`
--
ALTER TABLE `curr_sit_in`
  MODIFY `sit_in_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lab_computers`
--
ALTER TABLE `lab_computers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `lab_schedules`
--
ALTER TABLE `lab_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sit_in_ratings`
--
ALTER TABLE `sit_in_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `student_points`
--
ALTER TABLE `student_points`
  MODIFY `point_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stud_session`
--
ALTER TABLE `stud_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `curr_sit_in`
--
ALTER TABLE `curr_sit_in`
  ADD CONSTRAINT `curr_sit_in_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `admin` (`id`);

--
-- Constraints for table `sit_in_ratings`
--
ALTER TABLE `sit_in_ratings`
  ADD CONSTRAINT `sit_in_ratings_ibfk_1` FOREIGN KEY (`sit_in_id`) REFERENCES `curr_sit_in` (`sit_in_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sit_in_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_points`
--
ALTER TABLE `student_points`
  ADD CONSTRAINT `student_points_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stud_session`
--
ALTER TABLE `stud_session`
  ADD CONSTRAINT `stud_session_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
