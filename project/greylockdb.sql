-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 10:21 AM
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
-- Database: `greylockdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(0, 'test', 'test@gmail.com', 'This is a test message!', '2025-05-17 17:42:59');

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `forwarded_ips` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `username`, `status`, `ip_address`, `created_at`, `forwarded_ips`) VALUES
(1, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-17 19:16:49', NULL),
(2, 'as', 'INVALID_CREDENTIALS', '::1', '2025-05-17 19:21:27', NULL),
(3, 'a', 'SUCCESS', '::1', '2025-05-17 19:25:08', NULL),
(4, 'a', 'SUCCESS', '::1', '2025-05-17 19:41:53', NULL),
(5, 'a', 'SUCCESS', '::1', '2025-05-17 21:05:50', NULL),
(6, 'a', 'SUCCESS', '::1', '2025-05-17 21:10:17', NULL),
(7, 'a', 'SUCCESS', '::1', '2025-05-18 11:48:34', NULL),
(8, 'admin', 'SUCCESS', '::1', '2025-05-18 17:30:59', NULL),
(9, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 18:30:56', NULL),
(10, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 18:31:03', NULL),
(11, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 18:31:07', NULL),
(12, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 18:31:11', NULL),
(13, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 18:31:14', NULL),
(14, 'a', 'HONEYPOT_TRIGGERED', '::1', '2025-05-18 18:31:19', NULL),
(15, 'a', 'HONEYPOT_TRIGGERED', '::1', '2025-05-18 18:39:21', NULL),
(16, 'a', 'HONEYPOT_TRIGGERED', '::1', '2025-05-18 18:40:11', NULL),
(17, 'a', 'SUCCESS', '::1', '2025-05-18 18:40:58', NULL),
(18, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:00:36', NULL),
(19, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:00:40', NULL),
(20, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:00:44', NULL),
(21, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:00:48', NULL),
(22, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:00:52', NULL),
(23, 'a', 'HONEYPOT_TRIGGERED', '::1', '2025-05-18 19:00:55', NULL),
(24, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:05:11', NULL),
(25, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:05:15', NULL),
(26, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:05:19', NULL),
(27, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:05:23', NULL),
(28, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:05:27', NULL),
(29, 'a', 'HONEYPOT_TRIGGERED', '::1', '2025-05-18 19:05:32', NULL),
(30, 'a', 'SUCCESS', '::1', '2025-05-18 19:16:13', NULL),
(31, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:17:16', NULL),
(32, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:17:20', NULL),
(33, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:17:24', NULL),
(34, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:17:27', NULL),
(35, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-18 19:17:30', NULL),
(36, 'a', 'HONEYPOT_TRIGGERED', '::1', '2025-05-18 19:17:34', NULL),
(37, 'a', 'SUCCESS', '::1', '2025-05-20 08:32:14', NULL),
(38, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:45:51', NULL),
(39, 'a', 'HONEYPOT_TRIGGERED', '::1', '2025-05-20 08:45:54', NULL),
(40, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:47:28', NULL),
(41, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:47:32', NULL),
(42, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:47:35', NULL),
(43, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:47:38', NULL),
(44, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:47:42', NULL),
(45, 'a', 'HONEYPOT_TRIGGERED', '::1', '2025-05-20 08:47:45', NULL),
(46, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:50:35', NULL),
(47, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:50:38', NULL),
(48, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:50:41', NULL),
(49, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:50:44', NULL),
(50, 'a', 'INVALID_CREDENTIALS', '::1', '2025-05-20 08:50:47', NULL),
(51, 'a', 'HONEYPOT_TRIGGERED', '::1', '2025-05-20 08:50:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `passwords`
--

CREATE TABLE `passwords` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `website_link` varchar(255) NOT NULL,
  `service_username` varchar(100) NOT NULL,
  `encrypted_password` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passwords`
--

INSERT INTO `passwords` (`id`, `user_id`, `service_name`, `website_link`, `service_username`, `encrypted_password`, `created_at`, `updated_at`) VALUES
(0, 1, 'DMU', 'www.dmu.ac.uk', 'p12345', 'O6r5TTioE8X3xrlwDO/ifDo6UXVCRnMvZ3hjNUVuaExhQkZuclVwQT09', '2025-05-17 17:50:09', '2025-05-17 17:50:09'),
(3, 1, 'test1', 'http://test1.com', 'test', '/bZr33TKEMiBvJWFq85G+VBrUUp1SE5OVE9YMUxYTDMzY3Q3MFE9PQ==', '2025-05-05 22:36:52', '2025-05-17 16:13:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `expires`) VALUES
(1, 'a@a.com', 'b09a190f76e7b3308b7c26a686428c758f3c3da87292d2d07db6816d668f9cfa', '0000-00-00 00:00:00', 1746482969),
(0, 'alexmenezes141184@gmail.com', 'b1aca2bdc84c6d947f575a4247defde18f36cf69ecdbfbc0d8837c17a8524126', '0000-00-00 00:00:00', 1747731815);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `updated_at`, `blocked`, `is_admin`) VALUES
(1, 'a', '$2y$10$.rnLdns156rz4oWR9yM5geVlOEn60f8HffvyTzsGr6pJWWTa5UmWm', 'aa@a.com', '2025-05-05 18:12:40', '2025-05-17 20:48:45', 0, 0),
(2, 'b', '$2y$10$5O5a6wCsUoqoMoq.HG3.7OJ19qRklhgCzM/M042idFwQ1P4JS9Xem', 'b@a.com', '2025-05-05 18:42:59', '2025-05-05 18:42:59', 0, 0),
(3, 'q', '$2y$10$bPPxGxJPjduD8UATczM5POgGXbqqM1br0z3RUQudj51ocmhq0e7UG', 's@a.com', '2025-05-05 19:05:44', '2025-05-05 19:05:44', 0, 0),
(0, 'admin', '$2y$10$8q/QPOLge/LhCK8yAd5Ftu5KHcpT6LmnYQYvKbTgrf6Rw7pt1E9FC', 'greylockwebsite@proton.me', '2025-05-18 16:27:48', '2025-05-18 16:28:33', 0, 1),
(0, 'alex', '$2y$10$JhUQCBY9OCfQgBPR4dv4dO/F6OiJRLxVfQSeH4Jg5XH3X4vpaW2Vu', 'alexmenezes141184@gmail.com', '2025-05-20 08:03:03', '2025-05-20 08:03:03', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_logs`
--

CREATE TABLE `user_activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity_logs`
--

INSERT INTO `user_activity_logs` (`id`, `user_id`, `username`, `activity`, `ip_address`, `created_at`) VALUES
(1, 1, 'a', 'Changed email', '::1', '2025-05-17 21:48:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passwords`
--
ALTER TABLE `passwords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
