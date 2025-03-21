-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2025 at 01:58 AM
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
(2, 'CCS-Admin', '2025-03-04 17:38:40', 'System maintenance is scheduled for this Friday from 8:00 PM to 10:00 PM.'),
(3, 'CCS-Admin', '2025-03-04 17:40:12', 'All users must update their profiles with their latest student ID numbers.'),
(4, 'CCS-Admin', '2025-03-04 17:41:05', 'Reminder: All students must log their sit-in sessions properly.'),
(5, 'CCS-Admin', '2025-03-04 17:41:14', 'Sit-in schedules for next week are now available. Book your slots in advance. Thank You.'),
(6, 'CCS-Admin', '2025-03-05 02:52:47', 'Attention students and faculty! 🎉 We are excited to introduce the Sit-in Lab System, designed to streamline the sit-in process for laboratory sessions. Thank u'),
(7, 'CCS-Admin', '2025-03-11 04:55:08', 'Goodluck CSS. Thanks'),
(8, 'CCS-Admin', '2025-03-11 11:24:37', 'Attention students and faculty! 🎉 We are thrilled to announce the launch of the Sit-in Lab System, created to simplify and enhance the process of attending laboratory sessions. Thank you for your support!'),
(9, 'CCS-Admin', '2025-03-13 04:03:13', 'GOODLUCK, TIDERT');

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
(19, 10, '530', 'C#', '2025-03-21 07:38:11', '2025-03-21 07:42:17', 'completed');

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
(1, 30),
(2, 30),
(3, 30),
(4, 30),
(5, 30),
(6, 30),
(7, 30),
(8, 30),
(9, 30),
(10, 30),
(11, 30);

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
(1, '22692693', 'Does', 'John', 'D.', 'BSCS', '1st Year', 'Talisay City', 'uploadimg/profile_67bd972925704.jpg', 'doe@gmail.com', '123'),
(2, '21864648', 'Smith', 'David', 'C.', 'BSIT', '4th Year', 'Cordova, Lapu-lapu', 'uploadimg/dog.jpg', 'smith@gmail.com', '123'),
(3, '48965754', 'Major', 'Mary', 'L.', 'BSIT', '1st Year', 'Pahina, Cebu City', 'uploadimg/profile_67bd9bc827a4e.jpg', 'mary@gmail.com', '123'),
(4, '22889977', 'Michaels', 'Bron', 'C.', 'BSCS', '1st Year', 'Basak, Pardo', 'uploadimg/panda.jpg', 'mic@gmail.com', '$2y$10$NzuQBLCsFSVIqnOwMimST.Q8T11SH5Nygkeayg.IlvGKbZ2BCvq9W'),
(5, '33669944', 'Tatum', 'Lebron', 'L.', 'BSCompE', '2nd Year', 'Cebu, Boston', 'uploadimg/profile_67bfdabe507b0.jpg', 'tatum@gmail.com', 'Piggy123@'),
(6, '11556677', 'Doncics', 'Maxie', 'D.', 'BSCS', '1st Year', 'Sibonga, Cebu', 'images/person.jpg', 'max@gmail.com', '123'),
(7, '22596886', 'PaldoGodz', 'Rovic', 'T.', 'BSCompE', '2nd Year', 'Pahina, Cebu City', NULL, 'rovic@gmail.com', 'Paldo123@'),
(8, '55442211', 'Steph', 'Kevin', 'B.', 'AB PolSci', '1', 'Mandaue Cebu', NULL, 'step@gmail.com', '123'),
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
-- Indexes for table `sit_in_ratings`
--
ALTER TABLE `sit_in_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `sit_in_id` (`sit_in_id`),
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
  MODIFY `sit_in_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sit_in_ratings`
--
ALTER TABLE `sit_in_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- Constraints for table `sit_in_ratings`
--
ALTER TABLE `sit_in_ratings`
  ADD CONSTRAINT `sit_in_ratings_ibfk_1` FOREIGN KEY (`sit_in_id`) REFERENCES `curr_sit_in` (`sit_in_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sit_in_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stud_session`
--
ALTER TABLE `stud_session`
  ADD CONSTRAINT `stud_session_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
