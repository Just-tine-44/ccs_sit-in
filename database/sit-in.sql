-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 06:22 AM
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
(4, 30);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `idno` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `midname` varchar(50) NOT NULL,
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
(1, '22692693', 'Doe', 'John', 'A.', 'bsit', '3', 'Talisay City, Cebu', 'uploadimg/profile_67b9fe602d5b9.jpg', 'doe@gmail.com', '123'),
(2, '21864648', 'Smith', 'David', 'C.', 'bsit', '4', 'Pahina, Cebu City', 'uploadimg/dog.jpg', 'smith@gmail.com', '123'),
(3, '48965754', 'Major', 'Mary', 'L.', 'bscs', '1', 'Cordova, lapu-lapu', 'uploadimg/bird.jpg', 'mary@gmail.com', '123'),
(4, '22889977', 'Michael', 'Bron', 'C.', 'BSEd', '1', 'Talisay City, Cebu', 'uploadimg/panda.jpg', 'mic@gmail.com', '$2y$10$NzuQBLCsFSVIqnOwMimST.Q8T11SH5Nygkeayg.IlvGKbZ2BCvq9W');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
