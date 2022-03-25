-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2022 at 03:07 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tvstestjob`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `message` longtext DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `blog_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `upvote` int(11) DEFAULT NULL,
  `downvote` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `parent_id`, `message`, `user_id`, `blog_id`, `status`, `upvote`, `downvote`, `created_at`, `updated_at`) VALUES
(1, 2, 'test', 1, 1, 1, 8, NULL, '2022-03-13 10:42:15', NULL),
(2, NULL, 'nasi', 1, 1, 1, 1, NULL, '2022-03-11 21:27:43', '2022-03-11 16:57:51'),
(4, NULL, 'comment7', 1, 1, 1, 7, NULL, NULL, NULL),
(5, NULL, 'naseema', 1, 1, 1, NULL, NULL, '2022-03-11 14:18:30', NULL),
(6, 2, 'reply', 1, NULL, 3, NULL, NULL, '2022-03-14 05:57:53', NULL),
(7, 2, 'ghjjhgjghjhj', 2, NULL, 2, 5, 3, '2022-03-14 06:14:47', NULL),
(8, 2, 'naseema', 2, NULL, 2, 9, NULL, '2022-03-14 07:12:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `comment_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 2, 1, '2022-03-14 06:38:35', '0000-00-00 00:00:00'),
(10, 2, 1, '2022-03-14 06:39:20', '0000-00-00 00:00:00'),
(11, 2, 1, '2022-03-14 06:39:52', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `moderator` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `moderator`, `created_at`, `updated_at`) VALUES
(1, 'nasi', 'nasi@gmail.com', '$2y$10$QvDEPNT2SE6T814hFOZ5feq/bBgpEQ4Z8XNRuFO0hk3HXDnP8JVJi', 1, NULL, NULL),
(2, 'nasi', 'nasinew@gmail.com', '$2y$10$VqSnUmcsxvmJk.68Qp193OwuFssmYcYuh5gneeEnVS7XY14XO2kx.', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
