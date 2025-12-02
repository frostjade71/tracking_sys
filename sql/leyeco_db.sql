-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Dec 02, 2025 at 12:48 PM
-- Server version: 8.0.44
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leyeco_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `user_id` int DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `action`, `details`, `user_id`, `ip_address`, `created_at`) VALUES
(1, 'LOGIN_FAILED', 'Failed login attempt for admin@example.com', NULL, '172.20.0.1', '2025-12-02 04:15:16'),
(2, 'LOGIN_FAILED', 'Failed login attempt for admin@example.com', NULL, '172.20.0.1', '2025-12-02 04:15:22'),
(3, 'REPORT_CREATED', 'Report LEY-20251202-0001 created', NULL, '172.20.0.1', '2025-12-02 04:15:37'),
(4, 'LOGIN_FAILED', 'Failed login attempt for admin@example.com', NULL, '172.20.0.1', '2025-12-02 04:23:54'),
(5, 'LOGIN_FAILED', 'Failed login attempt for operator@example.com', NULL, '172.20.0.1', '2025-12-02 04:24:11'),
(6, 'LOGIN_FAILED', 'Failed login attempt for operator@example.com', NULL, '172.20.0.1', '2025-12-02 04:25:16'),
(7, 'LOGIN_FAILED', 'Failed login attempt for admin@example.com', NULL, '172.20.0.1', '2025-12-02 04:25:29'),
(8, 'LOGIN_FAILED', 'Failed login attempt for admin@example.com', NULL, '172.20.0.1', '2025-12-02 04:28:24'),
(9, 'LOGIN_FAILED', 'Failed login attempt for admin@example.com', NULL, '172.20.0.1', '2025-12-02 04:28:39'),
(10, 'LOGIN_FAILED', 'Failed login attempt for admin@example.com', NULL, '172.20.0.1', '2025-12-02 04:30:01'),
(11, 'LOGIN_FAILED', 'Failed login attempt for admin@example.com', NULL, '172.20.0.1', '2025-12-02 04:30:23'),
(12, 'LOGIN_FAILED', 'Failed login attempt for jaderzkiepenaranda@gmail.com', NULL, '172.20.0.1', '2025-12-02 04:32:53'),
(13, 'LOGIN_FAILED', 'Failed login attempt for jaderzkiepenaranda@gmail.com', NULL, '172.20.0.1', '2025-12-02 04:32:59'),
(14, 'LOGIN_FAILED', 'Failed login attempt for jaderzkiepenaranda@gmail.com', NULL, '172.20.0.1', '2025-12-02 04:35:04'),
(15, 'LOGIN_FAILED', 'Failed login attempt for jaderzkiepenaranda@gmail.com', NULL, '172.20.0.1', '2025-12-02 04:35:12'),
(16, 'LOGIN_FAILED', 'Failed login attempt for jaderzkiepenaranda@gmail.com', NULL, '172.20.0.1', '2025-12-02 04:35:17'),
(17, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 04:35:20'),
(18, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 04:38:06'),
(19, 'LOGIN_FAILED', 'Failed login attempt for jaderzkiepenaranda@gmail.com', NULL, '172.20.0.1', '2025-12-02 04:38:11'),
(20, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 04:38:15'),
(21, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 04:38:37'),
(22, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 04:41:54'),
(23, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 04:42:20'),
(24, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 05:02:00'),
(25, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 05:08:46'),
(26, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 05:08:58'),
(27, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 05:14:37'),
(28, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 05:14:47'),
(29, 'REPORT_STATUS_UPDATED', 'Report LEY-20251202-0001 status changed to CLOSED', 3, '172.20.0.1', '2025-12-02 05:16:32'),
(30, 'REPORT_STATUS_UPDATED', 'Report LEY-20251202-0001 status changed to RESOLVED', 3, '172.20.0.1', '2025-12-02 05:16:44'),
(31, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 05:17:01'),
(32, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 05:18:31'),
(33, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 05:18:33'),
(34, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 05:24:46'),
(35, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 05:29:23'),
(36, 'REPORT_CREATED', 'Report LEY-20251202-0002 created', NULL, '172.20.0.1', '2025-12-02 05:40:29'),
(37, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 05:40:59'),
(38, 'REPORT_STATUS_UPDATED', 'Report LEY-20251202-0002 status changed to INVESTIGATING', 3, '172.20.0.1', '2025-12-02 05:41:48'),
(39, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 05:41:58'),
(40, 'REPORT_CREATED', 'Report LEY-20251202-0003 created', NULL, '172.20.0.1', '2025-12-02 05:43:45'),
(41, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 05:44:10'),
(42, 'REPORT_STATUS_UPDATED', 'Report LEY-20251202-0003 status changed to RESOLVED', 3, '172.20.0.1', '2025-12-02 05:44:56'),
(43, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 05:45:03'),
(44, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 05:46:02'),
(45, 'REPORT_STATUS_UPDATED', 'Report LEY-20251202-0003 status changed to CLOSED', 3, '172.20.0.1', '2025-12-02 05:46:49'),
(46, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 05:46:54'),
(47, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 05:47:50'),
(48, 'USER_CREATED', 'User jeric@gmail.com created with role OPERATOR', 3, '172.20.0.1', '2025-12-02 06:38:40'),
(49, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 06:38:46'),
(50, 'USER_LOGIN', 'User jeric@gmail.com logged in', NULL, '172.20.0.1', '2025-12-02 06:38:54'),
(51, 'USER_LOGOUT', 'User logged out', NULL, '172.20.0.1', '2025-12-02 06:39:35'),
(52, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 06:42:29'),
(53, 'USER_ROLE_UPDATED', 'User jeric@gmail.com role changed to ADMIN', 3, '172.20.0.1', '2025-12-02 06:42:39'),
(54, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 06:42:45'),
(55, 'USER_LOGIN', 'User jeric@gmail.com logged in', NULL, '172.20.0.1', '2025-12-02 06:42:53'),
(56, 'USER_LOGOUT', 'User logged out', NULL, '172.20.0.1', '2025-12-02 06:43:18'),
(57, 'REPORT_CREATED', 'Report LEY-20251202-0004 created', NULL, '172.20.0.1', '2025-12-02 07:06:28'),
(58, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 07:07:11'),
(59, 'REPORT_STATUS_UPDATED', 'Report LEY-20251202-0004 status changed to INVESTIGATING', 3, '172.20.0.1', '2025-12-02 07:07:38'),
(60, 'REPORT_STATUS_UPDATED', 'Report LEY-20251202-0004 status changed to INVESTIGATING', 3, '172.20.0.1', '2025-12-02 07:07:53'),
(61, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 07:08:08'),
(62, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 07:10:05'),
(63, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 07:10:23'),
(64, 'USER_LOGIN', 'User jeric@gmail.com logged in', NULL, '172.20.0.1', '2025-12-02 07:10:36'),
(65, 'USER_CREATED', 'User lorenmae@gmail.com created with role OPERATOR', NULL, '172.20.0.1', '2025-12-02 07:12:18'),
(66, 'USER_LOGOUT', 'User logged out', NULL, '172.20.0.1', '2025-12-02 07:12:40'),
(67, 'LOGIN_FAILED', 'Failed login attempt for lorenmae@gmail.com', NULL, '172.20.0.1', '2025-12-02 07:12:49'),
(68, 'LOGIN_FAILED', 'Failed login attempt for lorenmae@gmail.com', NULL, '172.20.0.1', '2025-12-02 07:12:52'),
(69, 'LOGIN_FAILED', 'Failed login attempt for lorenmae@gmail.com', NULL, '172.20.0.1', '2025-12-02 07:13:01'),
(70, 'LOGIN_FAILED', 'Failed login attempt for lorenmae@gmail.com', NULL, '172.20.0.1', '2025-12-02 07:13:40'),
(71, 'LOGIN_FAILED', 'Failed login attempt for lorenmae@gmail.com', NULL, '172.20.0.1', '2025-12-02 07:13:44'),
(72, 'LOGIN_FAILED', 'Failed login attempt for lorenmae@gmail.com', NULL, '172.20.0.1', '2025-12-02 07:13:56'),
(73, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 07:14:16'),
(74, 'USER_CREATED', 'User lorenmae@gmail.com created with role OPERATOR', 3, '172.20.0.1', '2025-12-02 07:14:50'),
(75, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 07:15:00'),
(76, 'USER_LOGIN', 'User lorenmae@gmail.com logged in', NULL, '172.20.0.1', '2025-12-02 07:15:08'),
(77, 'USER_LOGOUT', 'User logged out', NULL, '172.20.0.1', '2025-12-02 07:15:57'),
(78, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 07:16:18'),
(79, 'USER_ROLE_UPDATED', 'User jeric@gmail.com role changed to ADMIN', 3, '172.20.0.1', '2025-12-02 07:16:36'),
(80, 'REPORT_STATUS_UPDATED', 'Report LEY-20251202-0004 status changed to RESOLVED', 3, '172.20.0.1', '2025-12-02 07:20:32'),
(81, 'REPORT_ASSIGNED', 'Report LEY-20251202-0004 assigned to user 2', 3, '172.20.0.1', '2025-12-02 07:21:04'),
(82, 'REPORT_STATUS_UPDATED', 'Report LEY-20251202-0004 status changed to RESOLVED', 3, '172.20.0.1', '2025-12-02 07:21:11'),
(83, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 07:21:12'),
(84, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 07:22:32'),
(85, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 07:26:31'),
(86, 'REPORT_CREATED', 'Report LEY-20251202-0005 created', NULL, '172.20.0.1', '2025-12-02 08:08:23'),
(87, 'REPORT_CREATED', 'Report LEY-20251202-0006 created', NULL, '172.20.0.1', '2025-12-02 08:08:31'),
(88, 'REPORT_CREATED', 'Report LEY-20251202-0007 created', NULL, '172.20.0.1', '2025-12-02 08:08:38'),
(89, 'REPORT_CREATED', 'Report LEY-20251202-0008 created', NULL, '172.20.0.1', '2025-12-02 08:10:24'),
(90, 'REPORT_CREATED', 'Report LEY-20251202-0009 created', NULL, '172.20.0.1', '2025-12-02 08:10:33'),
(91, 'REPORT_CREATED', 'Report LEY-20251202-0010 created', NULL, '172.20.0.1', '2025-12-02 08:13:00'),
(92, 'REPORT_CREATED', 'Report LEY-20251202-0011 created', NULL, '172.20.0.1', '2025-12-02 08:14:32'),
(93, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 10:48:33'),
(94, 'USER_DELETED', 'User lorenmae@gmail.com deleted', 3, '172.20.0.1', '2025-12-02 10:50:00'),
(95, 'USER_DELETED', 'User jeric@gmail.com deleted', 3, '172.20.0.1', '2025-12-02 10:50:03'),
(96, 'USER_DELETED', 'User admin@example.com deleted', 3, '172.20.0.1', '2025-12-02 10:50:20'),
(97, 'USER_DELETED', 'User operator@example.com deleted', 3, '172.20.0.1', '2025-12-02 10:50:24'),
(98, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 10:50:40'),
(99, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 10:52:26'),
(100, 'USER_CREATED', 'User jeric@gmail.com created with role OPERATOR', 3, '172.20.0.1', '2025-12-02 10:54:09'),
(101, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 10:54:28'),
(102, 'LOGIN_FAILED', 'Failed login attempt for jeric@gmail.com', NULL, '172.20.0.1', '2025-12-02 10:54:35'),
(103, 'USER_LOGIN', 'User jeric@gmail.com logged in', 7, '172.20.0.1', '2025-12-02 10:54:38'),
(104, 'USER_LOGOUT', 'User logged out', 7, '172.20.0.1', '2025-12-02 10:54:56'),
(105, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 10:55:11'),
(106, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 10:55:59'),
(107, 'REPORT_CREATED', 'Report LEY-20251202-0001 created', NULL, '172.20.0.1', '2025-12-02 10:56:46'),
(108, 'USER_LOGIN', 'User jeric@gmail.com logged in', 7, '172.20.0.1', '2025-12-02 10:57:13'),
(109, 'REPORT_STATUS_UPDATED', 'Report LEY-20251202-0001 status changed to INVESTIGATING', 7, '172.20.0.1', '2025-12-02 11:17:42'),
(110, 'USER_LOGOUT', 'User logged out', 7, '172.20.0.1', '2025-12-02 12:31:12'),
(111, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 12:31:18'),
(112, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 12:35:43'),
(113, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 12:36:03'),
(114, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 12:42:20'),
(115, 'USER_LOGIN', 'User jaderzkiepenaranda@gmail.com logged in', 3, '172.20.0.1', '2025-12-02 12:43:02'),
(116, 'USER_LOGOUT', 'User logged out', 3, '172.20.0.1', '2025-12-02 12:43:52');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `report_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `report_id`, `user_id`, `message`, `created_at`) VALUES
(24, 12, NULL, 'Report submitted', '2025-12-02 10:56:46'),
(25, 12, 7, 'Status changed to: Under Investigation', '2025-12-02 11:17:42'),
(28, 12, 7, 'otw', '2025-12-02 11:19:02'),
(29, 12, 7, 'otw', '2025-12-02 11:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int NOT NULL,
  `reference_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reporter_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('OUTAGE','TRANSFORMER_DAMAGE','WIRES_DOWN','HAZARD','OTHER') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('NEW','INVESTIGATING','RESOLVED','CLOSED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NEW',
  `municipality` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_to` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `reference_code`, `reporter_name`, `contact`, `description`, `type`, `status`, `municipality`, `address`, `lat`, `lon`, `photo_path`, `assigned_to`, `created_at`, `updated_at`) VALUES
(12, 'LEY-20251202-0001', 'Jaderby Peñaranda', 'jaderzkiepenaranda@gmail.com', 'Brownout po ha amon', 'OUTAGE', 'INVESTIGATING', 'Barugo', 'Delgado Avenue', 11.3206, 124.737, 'assets/uploads/report_692ec5eec348b7.00729146.png', NULL, '2025-12-02 10:56:46', '2025-12-02 11:17:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('ADMIN','OPERATOR') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OPERATOR',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(3, 'Jaderby', 'jaderzkiepenaranda@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', '2025-12-02 04:32:38'),
(7, 'Jeric', 'jeric@gmail.com', '$2y$10$a9qy2mKS9EQEcrdpScL40ee48eIp6QpF9uaP2BfMMGgo70s7GWeDu', 'OPERATOR', '2025-12-02 10:54:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_report_id` (`report_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_code` (`reference_code`),
  ADD KEY `idx_reference_code` (`reference_code`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_municipality` (`municipality`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
