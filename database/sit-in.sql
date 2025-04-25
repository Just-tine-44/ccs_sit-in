-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 07:16 AM
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
(1, 1, 'CCS Lab 1', 'PHP PROGRAMMING', '2025-03-11 12:33:11', '2025-03-11 12:49:16', 'completed'),
(2, 7, 'CCS Lab 2', 'Java Programming', '2025-03-11 12:43:30', '2025-03-11 12:49:18', 'completed'),
(3, 7, 'CCS Lab 2', 'Research', '2025-03-11 12:52:12', '2025-03-11 12:52:23', 'completed'),
(4, 7, 'CCS Lab 1', 'Research', '2025-03-11 12:54:21', '2025-03-11 12:54:26', 'completed'),
(5, 7, 'CCS Lab 1', 'Java Programming', '2025-03-11 12:56:21', '2025-03-11 12:56:41', 'completed'),
(6, 7, 'CCS Lab 1', 'Assignment', '2025-03-11 17:15:53', '2025-03-11 17:16:30', 'completed'),
(7, 2, '524', 'Study', '2025-03-11 17:53:21', '2025-03-11 17:58:38', 'completed'),
(8, 1, '528', 'Assignment', '2025-03-11 17:55:20', '2025-03-11 17:58:36', 'completed'),
(9, 7, '528', 'Project', '2025-03-11 17:57:20', '2025-03-11 17:58:34', 'completed'),
(10, 1, '528', 'Assignment', '2025-03-11 18:09:22', '2025-03-11 18:16:30', 'completed'),
(11, 6, '524', 'C Programming', '2025-03-11 19:16:42', '2025-03-11 19:17:13', 'completed'),
(12, 5, '530', 'Other', '2025-03-11 21:49:54', '2025-03-11 21:50:09', 'completed'),
(13, 3, '530', 'C#', '2025-03-11 21:52:42', '2025-03-11 21:53:35', 'completed'),
(14, 5, '528', 'PHP', '2025-03-11 21:55:17', '2025-03-11 21:57:28', 'completed'),
(15, 1, '526', 'C#', '2025-03-13 11:10:26', '2025-03-13 11:11:04', 'completed'),
(16, 1, '526', 'C Programming', '2025-03-13 12:02:33', '2025-03-13 12:02:48', 'completed'),
(17, 1, '530', 'ASP.Net', '2025-03-13 12:05:20', '2025-03-13 12:05:58', 'completed'),
(18, 1, '524', 'PHP', '2025-03-13 12:38:40', '2025-03-13 12:39:10', 'completed'),
(19, 10, '530', 'C#', '2025-03-21 07:38:11', '2025-03-21 07:42:17', 'completed'),
(20, 1, '530', 'PHP', '2025-03-21 21:47:02', '2025-03-21 21:47:13', 'completed'),
(21, 1, '528', 'PHP', '2025-04-08 09:06:59', '2025-04-08 09:07:09', 'completed'),
(22, 1, '530', 'PHP', '2025-04-08 09:44:45', '2025-04-08 09:45:02', 'completed'),
(23, 8, '528', 'C#', '2025-04-08 10:25:49', '2025-04-08 10:26:03', 'completed'),
(24, 5, '524', 'Other', '2025-04-08 10:31:47', '2025-04-08 10:32:04', 'completed'),
(25, 8, '544', 'Systems Integration & Architecture', '2025-04-10 19:34:40', '2025-04-10 19:35:13', 'completed'),
(26, 5, '530', 'Systems Integration & Architecture', '2025-04-25 00:48:36', '2025-04-25 00:48:47', 'completed'),
(27, 2, '544', 'Computer Application', '2025-04-25 00:50:04', '2025-04-25 00:50:18', 'completed'),
(28, 8, '528', 'Computer Application', '2025-04-25 00:56:37', '2025-04-25 00:56:45', 'completed'),
(29, 7, '542', 'PHP', '2025-04-25 01:00:29', '2025-04-25 01:00:36', 'completed'),
(30, 7, '530', 'Digital Logic & Design', '2025-04-25 01:05:27', '2025-04-25 01:05:36', 'completed'),
(31, 10, '542', 'Systems Integration & Architecture', '2025-04-25 01:06:54', '2025-04-25 01:07:01', 'completed'),
(32, 10, '528', 'Other', '2025-04-25 01:17:37', '2025-04-25 01:17:44', 'completed'),
(33, 4, '524', 'Java Programming', '2025-04-25 09:35:19', '2025-04-25 09:35:26', 'completed'),
(34, 8, '526', 'Computer Application', '2025-04-25 09:52:34', '2025-04-25 09:52:41', 'completed'),
(35, 1, '542', 'Systems Integration & Architecture', '2025-04-25 11:06:18', '2025-04-25 05:06:40', 'completed'),
(36, 3, '528', 'Embedded System % IOT', '2025-04-25 11:14:07', '2025-04-25 05:14:14', 'completed'),
(37, 6, '526', 'Systems Integration & Architecture', '2025-04-25 11:25:55', '2025-04-25 05:26:01', 'completed'),
(38, 9, '530', 'Systems Integration & Architecture', '2025-04-25 11:41:15', '2025-04-25 05:41:21', 'completed'),
(39, 5, '542', 'Database', '2025-04-25 11:48:04', '2025-04-25 11:48:13', 'completed');

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
(1, '524', '1', 'available', '2025-04-24 11:41:14'),
(2, '524', '2', 'available', '2025-04-24 11:41:15'),
(3, '524', '3', 'available', '2025-04-24 11:41:16'),
(4, '517', '1', 'available', '2025-04-24 12:53:50'),
(5, '517', '2', 'available', '2025-04-25 04:03:00'),
(6, '517', '3', 'available', '2025-04-24 12:53:53'),
(7, '517', '4', 'available', '2025-04-25 02:56:24'),
(8, '517', '5', 'available', '2025-04-24 15:14:17'),
(9, '517', '6', 'available', '2025-04-25 02:55:57'),
(10, '517', '7', 'available', '2025-04-24 12:53:57'),
(11, '517', '8', 'available', '2025-04-25 02:13:15'),
(12, '517', '9', 'available', '2025-04-25 01:44:39'),
(13, '517', '10', 'used', '2025-04-24 16:15:55'),
(14, '517', '11', 'used', '2025-04-24 12:54:11'),
(15, '517', '12', 'used', '2025-04-24 12:54:11'),
(16, '517', '13', 'used', '2025-04-24 12:54:14'),
(17, '517', '14', 'used', '2025-04-24 12:54:14'),
(18, '517', '15', 'used', '2025-04-24 14:50:59'),
(19, '517', '16', 'used', '2025-04-24 12:54:10'),
(20, '517', '17', 'used', '2025-04-24 12:54:12'),
(21, '517', '18', 'used', '2025-04-24 12:54:12'),
(22, '517', '19', 'used', '2025-04-24 12:54:12'),
(23, '517', '20', 'used', '2025-04-24 12:54:13'),
(24, '544', '1', 'available', '2025-04-24 16:21:04'),
(25, '544', '2', 'available', '2025-04-24 16:21:10'),
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
(39, '544', '50', 'used', '2025-04-24 12:55:12'),
(40, '544', '49', 'used', '2025-04-24 12:55:11'),
(41, '544', '48', 'used', '2025-04-24 12:55:11'),
(42, '544', '47', 'used', '2025-04-24 12:55:11'),
(43, '544', '46', 'used', '2025-04-24 12:55:10'),
(44, '544', '42', 'used', '2025-04-24 12:55:09'),
(45, '544', '43', 'used', '2025-04-24 12:55:10'),
(46, '544', '44', 'used', '2025-04-24 12:55:08'),
(47, '544', '45', 'used', '2025-04-24 12:55:07'),
(48, '544', '41', 'used', '2025-04-24 12:55:09'),
(49, '544', '36', 'used', '2025-04-24 12:55:04'),
(50, '544', '37', 'used', '2025-04-24 12:55:05'),
(51, '544', '38', 'used', '2025-04-24 12:55:05'),
(52, '544', '39', 'used', '2025-04-24 12:55:07'),
(53, '544', '40', 'used', '2025-04-24 12:55:06'),
(54, '544', '35', 'used', '2025-04-24 12:55:03'),
(55, '544', '34', 'used', '2025-04-24 12:55:03'),
(56, '544', '33', 'used', '2025-04-24 12:55:02'),
(57, '544', '31', 'used', '2025-04-24 12:55:02'),
(58, '544', '32', 'used', '2025-04-24 12:55:02'),
(59, '544', '26', 'available', '2025-04-24 16:21:22'),
(60, '544', '27', 'maintenance', '2025-04-25 04:29:17'),
(61, '544', '28', 'used', '2025-04-24 12:54:59'),
(62, '544', '29', 'used', '2025-04-24 12:55:00'),
(63, '544', '30', 'used', '2025-04-24 12:55:00'),
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
(74, '517', '25', 'used', '2025-04-25 04:03:08'),
(75, '517', '50', 'used', '2025-04-25 03:58:23'),
(77, '524', '4', 'available', '2025-04-24 16:21:32'),
(78, '524', '5', 'available', '2025-04-24 16:21:34'),
(79, '524', '10', 'available', '2025-04-25 04:28:58'),
(80, '524', '9', 'available', '2025-04-24 16:21:34'),
(81, '517', '40', 'used', '2025-04-25 04:03:22'),
(82, '517', '21', 'used', '2025-04-25 04:03:07'),
(83, '517', '22', 'used', '2025-04-25 04:03:07'),
(84, '517', '23', 'used', '2025-04-25 04:03:06'),
(85, '517', '24', 'used', '2025-04-25 04:03:05'),
(86, '517', '26', 'used', '2025-04-25 04:03:11'),
(87, '517', '27', 'used', '2025-04-25 04:03:11'),
(88, '517', '28', 'used', '2025-04-25 04:03:11'),
(89, '517', '32', 'used', '2025-04-25 04:03:12'),
(90, '517', '31', 'used', '2025-04-25 04:03:13'),
(91, '517', '30', 'used', '2025-04-25 04:03:13'),
(92, '517', '29', 'used', '2025-04-25 04:03:14'),
(93, '517', '33', 'used', '2025-04-25 04:03:15'),
(94, '517', '34', 'used', '2025-04-25 04:03:16'),
(95, '517', '35', 'used', '2025-04-25 04:03:16'),
(96, '517', '36', 'used', '2025-04-25 04:03:17'),
(97, '517', '37', 'used', '2025-04-25 04:03:18'),
(98, '517', '38', 'used', '2025-04-25 04:03:19'),
(99, '517', '39', 'used', '2025-04-25 04:03:20'),
(100, '517', '44', 'used', '2025-04-25 05:07:22'),
(101, '517', '43', 'used', '2025-04-25 04:03:27'),
(102, '517', '42', 'used', '2025-04-25 04:03:27'),
(103, '517', '41', 'available', '2025-04-25 04:03:28'),
(104, '517', '45', 'available', '2025-04-25 04:03:29'),
(105, '517', '47', 'used', '2025-04-25 04:03:30'),
(106, '517', '46', 'available', '2025-04-25 04:03:30'),
(107, '517', '48', 'available', '2025-04-25 04:03:31'),
(108, '517', '49', 'available', '2025-04-25 04:03:31'),
(109, '528', '50', 'used', '2025-04-25 04:13:14');

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

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `lab_room`, `pc_number`, `purpose`, `reservation_date`, `time_in`, `time_out`, `status`, `disapproval_reason`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 1, '544', '20', 'Database', '2025-04-27', '09:00:00', NULL, 'completed', NULL, 1, '2025-04-25 02:35:07', '2025-04-25 02:46:47'),
(2, 11, '517', '4', 'Project Management', '2025-04-28', '15:00:00', '04:56:24', 'completed', NULL, 1, '2025-04-25 02:55:29', '2025-04-25 02:56:24'),
(3, 1, '544', '27', 'Other', '2025-04-28', '08:00:00', NULL, 'approved', NULL, 1, '2025-04-25 02:59:57', '2025-04-25 03:00:41'),
(4, 1, '517', '9', 'Systems Integration & Architecture', '2025-04-26', '07:00:00', NULL, 'disapproved', 'Conflicting schedule', 1, '2025-04-25 04:23:27', '2025-04-25 04:28:30'),
(5, 2, '524', '10', 'Embedded System & IOT', '2025-04-26', '08:00:00', NULL, 'approved', NULL, 1, '2025-04-25 04:24:06', '2025-04-25 04:28:53'),
(6, 3, '544', '27', 'Computer Application', '2025-04-30', '09:00:00', NULL, 'approved', NULL, 1, '2025-04-25 04:24:49', '2025-04-25 04:29:17'),
(7, 5, '517', '44', 'Computer Application', '2025-04-30', '11:00:00', NULL, 'approved', NULL, 1, '2025-04-25 04:25:31', '2025-04-25 04:44:13');

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
(8, 2, 7, 3, 'HAHAHAHAHHA', '2025-03-13 17:42:26');

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
(1, 24),
(2, 27),
(3, 28),
(4, 29),
(5, 26),
(6, 29),
(7, 27),
(8, 27),
(9, 29),
(10, 27),
(11, 29);

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
(1, '22692693', 'Does', 'John', 'D.', 'BSCS', '1st Year', 'Talisay City', 'uploadimg/profile_67dd6e4380901.jpg', 'doe@gmail.com', 'doe123@J'),
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
  MODIFY `sit_in_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `lab_computers`
--
ALTER TABLE `lab_computers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `lab_schedules`
--
ALTER TABLE `lab_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sit_in_ratings`
--
ALTER TABLE `sit_in_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student_points`
--
ALTER TABLE `student_points`
  MODIFY `point_id` int(11) NOT NULL AUTO_INCREMENT;

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
