-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2024 at 12:57 PM
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
-- Database: `focus-user-data`
--

-- --------------------------------------------------------

--
-- Table structure for table `login-info`
--

CREATE TABLE `login-info` (
  `userid` int(8) NOT NULL,
  `username` varchar(12) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(12) NOT NULL,
  `coins` int(8) NOT NULL DEFAULT 20,
  `inventory` varchar(1000) NOT NULL,
  `settings` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login-info`
--

INSERT INTO `login-info` (`userid`, `username`, `email`, `password`, `coins`, `inventory`, `settings`) VALUES
(1, 'admin', 'admin@tony.com', '3012', 20, '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login-info`
--
ALTER TABLE `login-info`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login-info`
--
ALTER TABLE `login-info`
  MODIFY `userid` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
