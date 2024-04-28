-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2024 at 05:30 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydata`
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
  `settings` varchar(500) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login-info`
--

INSERT INTO `login-info` (`userid`, `username`, `email`, `password`, `coins`, `inventory`, `settings`, `last_update`) VALUES
(1, 'admin', 'admin@tony.com', '3012', 15, '', '', '2024-04-26 15:20:19'),
(5, 'zxz', '1223', '200211', 45, '1;3', '', '2024-04-28 03:23:52'),
(6, 'moon', '250', '111111', 20, '', '', '2024-04-26 15:20:19');

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
  MODIFY `userid` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
