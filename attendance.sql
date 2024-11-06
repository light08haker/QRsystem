-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 03:42 PM
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
-- Database: `attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `timein`
--

CREATE TABLE `timein` (
  `id` int(11) NOT NULL,
  `student_id` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `department` varchar(250) NOT NULL,
  `year` varchar(250) NOT NULL,
  `section` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `qr` varchar(250) NOT NULL,
  `timeIn` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timein`
--

INSERT INTO `timein` (`id`, `student_id`, `name`, `department`, `year`, `section`, `email`, `qr`, `timeIn`) VALUES
(6, '21-0000', 'De Vera, Madison D.', 'BSCS', '4th', 'N/A', '', 'f9Vche0HB2', '2024-09-18 08:23:05'),
(7, '21-0000', 'De Vera, Madison D.', 'BSCS', '4th', 'N/A', '', 'f9Vche0HB2', '2024-09-19 07:58:37'),
(8, '21-0000', 'De Vera, Madison D.', 'BSCS', '4th', 'N/A', '', 'f9Vche0HB2', '2024-09-21 06:19:57'),
(11, '21-0000', 'De Vera, Madison D.', 'BSCS', '4th', 'N/A', 'Madisondevera08@gmail.com', 'zj45VIvve8', '2024-09-27 19:22:41'),
(12, '21-0000', 'De Vera, Madison D.', 'BSCS', '4th', 'N/A', 'Madisondevera08@gmail.com', '2b4cDMFIN3', '2024-09-27 19:25:56'),
(13, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 00:31:09'),
(14, '21-0000a', 'Madison D. De Vera', 'BSCS', '4th', 'N/A', 'mldevera1035@gmail.com', 'uSmACQjQXr', '2024-09-28 07:36:32'),
(17, '21-0000', 'De Vera, Madison D.', 'BSCS', '4th', 'N/A', 'Madisondevera08@gmail.com', 'zzrAysS0je', '2024-09-29 05:57:08'),
(18, '21-0000', 'De Vera, Madison D.', 'BSCS', '4th', 'N/A', 'Madisondevera08@gmail.com', 'ah7VPZx0yU', '2024-10-01 14:52:50'),
(19, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-10-01 15:33:34'),
(20, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-10-01 16:00:27'),
(22, '21-0000', 'De Vera, Madison D.', 'BSCS', '4th', 'N/A', 'Madisondevera08@gmail.com', 'ah7VPZx0yU', '2024-10-04 13:51:00'),
(23, '21-0000', 'De Vera, Madison D.', 'BSCS', '4th', 'N/A', 'Madisondevera08@gmail.com', 'ah7VPZx0yU', '2024-10-05 08:28:24'),
(40, '21-0000', 'Madison D. De Vera', 'BSCS', '4th', 'N/A', 'Madisondevera08@gmail.com', 'lT9JOiV8DW', '2024-10-11 13:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `timeout`
--

CREATE TABLE `timeout` (
  `id` int(11) NOT NULL,
  `student_id` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `department` varchar(250) NOT NULL,
  `year` varchar(250) NOT NULL,
  `section` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `qr` varchar(250) NOT NULL,
  `timeout` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeout`
--

INSERT INTO `timeout` (`id`, `student_id`, `name`, `department`, `year`, `section`, `email`, `qr`, `timeout`) VALUES
(6, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:05:33'),
(7, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:05:47'),
(8, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:09:23'),
(9, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:10:47'),
(10, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:21'),
(11, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:23'),
(12, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:24'),
(13, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:25'),
(14, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:27'),
(15, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:28'),
(16, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:29'),
(17, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:30'),
(18, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:32'),
(19, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:33'),
(20, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:34'),
(21, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:36'),
(22, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:37'),
(23, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:38'),
(24, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:40'),
(25, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:41'),
(26, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:43'),
(27, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:44'),
(28, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:45'),
(29, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:47'),
(31, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:49'),
(32, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:51'),
(33, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:52'),
(34, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:54'),
(35, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:55'),
(36, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:56'),
(37, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:58'),
(38, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:11:59'),
(39, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:12:00'),
(40, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:12:02'),
(41, '21-5070', 'Shon Kieron Andaya', 'BSCS', '4th', 'N/A', 'shonandaya@gmail.com', 'AA3f5C9RDi', '2024-09-28 08:12:03'),
(42, '21-0000', 'De Vera, Madison D.', 'BSCS', '4th', 'N/A', 'Madisondevera08@gmail.com', 'zzrAysS0je', '2024-09-28 08:22:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `student_id` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `department` varchar(250) NOT NULL,
  `year` varchar(250) NOT NULL,
  `section` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `qr` varchar(250) NOT NULL,
  `user_type` varchar(250) NOT NULL DEFAULT 'user',
  `token` varchar(250) DEFAULT NULL,
  `tokenexpire` date DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `student_id`, `name`, `department`, `year`, `section`, `email`, `password`, `qr`, `user_type`, `token`, `tokenexpire`, `last_updated`) VALUES
(64, '21-0000', 'Madison D. De Vera', 'BSCS', '4th', 'N/A', 'Madisondevera08@gmail.com', 'a', 'lT9JOiV8DW', 'user', NULL, NULL, '2024-10-05 18:20:13'),
(65, '3456', 'gc', 'BSCS', '1st', 'N/A', 'mldevera1035@gmail.com', 'a', '9ZWwp5d3kA', 'user', NULL, NULL, '2024-10-05 17:55:27'),
(66, '45678', 'De Vera, Madison D.', 'BSCS', '1st', 'alc', 'mldevera1035@gmail.combb', 'hjgc', 'sHOL1Iq7is', 'user', NULL, NULL, '2024-10-05 17:55:27'),
(67, '21-0000a', 'credential_system', 'BSCS', '2nd', 'a', 'busrfygbsir@dsfjhsdfc', 'a', 'hqvYUV7dxw', 'user', NULL, NULL, '2024-10-14 10:43:11'),
(68, '21-0000aa', 'light08_1', 'BSCS', '1st', 'alc', 'm@m', 'a', 'nQIxm3l6tG', 'user', NULL, NULL, '0000-00-00 00:00:00'),
(69, '8236743', 'De Vera, Madison D.', 'BSCS', '2nd', 'alcatraz', 'f@a', 'a', 'yju181eSw3', 'user', NULL, NULL, '0000-00-00 00:00:00'),
(71, 'a', '', '', '', '', '', 'a', '', 'admin', NULL, NULL, '2024-10-05 18:26:15'),
(72, 'aa', '', '', '', '', 'aa@a', 'a', '', 'admin2', NULL, NULL, '2024-10-11 22:10:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timein`
--
ALTER TABLE `timein`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeout`
--
ALTER TABLE `timeout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timein`
--
ALTER TABLE `timein`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `timeout`
--
ALTER TABLE `timeout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
