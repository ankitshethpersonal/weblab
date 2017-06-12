-- phpMyAdmin SQL Dump
-- version 4.6.4deb1+deb.cihar.com~wily.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 12, 2017 at 10:05 PM
-- Server version: 5.7.16-0ubuntu0.16.04.1
-- PHP Version: 7.0.15-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `weblab`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `role_id` int(1) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL,
  `salt` text NOT NULL,
  `token` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='for system users';

-- --------------------------------------------------------

--
-- Table structure for table `system_users`
--

CREATE TABLE `system_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_users`
--

INSERT INTO `system_users` (`id`, `username`, `status`, `created`) VALUES
(1, 'ankit.sheth', 1, '2017-06-10 13:33:20'),
(2, 'webtest', 1, '2017-06-10 13:33:20');

-- --------------------------------------------------------

--
-- Table structure for table `system_user_timings`
--

CREATE TABLE `system_user_timings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `login_date` date NOT NULL,
  `login_time` time NOT NULL,
  `is_late` tinyint(1) NOT NULL DEFAULT '0',
  `late_by_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_user_timings`
--

INSERT INTO `system_user_timings` (`id`, `user_id`, `created`, `login_date`, `login_time`, `is_late`, `late_by_time`) VALUES
(1, 1, '2017-06-10 15:04:46', '2017-06-09', '15:04:00', 1, '10:04:00'),
(2, 1, '2017-06-10 15:05:03', '2017-06-10', '15:05:00', 1, '10:05:00'),
(3, 1, '2017-06-11 01:33:22', '2017-06-11', '01:33:00', 0, '00:00:00'),
(4, 2, '2017-06-11 01:36:19', '2017-06-11', '01:36:00', 0, '00:00:00'),
(5, 2, '2017-06-12 11:00:11', '2017-06-12', '11:00:00', 1, '06:00:00'),
(6, 1, '2017-06-12 21:39:15', '2017-06-12', '21:39:00', 1, '16:39:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_users`
--
ALTER TABLE `system_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_user_timings`
--
ALTER TABLE `system_user_timings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`login_date`,`login_time`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `system_users`
--
ALTER TABLE `system_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `system_user_timings`
--
ALTER TABLE `system_user_timings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
