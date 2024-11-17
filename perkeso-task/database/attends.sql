-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 08, 2023 at 05:16 PM
-- Server version: MySQL
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attends`
--

-- --------------------------------------------------------

--
-- Table structure for table `Attendance`
--

CREATE TABLE `Attendance` (
  `att_id` int(10) NOT NULL,
  `att_date` date NOT NULL,
  `staff_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Attendance`
--

INSERT INTO `Attendance` (`att_id`, `att_date`, `staff_id`) VALUES
(1, '2022-02-03', 5312),
(3, '2022-02-03', 6945),
(2, '2022-02-03', 7721),
(4, '2022-02-03', 9514),
(6, '2022-02-04', 5312),
(7, '2022-02-04', 6722),
(5, '2022-02-04', 6945),
(8, '2022-02-04', 7721),
(9, '2022-02-05', 6945),
(10, '2022-02-05', 9514);

-- --------------------------------------------------------

--
-- Table structure for table `Dept`
--

CREATE TABLE `Dept` (
  `dept_id` int(5) NOT NULL,
  `dept_name` varchar(20) NOT NULL,
  `dept_status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Dept`
--

INSERT INTO `Dept` (`dept_id`, `dept_name`, `dept_status`) VALUES
(21, 'ICT', 0),
(22, 'Pentadbiran', 0),
(23, 'Transformasi', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE `Staff` (
  `staff_id` int(10) NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `staff_dept` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Staff`
--

INSERT INTO `Staff` (`staff_id`, `staff_name`, `staff_dept`) VALUES
(5312, 'Tuah', 21),
(6722, 'Arjuna', 23),
(6945, 'Melur', 21),
(7721, 'Sakti', 23),
(9514, 'Kesuma', 22);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Attendance`
--
ALTER TABLE `Attendance`
  ADD PRIMARY KEY (`att_id`),
  ADD UNIQUE KEY `att_date` (`att_date`,`staff_id`);

--
-- Indexes for table `Dept`
--
ALTER TABLE `Dept`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- AUTO_INCREMENT for table `Attendance`
--
ALTER TABLE `Attendance`
  MODIFY `att_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
