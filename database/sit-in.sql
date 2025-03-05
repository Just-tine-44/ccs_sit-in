-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2025 at 06:42 AM
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
(1, 'admin', 'admin123');

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
(6, 'CCS-Admin', '2025-03-05 02:52:47', 'Attention students and faculty! 🎉 We are excited to introduce the Sit-in Lab System, designed to streamline the sit-in process for laboratory sessions.');

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
(6, 30);

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
(5, '33669944', 'Tatums', 'Lebron', 'L.', 'BSCompE', '2nd Year', 'Cebu, Boston', 'uploadimg/profile_67bfdabe507b0.jpg', 'tatum@gmail.com', '123'),
(6, '11556677', 'Doncics', 'Maxie', 'D.', 'BSCS', '1st Year', 'Sibonga, Cebu', 'images/person.jpg', 'max@gmail.com', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

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
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stud_session`
--
ALTER TABLE `stud_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stud_session`
--
ALTER TABLE `stud_session`
  ADD CONSTRAINT `stud_session_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
