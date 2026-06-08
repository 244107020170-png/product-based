-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 08, 2026 at 03:28 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spies`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `field_id` bigint UNSIGNED NOT NULL,
  `court_number` int NOT NULL DEFAULT '1',
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_deadline` datetime DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `field_id`, `court_number`, `date`, `start_time`, `end_time`, `status`, `payment_deadline`, `expired_at`, `paid_at`, `confirmed_at`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 1, '2026-05-13', '09:00:00', '10:00:00', 'completed', NULL, NULL, NULL, NULL, '2026-05-08 02:03:29', '2026-06-01 10:25:19'),
(2, 4, 1, 1, '2026-05-26', '10:00:00', '11:00:00', 'completed', '2026-05-25 20:23:09', '2026-05-25 23:53:09', NULL, NULL, '2026-05-25 12:53:09', '2026-06-01 10:25:19'),
(3, 4, 1, 1, '2026-05-26', '09:00:00', '10:00:00', 'completed', '2026-05-25 20:24:35', '2026-05-25 23:54:35', NULL, NULL, '2026-05-25 12:54:35', '2026-06-01 10:25:19'),
(4, 29, 6, 1, '2026-05-24', '10:00:00', '11:00:00', 'completed', NULL, NULL, NULL, NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(5, 29, 7, 1, '2026-05-26', '14:00:00', '16:00:00', 'confirmed', NULL, NULL, NULL, NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(6, 29, 8, 1, '2026-05-28', '09:00:00', '10:00:00', 'confirmed', NULL, NULL, NULL, NULL, '2026-05-25 14:41:44', '2026-05-25 15:05:16'),
(7, 29, 6, 1, '2026-05-23', '16:00:00', '18:00:00', 'cancelled', NULL, NULL, NULL, NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(8, 29, 9, 1, '2026-05-20', '08:00:00', '09:00:00', 'completed', NULL, NULL, NULL, NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(9, 6, 6, 1, '2026-05-27', '10:00:00', '11:00:00', 'confirmed', '2026-05-25 22:34:34', '2026-05-26 02:04:34', NULL, NULL, '2026-05-25 15:04:34', '2026-05-25 15:07:17'),
(11, 4, 2, 1, '2026-05-27', '12:00:00', '13:00:00', 'completed', '2026-05-26 15:41:41', '2026-05-26 19:26:41', '2026-05-26 15:26:59', '2026-05-26 16:24:23', '2026-05-26 08:26:41', '2026-06-01 10:25:19'),
(12, 4, 6, 1, '2026-05-27', '18:00:00', '19:00:00', 'confirmed', '2026-05-26 15:49:32', '2026-05-26 19:34:32', '2026-05-26 15:41:05', '2026-05-26 15:53:18', '2026-05-26 08:34:32', '2026-05-26 08:53:18'),
(13, 4, 9, 1, '2026-05-28', '15:00:00', '16:00:00', 'confirmed', '2026-05-26 15:50:52', '2026-05-26 19:35:52', '2026-05-26 15:40:55', '2026-05-26 15:53:20', '2026-05-26 08:35:52', '2026-05-26 08:53:20'),
(14, 4, 1, 1, '2026-05-27', '19:00:00', '20:00:00', 'completed', '2026-05-26 15:59:12', '2026-05-26 19:44:12', '2026-05-26 15:44:17', '2026-05-26 16:24:21', '2026-05-26 08:44:12', '2026-06-01 10:25:19'),
(15, 4, 1, 1, '2026-05-29', '11:00:00', '12:00:00', 'completed', '2026-05-26 16:03:18', '2026-05-26 19:48:18', '2026-05-26 15:48:22', '2026-05-26 16:24:18', '2026-05-26 08:48:18', '2026-06-01 10:25:19'),
(16, 4, 1, 1, '2026-05-27', '09:00:00', '10:00:00', 'completed', '2026-05-26 16:05:32', '2026-05-26 19:50:32', '2026-05-26 15:50:35', '2026-05-26 16:24:26', '2026-05-26 08:50:32', '2026-06-01 10:25:19'),
(17, 30, 9, 1, '2026-05-28', '10:00:00', '11:00:00', 'confirmed', '2026-05-26 19:52:19', '2026-05-26 23:37:19', '2026-05-26 19:37:24', '2026-05-27 13:43:19', '2026-05-26 12:37:19', '2026-05-27 06:43:19'),
(18, 30, 5, 1, '2026-05-29', '12:00:00', '13:00:00', 'completed', '2026-05-26 19:52:54', '2026-05-26 23:37:54', '2026-05-26 19:37:57', '2026-05-29 21:15:12', '2026-05-26 12:37:54', '2026-06-01 10:25:19'),
(21, 30, 7, 1, '2026-05-31', '16:00:00', '18:00:00', 'confirmed', '2026-05-26 20:54:02', '2026-05-27 00:39:02', '2026-05-26 20:39:07', '2026-05-27 13:43:13', '2026-05-26 13:39:02', '2026-05-27 06:43:13'),
(23, 30, 5, 1, '2026-05-28', '12:00:00', '13:00:00', 'completed', '2026-05-26 22:09:19', '2026-05-27 01:54:19', '2026-05-26 21:54:23', '2026-05-29 21:15:15', '2026-05-26 14:54:19', '2026-06-01 10:25:19'),
(24, 6, 7, 1, '2026-05-29', '13:00:00', '15:00:00', 'confirmed', '2026-05-27 13:56:58', '2026-05-27 17:41:58', '2026-05-27 13:42:02', '2026-05-27 13:43:15', '2026-05-27 06:41:58', '2026-05-27 06:43:15'),
(25, 30, 5, 1, '2026-05-30', '15:00:00', '16:00:00', 'completed', '2026-05-29 21:32:45', '2026-05-30 01:17:45', '2026-05-29 21:17:50', '2026-05-29 21:18:17', '2026-05-29 14:17:45', '2026-06-01 10:25:19'),
(26, 6, 2, 1, '2026-05-31', '15:00:00', '16:00:00', 'completed', '2026-05-29 21:37:03', '2026-05-30 01:22:03', '2026-05-29 21:22:06', '2026-05-29 21:22:39', '2026-05-29 14:22:03', '2026-06-01 10:25:19'),
(27, 32, 1, 1, '2026-05-31', '14:00:00', '15:00:00', 'completed', '2026-05-29 21:39:45', '2026-05-30 01:24:45', '2026-05-29 21:24:48', '2026-05-29 21:25:10', '2026-05-29 14:24:45', '2026-06-01 10:25:19'),
(28, 33, 3, 1, '2026-05-31', '13:00:00', '14:00:00', 'completed', '2026-05-29 21:44:23', '2026-05-30 01:29:23', '2026-05-29 21:29:26', '2026-05-29 21:29:46', '2026-05-29 14:29:23', '2026-06-01 10:25:19'),
(29, 12, 3, 1, '2026-06-02', '12:00:00', '13:00:00', 'completed', '2026-05-30 17:06:39', '2026-05-30 20:51:39', '2026-05-30 16:51:42', '2026-05-30 16:58:42', '2026-05-30 09:51:39', '2026-06-04 03:30:00'),
(30, 30, 2, 1, '2026-05-31', '13:00:00', '15:00:00', 'completed', '2026-05-30 21:02:58', '2026-05-31 00:47:58', '2026-05-30 20:54:17', '2026-05-30 21:04:18', '2026-05-30 13:47:58', '2026-06-01 10:25:19'),
(31, 6, 1, 1, '2026-05-31', '17:00:00', '19:00:00', 'completed', '2026-05-30 21:28:35', '2026-05-31 01:13:35', '2026-05-30 21:13:44', '2026-05-30 21:14:57', '2026-05-30 14:13:35', '2026-06-01 10:25:19'),
(32, 17, 3, 1, '2026-05-31', '09:00:00', '12:00:00', 'completed', '2026-05-30 23:01:12', '2026-05-31 02:46:12', '2026-05-30 22:46:15', '2026-05-30 22:47:28', '2026-05-30 15:46:12', '2026-06-01 10:25:19'),
(33, 17, 4, 1, '2026-06-01', '15:00:00', '17:00:00', 'completed', '2026-05-30 23:04:28', '2026-05-31 02:49:28', '2026-05-30 22:49:32', '2026-05-30 22:51:07', '2026-05-30 15:49:28', '2026-06-01 10:25:19'),
(34, 33, 1, 1, '2026-06-02', '12:00:00', '15:00:00', 'completed', '2026-05-31 20:26:46', '2026-06-01 00:11:46', '2026-05-31 20:11:55', '2026-05-31 20:16:49', '2026-05-31 13:11:46', '2026-06-04 03:30:00'),
(35, 33, 4, 1, '2026-06-03', '10:00:00', '12:00:00', 'completed', '2026-05-31 20:37:26', '2026-06-01 00:22:26', '2026-05-31 20:22:30', '2026-05-31 22:31:37', '2026-05-31 13:22:26', '2026-06-04 03:30:00'),
(36, 4, 5, 1, '2026-06-02', '09:00:00', '11:00:00', 'completed', '2026-05-31 22:44:09', '2026-06-01 02:29:09', '2026-05-31 22:29:13', '2026-05-31 22:31:40', '2026-05-31 15:29:09', '2026-06-04 03:30:00'),
(37, 18, 2, 1, '2026-06-02', '09:00:00', '12:00:00', 'completed', '2026-06-01 00:52:44', '2026-06-01 04:37:44', '2026-06-01 00:37:49', '2026-06-01 00:38:37', '2026-05-31 17:37:44', '2026-06-04 03:30:00'),
(38, 18, 16, 1, '2026-06-03', '09:00:00', '12:00:00', 'cancelled', '2026-06-01 18:09:02', '2026-06-01 21:54:02', '2026-06-01 17:54:38', '2026-06-01 18:23:35', '2026-06-01 10:54:02', '2026-06-01 11:48:32'),
(39, 18, 15, 1, '2026-06-02', '15:00:00', '17:00:00', 'completed', '2026-06-01 18:11:50', '2026-06-01 21:56:50', '2026-06-01 18:00:25', '2026-06-01 18:41:13', '2026-06-01 10:56:50', '2026-06-04 03:30:00'),
(40, 18, 13, 2, '2026-06-11', '10:00:00', '13:00:00', 'confirmed', '2026-06-01 18:31:34', '2026-06-01 22:16:34', '2026-06-01 18:20:17', '2026-06-01 18:20:17', '2026-06-01 11:16:34', '2026-06-01 11:20:17'),
(41, 18, 10, 1, '2026-06-09', '09:00:00', '11:00:00', 'confirmed', '2026-06-02 17:33:50', '2026-06-02 21:18:50', '2026-06-02 17:18:55', '2026-06-02 17:18:55', '2026-06-02 10:18:50', '2026-06-02 10:18:55'),
(42, 4, 18, 1, '2026-06-11', '09:00:00', '12:00:00', 'confirmed', '2026-06-04 10:59:59', '2026-06-04 14:44:59', '2026-06-04 10:45:07', '2026-06-04 10:45:07', '2026-06-04 03:44:59', '2026-06-04 03:45:07'),
(43, 18, 1, 1, '2026-06-12', '08:00:00', '10:00:00', 'confirmed', '2026-06-04 14:40:07', '2026-06-04 18:25:07', '2026-06-04 14:25:13', '2026-06-04 14:25:13', '2026-06-04 07:25:07', '2026-06-04 07:25:13');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-andra10|127.0.0.1', 'i:1;', 1780065061),
('laravel-cache-andra10|127.0.0.1:timer', 'i:1780065061;', 1780065061),
('laravel-cache-lin|127.0.0.1', 'i:1;', 1780235907),
('laravel-cache-lin|127.0.0.1:timer', 'i:1780235907;', 1780235907),
('laravel-cache-livewire-rate-limiter:5d78df84c8e95393aab939795270e165e3d40f37', 'i:1;', 1780550461),
('laravel-cache-livewire-rate-limiter:5d78df84c8e95393aab939795270e165e3d40f37:timer', 'i:1780550461;', 1780550461),
('laravel-cache-nasywa@gmail.com|127.0.0.1', 'i:1;', 1780501090),
('laravel-cache-nasywa@gmail.com|127.0.0.1:timer', 'i:1780501090;', 1780501090),
('laravel-cache-siska.swipe@example.com|127.0.0.1', 'i:1;', 1780035929),
('laravel-cache-siska.swipe@example.com|127.0.0.1:timer', 'i:1780035929;', 1780035929),
('laravel-cache-wa|127.0.0.1', 'i:1;', 1779997824),
('laravel-cache-wa|127.0.0.1:timer', 'i:1779997824;', 1779997824),
('laravel-cache-wawa111|127.0.0.1', 'i:1;', 1779997839),
('laravel-cache-wawa111|127.0.0.1:timer', 'i:1779997839;', 1779997839);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sport_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `communities`
--

INSERT INTO `communities` (`id`, `created_by`, `name`, `sport_category`, `city`, `description`, `photo`, `whatsapp_link`, `instagram_link`, `created_at`, `updated_at`) VALUES
(1, 4, 'Badminton Malang Raya', 'Badminton', 'Malang', 'Latihan rutin dihari Jum\'at sore', NULL, 'https://chat.whatsapp.com/CzwifpmVniGJLsUZ1qiBNI', NULL, '2026-05-31 16:11:18', '2026-05-31 16:11:18'),
(2, 31, 'Rugby Boyy', 'Rugby', 'Malang', 'Latihan rutin hari Minggu Pagi, jam 09:00.', NULL, 'https://chat.whatsapp.com/CzwifpmVniGJLsUZ1qiBNI', NULL, '2026-05-31 16:23:04', '2026-05-31 16:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `community_members`
--

CREATE TABLE `community_members` (
  `id` bigint UNSIGNED NOT NULL,
  `community_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_members`
--

INSERT INTO `community_members` (`id`, `community_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '2026-05-31 16:11:18', '2026-05-31 16:11:18'),
(2, 1, 6, '2026-05-31 16:16:34', '2026-05-31 16:16:34'),
(3, 1, 33, '2026-05-31 16:17:09', '2026-05-31 16:17:09'),
(4, 1, 31, '2026-05-31 16:21:52', '2026-05-31 16:21:52'),
(5, 2, 31, '2026-05-31 16:23:04', '2026-05-31 16:23:04'),
(6, 2, 4, '2026-05-31 16:34:10', '2026-05-31 16:34:10'),
(7, 1, 18, '2026-06-02 09:38:23', '2026-06-02 09:38:23'),
(8, 1, 12, '2026-06-02 10:20:16', '2026-06-02 10:20:16');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `owner_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `value` decimal(12,2) NOT NULL,
  `min_booking_amount` decimal(12,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `usage_count` int NOT NULL DEFAULT '0',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `owner_id`, `name`, `code`, `description`, `type`, `value`, `min_booking_amount`, `usage_limit`, `usage_count`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 9, 'Promo Tes', 'TESTING', 'Ini hanya test sistem', 'percentage', 10.00, 70.00, 1, 0, '2026-05-30', '2026-06-01', 1, '2026-05-30 10:10:20', '2026-05-30 10:10:20'),
(2, 9, 'Part 2', 'TESTING2', 'Tes sistem kedua', 'fixed', 50000.00, 150000.00, 8, 0, '2026-05-31', '2026-06-02', 1, '2026-05-30 10:55:07', '2026-05-30 10:57:49'),
(3, 9, 'Hari Raya Besar!', 'WEEKDAY-OFF!', 'Test ketiga', 'percentage', 70.00, 100000.00, 5, 0, '2026-05-30', '2026-06-02', 1, '2026-05-30 11:00:04', '2026-05-30 11:00:04'),
(4, 9, 'HOLIDAYAY!!', NULL, NULL, 'percentage', 70.00, 70.00, NULL, 0, '2026-06-04', '2026-06-08', 1, '2026-06-04 03:25:54', '2026-06-04 03:25:54'),
(5, 9, 'BADMINTODAY', 'CAKEP', NULL, 'fixed', 25000.00, NULL, NULL, 0, '2026-06-04', '2026-06-06', 1, '2026-06-04 03:28:20', '2026-06-04 03:29:39'),
(6, 9, 'TETDAY!', NULL, 'Ini untuk display utama', 'percentage', 75.00, NULL, NULL, 0, '2026-06-04', '2026-06-11', 1, '2026-06-04 03:39:47', '2026-06-04 03:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `discount_field`
--

CREATE TABLE `discount_field` (
  `id` bigint UNSIGNED NOT NULL,
  `discount_id` bigint UNSIGNED NOT NULL,
  `field_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discount_field`
--

INSERT INTO `discount_field` (`id`, `discount_id`, `field_id`) VALUES
(2, 1, 3),
(1, 2, 1),
(4, 3, 1),
(5, 3, 2),
(6, 3, 3),
(7, 3, 4),
(8, 3, 5),
(9, 4, 11),
(10, 5, 12),
(11, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `field_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `field_id`, `created_at`, `updated_at`) VALUES
(1, 6, 1, '2026-05-29 04:37:22', '2026-05-29 04:37:22'),
(2, 32, 10, '2026-05-29 05:01:16', '2026-05-29 05:01:16'),
(3, 32, 1, '2026-05-30 15:16:07', '2026-05-30 15:16:07'),
(8, 12, 3, '2026-05-30 16:49:45', '2026-05-30 16:49:45'),
(9, 12, 1, '2026-05-30 16:49:58', '2026-05-30 16:49:58'),
(10, 12, 2, '2026-05-30 16:50:00', '2026-05-30 16:50:00'),
(11, 12, 4, '2026-05-30 16:50:02', '2026-05-30 16:50:02'),
(12, 33, 3, '2026-05-31 12:08:44', '2026-05-31 12:08:44'),
(13, 30, 5, '2026-05-31 14:27:11', '2026-05-31 14:27:11'),
(14, 18, 5, '2026-06-02 09:38:00', '2026-06-02 09:38:00');

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE `fields` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maps_link` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_per_hour` int NOT NULL,
  `open_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '08:00',
  `close_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '22:00',
  `number_of_courts` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `facilities` json DEFAULT NULL,
  `rating` float NOT NULL DEFAULT '0',
  `review_count` int NOT NULL DEFAULT '0',
  `verification_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verification_notes` text COLLATE utf8mb4_unicode_ci,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `owner_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `name`, `description`, `type`, `location`, `maps_link`, `image`, `price_per_hour`, `open_time`, `close_time`, `number_of_courts`, `is_available`, `featured`, `facilities`, `rating`, `review_count`, `verification_status`, `verification_notes`, `verified_at`, `verified_by`, `owner_id`, `created_at`, `updated_at`) VALUES
(1, 'GOR Bimasakti Malang', 'Lapangan untuk cari tim dan public match', 'Futsal', 'Klojen, Malang Kota', 'https://www.google.com/maps', 'fields/0lHAdYAlxJQjAR1ymtDMfKziR59r1TYTNVOGsBAM.jpg', 120000, '08:00', '20:00', 6, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"Mushala\", \"Kantin\", \"Gazebo\"]', 4.8, 2, 'approved', NULL, '2026-05-25 03:34:16', 22, 9, '2026-05-07 19:13:00', '2026-06-01 09:31:41'),
(2, 'Champion Futsal Malang', 'Lapangan untuk cari tim dan public match', 'Futsal', 'Sukun, Malang Kota', 'https://www.google.com/maps', 'fields/80xAEF5tMX9ltWCew8ovIELYUdB3hP7e5fRuAAES.jpg', 110000, '08:00', '17:00', 6, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"AC\", \"Mushala\", \"Ruang Ganti\", \"Kantin\"]', 4.8, 2, 'approved', NULL, '2026-05-25 03:34:27', 22, 9, '2026-05-07 19:13:00', '2026-06-01 09:31:51'),
(3, 'GOR Bulu Tangkis Tidar', 'Lapangan untuk cari tim dan public match', 'Badminton', 'Lowokwaru, Malang Kota', 'https://www.google.com/maps', 'fields/icMNhlvXOvUnZH4kfSj9SJ1o2E8AunBCn4xZZet6.jpg', 100000, '08:00', '17:00', 6, 1, 0, '[\"WiFi\", \"Toilet\", \"AC\", \"kantin\", \"gazebo\"]', 4.8, 2, 'rejected', 'not qualified\n', '2026-05-25 03:34:43', 22, 9, '2026-05-07 19:13:00', '2026-06-01 09:32:03'),
(4, 'Lapangan Voli Veteran', 'Lapangan untuk cari tim dan public match', 'Voli', 'Dago, Bandung', 'https://www.google.com/maps', 'fields/zyQ788z5Fo0uSItalQ027KnYQQre3CXY77cnFyIL.jpg', 90000, '09:00', '20:00', 6, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"AC\", \"Mushala\", \"ruang ganti\", \"kantin\", \"gazebo\"]', 4, 1, 'approved', NULL, '2026-05-25 03:34:48', 22, 9, '2026-05-07 19:13:00', '2026-06-01 09:32:17'),
(5, 'Tennis Court Soekarno', 'Lapangan untuk cari tim dan public match', 'Tennis', 'Bundaran HI, Jakarta', 'https://www.google.com/maps', 'fields/Jz0RUxZk25MbzWWRnPZf0X63SRI9nc5OIyFLMiEp.jpg', 130000, '08:00', '17:00', 6, 1, 0, '[\"WiFi\", \"Parkir\"]', 4.2, 3, 'rejected', 'not qualified', '2026-05-25 03:38:56', 22, 9, '2026-05-07 19:13:00', '2026-06-01 09:32:26'),
(6, 'Lapangan Futsal A', 'Lapangan Futsal berkualitas dengan fasilitas lengkap.', 'Futsal', 'Lowokwaru, Malang', NULL, 'fields/lapangan_futsal_a.png', 120000, '08:00', '22:00', 1, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"Mushala\"]', 5, 2, 'pending', NULL, NULL, NULL, 28, '2026-05-25 14:41:39', '2026-05-31 15:55:33'),
(7, 'Lapangan Futsal B', 'Lapangan Futsal berkualitas dengan fasilitas lengkap.', 'Futsal', 'Sukun, Malang', NULL, 'fields/lapangan_futsal_b.png', 150000, '08:00', '22:00', 1, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"Mushala\"]', 5, 1, 'pending', NULL, NULL, NULL, 28, '2026-05-25 14:41:39', '2026-05-29 14:20:14'),
(8, 'Lapangan Basket', 'Lapangan Basket berkualitas dengan fasilitas lengkap.', 'Basket', 'Klojen, Malang', NULL, 'fields/lapangan_basket.png', 180000, '09:00', '21:00', 1, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"Mushala\"]', 0, 0, 'pending', NULL, NULL, NULL, 28, '2026-05-25 14:41:39', '2026-05-30 15:27:29'),
(9, 'Lapangan Badminton', 'Lapangan Badminton berkualitas dengan fasilitas lengkap.', 'Badminton', 'Blimbing, Malang', NULL, 'fields/lapangan_badminton.png', 80000, '07:00', '22:00', 1, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"Mushala\"]', 5, 1, 'pending', NULL, NULL, NULL, 28, '2026-05-25 14:41:39', '2026-05-31 15:49:35'),
(10, 'Rugby Community', NULL, 'Rugby', 'Sawojajar, Malang', 'https://www.google.com/maps', 'fields/4FHjXIQ8DayrwRpQtqRmG4H7sEJC5Jq7e8iUyFuD.webp', 120000, '09:00', '18:00', 3, 1, 0, '[\"Toilet\", \"Parkir\", \"AC\", \"gazebo\", \"kantin\", \"ruang ganti\"]', 0, 0, 'pending', NULL, NULL, NULL, 27, '2026-05-25 14:58:21', '2026-06-02 09:47:46'),
(11, 'Stadion Futsal Kencana', NULL, 'Futsal', 'Jakarta Utara, DKI Jakarta', 'https://www.google.com/maps', 'fields/ZcSNNqCxenvZBnMNg9xc2AhYU1ySMi7ui8EBo3qT.jpg', 150000, '08:00', '22:00', 6, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"AC\", \"Mushala\"]', 0, 0, 'pending', NULL, NULL, NULL, 9, '2026-05-31 18:54:10', '2026-06-01 09:32:35'),
(12, 'Spies Shuttle Arena', NULL, 'Badminton', 'Tangerang, Banten', 'https://www.google.com/maps', 'fields/luyR7IbIRUMfShv5IjPxgdgrP0JGHB96tQxkQ6Vz.jpg', 80000, '08:00', '22:00', 6, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"AC\", \"Mushala\"]', 0, 0, 'pending', NULL, NULL, NULL, 9, '2026-05-31 18:54:10', '2026-06-01 09:32:45'),
(13, 'Elite Hoops Center', NULL, 'Basket', 'Jakarta Pusat, DKI Jakarta', 'https://www.google.com/maps', 'fields/FnfvyVrYYUTsoi4NUHzCfI0Gt0AWoeJYB3Mqrb4K.jpg', 200000, '08:00', '22:00', 6, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"AC\", \"Mushala\"]', 0, 0, 'pending', NULL, NULL, NULL, 9, '2026-05-31 18:54:10', '2026-06-01 09:32:58'),
(14, 'Grand Slam Court', NULL, 'Tennis', 'Dago, Bandung', 'https://www.google.com/maps', 'fields/xEyVDkmLricWtsRAKw8VOPUu5G3QUXE9NZi1yGfD.jpg', 120000, '08:00', '22:00', 6, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\"]', 0, 0, 'pending', NULL, NULL, NULL, 9, '2026-05-31 18:54:10', '2026-06-01 09:33:10'),
(15, 'Volley Profesional Venue', NULL, 'Voli', 'Jakatsetia, Bekasi', 'https://www.google.com/maps', 'fields/CIbejkTty8AAvRygjtvwMJC70Rme5In4WAX9hm2J.jpg', 95000, '08:00', '22:00', 6, 1, 0, '[\"WiFi\", \"Parkir\", \"Mushala\"]', 0, 0, 'pending', NULL, NULL, NULL, 9, '2026-05-31 18:54:10', '2026-06-01 09:34:06'),
(16, 'Aquatic Spies Club', NULL, 'Renang', 'Jakarta Selatan, DKI Jakarta', 'https://www.google.com/maps', 'fields/UiatbrdRPuO4BCRnslYDok8tq4rMOi13Ne45oMHL.jpg', 60000, '08:00', '17:00', 6, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"Mushala\"]', 0, 0, 'pending', NULL, NULL, NULL, 9, '2026-05-31 18:54:10', '2026-06-01 09:34:48'),
(18, 'GOR Polinema Grapol', NULL, 'Lari', 'JL. Soehat No.3000, Malang', 'https://www.google.com/maps', 'fields/6xzzgIOEC2sBvycig0ocAatu4IXZMrDOop5Qa06F.jpg', 170000, '08:00', '15:00', 3, 1, 0, '[\"WiFi\", \"Toilet\", \"Parkir\", \"AC\", \"Mushala\"]', 0, 0, 'pending', NULL, NULL, NULL, 34, '2026-06-04 03:43:27', '2026-06-04 03:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint UNSIGNED NOT NULL,
  `field_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `is_holiday` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `field_id`, `date`, `is_holiday`, `created_at`, `updated_at`) VALUES
(1, 6, '2026-05-30', 1, '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(2, 7, '2026-05-30', 1, '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(3, 8, '2026-05-30', 1, '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(4, 9, '2026-05-30', 1, '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(5, 6, '2026-06-04', 1, '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(6, 7, '2026-06-04', 1, '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(7, 8, '2026-06-04', 1, '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(8, 9, '2026-06-04', 1, '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(15, 1, '2026-06-03', 1, '2026-05-31 20:08:24', '2026-05-31 20:08:24'),
(16, 1, '2026-06-04', 1, '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(17, 1, '2026-06-09', 1, '2026-06-04 04:15:10', '2026-06-04 04:15:10');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenances`
--

CREATE TABLE `maintenances` (
  `id` bigint UNSIGNED NOT NULL,
  `field_id` bigint UNSIGNED NOT NULL,
  `task_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sedang',
  `pic_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenances`
--

INSERT INTO `maintenances` (`id`, `field_id`, `task_name`, `type`, `schedule_date`, `priority`, `pic_name`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 6, 'Perbaikan Lampu Lapangan A', 'Elektrikal', '2026-05-27', 'Tinggi', 'Budi Setiawan', 'Menunggu', NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(2, 6, 'Pengecekan Jaring', 'Lapangan', '2026-05-26', 'Sedang', 'Andi Permana', 'Dikerjakan', NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(3, 7, 'Kalibrasi Scoreboard', 'Elektrikal', '2026-05-24', 'Rendah', 'Rizky', 'Selesai', NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(4, 7, 'Kebersihan Lapangan B', 'Kebersihan', '2026-05-28', 'Sedang', 'Dewi', 'Menunggu', NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(5, 8, 'Pengecatan Ulang Garis', 'Lapangan', '2026-05-30', 'Rendah', 'Siti', 'Menunggu', NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(6, 8, 'Perbaiki Papan Skor', 'Elektrikal', '2026-05-23', 'Tinggi', 'Budi Setiawan', 'Dikerjakan', NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(7, 9, 'Ganti Lampu Ruang Ganti', 'Elektrikal', '2026-05-18', 'Sedang', 'Andi Permana', 'Selesai', NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(8, 9, 'Pembersihan Drainase', 'Kebersihan', '2026-05-29', 'Sedang', 'Rizky', 'Menunggu', NULL, '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(9, 13, 'Perbaikan Lampu', 'Elektrikal', '2026-06-03', 'Rendah', 'Chandra Santoso', 'Dikerjakan', 'Lampu redup', '2026-06-01 15:15:01', '2026-06-01 15:34:51');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sport` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `max_player` int NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `title`, `sport`, `field_id`, `date`, `time`, `max_player`, `created_by`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Basket Sabtu Night', NULL, 1, '2026-05-10', '20:00:00', 10, 9, 'public', '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(2, 'Basket Fun Match', NULL, 1, '2026-05-12', '19:00:00', 10, 9, 'public', '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(3, 'Futsal Sparring', NULL, 2, '2026-05-09', '21:00:00', 12, 9, 'public', '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(4, 'Futsal Friendly', NULL, 2, '2026-05-13', '20:30:00', 12, 9, 'public', '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(5, 'Badminton Double Mix', NULL, 3, '2026-05-11', '18:00:00', 8, 9, 'public', '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(6, 'Badminton Latihan', NULL, 3, '2026-05-14', '19:30:00', 8, 9, 'public', '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(7, 'Voli Sore Seru', NULL, 4, '2026-05-10', '17:00:00', 12, 9, 'public', '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(8, 'Voli Weekend', NULL, 4, '2026-05-15', '16:30:00', 12, 9, 'public', '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(9, 'Tennis Rally Session', NULL, 5, '2026-05-09', '18:30:00', 6, 9, 'public', '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(10, 'Tennis Match Up', NULL, 5, '2026-05-13', '08:00:00', 6, 9, 'public', '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(11, 'Voli Pagi Part 98', NULL, 4, '2026-05-11', '10:30:00', 4, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(12, 'Mini Soccer Fun Part 6', NULL, 2, '2026-06-28', '21:00:00', 11, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(13, 'Voli Sore Part 93', NULL, 4, '2026-06-22', '11:00:00', 6, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(14, 'Basket Fun Match Part 27', NULL, 1, '2026-05-27', '13:00:00', 6, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(15, 'Basket Sparring Part 98', NULL, 1, '2026-06-23', '19:30:00', 10, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(16, 'Basket Fun Match Part 62', NULL, 1, '2026-07-03', '14:30:00', 9, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(17, 'Badminton Pagi Part 14', NULL, 3, '2026-05-24', '07:30:00', 12, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(18, 'Tennis Rally Part 69', NULL, 5, '2026-07-03', '17:00:00', 7, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(19, 'Basket Sparring Part 19', NULL, 1, '2026-06-22', '07:30:00', 8, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(20, 'Badminton Latihan Part 93', NULL, 3, '2026-05-14', '14:30:00', 7, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(21, 'Tennis Match Up Part 53', NULL, 5, '2026-05-24', '16:00:00', 10, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(22, 'Tennis Santai Part 94', NULL, 5, '2026-06-11', '13:00:00', 7, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(23, 'Futsal Kantor Part 47', NULL, 2, '2026-07-01', '06:00:00', 11, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(24, 'Tennis Match Up Part 19', NULL, 5, '2026-07-05', '15:00:00', 11, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(25, 'Futsal Kantor Part 51', NULL, 2, '2026-06-24', '09:30:00', 12, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(26, 'Futsal Sparring Part 89', NULL, 2, '2026-06-19', '15:30:00', 13, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(27, 'Tennis Rally Part 25', NULL, 5, '2026-06-17', '16:30:00', 9, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(28, 'Basket Sparring Part 52', NULL, 1, '2026-05-24', '07:30:00', 6, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(29, 'Badminton Pagi Part 25', NULL, 3, '2026-05-26', '19:30:00', 11, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(30, 'Tennis Santai Part 13', NULL, 5, '2026-07-02', '17:00:00', 5, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(31, 'Badminton Latihan Part 23', NULL, 3, '2026-05-16', '22:00:00', 6, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(32, 'Voli Santai Part 15', NULL, 4, '2026-07-01', '07:30:00', 8, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(33, 'Tepok Bulu Part 71', NULL, 3, '2026-05-21', '12:00:00', 9, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(34, 'Badminton Latihan Part 84', NULL, 3, '2026-06-11', '21:00:00', 10, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(35, 'Futsal Kantor Part 36', NULL, 2, '2026-06-28', '11:00:00', 13, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(36, 'Badminton Latihan Part 80', NULL, 3, '2026-06-18', '14:00:00', 8, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(37, 'Mini Soccer Fun Part 56', NULL, 2, '2026-06-25', '16:00:00', 9, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(38, 'Futsal Kantor Part 64', NULL, 2, '2026-06-15', '21:00:00', 14, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(39, 'Badminton Pagi Part 12', NULL, 3, '2026-05-13', '16:30:00', 8, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(40, 'Futsal Sparring Part 12', NULL, 2, '2026-05-23', '09:00:00', 14, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(41, 'Futsal Kantor Part 7', NULL, 2, '2026-05-19', '06:30:00', 10, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(42, 'Badminton Santai Part 14', NULL, 3, '2026-05-15', '08:00:00', 4, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(43, 'Tennis Match Up Part 100', NULL, 5, '2026-07-01', '21:00:00', 8, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(44, 'Tennis Sore Part 57', NULL, 5, '2026-05-11', '17:30:00', 10, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(45, 'Voli Sparring Part 1', NULL, 4, '2026-07-07', '21:30:00', 6, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(46, 'Badminton Double Mix Part 46', NULL, 3, '2026-06-20', '15:00:00', 10, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(47, 'Basket Fun Match Part 39', NULL, 1, '2026-06-01', '16:30:00', 9, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(48, 'Badminton Latihan Part 47', NULL, 3, '2026-05-15', '06:30:00', 10, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(49, 'Tennis Rally Part 96', NULL, 5, '2026-06-28', '20:30:00', 9, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(50, 'Mini Soccer Fun Part 33', NULL, 2, '2026-06-22', '21:30:00', 9, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(51, 'Voli Pagi Part 63', NULL, 4, '2026-06-01', '20:00:00', 7, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(52, 'Basket Malam Part 58', NULL, 1, '2026-06-08', '10:30:00', 8, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(53, 'Tennis Rally Part 55', NULL, 5, '2026-06-14', '21:00:00', 5, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(54, 'Badminton Double Mix Part 13', NULL, 3, '2026-05-25', '20:00:00', 4, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(55, 'Futsal Sparring Part 84', NULL, 2, '2026-06-21', '11:30:00', 12, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(56, 'Futsal Mabar Part 82', NULL, 2, '2026-07-02', '07:00:00', 14, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(57, 'Basket Malam Part 35', NULL, 1, '2026-05-17', '14:00:00', 10, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(58, 'Voli Sparring Part 79', NULL, 4, '2026-07-07', '08:30:00', 10, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(59, 'Mini Soccer Fun Part 43', NULL, 2, '2026-06-29', '18:30:00', 10, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(60, 'Basket Fun Match Part 34', NULL, 1, '2026-06-16', '22:00:00', 9, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(61, 'Tennis Rally Part 24', NULL, 5, '2026-06-10', '20:00:00', 6, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(62, 'Tennis Pagi Part 15', NULL, 5, '2026-05-30', '17:00:00', 8, 9, 'public', '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(63, 'Tennis Rally Part 63', NULL, 5, '2026-06-18', '19:00:00', 12, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(64, 'Voli Pagi Part 56', NULL, 4, '2026-06-15', '10:00:00', 8, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(65, 'Futsal Malam Part 66', NULL, 2, '2026-07-06', '20:30:00', 11, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(66, 'Basket Fun Match Part 98', NULL, 1, '2026-05-10', '11:30:00', 7, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(67, 'Tennis Pagi Part 21', NULL, 5, '2026-06-06', '17:30:00', 8, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(68, 'Tennis Match Up Part 64', NULL, 5, '2026-06-18', '20:30:00', 9, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(69, 'Futsal Sparring Part 12', NULL, 2, '2026-06-24', '08:30:00', 11, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(70, 'Badminton Double Mix Part 94', NULL, 3, '2026-06-09', '08:30:00', 11, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(71, 'Futsal Sparring Part 93', NULL, 2, '2026-05-26', '18:30:00', 11, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(72, 'Futsal Kantor Part 37', NULL, 2, '2026-05-18', '21:30:00', 11, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(73, 'Basket Sparring Part 63', NULL, 1, '2026-05-31', '20:30:00', 8, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(74, 'Voli Sparring Part 42', NULL, 4, '2026-06-07', '07:00:00', 12, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(75, 'Tepok Bulu Part 43', NULL, 3, '2026-05-24', '21:00:00', 7, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(76, 'Badminton Latihan Part 34', NULL, 3, '2026-05-12', '10:30:00', 8, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(77, 'Voli Sore Part 4', NULL, 4, '2026-05-30', '11:30:00', 8, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(78, 'Tennis Sore Part 73', NULL, 5, '2026-05-12', '12:00:00', 7, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(79, 'Badminton Double Mix Part 49', NULL, 3, '2026-06-06', '07:00:00', 9, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(80, 'Futsal Malam Part 23', NULL, 2, '2026-06-22', '22:30:00', 12, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(81, 'Basket Sparring Part 79', NULL, 1, '2026-07-03', '21:30:00', 9, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(82, 'Tepok Bulu Part 78', NULL, 3, '2026-05-09', '12:30:00', 12, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(83, 'Futsal Malam Part 9', NULL, 2, '2026-05-10', '12:30:00', 14, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(84, 'Voli Sore Part 52', NULL, 4, '2026-06-18', '18:00:00', 4, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(85, 'Voli Pagi Part 6', NULL, 4, '2026-07-05', '11:00:00', 6, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(86, 'Badminton Santai Part 12', NULL, 3, '2026-06-30', '06:30:00', 9, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(87, 'Basket 3x3 Part 82', NULL, 1, '2026-06-09', '08:00:00', 10, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(88, 'Badminton Santai Part 17', NULL, 3, '2026-06-05', '17:00:00', 12, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(89, 'Futsal Mabar Part 37', NULL, 2, '2026-05-29', '22:30:00', 13, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(90, 'Tennis Match Up Part 4', NULL, 5, '2026-06-30', '13:30:00', 5, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(91, 'Tennis Santai Part 12', NULL, 5, '2026-06-17', '22:30:00', 7, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(92, 'Basket Malam Part 71', NULL, 1, '2026-06-07', '07:00:00', 6, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(93, 'Basket Pagi Part 82', NULL, 1, '2026-05-22', '06:30:00', 9, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(94, 'Mini Soccer Fun Part 54', NULL, 2, '2026-06-16', '18:30:00', 9, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(95, 'Voli Sparring Part 5', NULL, 4, '2026-05-11', '06:00:00', 4, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(96, 'Futsal Sparring Part 10', NULL, 2, '2026-07-01', '19:00:00', 12, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(97, 'Voli Sore Part 2', NULL, 4, '2026-06-11', '07:00:00', 10, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(98, 'Badminton Double Mix Part 32', NULL, 3, '2026-06-03', '18:30:00', 11, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(99, 'Futsal Mabar Part 40', NULL, 2, '2026-06-26', '08:00:00', 10, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(100, 'Tennis Rally Part 89', NULL, 5, '2026-05-26', '09:00:00', 10, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(101, 'Basket Fun Match Part 43', NULL, 1, '2026-06-24', '06:30:00', 10, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(102, 'Badminton Santai Part 82', NULL, 3, '2026-05-26', '09:00:00', 11, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(103, 'Futsal Malam Part 1', NULL, 2, '2026-05-22', '17:00:00', 14, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(104, 'Basket Malam Part 49', NULL, 1, '2026-06-20', '13:30:00', 6, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(105, 'Voli Weekend Part 96', NULL, 4, '2026-05-27', '19:00:00', 6, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(106, 'Basket 3x3 Part 31', NULL, 1, '2026-06-18', '12:00:00', 7, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(107, 'Basket Pagi Part 54', NULL, 1, '2026-05-14', '12:30:00', 6, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(108, 'Basket Pagi Part 9', NULL, 1, '2026-06-01', '14:00:00', 6, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(109, 'Badminton Santai Part 60', NULL, 3, '2026-05-15', '13:30:00', 4, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(110, 'Tennis Santai Part 8', NULL, 5, '2026-06-30', '16:30:00', 9, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(111, 'Tennis Pagi Part 79', NULL, 5, '2026-06-17', '14:30:00', 7, 9, 'public', '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(112, 'Badminton Latihan Part 40', NULL, 3, '2026-05-15', '15:30:00', 5, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(113, 'Futsal Sparring Part 52', NULL, 2, '2026-06-06', '20:30:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(114, 'Basket Sparring Part 65', NULL, 1, '2026-07-06', '12:30:00', 7, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(115, 'Tennis Sore Part 29', NULL, 5, '2026-05-09', '14:00:00', 11, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(116, 'Voli Weekend Part 88', NULL, 4, '2026-05-19', '10:30:00', 11, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(117, 'Voli Santai Part 9', NULL, 4, '2026-05-10', '16:00:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(118, 'Basket Malam Part 26', NULL, 1, '2026-07-06', '08:00:00', 9, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(119, 'Voli Sore Part 16', NULL, 4, '2026-07-05', '07:00:00', 8, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(120, 'Badminton Pagi Part 54', NULL, 3, '2026-06-08', '10:30:00', 6, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(121, 'Basket Fun Match Part 79', NULL, 1, '2026-06-11', '07:00:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(122, 'Mini Soccer Fun Part 40', NULL, 2, '2026-06-23', '18:30:00', 5, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(123, 'Futsal Kantor Part 32', NULL, 2, '2026-05-23', '13:30:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(124, 'Tennis Match Up Part 60', NULL, 5, '2026-06-19', '08:00:00', 4, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(125, 'Tennis Rally Part 100', NULL, 5, '2026-06-24', '18:30:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(126, 'Badminton Double Mix Part 51', NULL, 3, '2026-06-07', '10:30:00', 5, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(127, 'Basket Malam Part 92', NULL, 1, '2026-06-01', '17:00:00', 8, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(128, 'Basket Sparring Part 26', NULL, 1, '2026-06-07', '17:00:00', 6, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(129, 'Voli Sparring Part 8', NULL, 4, '2026-06-25', '19:30:00', 6, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(130, 'Tennis Sore Part 71', NULL, 5, '2026-06-13', '08:00:00', 11, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(131, 'Futsal Mabar Part 34', NULL, 2, '2026-05-20', '20:30:00', 11, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(132, 'Badminton Latihan Part 8', NULL, 3, '2026-05-14', '18:00:00', 11, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(133, 'Futsal Kantor Part 5', NULL, 2, '2026-06-04', '11:30:00', 14, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(134, 'Tepok Bulu Part 3', NULL, 3, '2026-05-29', '15:30:00', 4, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(135, 'Tennis Rally Part 71', NULL, 5, '2026-06-19', '10:00:00', 5, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(136, 'Voli Santai Part 83', NULL, 4, '2026-05-21', '22:00:00', 11, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(137, 'Futsal Sparring Part 58', NULL, 2, '2026-05-09', '12:30:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(138, 'Futsal Malam Part 19', NULL, 2, '2026-06-15', '17:00:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(139, 'Basket 3x3 Part 83', NULL, 1, '2026-05-23', '14:00:00', 7, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(140, 'Voli Santai Part 71', NULL, 4, '2026-05-30', '14:30:00', 9, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(141, 'Mini Soccer Fun Part 49', NULL, 2, '2026-05-24', '20:30:00', 6, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(142, 'Tennis Santai Part 21', NULL, 5, '2026-05-26', '06:00:00', 12, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(143, 'Tennis Match Up Part 77', NULL, 5, '2026-06-22', '15:30:00', 4, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(144, 'Tennis Sore Part 50', NULL, 5, '2026-06-09', '21:00:00', 6, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(145, 'Voli Santai Part 100', NULL, 4, '2026-05-31', '19:30:00', 12, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(146, 'Mini Soccer Fun Part 2', NULL, 2, '2026-05-11', '22:30:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(147, 'Futsal Kantor Part 6', NULL, 2, '2026-05-23', '19:00:00', 12, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(148, 'Voli Sparring Part 70', NULL, 4, '2026-07-04', '15:30:00', 6, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(149, 'Voli Santai Part 55', NULL, 4, '2026-05-14', '07:00:00', 7, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(150, 'Futsal Malam Part 56', NULL, 2, '2026-05-29', '14:30:00', 11, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(151, 'Futsal Kantor Part 52', NULL, 2, '2026-05-16', '11:30:00', 12, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(152, 'Tepok Bulu Part 73', NULL, 3, '2026-05-25', '18:00:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(153, 'Futsal Malam Part 54', NULL, 2, '2026-06-11', '22:00:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(154, 'Badminton Double Mix Part 61', NULL, 3, '2026-06-28', '11:30:00', 10, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(155, 'Tepok Bulu Part 33', NULL, 3, '2026-06-26', '15:30:00', 12, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(156, 'Basket Sparring Part 41', NULL, 1, '2026-07-05', '18:00:00', 6, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(157, 'Futsal Sparring Part 42', NULL, 2, '2026-05-26', '10:30:00', 14, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(158, 'Futsal Malam Part 1', NULL, 2, '2026-05-25', '11:30:00', 13, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(159, 'Futsal Mabar Part 67', NULL, 2, '2026-06-28', '21:00:00', 11, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(160, 'Futsal Kantor Part 88', NULL, 2, '2026-07-07', '10:30:00', 13, 9, 'public', '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(161, 'gabut aja pengen tes gas yuk', NULL, 3, '1010-10-10', '10:10:00', 8, 4, 'public', '2026-05-08 02:22:15', '2026-05-08 02:22:15'),
(162, 'Badminton Date', NULL, 9, '2026-06-10', '09:00:00', 4, 4, 'public', '2026-05-26 12:18:29', '2026-05-26 12:18:29'),
(163, 'Padel Date', NULL, 9, '2026-05-30', '09:00:00', 3, 30, 'public', '2026-05-26 12:56:54', '2026-05-26 12:56:54'),
(165, 'Badminton Date', NULL, 9, '2026-05-30', '09:00:00', 4, 30, 'public', '2026-05-26 13:10:26', '2026-05-26 13:10:26'),
(166, 'Tenis Date', NULL, 5, '2026-05-28', '12:00:00', 2, 30, 'private', '2026-05-26 13:13:40', '2026-05-26 13:13:40'),
(167, 'Test', NULL, 10, '2026-05-28', '10:00:00', 11, 30, 'private', '2026-05-26 13:23:51', '2026-05-26 13:23:51'),
(168, 'Tennis Test', NULL, 5, '2026-05-29', '12:00:00', 2, 30, 'private', '2026-05-26 13:31:17', '2026-05-26 13:31:17'),
(169, 'Futsal', NULL, 7, '2026-05-31', '16:00:00', 11, 30, 'private', '2026-05-26 13:39:02', '2026-05-26 13:39:02'),
(170, 'Arek lanang gas!', 'Baseball', 7, '2026-05-29', '13:00:00', 5, 6, 'public', '2026-05-27 06:41:58', '2026-05-27 06:41:58'),
(171, 'Rugby bareng!', 'Rugby', 4, '2026-06-03', '10:00:00', 8, 33, 'public', '2026-05-31 13:22:26', '2026-05-31 13:22:26');

-- --------------------------------------------------------

--
-- Table structure for table `match_players`
--

CREATE TABLE `match_players` (
  `id` bigint UNSIGNED NOT NULL,
  `match_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `contribution_amount` bigint UNSIGNED NOT NULL DEFAULT '0',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `paid_at` datetime DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `match_players`
--

INSERT INTO `match_players` (`id`, `match_id`, `user_id`, `contribution_amount`, `payment_status`, `paid_at`, `confirmed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(2, 1, 11, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(3, 1, 12, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(4, 2, 10, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(5, 2, 11, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(6, 2, 12, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(7, 2, 13, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(8, 3, 10, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(9, 3, 11, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(10, 3, 12, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(11, 3, 13, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(12, 3, 14, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(13, 4, 10, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(14, 4, 11, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00'),
(15, 4, 12, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(16, 4, 13, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(17, 4, 14, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(18, 4, 15, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(19, 5, 10, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(20, 5, 11, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(21, 5, 12, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(22, 6, 10, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(23, 6, 11, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(24, 6, 12, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(25, 6, 13, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(26, 7, 10, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(27, 7, 11, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(28, 7, 12, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(29, 7, 13, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(30, 7, 14, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(31, 8, 10, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(32, 8, 11, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(33, 8, 12, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(34, 8, 13, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(35, 8, 14, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(36, 8, 15, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(37, 9, 10, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(38, 9, 11, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(39, 9, 12, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(40, 10, 10, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(41, 10, 11, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(42, 10, 12, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(43, 10, 13, 0, 'waiting', NULL, NULL, '2026-05-07 19:13:01', '2026-05-07 19:13:01'),
(44, 11, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(45, 11, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(46, 11, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(47, 12, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(48, 12, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(49, 12, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(50, 12, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(51, 12, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(52, 12, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(53, 12, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(54, 13, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(55, 13, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(56, 14, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(57, 15, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(58, 16, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(59, 16, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(60, 17, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(61, 17, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(62, 17, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(63, 18, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(64, 18, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(65, 18, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(66, 18, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(67, 18, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(68, 19, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(69, 19, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(70, 19, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(71, 20, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(72, 21, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(73, 21, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(74, 21, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(75, 21, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(76, 22, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(77, 22, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(78, 22, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(79, 23, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(80, 23, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(81, 23, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(82, 24, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(83, 24, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(84, 24, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(85, 24, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(86, 24, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(87, 24, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(88, 24, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(89, 24, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(90, 25, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(91, 25, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(92, 25, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(93, 25, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(94, 25, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(95, 25, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(96, 25, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(97, 25, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(98, 25, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(99, 25, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(100, 26, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(101, 26, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(102, 26, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(103, 26, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(104, 26, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(105, 26, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(106, 26, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(107, 26, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(108, 27, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(109, 27, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(110, 28, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(111, 28, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(112, 28, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(113, 28, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(114, 28, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(115, 29, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(116, 29, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(117, 29, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(118, 29, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(119, 29, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(120, 29, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(121, 29, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(122, 29, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(123, 30, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(124, 30, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(125, 30, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(126, 31, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(127, 31, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(128, 31, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(129, 32, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(130, 33, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(131, 33, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(132, 33, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(133, 33, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(134, 33, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(135, 33, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(136, 33, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(137, 33, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(138, 34, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(139, 34, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(140, 34, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(141, 35, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(142, 35, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(143, 35, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(144, 35, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(145, 35, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(146, 35, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(147, 36, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(148, 38, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(149, 38, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(150, 38, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(151, 38, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(152, 38, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(153, 38, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(154, 38, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(155, 38, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(156, 38, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(157, 38, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(158, 40, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(159, 40, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(160, 40, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(161, 40, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(162, 40, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(163, 41, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(164, 42, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(165, 42, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(166, 42, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(167, 43, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(168, 43, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(169, 43, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(170, 43, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(171, 43, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(172, 44, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(173, 44, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(174, 44, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(175, 45, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(176, 45, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(177, 45, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(178, 45, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(179, 46, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(180, 46, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(181, 46, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(182, 46, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(183, 46, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(184, 46, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(185, 47, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(186, 47, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(187, 47, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(188, 48, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(189, 48, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(190, 48, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(191, 48, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(192, 48, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(193, 48, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(194, 48, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(195, 49, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(196, 49, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(197, 49, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(198, 49, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(199, 50, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(200, 51, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(201, 52, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(202, 52, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(203, 52, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(204, 52, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(205, 53, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(206, 53, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(207, 55, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(208, 55, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(209, 55, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(210, 55, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(211, 55, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(212, 55, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(213, 55, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(214, 56, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(215, 56, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(216, 56, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(217, 56, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(218, 56, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(219, 56, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(220, 56, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(221, 56, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(222, 56, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(223, 56, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(224, 56, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(225, 56, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(226, 57, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(227, 57, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(228, 57, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(229, 57, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(230, 57, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(231, 57, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(232, 57, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(233, 58, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(234, 58, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(235, 58, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(236, 58, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(237, 58, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(238, 58, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(239, 60, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(240, 60, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(241, 62, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(242, 62, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(243, 62, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(244, 62, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(245, 62, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47'),
(246, 63, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(247, 63, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(248, 63, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(249, 64, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(250, 64, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(251, 64, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(252, 64, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(253, 64, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(254, 65, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(255, 65, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(256, 65, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(257, 65, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(258, 65, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(259, 66, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(260, 67, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(261, 67, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(262, 67, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(263, 68, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(264, 68, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(265, 69, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(266, 69, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(267, 69, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(268, 69, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(269, 69, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(270, 69, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(271, 69, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(272, 69, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(273, 69, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(274, 70, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(275, 71, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(276, 71, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(277, 71, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(278, 71, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(279, 71, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(280, 72, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(281, 72, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(282, 72, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(283, 72, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(284, 73, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(285, 73, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(286, 73, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(287, 74, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(288, 74, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(289, 74, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(290, 74, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(291, 74, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(292, 74, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(293, 74, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(294, 74, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(295, 75, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(296, 75, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(297, 76, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(298, 76, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(299, 77, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(300, 77, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(301, 77, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(302, 77, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(303, 77, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(304, 77, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(305, 77, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(306, 78, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(307, 78, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(308, 79, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(309, 79, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(310, 79, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(311, 79, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(312, 79, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(313, 79, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(314, 80, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(315, 80, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(316, 80, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(317, 80, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(318, 80, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(319, 80, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(320, 80, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(321, 81, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(322, 81, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(323, 81, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(324, 81, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(325, 81, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(326, 81, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(327, 81, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(328, 83, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(329, 83, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(330, 83, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(331, 83, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(332, 83, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(333, 83, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(334, 83, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(335, 83, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(336, 83, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(337, 83, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(338, 83, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(339, 83, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(340, 84, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(341, 84, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(342, 86, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(343, 87, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(344, 87, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(345, 87, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(346, 88, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(347, 88, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(348, 88, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(349, 88, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(350, 89, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(351, 89, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(352, 89, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(353, 90, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(354, 90, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(355, 90, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(356, 90, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(357, 91, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(358, 92, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(359, 92, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(360, 92, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(361, 92, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(362, 93, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(363, 93, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(364, 93, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(365, 93, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(366, 94, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(367, 94, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(368, 94, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(369, 94, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(370, 94, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(371, 96, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(372, 96, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(373, 96, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(374, 96, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(375, 96, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(376, 97, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(377, 97, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(378, 97, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(379, 97, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(380, 97, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(381, 97, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(382, 97, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(383, 98, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(384, 98, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(385, 98, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(386, 98, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(387, 98, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(388, 98, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(389, 98, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(390, 98, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(391, 98, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(392, 98, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(393, 99, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(394, 99, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(395, 99, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(396, 99, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(397, 100, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(398, 100, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(399, 100, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(400, 100, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(401, 101, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(402, 101, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(403, 101, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(404, 101, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(405, 101, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(406, 101, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(407, 101, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(408, 101, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(409, 102, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(410, 102, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(411, 102, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(412, 102, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(413, 102, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(414, 103, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(415, 103, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(416, 103, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(417, 103, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(418, 103, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(419, 103, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(420, 103, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(421, 103, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(422, 103, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(423, 103, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(424, 103, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(425, 104, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(426, 105, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(427, 105, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(428, 105, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(429, 106, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(430, 106, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(431, 106, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(432, 106, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(433, 106, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(434, 108, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(435, 108, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(436, 108, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(437, 108, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(438, 108, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(439, 109, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(440, 109, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(441, 109, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(442, 110, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(443, 110, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(444, 110, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(445, 110, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(446, 110, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(447, 110, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(448, 110, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(449, 110, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(450, 111, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:48', '2026-05-08 01:55:48'),
(451, 112, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(452, 113, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(453, 113, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(454, 114, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(455, 114, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(456, 115, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(457, 115, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(458, 116, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(459, 116, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(460, 116, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(461, 116, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(462, 117, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(463, 117, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(464, 117, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(465, 117, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(466, 117, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(467, 117, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(468, 117, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(469, 117, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(470, 117, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(471, 118, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(472, 119, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(473, 119, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(474, 120, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(475, 120, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(476, 120, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(477, 120, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(478, 120, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(479, 121, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(480, 121, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(481, 122, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(482, 122, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(483, 123, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(484, 123, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(485, 123, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(486, 123, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(487, 123, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(488, 123, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(489, 123, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(490, 124, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(491, 124, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(492, 125, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(493, 125, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(494, 125, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(495, 125, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(496, 126, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(497, 127, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(498, 127, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(499, 127, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(500, 127, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(501, 128, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(502, 128, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(503, 128, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(504, 128, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(505, 129, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(506, 129, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(507, 129, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(508, 130, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(509, 130, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(510, 130, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(511, 130, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(512, 130, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(513, 130, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(514, 130, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(515, 130, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(516, 130, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(517, 131, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(518, 132, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(519, 132, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(520, 132, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(521, 132, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(522, 132, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(523, 132, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(524, 132, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(525, 132, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(526, 132, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(527, 133, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(528, 133, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(529, 133, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(530, 133, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(531, 133, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(532, 133, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(533, 133, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(534, 133, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(535, 134, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(536, 134, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(537, 136, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(538, 136, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(539, 136, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(540, 136, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(541, 138, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(542, 138, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(543, 140, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(544, 140, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(545, 141, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(546, 141, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(547, 144, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(548, 144, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(549, 145, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(550, 145, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(551, 145, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(552, 145, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(553, 145, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(554, 145, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(555, 145, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(556, 145, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(557, 146, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(558, 146, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(559, 146, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(560, 146, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(561, 146, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(562, 147, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(563, 147, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(564, 149, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(565, 149, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(566, 150, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(567, 150, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(568, 150, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(569, 151, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(570, 151, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(571, 151, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(572, 152, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(573, 152, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(574, 152, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(575, 152, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(576, 152, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(577, 153, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(578, 153, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(579, 153, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(580, 153, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(581, 153, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(582, 153, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(583, 154, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(584, 154, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(585, 154, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49');
INSERT INTO `match_players` (`id`, `match_id`, `user_id`, `contribution_amount`, `payment_status`, `paid_at`, `confirmed_at`, `created_at`, `updated_at`) VALUES
(586, 154, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(587, 154, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(588, 154, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(589, 154, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(590, 154, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(591, 155, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(592, 155, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(593, 155, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(594, 155, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(595, 155, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(596, 155, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(597, 155, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(598, 155, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(599, 155, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(600, 156, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(601, 156, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(602, 156, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(603, 157, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(604, 157, 14, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(605, 157, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(606, 157, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(607, 157, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(608, 157, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(609, 157, 13, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(610, 157, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(611, 157, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(612, 158, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(613, 158, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(614, 158, 18, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(615, 159, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(616, 159, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(617, 159, 19, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(618, 159, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(619, 159, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(620, 159, 11, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(621, 159, 12, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(622, 160, 21, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(623, 160, 15, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(624, 160, 16, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(625, 160, 10, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(626, 160, 20, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(627, 160, 17, 0, 'waiting', NULL, NULL, '2026-05-08 01:55:49', '2026-05-08 01:55:49'),
(628, 82, 4, 0, 'waiting', NULL, NULL, NULL, NULL),
(629, 9, 17, 0, 'waiting', NULL, NULL, NULL, NULL),
(630, 165, 4, 40000, 'paid', '2026-05-26 21:19:11', '2026-05-26 21:19:46', '2026-05-26 13:41:43', '2026-05-26 14:19:46'),
(631, 170, 31, 60000, 'paid', '2026-05-27 13:44:26', '2026-05-27 13:45:49', '2026-05-27 06:43:57', '2026-05-27 06:45:49'),
(632, 170, 32, 60000, 'paid', '2026-05-29 11:59:30', '2026-05-29 12:00:53', '2026-05-29 04:57:27', '2026-05-29 05:00:53'),
(633, 170, 30, 60000, 'paid', '2026-05-29 12:08:19', '2026-05-29 12:10:01', '2026-05-29 05:08:14', '2026-05-29 05:10:01'),
(634, 170, 17, 60000, 'paid', '2026-05-29 12:08:49', '2026-05-29 12:09:59', '2026-05-29 05:08:44', '2026-05-29 05:09:59'),
(635, 170, 19, 60000, 'paid', '2026-05-29 12:09:23', '2026-05-29 12:09:56', '2026-05-29 05:09:19', '2026-05-29 05:09:56'),
(636, 171, 12, 22500, 'paid', '2026-05-31 20:36:42', '2026-05-31 21:02:22', '2026-05-31 13:25:59', '2026-05-31 14:02:22');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_16_110836_add_role_to_users_table', 2),
(5, '2026_04_16_111424_create_fields_table', 3),
(6, '2026_04_16_111438_create_bookings_table', 3),
(7, '2026_04_16_111447_create_matches_table', 3),
(8, '2026_04_16_111500_create_match_players_table', 3),
(9, '2026_04_16_111508_create_reviews_table', 3),
(10, '2026_04_22_000000_add_username_to_users_table', 4),
(11, '2026_05_05_000000_add_gender_and_avatar_to_users_table', 5),
(12, '2026_05_06_000000_add_profile_fields_to_users_table', 6),
(13, '2026_05_06_000001_add_columns_to_match_players_table', 6),
(14, '2026_05_07_000001_create_favorites_table', 7),
(15, '2026_05_07_000002_add_points_to_users_table', 7),
(16, '2026_05_07_000003_add_fields_to_fields_table', 7),
(17, '2026_05_08_000000_add_cover_photo_to_users_table', 8),
(18, '2026_05_20_000000_add_verification_fields_to_fields_table', 9),
(19, '2026_05_20_000001_add_type_to_matches_table', 10),
(20, '2026_05_25_113000_set_default_points_on_users_table', 11),
(21, '2026_05_25_120000_add_availability_and_booking_metadata', 12),
(22, '2026_05_25_130000_add_type_and_hours_to_fields_table', 13),
(23, '2026_05_25_130001_create_maintenances_table', 13),
(24, '2026_05_25_130002_create_slots_table', 13),
(25, '2026_05_25_130003_create_holidays_table', 13),
(26, '2026_05_26_000001_add_payment_columns_to_match_players_table', 14),
(27, '2026_05_26_000002_add_payment_timestamps_to_bookings_table', 14),
(28, '2026_05_26_204733_create_notifications_table', 15),
(29, '2026_05_27_000000_add_sport_to_matches_table', 16),
(30, '2026_05_29_000001_add_columns_to_reviews_table', 17),
(31, '2026_05_29_000002_add_partner_fields_to_users_table', 17),
(32, '2026_05_29_121311_add_city_to_users_table', 18),
(33, '2026_05_29_202006_create_discounts_table', 19),
(34, '2026_05_30_164508_add_featured_to_fields_table', 20),
(35, '2026_05_30_201543_add_photos_to_reviews_table', 21),
(36, '2026_05_30_212554_add_maps_link_to_fields_table', 22),
(37, '2026_05_30_230000_change_rating_to_decimal_in_reviews_table', 23),
(38, '2026_05_31_000001_create_discount_field_table', 24),
(39, '2026_05_31_000002_create_communities_table', 25),
(40, '2026_05_31_000003_create_community_members_table', 25),
(41, '2026_06_01_000001_add_court_support_to_fields_and_slots', 26),
(42, '2026_06_01_000002_add_court_number_to_bookings', 27),
(43, '2026_06_01_000003_add_address_to_users_table', 28);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0032f815-f8c0-423e-9830-500449aed4b9', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 4, '{\"booking_id\":36,\"field_name\":\"Tennis Court Soekarno\",\"maps_link\":null,\"date\":\"2026-06-01T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"11:00:00\",\"type\":\"booking_payment_received\"}', '2026-05-31 15:29:47', '2026-05-31 15:29:16', '2026-05-31 15:29:47'),
('0607bcc7-f3ef-4faf-a2ac-68b2c45f13c8', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 18, '{\"booking_id\":39,\"field_name\":\"Volley Profesional Venue\",\"maps_link\":\"https:\\/\\/www.google.com\\/maps\",\"date\":\"2026-06-01T17:00:00.000000Z\",\"start_time\":\"15:00:00\",\"end_time\":\"17:00:00\",\"type\":\"booking_payment_received\"}', '2026-06-01 11:41:55', '2026-06-01 11:00:25', '2026-06-01 11:41:55'),
('0cc1cebf-4feb-47fe-8f5f-c8bdbf85e1ff', 'App\\Notifications\\CommunityJoined', 'App\\Models\\User', 4, '{\"community_id\":1,\"community_name\":\"Badminton Malang Raya\",\"user_id\":18,\"user_name\":\"Sasa\",\"type\":\"community_joined\"}', NULL, '2026-06-02 09:38:26', '2026-06-02 09:38:26'),
('0d841a5c-053c-4d2f-8e2d-8fcf9a08cc90', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 33, '{\"booking_id\":35,\"field_name\":\"Lapangan Voli Veteran\",\"maps_link\":null,\"date\":\"2026-06-02T17:00:00.000000Z\",\"start_time\":\"10:00:00\",\"end_time\":\"12:00:00\",\"type\":\"booking_confirmed\"}', NULL, '2026-05-31 15:31:37', '2026-05-31 15:31:37'),
('14440bbc-200b-40a4-a5cc-71e061ac5a8e', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 33, '{\"booking_id\":34,\"field_name\":\"GOR Bimasakti Malang\",\"maps_link\":null,\"date\":\"2026-06-01T17:00:00.000000Z\",\"start_time\":\"12:00:00\",\"end_time\":\"15:00:00\",\"type\":\"booking_confirmed\"}', '2026-05-31 14:02:24', '2026-05-31 13:16:50', '2026-05-31 14:02:24'),
('23821983-b7f5-4a33-bdb3-9a590fa3816d', 'App\\Notifications\\Owner\\OwnerPaymentReceived', 'App\\Models\\User', 9, '{\"type\":\"owner_payment_received\",\"booking_id\":40,\"field_name\":\"Elite Hoops Center\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-10T17:00:00.000000Z\",\"start_time\":\"10:00:00\",\"end_time\":\"13:00:00\"}', '2026-06-01 11:54:55', '2026-06-01 11:20:17', '2026-06-01 11:54:55'),
('417ff5d0-2de9-4b6a-a557-ccfd35ac31b1', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 17, '{\"booking_id\":33,\"field_name\":\"Lapangan Voli Veteran\",\"maps_link\":null,\"date\":\"2026-05-31T17:00:00.000000Z\",\"start_time\":\"15:00:00\",\"end_time\":\"17:00:00\",\"type\":\"booking_confirmed\"}', '2026-05-30 17:27:12', '2026-05-30 15:51:07', '2026-05-30 17:27:12'),
('45b78115-80b6-4908-8a22-0dfbdead4a70', 'App\\Notifications\\Owner\\OwnerNewBooking', 'App\\Models\\User', 9, '{\"type\":\"owner_new_booking\",\"booking_id\":38,\"field_name\":\"Aquatic Spies Club\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-02T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\"}', '2026-06-01 11:54:55', '2026-06-01 10:54:05', '2026-06-01 11:54:55'),
('4a168a0c-b50d-4a5f-a6fe-0e5aa16fd3d2', 'App\\Notifications\\PaymentConfirmed', 'App\\Models\\User', 17, '{\"match_id\":170,\"match_title\":\"Arek lanang gas!\",\"field_name\":\"Lapangan Futsal B\",\"match_date\":\"2026-05-29\",\"match_time\":\"13:00:00\",\"type\":\"payment_confirmed\"}', '2026-05-30 11:50:49', '2026-05-29 05:09:59', '2026-05-30 11:50:49'),
('4b25f7cc-2ff9-4107-ae58-bc10ea9eef85', 'App\\Notifications\\Owner\\OwnerPaymentReceived', 'App\\Models\\User', 9, '{\"type\":\"owner_payment_received\",\"booking_id\":37,\"field_name\":\"Champion Futsal Malang\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-01T17:00:00.000000Z\"}', '2026-05-31 17:38:42', '2026-05-31 17:37:49', '2026-05-31 17:38:42'),
('4b459a06-1ecf-493e-b617-5d99d00ec771', 'App\\Notifications\\CommunityJoined', 'App\\Models\\User', 4, '{\"community_id\":1,\"community_name\":\"Badminton Malang Raya\",\"user_id\":12,\"user_name\":\"Ayu\",\"type\":\"community_joined\"}', NULL, '2026-06-02 10:20:16', '2026-06-02 10:20:16'),
('4ba1aabd-ad6c-4c3f-ba4c-412734f119cd', 'App\\Notifications\\Owner\\OwnerPaymentReceived', 'App\\Models\\User', 27, '{\"type\":\"owner_payment_received\",\"booking_id\":41,\"field_name\":\"Rugby Community\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-08T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"11:00:00\"}', NULL, '2026-06-02 10:18:55', '2026-06-02 10:18:55'),
('4c469db4-7651-42ea-a830-8d24eb6c26b5', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 4, '{\"booking_id\":36,\"field_name\":\"Tennis Court Soekarno\",\"maps_link\":null,\"date\":\"2026-06-01T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"11:00:00\",\"type\":\"booking_confirmed\"}', '2026-05-31 15:31:59', '2026-05-31 15:31:40', '2026-05-31 15:31:59'),
('4d30c4ba-21bd-41e9-8f26-19a03bf78581', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 33, '{\"booking_id\":34,\"field_name\":\"GOR Bimasakti Malang\",\"maps_link\":null,\"date\":\"2026-06-01T17:00:00.000000Z\",\"start_time\":\"12:00:00\",\"end_time\":\"15:00:00\",\"type\":\"booking_payment_received\"}', '2026-05-31 14:02:24', '2026-05-31 13:11:58', '2026-05-31 14:02:24'),
('4dcfdbec-7e82-43e8-bfe3-d321682075b0', 'App\\Notifications\\CommunityJoined', 'App\\Models\\User', 4, '{\"community_id\":1,\"community_name\":\"Badminton Malang Raya\",\"user_id\":31,\"user_name\":\"Rifky\",\"type\":\"community_joined\"}', '2026-05-31 16:23:46', '2026-05-31 16:21:52', '2026-05-31 16:23:46'),
('4e30bf91-8a45-4d5a-8b8d-e0d735fd740e', 'App\\Notifications\\PaymentConfirmed', 'App\\Models\\User', 19, '{\"match_id\":170,\"match_title\":\"Arek lanang gas!\",\"field_name\":\"Lapangan Futsal B\",\"match_date\":\"2026-05-29\",\"match_time\":\"13:00:00\",\"type\":\"payment_confirmed\"}', NULL, '2026-05-29 05:09:56', '2026-05-29 05:09:56'),
('593ca041-de8d-459b-83d1-2f2959d66d61', 'App\\Notifications\\Owner\\OwnerNewBooking', 'App\\Models\\User', 34, '{\"type\":\"owner_new_booking\",\"booking_id\":42,\"field_name\":\"GOR Polinema Grapol\",\"user_name\":\"nasywaaaa\",\"user_id\":4,\"date\":\"2026-06-10T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\"}', NULL, '2026-06-04 03:45:02', '2026-06-04 03:45:02'),
('5cc5d615-fb08-45fb-9384-9484a207d4d4', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 18, '{\"booking_id\":41,\"field_name\":\"Rugby Community\",\"maps_link\":\"https:\\/\\/www.google.com\\/maps\",\"date\":\"2026-06-08T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"11:00:00\",\"type\":\"booking_payment_received\"}', '2026-06-04 07:16:44', '2026-06-02 10:18:55', '2026-06-04 07:16:44'),
('5fbc756f-f1e2-4fd7-ae06-74c21c80c094', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 33, '{\"booking_id\":35,\"field_name\":\"Lapangan Voli Veteran\",\"maps_link\":null,\"date\":\"2026-06-02T17:00:00.000000Z\",\"start_time\":\"10:00:00\",\"end_time\":\"12:00:00\",\"type\":\"booking_payment_received\"}', '2026-05-31 14:02:24', '2026-05-31 13:22:30', '2026-05-31 14:02:24'),
('6037c8c7-2ce1-40c5-b669-95cf487a0103', 'App\\Notifications\\PaymentConfirmed', 'App\\Models\\User', 32, '{\"match_id\":170,\"match_title\":\"Arek lanang gas!\",\"field_name\":\"Lapangan Futsal B\",\"match_date\":\"2026-05-29\",\"match_time\":\"13:00:00\",\"type\":\"payment_confirmed\"}', '2026-05-29 14:37:13', '2026-05-29 05:00:53', '2026-05-29 14:37:13'),
('60b04397-595c-49f2-8650-c0dd7694d008', 'App\\Notifications\\Owner\\OwnerPaymentReceived', 'App\\Models\\User', 34, '{\"type\":\"owner_payment_received\",\"booking_id\":42,\"field_name\":\"GOR Polinema Grapol\",\"user_name\":\"nasywaaaa\",\"user_id\":4,\"date\":\"2026-06-10T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\"}', NULL, '2026-06-04 03:45:07', '2026-06-04 03:45:07'),
('6c970c4a-f286-4f54-afaf-eceefcc6c48a', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 18, '{\"booking_id\":40,\"field_name\":\"Elite Hoops Center\",\"maps_link\":\"https:\\/\\/www.google.com\\/maps\",\"date\":\"2026-06-10T17:00:00.000000Z\",\"start_time\":\"10:00:00\",\"end_time\":\"13:00:00\",\"type\":\"booking_payment_received\"}', '2026-06-01 11:41:55', '2026-06-01 11:20:17', '2026-06-01 11:41:55'),
('73424126-60e6-4cef-b893-94124bb6d3c1', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 17, '{\"booking_id\":32,\"field_name\":\"GOR Bulu Tangkis Tidar\",\"maps_link\":null,\"date\":\"2026-05-30T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\",\"type\":\"booking_confirmed\"}', '2026-05-30 17:27:12', '2026-05-30 15:47:28', '2026-05-30 17:27:12'),
('73ce3a83-0401-4937-9eac-a682fc225904', 'App\\Notifications\\Owner\\OwnerPaymentReceived', 'App\\Models\\User', 9, '{\"type\":\"owner_payment_received\",\"booking_id\":43,\"field_name\":\"GOR Bimasakti Malang\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-11T17:00:00.000000Z\",\"start_time\":\"08:00:00\",\"end_time\":\"10:00:00\"}', '2026-06-04 08:16:14', '2026-06-04 07:25:13', '2026-06-04 08:16:14'),
('75ae9174-1885-4595-8f3e-bf01b2d6ee12', 'App\\Notifications\\Owner\\OwnerPaymentReceived', 'App\\Models\\User', 9, '{\"type\":\"owner_payment_received\",\"booking_id\":39,\"field_name\":\"Volley Profesional Venue\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-01T17:00:00.000000Z\"}', '2026-06-01 11:54:55', '2026-06-01 11:00:25', '2026-06-01 11:54:55'),
('799041cc-71e1-4848-a4f3-2ce12205d205', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 18, '{\"booking_id\":37,\"field_name\":\"Champion Futsal Malang\",\"maps_link\":null,\"date\":\"2026-06-01T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\",\"type\":\"booking_confirmed\"}', '2026-05-31 19:00:45', '2026-05-31 17:38:37', '2026-05-31 19:00:45'),
('7b5a8ca5-5f29-48ba-87d3-99745048b533', 'App\\Notifications\\Owner\\OwnerNewBooking', 'App\\Models\\User', 27, '{\"type\":\"owner_new_booking\",\"booking_id\":41,\"field_name\":\"Rugby Community\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-08T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"11:00:00\"}', NULL, '2026-06-02 10:18:50', '2026-06-02 10:18:50'),
('81b45233-7f6d-43ec-a3fd-69120acbffba', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 18, '{\"booking_id\":37,\"field_name\":\"Champion Futsal Malang\",\"maps_link\":null,\"date\":\"2026-06-01T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\",\"type\":\"booking_payment_received\"}', '2026-05-31 19:00:45', '2026-05-31 17:37:49', '2026-05-31 19:00:45'),
('8aa1afaa-d957-440d-bfa9-d9101b454f57', 'App\\Notifications\\CommunityJoined', 'App\\Models\\User', 31, '{\"community_id\":2,\"community_name\":\"Rugby Boyy\",\"user_id\":4,\"user_name\":\"nasywaaaa\",\"type\":\"community_joined\"}', NULL, '2026-05-31 16:34:10', '2026-05-31 16:34:10'),
('93cbffd1-348a-4c9c-8b80-eaff69c1e92f', 'App\\Notifications\\BookingConfirmed', 'App\\Models\\User', 6, '{\"booking_id\":31,\"field_name\":\"GOR Bimasakti Malang\",\"date\":\"2026-05-30T17:00:00.000000Z\",\"start_time\":\"17:00:00\",\"end_time\":\"19:00:00\",\"type\":\"booking_confirmed\"}', '2026-05-30 14:15:21', '2026-05-30 14:14:57', '2026-05-30 14:15:21'),
('985bbd84-a881-467e-a58b-e7239e99ae28', 'App\\Notifications\\Owner\\OwnerNewBooking', 'App\\Models\\User', 9, '{\"type\":\"owner_new_booking\",\"booking_id\":43,\"field_name\":\"GOR Bimasakti Malang\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-11T17:00:00.000000Z\",\"start_time\":\"08:00:00\",\"end_time\":\"10:00:00\"}', '2026-06-04 08:16:14', '2026-06-04 07:25:08', '2026-06-04 08:16:14'),
('9a9f0136-7b9b-465e-a2ec-9f771fa9a030', 'App\\Notifications\\Owner\\OwnerNewBooking', 'App\\Models\\User', 9, '{\"type\":\"owner_new_booking\",\"booking_id\":39,\"field_name\":\"Volley Profesional Venue\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-01T17:00:00.000000Z\",\"start_time\":\"15:00:00\",\"end_time\":\"17:00:00\"}', '2026-06-01 11:54:55', '2026-06-01 10:56:51', '2026-06-01 11:54:55'),
('ab18f2ad-69d1-45f6-aa6a-06c17335a722', 'App\\Notifications\\Owner\\OwnerPaymentReceived', 'App\\Models\\User', 9, '{\"type\":\"owner_payment_received\",\"booking_id\":38,\"field_name\":\"Aquatic Spies Club\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-02T17:00:00.000000Z\"}', '2026-06-01 11:54:55', '2026-06-01 10:54:38', '2026-06-01 11:54:55'),
('ac3288d0-daa6-4351-aebf-c16f8ae74bfd', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 17, '{\"booking_id\":33,\"field_name\":\"Lapangan Voli Veteran\",\"maps_link\":null,\"date\":\"2026-05-31T17:00:00.000000Z\",\"start_time\":\"15:00:00\",\"end_time\":\"17:00:00\",\"type\":\"booking_payment_received\"}', '2026-05-30 17:27:12', '2026-05-30 15:49:32', '2026-05-30 17:27:12'),
('be2a8458-6b36-41c6-b351-8a90c9734ba9', 'App\\Notifications\\PaymentConfirmed', 'App\\Models\\User', 30, '{\"match_id\":170,\"match_title\":\"Arek lanang gas!\",\"field_name\":\"Lapangan Futsal B\",\"match_date\":\"2026-05-29\",\"match_time\":\"13:00:00\",\"type\":\"payment_confirmed\"}', '2026-05-29 05:20:03', '2026-05-29 05:10:01', '2026-05-29 05:20:03'),
('d578b00c-b766-4a0d-848e-7d87e69288a2', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 18, '{\"booking_id\":43,\"field_name\":\"GOR Bimasakti Malang\",\"maps_link\":\"https:\\/\\/www.google.com\\/maps\",\"date\":\"2026-06-11T17:00:00.000000Z\",\"start_time\":\"08:00:00\",\"end_time\":\"10:00:00\",\"type\":\"booking_payment_received\"}', NULL, '2026-06-04 07:25:13', '2026-06-04 07:25:13'),
('da54cddd-79b8-4a16-a320-63da8b51c9f4', 'App\\Notifications\\Owner\\OwnerNewBooking', 'App\\Models\\User', 9, '{\"type\":\"owner_new_booking\",\"booking_id\":40,\"field_name\":\"Elite Hoops Center\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-10T17:00:00.000000Z\",\"start_time\":\"10:00:00\",\"end_time\":\"13:00:00\"}', '2026-06-01 11:54:55', '2026-06-01 11:16:34', '2026-06-01 11:54:55'),
('e08fa2c0-c5df-4752-b854-de678a18c16c', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 18, '{\"booking_id\":38,\"field_name\":\"Aquatic Spies Club\",\"maps_link\":\"https:\\/\\/www.google.com\\/maps\",\"date\":\"2026-06-02T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\",\"type\":\"booking_payment_received\"}', '2026-06-01 11:41:55', '2026-06-01 10:54:38', '2026-06-01 11:41:55'),
('e0ee85c5-5999-4880-8afd-f9efe035f0b0', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 17, '{\"booking_id\":32,\"field_name\":\"GOR Bulu Tangkis Tidar\",\"maps_link\":null,\"date\":\"2026-05-30T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\",\"type\":\"booking_payment_received\"}', '2026-05-30 17:27:12', '2026-05-30 15:46:15', '2026-05-30 17:27:12'),
('e2476515-f03b-4bc9-96b8-43fd60b1b8a4', 'App\\Notifications\\PaymentConfirmed', 'App\\Models\\User', 12, '{\"match_id\":171,\"match_title\":\"Rugby bareng!\",\"field_name\":\"Lapangan Voli Veteran\",\"match_date\":\"2026-06-03\",\"match_time\":\"10:00:00\",\"type\":\"payment_confirmed\"}', NULL, '2026-05-31 14:02:22', '2026-05-31 14:02:22'),
('e2b62501-47d8-4e19-8bb0-202f99e1f921', 'App\\Notifications\\Owner\\OwnerBookingCancelled', 'App\\Models\\User', 9, '{\"type\":\"owner_booking_cancelled\",\"booking_id\":38,\"field_name\":\"Aquatic Spies Club\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-02T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\"}', '2026-06-01 11:54:55', '2026-06-01 11:48:33', '2026-06-01 11:54:55'),
('e2c676ef-43f8-4811-bd97-fd3ac28f8103', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 4, '{\"booking_id\":42,\"field_name\":\"GOR Polinema Grapol\",\"maps_link\":\"https:\\/\\/www.google.com\\/maps\",\"date\":\"2026-06-10T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\",\"type\":\"booking_payment_received\"}', NULL, '2026-06-04 03:45:07', '2026-06-04 03:45:07'),
('fa2927f6-be68-45c2-881f-0dfcc4bc70ca', 'App\\Notifications\\BookingPaymentReceived', 'App\\Models\\User', 6, '{\"booking_id\":31,\"field_name\":\"GOR Bimasakti Malang\",\"date\":\"2026-05-30T17:00:00.000000Z\",\"start_time\":\"17:00:00\",\"end_time\":\"19:00:00\",\"type\":\"booking_payment_received\"}', '2026-05-30 14:15:21', '2026-05-30 14:13:47', '2026-05-30 14:15:21'),
('fed92e3b-c95b-4d02-a0b3-4ff731db373f', 'App\\Notifications\\Owner\\OwnerNewBooking', 'App\\Models\\User', 9, '{\"type\":\"owner_new_booking\",\"booking_id\":37,\"field_name\":\"Champion Futsal Malang\",\"user_name\":\"Sasa\",\"user_id\":18,\"date\":\"2026-06-01T17:00:00.000000Z\",\"start_time\":\"09:00:00\",\"end_time\":\"12:00:00\"}', '2026-05-31 17:38:42', '2026-05-31 17:37:44', '2026-05-31 17:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `field_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED DEFAULT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT '0.0',
  `review` text COLLATE utf8mb4_unicode_ci,
  `photos` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `created_at`, `updated_at`, `user_id`, `field_id`, `booking_id`, `rating`, `review`, `photos`) VALUES
(1, '2026-05-29 14:20:14', '2026-05-29 14:20:14', 6, 7, 24, 5.0, 'BAGUS! Tempatnya enak meski lari-lari adem', NULL),
(2, '2026-05-30 12:39:31', '2026-05-30 12:39:31', 6, 6, 9, 5.0, 'Bagussssss', NULL),
(3, '2026-05-30 12:39:50', '2026-05-30 12:39:50', 6, 2, 26, 5.0, 'MANTAP BANGETT', NULL),
(4, '2026-05-30 13:20:55', '2026-05-30 13:20:55', 30, 5, 18, 4.0, 'okelaahh enak aja', '[]'),
(5, '2026-05-30 14:54:07', '2026-05-30 14:54:07', 30, 5, 25, 5.0, 'tempatnya adem enak aku suka, bakal balik lagi sih!!', '[]'),
(6, '2026-05-30 14:55:08', '2026-05-30 14:55:08', 32, 1, 27, 5.0, 'Tempatnya luas, lahan parkir aman, ada kantin juga enakkk', '[]'),
(7, '2026-05-30 15:48:30', '2026-05-30 15:48:30', 17, 3, 32, 5.0, 'ADA KANTIN ENAK!!', '[]'),
(8, '2026-05-30 15:52:29', '2026-05-30 15:52:29', 17, 4, 33, 4.0, 'Lantainya terlalu licin ah', '[\"review-photos/HHyYceP4z8hZSgyz14DaDInzGToDYM00KCnbnrmE.jpg\"]'),
(9, '2026-05-30 17:29:01', '2026-05-30 17:29:01', 12, 3, 29, 4.5, 'Aman lahhh enak ya', '[]'),
(10, '2026-05-31 15:42:05', '2026-05-31 15:42:05', 4, 1, 15, 4.5, 'Baguslah yaaa dingin enak ga gampang keringet', '[]'),
(11, '2026-05-31 15:49:35', '2026-05-31 15:49:35', 4, 9, 13, 5.0, 'Amat sangat sukaaa bakal balik lagi sih', '[]'),
(12, '2026-05-31 15:55:32', '2026-05-31 15:55:32', 4, 6, 12, 5.0, 'GAS LAH LAGI WOY', '[]'),
(13, '2026-05-31 15:56:37', '2026-05-31 15:56:37', 4, 2, 11, 4.5, 'aman lahh, kantinnya okeee', '[]'),
(14, '2026-05-31 15:56:58', '2026-05-31 15:56:58', 4, 5, 1, 3.5, 'mas yang jaga judes males ah', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('uiYG3UyKDcRZTa254KCPPhKpYydSCvSHvMGqpQSK', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'eyJfdG9rZW4iOiJVN3hSVW41SVhkR1dUMlFoNHVqcTBvUVpRRGUweXN2cE93M0ZvM3pCIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvc3BpZXMtc3BvcnQudGVzdFwvb3duZXJcL2Rhc2hib2FyZCIsInJvdXRlIjoib3duZXIuZGFzaGJvYXJkIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo5fQ==', 1780561860);

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` bigint UNSIGNED NOT NULL,
  `field_id` bigint UNSIGNED NOT NULL,
  `court_number` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `date` date NOT NULL,
  `hour` tinyint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tersedia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slots`
--

INSERT INTO `slots` (`id`, `field_id`, `court_number`, `date`, `hour`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 1, '2026-05-25', 8, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(2, 6, 1, '2026-05-25', 9, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(3, 6, 1, '2026-05-25', 10, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(4, 6, 1, '2026-05-25', 11, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(5, 6, 1, '2026-05-25', 12, 'perbaikan', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(6, 6, 1, '2026-05-25', 13, 'perbaikan', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(7, 6, 1, '2026-05-25', 14, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(8, 6, 1, '2026-05-25', 15, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(9, 6, 1, '2026-05-25', 16, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(10, 6, 1, '2026-05-25', 17, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(11, 6, 1, '2026-05-25', 18, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(12, 6, 1, '2026-05-25', 19, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(13, 6, 1, '2026-05-25', 20, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(14, 6, 1, '2026-05-25', 21, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(15, 7, 1, '2026-05-25', 8, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(16, 7, 1, '2026-05-25', 9, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(17, 7, 1, '2026-05-25', 10, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(18, 7, 1, '2026-05-25', 11, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(19, 7, 1, '2026-05-25', 12, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(20, 7, 1, '2026-05-25', 13, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(21, 7, 1, '2026-05-25', 14, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(22, 7, 1, '2026-05-25', 15, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(23, 7, 1, '2026-05-25', 16, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(24, 7, 1, '2026-05-25', 17, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(25, 7, 1, '2026-05-25', 18, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(26, 7, 1, '2026-05-25', 19, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(27, 7, 1, '2026-05-25', 20, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(28, 7, 1, '2026-05-25', 21, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(29, 8, 1, '2026-05-25', 8, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(30, 8, 1, '2026-05-25', 9, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(31, 8, 1, '2026-05-25', 10, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(32, 8, 1, '2026-05-25', 11, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(33, 8, 1, '2026-05-25', 12, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(34, 8, 1, '2026-05-25', 13, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(35, 8, 1, '2026-05-25', 14, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(36, 8, 1, '2026-05-25', 15, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(37, 8, 1, '2026-05-25', 16, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(38, 8, 1, '2026-05-25', 17, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(39, 8, 1, '2026-05-25', 18, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(40, 8, 1, '2026-05-25', 19, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(41, 8, 1, '2026-05-25', 20, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(42, 8, 1, '2026-05-25', 21, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(43, 9, 1, '2026-05-25', 8, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(44, 9, 1, '2026-05-25', 9, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(45, 9, 1, '2026-05-25', 10, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(46, 9, 1, '2026-05-25', 11, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(47, 9, 1, '2026-05-25', 12, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(48, 9, 1, '2026-05-25', 13, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(49, 9, 1, '2026-05-25', 14, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(50, 9, 1, '2026-05-25', 15, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(51, 9, 1, '2026-05-25', 16, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(52, 9, 1, '2026-05-25', 17, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(53, 9, 1, '2026-05-25', 18, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(54, 9, 1, '2026-05-25', 19, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(55, 9, 1, '2026-05-25', 20, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(56, 9, 1, '2026-05-25', 21, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(57, 6, 1, '2026-05-26', 8, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(58, 6, 1, '2026-05-26', 9, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(59, 6, 1, '2026-05-26', 10, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(60, 6, 1, '2026-05-26', 11, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(61, 6, 1, '2026-05-26', 12, 'perbaikan', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(62, 6, 1, '2026-05-26', 13, 'perbaikan', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(63, 6, 1, '2026-05-26', 14, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(64, 6, 1, '2026-05-26', 15, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(65, 6, 1, '2026-05-26', 16, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(66, 6, 1, '2026-05-26', 17, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(67, 6, 1, '2026-05-26', 18, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(68, 6, 1, '2026-05-26', 19, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(69, 6, 1, '2026-05-26', 20, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(70, 6, 1, '2026-05-26', 21, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(71, 7, 1, '2026-05-26', 8, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(72, 7, 1, '2026-05-26', 9, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(73, 7, 1, '2026-05-26', 10, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(74, 7, 1, '2026-05-26', 11, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(75, 7, 1, '2026-05-26', 12, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(76, 7, 1, '2026-05-26', 13, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(77, 7, 1, '2026-05-26', 14, 'dibooking', '2026-05-25 14:41:39', '2026-06-01 09:38:41'),
(78, 7, 1, '2026-05-26', 15, 'dibooking', '2026-05-25 14:41:39', '2026-06-01 09:38:41'),
(79, 7, 1, '2026-05-26', 16, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(80, 7, 1, '2026-05-26', 17, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(81, 7, 1, '2026-05-26', 18, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(82, 7, 1, '2026-05-26', 19, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(83, 7, 1, '2026-05-26', 20, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(84, 7, 1, '2026-05-26', 21, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(85, 8, 1, '2026-05-26', 8, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(86, 8, 1, '2026-05-26', 9, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(87, 8, 1, '2026-05-26', 10, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(88, 8, 1, '2026-05-26', 11, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(89, 8, 1, '2026-05-26', 12, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(90, 8, 1, '2026-05-26', 13, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(91, 8, 1, '2026-05-26', 14, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(92, 8, 1, '2026-05-26', 15, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(93, 8, 1, '2026-05-26', 16, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(94, 8, 1, '2026-05-26', 17, 'tersedia', '2026-05-25 14:41:39', '2026-05-25 14:41:39'),
(95, 8, 1, '2026-05-26', 18, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(96, 8, 1, '2026-05-26', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(97, 8, 1, '2026-05-26', 20, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(98, 8, 1, '2026-05-26', 21, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(99, 9, 1, '2026-05-26', 8, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(100, 9, 1, '2026-05-26', 9, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(101, 9, 1, '2026-05-26', 10, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(102, 9, 1, '2026-05-26', 11, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(103, 9, 1, '2026-05-26', 12, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(104, 9, 1, '2026-05-26', 13, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(105, 9, 1, '2026-05-26', 14, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(106, 9, 1, '2026-05-26', 15, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(107, 9, 1, '2026-05-26', 16, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(108, 9, 1, '2026-05-26', 17, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(109, 9, 1, '2026-05-26', 18, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(110, 9, 1, '2026-05-26', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(111, 9, 1, '2026-05-26', 20, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(112, 9, 1, '2026-05-26', 21, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(113, 6, 1, '2026-05-27', 8, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(114, 6, 1, '2026-05-27', 9, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(115, 6, 1, '2026-05-27', 10, 'dibooking', '2026-05-25 14:41:40', '2026-06-01 09:38:41'),
(116, 6, 1, '2026-05-27', 11, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(117, 6, 1, '2026-05-27', 12, 'perbaikan', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(118, 6, 1, '2026-05-27', 13, 'perbaikan', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(119, 6, 1, '2026-05-27', 14, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(120, 6, 1, '2026-05-27', 15, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(121, 6, 1, '2026-05-27', 16, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(122, 6, 1, '2026-05-27', 17, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(123, 6, 1, '2026-05-27', 18, 'dibooking', '2026-05-25 14:41:40', '2026-06-01 09:38:41'),
(124, 6, 1, '2026-05-27', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(125, 6, 1, '2026-05-27', 20, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(126, 6, 1, '2026-05-27', 21, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(127, 7, 1, '2026-05-27', 8, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(128, 7, 1, '2026-05-27', 9, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(129, 7, 1, '2026-05-27', 10, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(130, 7, 1, '2026-05-27', 11, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(131, 7, 1, '2026-05-27', 12, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(132, 7, 1, '2026-05-27', 13, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(133, 7, 1, '2026-05-27', 14, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(134, 7, 1, '2026-05-27', 15, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(135, 7, 1, '2026-05-27', 16, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(136, 7, 1, '2026-05-27', 17, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(137, 7, 1, '2026-05-27', 18, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(138, 7, 1, '2026-05-27', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(139, 7, 1, '2026-05-27', 20, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(140, 7, 1, '2026-05-27', 21, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(141, 8, 1, '2026-05-27', 8, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(142, 8, 1, '2026-05-27', 9, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(143, 8, 1, '2026-05-27', 10, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(144, 8, 1, '2026-05-27', 11, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(145, 8, 1, '2026-05-27', 12, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(146, 8, 1, '2026-05-27', 13, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(147, 8, 1, '2026-05-27', 14, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(148, 8, 1, '2026-05-27', 15, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(149, 8, 1, '2026-05-27', 16, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(150, 8, 1, '2026-05-27', 17, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(151, 8, 1, '2026-05-27', 18, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(152, 8, 1, '2026-05-27', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(153, 8, 1, '2026-05-27', 20, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(154, 8, 1, '2026-05-27', 21, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(155, 9, 1, '2026-05-27', 8, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(156, 9, 1, '2026-05-27', 9, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(157, 9, 1, '2026-05-27', 10, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(158, 9, 1, '2026-05-27', 11, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(159, 9, 1, '2026-05-27', 12, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(160, 9, 1, '2026-05-27', 13, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(161, 9, 1, '2026-05-27', 14, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(162, 9, 1, '2026-05-27', 15, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(163, 9, 1, '2026-05-27', 16, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(164, 9, 1, '2026-05-27', 17, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(165, 9, 1, '2026-05-27', 18, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(166, 9, 1, '2026-05-27', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(167, 9, 1, '2026-05-27', 20, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(168, 9, 1, '2026-05-27', 21, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(169, 6, 1, '2026-05-28', 8, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(170, 6, 1, '2026-05-28', 9, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(171, 6, 1, '2026-05-28', 10, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(172, 6, 1, '2026-05-28', 11, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(173, 6, 1, '2026-05-28', 12, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(174, 6, 1, '2026-05-28', 13, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(175, 6, 1, '2026-05-28', 14, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(176, 6, 1, '2026-05-28', 15, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(177, 6, 1, '2026-05-28', 16, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(178, 6, 1, '2026-05-28', 17, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(179, 6, 1, '2026-05-28', 18, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(180, 6, 1, '2026-05-28', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(181, 6, 1, '2026-05-28', 20, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(182, 6, 1, '2026-05-28', 21, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(183, 7, 1, '2026-05-28', 8, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(184, 7, 1, '2026-05-28', 9, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(185, 7, 1, '2026-05-28', 10, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(186, 7, 1, '2026-05-28', 11, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(187, 7, 1, '2026-05-28', 12, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(188, 7, 1, '2026-05-28', 13, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(189, 7, 1, '2026-05-28', 14, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(190, 7, 1, '2026-05-28', 15, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(191, 7, 1, '2026-05-28', 16, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(192, 7, 1, '2026-05-28', 17, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(193, 7, 1, '2026-05-28', 18, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(194, 7, 1, '2026-05-28', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(195, 7, 1, '2026-05-28', 20, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(196, 7, 1, '2026-05-28', 21, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(197, 8, 1, '2026-05-28', 8, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(198, 8, 1, '2026-05-28', 9, 'dibooking', '2026-05-25 14:41:40', '2026-06-01 09:38:41'),
(199, 8, 1, '2026-05-28', 10, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(200, 8, 1, '2026-05-28', 11, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(201, 8, 1, '2026-05-28', 12, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(202, 8, 1, '2026-05-28', 13, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(203, 8, 1, '2026-05-28', 14, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(204, 8, 1, '2026-05-28', 15, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(205, 8, 1, '2026-05-28', 16, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(206, 8, 1, '2026-05-28', 17, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(207, 8, 1, '2026-05-28', 18, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(208, 8, 1, '2026-05-28', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(209, 8, 1, '2026-05-28', 20, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(210, 8, 1, '2026-05-28', 21, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(211, 9, 1, '2026-05-28', 8, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(212, 9, 1, '2026-05-28', 9, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(213, 9, 1, '2026-05-28', 10, 'dibooking', '2026-05-25 14:41:40', '2026-06-01 09:38:41'),
(214, 9, 1, '2026-05-28', 11, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(215, 9, 1, '2026-05-28', 12, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(216, 9, 1, '2026-05-28', 13, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(217, 9, 1, '2026-05-28', 14, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(218, 9, 1, '2026-05-28', 15, 'dibooking', '2026-05-25 14:41:40', '2026-06-01 09:38:41'),
(219, 9, 1, '2026-05-28', 16, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(220, 9, 1, '2026-05-28', 17, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(221, 9, 1, '2026-05-28', 18, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(222, 9, 1, '2026-05-28', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(223, 9, 1, '2026-05-28', 20, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(224, 9, 1, '2026-05-28', 21, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(225, 6, 1, '2026-05-29', 8, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(226, 6, 1, '2026-05-29', 9, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(227, 6, 1, '2026-05-29', 10, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(228, 6, 1, '2026-05-29', 11, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(229, 6, 1, '2026-05-29', 12, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(230, 6, 1, '2026-05-29', 13, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(231, 6, 1, '2026-05-29', 14, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(232, 6, 1, '2026-05-29', 15, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(233, 6, 1, '2026-05-29', 16, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(234, 6, 1, '2026-05-29', 17, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(235, 6, 1, '2026-05-29', 18, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(236, 6, 1, '2026-05-29', 19, 'tersedia', '2026-05-25 14:41:40', '2026-05-25 14:41:40'),
(237, 6, 1, '2026-05-29', 20, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(238, 6, 1, '2026-05-29', 21, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(239, 7, 1, '2026-05-29', 8, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(240, 7, 1, '2026-05-29', 9, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(241, 7, 1, '2026-05-29', 10, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(242, 7, 1, '2026-05-29', 11, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(243, 7, 1, '2026-05-29', 12, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(244, 7, 1, '2026-05-29', 13, 'dibooking', '2026-05-25 14:41:41', '2026-06-01 09:38:41'),
(245, 7, 1, '2026-05-29', 14, 'dibooking', '2026-05-25 14:41:41', '2026-06-01 09:38:41'),
(246, 7, 1, '2026-05-29', 15, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(247, 7, 1, '2026-05-29', 16, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(248, 7, 1, '2026-05-29', 17, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(249, 7, 1, '2026-05-29', 18, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(250, 7, 1, '2026-05-29', 19, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(251, 7, 1, '2026-05-29', 20, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(252, 7, 1, '2026-05-29', 21, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(253, 8, 1, '2026-05-29', 8, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(254, 8, 1, '2026-05-29', 9, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(255, 8, 1, '2026-05-29', 10, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(256, 8, 1, '2026-05-29', 11, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(257, 8, 1, '2026-05-29', 12, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(258, 8, 1, '2026-05-29', 13, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(259, 8, 1, '2026-05-29', 14, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(260, 8, 1, '2026-05-29', 15, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(261, 8, 1, '2026-05-29', 16, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(262, 8, 1, '2026-05-29', 17, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(263, 8, 1, '2026-05-29', 18, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(264, 8, 1, '2026-05-29', 19, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(265, 8, 1, '2026-05-29', 20, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(266, 8, 1, '2026-05-29', 21, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(267, 9, 1, '2026-05-29', 8, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(268, 9, 1, '2026-05-29', 9, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(269, 9, 1, '2026-05-29', 10, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(270, 9, 1, '2026-05-29', 11, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(271, 9, 1, '2026-05-29', 12, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(272, 9, 1, '2026-05-29', 13, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(273, 9, 1, '2026-05-29', 14, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(274, 9, 1, '2026-05-29', 15, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(275, 9, 1, '2026-05-29', 16, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(276, 9, 1, '2026-05-29', 17, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(277, 9, 1, '2026-05-29', 18, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(278, 9, 1, '2026-05-29', 19, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(279, 9, 1, '2026-05-29', 20, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(280, 9, 1, '2026-05-29', 21, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(281, 6, 1, '2026-05-31', 8, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(282, 6, 1, '2026-05-31', 9, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(283, 6, 1, '2026-05-31', 10, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(284, 6, 1, '2026-05-31', 11, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(285, 6, 1, '2026-05-31', 12, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(286, 6, 1, '2026-05-31', 13, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(287, 6, 1, '2026-05-31', 14, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(288, 6, 1, '2026-05-31', 15, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(289, 6, 1, '2026-05-31', 16, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(290, 6, 1, '2026-05-31', 17, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(291, 6, 1, '2026-05-31', 18, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(292, 6, 1, '2026-05-31', 19, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(293, 6, 1, '2026-05-31', 20, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(294, 6, 1, '2026-05-31', 21, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(295, 7, 1, '2026-05-31', 8, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(296, 7, 1, '2026-05-31', 9, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(297, 7, 1, '2026-05-31', 10, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(298, 7, 1, '2026-05-31', 11, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(299, 7, 1, '2026-05-31', 12, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(300, 7, 1, '2026-05-31', 13, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(301, 7, 1, '2026-05-31', 14, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(302, 7, 1, '2026-05-31', 15, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(303, 7, 1, '2026-05-31', 16, 'dibooking', '2026-05-25 14:41:41', '2026-06-01 09:38:41'),
(304, 7, 1, '2026-05-31', 17, 'dibooking', '2026-05-25 14:41:41', '2026-06-01 09:38:41'),
(305, 7, 1, '2026-05-31', 18, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(306, 7, 1, '2026-05-31', 19, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(307, 7, 1, '2026-05-31', 20, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(308, 7, 1, '2026-05-31', 21, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(309, 8, 1, '2026-05-31', 8, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(310, 8, 1, '2026-05-31', 9, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(311, 8, 1, '2026-05-31', 10, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(312, 8, 1, '2026-05-31', 11, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(313, 8, 1, '2026-05-31', 12, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(314, 8, 1, '2026-05-31', 13, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(315, 8, 1, '2026-05-31', 14, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(316, 8, 1, '2026-05-31', 15, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(317, 8, 1, '2026-05-31', 16, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(318, 8, 1, '2026-05-31', 17, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(319, 8, 1, '2026-05-31', 18, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(320, 8, 1, '2026-05-31', 19, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(321, 8, 1, '2026-05-31', 20, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(322, 8, 1, '2026-05-31', 21, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(323, 9, 1, '2026-05-31', 8, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(324, 9, 1, '2026-05-31', 9, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(325, 9, 1, '2026-05-31', 10, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(326, 9, 1, '2026-05-31', 11, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(327, 9, 1, '2026-05-31', 12, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(328, 9, 1, '2026-05-31', 13, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(329, 9, 1, '2026-05-31', 14, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(330, 9, 1, '2026-05-31', 15, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(331, 9, 1, '2026-05-31', 16, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(332, 9, 1, '2026-05-31', 17, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(333, 9, 1, '2026-05-31', 18, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(334, 9, 1, '2026-05-31', 19, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(335, 9, 1, '2026-05-31', 20, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(336, 9, 1, '2026-05-31', 21, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(337, 6, 1, '2026-06-01', 8, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(338, 6, 1, '2026-06-01', 9, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(339, 6, 1, '2026-06-01', 10, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(340, 6, 1, '2026-06-01', 11, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(341, 6, 1, '2026-06-01', 12, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(342, 6, 1, '2026-06-01', 13, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(343, 6, 1, '2026-06-01', 14, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(344, 6, 1, '2026-06-01', 15, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(345, 6, 1, '2026-06-01', 16, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(346, 6, 1, '2026-06-01', 17, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(347, 6, 1, '2026-06-01', 18, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(348, 6, 1, '2026-06-01', 19, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(349, 6, 1, '2026-06-01', 20, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(350, 6, 1, '2026-06-01', 21, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(351, 7, 1, '2026-06-01', 8, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(352, 7, 1, '2026-06-01', 9, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(353, 7, 1, '2026-06-01', 10, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(354, 7, 1, '2026-06-01', 11, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(355, 7, 1, '2026-06-01', 12, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(356, 7, 1, '2026-06-01', 13, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(357, 7, 1, '2026-06-01', 14, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(358, 7, 1, '2026-06-01', 15, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(359, 7, 1, '2026-06-01', 16, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(360, 7, 1, '2026-06-01', 17, 'tersedia', '2026-05-25 14:41:41', '2026-05-25 14:41:41'),
(361, 7, 1, '2026-06-01', 18, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(362, 7, 1, '2026-06-01', 19, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(363, 7, 1, '2026-06-01', 20, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(364, 7, 1, '2026-06-01', 21, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(365, 8, 1, '2026-06-01', 8, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(366, 8, 1, '2026-06-01', 9, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(367, 8, 1, '2026-06-01', 10, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(368, 8, 1, '2026-06-01', 11, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(369, 8, 1, '2026-06-01', 12, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(370, 8, 1, '2026-06-01', 13, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(371, 8, 1, '2026-06-01', 14, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(372, 8, 1, '2026-06-01', 15, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(373, 8, 1, '2026-06-01', 16, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(374, 8, 1, '2026-06-01', 17, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(375, 8, 1, '2026-06-01', 18, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(376, 8, 1, '2026-06-01', 19, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(377, 8, 1, '2026-06-01', 20, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(378, 8, 1, '2026-06-01', 21, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(379, 9, 1, '2026-06-01', 8, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(380, 9, 1, '2026-06-01', 9, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(381, 9, 1, '2026-06-01', 10, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(382, 9, 1, '2026-06-01', 11, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(383, 9, 1, '2026-06-01', 12, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(384, 9, 1, '2026-06-01', 13, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(385, 9, 1, '2026-06-01', 14, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(386, 9, 1, '2026-06-01', 15, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(387, 9, 1, '2026-06-01', 16, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(388, 9, 1, '2026-06-01', 17, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(389, 9, 1, '2026-06-01', 18, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(390, 9, 1, '2026-06-01', 19, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(391, 9, 1, '2026-06-01', 20, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(392, 9, 1, '2026-06-01', 21, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(393, 6, 1, '2026-06-02', 8, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(394, 6, 1, '2026-06-02', 9, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(395, 6, 1, '2026-06-02', 10, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(396, 6, 1, '2026-06-02', 11, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(397, 6, 1, '2026-06-02', 12, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(398, 6, 1, '2026-06-02', 13, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(399, 6, 1, '2026-06-02', 14, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(400, 6, 1, '2026-06-02', 15, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(401, 6, 1, '2026-06-02', 16, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(402, 6, 1, '2026-06-02', 17, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(403, 6, 1, '2026-06-02', 18, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(404, 6, 1, '2026-06-02', 19, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(405, 6, 1, '2026-06-02', 20, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(406, 6, 1, '2026-06-02', 21, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(407, 7, 1, '2026-06-02', 8, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(408, 7, 1, '2026-06-02', 9, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(409, 7, 1, '2026-06-02', 10, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(410, 7, 1, '2026-06-02', 11, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(411, 7, 1, '2026-06-02', 12, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(412, 7, 1, '2026-06-02', 13, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(413, 7, 1, '2026-06-02', 14, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(414, 7, 1, '2026-06-02', 15, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(415, 7, 1, '2026-06-02', 16, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(416, 7, 1, '2026-06-02', 17, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(417, 7, 1, '2026-06-02', 18, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(418, 7, 1, '2026-06-02', 19, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(419, 7, 1, '2026-06-02', 20, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(420, 7, 1, '2026-06-02', 21, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(421, 8, 1, '2026-06-02', 8, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(422, 8, 1, '2026-06-02', 9, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(423, 8, 1, '2026-06-02', 10, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(424, 8, 1, '2026-06-02', 11, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(425, 8, 1, '2026-06-02', 12, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(426, 8, 1, '2026-06-02', 13, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(427, 8, 1, '2026-06-02', 14, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(428, 8, 1, '2026-06-02', 15, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(429, 8, 1, '2026-06-02', 16, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(430, 8, 1, '2026-06-02', 17, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(431, 8, 1, '2026-06-02', 18, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(432, 8, 1, '2026-06-02', 19, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(433, 8, 1, '2026-06-02', 20, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(434, 8, 1, '2026-06-02', 21, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(435, 9, 1, '2026-06-02', 8, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(436, 9, 1, '2026-06-02', 9, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(437, 9, 1, '2026-06-02', 10, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(438, 9, 1, '2026-06-02', 11, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(439, 9, 1, '2026-06-02', 12, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(440, 9, 1, '2026-06-02', 13, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(441, 9, 1, '2026-06-02', 14, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(442, 9, 1, '2026-06-02', 15, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(443, 9, 1, '2026-06-02', 16, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(444, 9, 1, '2026-06-02', 17, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(445, 9, 1, '2026-06-02', 18, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(446, 9, 1, '2026-06-02', 19, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(447, 9, 1, '2026-06-02', 20, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(448, 9, 1, '2026-06-02', 21, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(449, 6, 1, '2026-06-03', 8, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(450, 6, 1, '2026-06-03', 9, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(451, 6, 1, '2026-06-03', 10, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(452, 6, 1, '2026-06-03', 11, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(453, 6, 1, '2026-06-03', 12, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(454, 6, 1, '2026-06-03', 13, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(455, 6, 1, '2026-06-03', 14, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(456, 6, 1, '2026-06-03', 15, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(457, 6, 1, '2026-06-03', 16, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(458, 6, 1, '2026-06-03', 17, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(459, 6, 1, '2026-06-03', 18, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(460, 6, 1, '2026-06-03', 19, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(461, 6, 1, '2026-06-03', 20, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(462, 6, 1, '2026-06-03', 21, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(463, 7, 1, '2026-06-03', 8, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(464, 7, 1, '2026-06-03', 9, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(465, 7, 1, '2026-06-03', 10, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(466, 7, 1, '2026-06-03', 11, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(467, 7, 1, '2026-06-03', 12, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(468, 7, 1, '2026-06-03', 13, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(469, 7, 1, '2026-06-03', 14, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(470, 7, 1, '2026-06-03', 15, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(471, 7, 1, '2026-06-03', 16, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(472, 7, 1, '2026-06-03', 17, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(473, 7, 1, '2026-06-03', 18, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(474, 7, 1, '2026-06-03', 19, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(475, 7, 1, '2026-06-03', 20, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(476, 7, 1, '2026-06-03', 21, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(477, 8, 1, '2026-06-03', 8, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(478, 8, 1, '2026-06-03', 9, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(479, 8, 1, '2026-06-03', 10, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(480, 8, 1, '2026-06-03', 11, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(481, 8, 1, '2026-06-03', 12, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(482, 8, 1, '2026-06-03', 13, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(483, 8, 1, '2026-06-03', 14, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(484, 8, 1, '2026-06-03', 15, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(485, 8, 1, '2026-06-03', 16, 'tersedia', '2026-05-25 14:41:42', '2026-05-25 14:41:42'),
(486, 8, 1, '2026-06-03', 17, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(487, 8, 1, '2026-06-03', 18, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(488, 8, 1, '2026-06-03', 19, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(489, 8, 1, '2026-06-03', 20, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(490, 8, 1, '2026-06-03', 21, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(491, 9, 1, '2026-06-03', 8, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(492, 9, 1, '2026-06-03', 9, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(493, 9, 1, '2026-06-03', 10, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(494, 9, 1, '2026-06-03', 11, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(495, 9, 1, '2026-06-03', 12, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(496, 9, 1, '2026-06-03', 13, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(497, 9, 1, '2026-06-03', 14, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(498, 9, 1, '2026-06-03', 15, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(499, 9, 1, '2026-06-03', 16, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(500, 9, 1, '2026-06-03', 17, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(501, 9, 1, '2026-06-03', 18, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(502, 9, 1, '2026-06-03', 19, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(503, 9, 1, '2026-06-03', 20, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(504, 9, 1, '2026-06-03', 21, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(505, 6, 1, '2026-06-05', 8, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(506, 6, 1, '2026-06-05', 9, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(507, 6, 1, '2026-06-05', 10, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(508, 6, 1, '2026-06-05', 11, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(509, 6, 1, '2026-06-05', 12, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(510, 6, 1, '2026-06-05', 13, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(511, 6, 1, '2026-06-05', 14, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(512, 6, 1, '2026-06-05', 15, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(513, 6, 1, '2026-06-05', 16, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(514, 6, 1, '2026-06-05', 17, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(515, 6, 1, '2026-06-05', 18, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(516, 6, 1, '2026-06-05', 19, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(517, 6, 1, '2026-06-05', 20, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(518, 6, 1, '2026-06-05', 21, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(519, 7, 1, '2026-06-05', 8, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(520, 7, 1, '2026-06-05', 9, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(521, 7, 1, '2026-06-05', 10, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(522, 7, 1, '2026-06-05', 11, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(523, 7, 1, '2026-06-05', 12, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(524, 7, 1, '2026-06-05', 13, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(525, 7, 1, '2026-06-05', 14, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(526, 7, 1, '2026-06-05', 15, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(527, 7, 1, '2026-06-05', 16, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(528, 7, 1, '2026-06-05', 17, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(529, 7, 1, '2026-06-05', 18, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(530, 7, 1, '2026-06-05', 19, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(531, 7, 1, '2026-06-05', 20, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(532, 7, 1, '2026-06-05', 21, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(533, 8, 1, '2026-06-05', 8, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(534, 8, 1, '2026-06-05', 9, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(535, 8, 1, '2026-06-05', 10, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(536, 8, 1, '2026-06-05', 11, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(537, 8, 1, '2026-06-05', 12, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(538, 8, 1, '2026-06-05', 13, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(539, 8, 1, '2026-06-05', 14, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(540, 8, 1, '2026-06-05', 15, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(541, 8, 1, '2026-06-05', 16, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(542, 8, 1, '2026-06-05', 17, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(543, 8, 1, '2026-06-05', 18, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(544, 8, 1, '2026-06-05', 19, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(545, 8, 1, '2026-06-05', 20, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(546, 8, 1, '2026-06-05', 21, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(547, 9, 1, '2026-06-05', 8, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(548, 9, 1, '2026-06-05', 9, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(549, 9, 1, '2026-06-05', 10, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(550, 9, 1, '2026-06-05', 11, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(551, 9, 1, '2026-06-05', 12, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(552, 9, 1, '2026-06-05', 13, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(553, 9, 1, '2026-06-05', 14, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(554, 9, 1, '2026-06-05', 15, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(555, 9, 1, '2026-06-05', 16, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(556, 9, 1, '2026-06-05', 17, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(557, 9, 1, '2026-06-05', 18, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(558, 9, 1, '2026-06-05', 19, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(559, 9, 1, '2026-06-05', 20, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(560, 9, 1, '2026-06-05', 21, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(561, 6, 1, '2026-06-06', 8, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(562, 6, 1, '2026-06-06', 9, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(563, 6, 1, '2026-06-06', 10, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(564, 6, 1, '2026-06-06', 11, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(565, 6, 1, '2026-06-06', 12, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(566, 6, 1, '2026-06-06', 13, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(567, 6, 1, '2026-06-06', 14, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(568, 6, 1, '2026-06-06', 15, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(569, 6, 1, '2026-06-06', 16, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(570, 6, 1, '2026-06-06', 17, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(571, 6, 1, '2026-06-06', 18, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(572, 6, 1, '2026-06-06', 19, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(573, 6, 1, '2026-06-06', 20, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(574, 6, 1, '2026-06-06', 21, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(575, 7, 1, '2026-06-06', 8, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43');
INSERT INTO `slots` (`id`, `field_id`, `court_number`, `date`, `hour`, `status`, `created_at`, `updated_at`) VALUES
(576, 7, 1, '2026-06-06', 9, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(577, 7, 1, '2026-06-06', 10, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(578, 7, 1, '2026-06-06', 11, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(579, 7, 1, '2026-06-06', 12, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(580, 7, 1, '2026-06-06', 13, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(581, 7, 1, '2026-06-06', 14, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(582, 7, 1, '2026-06-06', 15, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(583, 7, 1, '2026-06-06', 16, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(584, 7, 1, '2026-06-06', 17, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(585, 7, 1, '2026-06-06', 18, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(586, 7, 1, '2026-06-06', 19, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(587, 7, 1, '2026-06-06', 20, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(588, 7, 1, '2026-06-06', 21, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(589, 8, 1, '2026-06-06', 8, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(590, 8, 1, '2026-06-06', 9, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(591, 8, 1, '2026-06-06', 10, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(592, 8, 1, '2026-06-06', 11, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(593, 8, 1, '2026-06-06', 12, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(594, 8, 1, '2026-06-06', 13, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(595, 8, 1, '2026-06-06', 14, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(596, 8, 1, '2026-06-06', 15, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(597, 8, 1, '2026-06-06', 16, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(598, 8, 1, '2026-06-06', 17, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(599, 8, 1, '2026-06-06', 18, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(600, 8, 1, '2026-06-06', 19, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(601, 8, 1, '2026-06-06', 20, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(602, 8, 1, '2026-06-06', 21, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(603, 9, 1, '2026-06-06', 8, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(604, 9, 1, '2026-06-06', 9, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(605, 9, 1, '2026-06-06', 10, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(606, 9, 1, '2026-06-06', 11, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(607, 9, 1, '2026-06-06', 12, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(608, 9, 1, '2026-06-06', 13, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(609, 9, 1, '2026-06-06', 14, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(610, 9, 1, '2026-06-06', 15, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(611, 9, 1, '2026-06-06', 16, 'tersedia', '2026-05-25 14:41:43', '2026-05-25 14:41:43'),
(612, 9, 1, '2026-06-06', 17, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(613, 9, 1, '2026-06-06', 18, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(614, 9, 1, '2026-06-06', 19, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(615, 9, 1, '2026-06-06', 20, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(616, 9, 1, '2026-06-06', 21, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(617, 6, 1, '2026-06-07', 8, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(618, 6, 1, '2026-06-07', 9, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(619, 6, 1, '2026-06-07', 10, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(620, 6, 1, '2026-06-07', 11, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(621, 6, 1, '2026-06-07', 12, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(622, 6, 1, '2026-06-07', 13, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(623, 6, 1, '2026-06-07', 14, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(624, 6, 1, '2026-06-07', 15, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(625, 6, 1, '2026-06-07', 16, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(626, 6, 1, '2026-06-07', 17, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(627, 6, 1, '2026-06-07', 18, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(628, 6, 1, '2026-06-07', 19, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(629, 6, 1, '2026-06-07', 20, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(630, 6, 1, '2026-06-07', 21, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(631, 7, 1, '2026-06-07', 8, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(632, 7, 1, '2026-06-07', 9, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(633, 7, 1, '2026-06-07', 10, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(634, 7, 1, '2026-06-07', 11, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(635, 7, 1, '2026-06-07', 12, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(636, 7, 1, '2026-06-07', 13, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(637, 7, 1, '2026-06-07', 14, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(638, 7, 1, '2026-06-07', 15, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(639, 7, 1, '2026-06-07', 16, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(640, 7, 1, '2026-06-07', 17, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(641, 7, 1, '2026-06-07', 18, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(642, 7, 1, '2026-06-07', 19, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(643, 7, 1, '2026-06-07', 20, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(644, 7, 1, '2026-06-07', 21, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(645, 8, 1, '2026-06-07', 8, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(646, 8, 1, '2026-06-07', 9, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(647, 8, 1, '2026-06-07', 10, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(648, 8, 1, '2026-06-07', 11, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(649, 8, 1, '2026-06-07', 12, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(650, 8, 1, '2026-06-07', 13, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(651, 8, 1, '2026-06-07', 14, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(652, 8, 1, '2026-06-07', 15, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(653, 8, 1, '2026-06-07', 16, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(654, 8, 1, '2026-06-07', 17, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(655, 8, 1, '2026-06-07', 18, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(656, 8, 1, '2026-06-07', 19, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(657, 8, 1, '2026-06-07', 20, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(658, 8, 1, '2026-06-07', 21, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(659, 9, 1, '2026-06-07', 8, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(660, 9, 1, '2026-06-07', 9, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(661, 9, 1, '2026-06-07', 10, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(662, 9, 1, '2026-06-07', 11, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(663, 9, 1, '2026-06-07', 12, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(664, 9, 1, '2026-06-07', 13, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(665, 9, 1, '2026-06-07', 14, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(666, 9, 1, '2026-06-07', 15, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(667, 9, 1, '2026-06-07', 16, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(668, 9, 1, '2026-06-07', 17, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(669, 9, 1, '2026-06-07', 18, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(670, 9, 1, '2026-06-07', 19, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(671, 9, 1, '2026-06-07', 20, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(672, 9, 1, '2026-06-07', 21, 'tersedia', '2026-05-25 14:41:44', '2026-05-25 14:41:44'),
(688, 1, 1, '2026-06-03', 8, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(689, 1, 1, '2026-06-03', 9, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(690, 1, 1, '2026-06-03', 10, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(691, 1, 1, '2026-06-03', 11, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(692, 1, 1, '2026-06-03', 12, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(693, 1, 1, '2026-06-03', 13, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(694, 1, 1, '2026-06-03', 14, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(695, 1, 1, '2026-06-03', 15, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(696, 1, 1, '2026-06-03', 16, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(697, 1, 1, '2026-06-03', 17, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(698, 1, 1, '2026-06-03', 18, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(699, 1, 1, '2026-06-03', 19, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(700, 1, 1, '2026-06-03', 20, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(701, 1, 1, '2026-06-03', 21, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(702, 1, 1, '2026-06-03', 22, 'tutup', '2026-05-31 19:46:14', '2026-05-31 19:46:14'),
(703, 1, 1, '2026-06-04', 8, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(704, 1, 1, '2026-06-04', 9, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(705, 1, 1, '2026-06-04', 10, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(706, 1, 1, '2026-06-04', 11, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(707, 1, 1, '2026-06-04', 12, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(708, 1, 1, '2026-06-04', 13, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(709, 1, 1, '2026-06-04', 14, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(710, 1, 1, '2026-06-04', 15, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(711, 1, 1, '2026-06-04', 16, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(712, 1, 1, '2026-06-04', 17, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(713, 1, 1, '2026-06-04', 18, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(714, 1, 1, '2026-06-04', 19, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(715, 1, 1, '2026-06-04', 20, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(716, 1, 1, '2026-06-04', 21, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(717, 1, 1, '2026-06-04', 22, 'tutup', '2026-05-31 20:08:33', '2026-05-31 20:08:33'),
(718, 1, 1, '2026-06-06', 8, 'tersedia', '2026-05-31 20:15:46', '2026-05-31 20:16:34'),
(719, 1, 1, '2026-06-01', 8, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 07:34:07'),
(720, 1, 1, '2026-06-01', 9, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 07:34:08'),
(721, 1, 1, '2026-06-01', 10, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 07:34:08'),
(722, 1, 1, '2026-06-01', 11, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 07:34:08'),
(723, 1, 1, '2026-06-01', 12, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 07:34:08'),
(724, 1, 1, '2026-06-01', 13, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 07:34:08'),
(725, 1, 1, '2026-06-01', 14, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 09:30:53'),
(726, 1, 1, '2026-06-01', 15, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 09:30:53'),
(727, 1, 1, '2026-06-01', 16, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 09:30:53'),
(728, 1, 1, '2026-06-01', 17, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 09:30:53'),
(729, 1, 1, '2026-06-01', 18, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 09:30:53'),
(730, 1, 1, '2026-06-01', 19, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 09:30:53'),
(731, 1, 1, '2026-06-01', 20, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 09:30:53'),
(732, 1, 1, '2026-06-01', 21, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 09:30:53'),
(733, 1, 1, '2026-06-01', 22, 'tersedia', '2026-05-31 20:16:55', '2026-06-01 09:30:53'),
(734, 5, 1, '2026-05-13', 9, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(735, 1, 1, '2026-05-26', 10, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(736, 1, 1, '2026-05-26', 9, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(737, 6, 1, '2026-05-24', 10, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(738, 9, 1, '2026-05-20', 8, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(739, 2, 1, '2026-05-27', 12, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(740, 1, 1, '2026-05-27', 19, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(741, 1, 1, '2026-05-29', 11, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(742, 1, 1, '2026-05-27', 9, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(743, 5, 1, '2026-05-29', 12, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(744, 5, 1, '2026-05-28', 12, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(745, 5, 1, '2026-05-30', 15, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(746, 2, 1, '2026-05-31', 15, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(747, 1, 1, '2026-05-31', 14, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(748, 3, 1, '2026-05-31', 13, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(749, 3, 1, '2026-06-02', 12, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(750, 2, 1, '2026-05-31', 13, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(751, 2, 1, '2026-05-31', 14, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(752, 1, 1, '2026-05-31', 17, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(753, 1, 1, '2026-05-31', 18, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(754, 3, 1, '2026-05-31', 9, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(755, 3, 1, '2026-05-31', 10, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(756, 3, 1, '2026-05-31', 11, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(757, 4, 1, '2026-06-01', 15, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(758, 4, 1, '2026-06-01', 16, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(759, 1, 1, '2026-06-02', 12, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(760, 1, 1, '2026-06-02', 13, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(761, 1, 1, '2026-06-02', 14, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(762, 4, 1, '2026-06-03', 10, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(763, 4, 1, '2026-06-03', 11, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(764, 5, 1, '2026-06-02', 9, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(765, 5, 1, '2026-06-02', 10, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(766, 2, 1, '2026-06-02', 9, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(767, 2, 1, '2026-06-02', 10, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(768, 2, 1, '2026-06-02', 11, 'dibooking', '2026-06-01 09:38:41', '2026-06-01 09:38:41'),
(769, 13, 1, '2026-06-11', 10, 'dibooking', '2026-06-01 11:16:40', '2026-06-01 11:16:40'),
(770, 13, 1, '2026-06-11', 11, 'dibooking', '2026-06-01 11:16:40', '2026-06-01 11:16:40'),
(771, 13, 1, '2026-06-11', 12, 'dibooking', '2026-06-01 11:16:40', '2026-06-01 11:16:40'),
(773, 13, 2, '2026-06-11', 10, 'dibooking', '2026-06-01 11:20:17', '2026-06-01 11:20:17'),
(774, 13, 2, '2026-06-11', 11, 'dibooking', '2026-06-01 11:20:17', '2026-06-01 11:20:17'),
(775, 13, 2, '2026-06-11', 12, 'dibooking', '2026-06-01 11:20:17', '2026-06-01 11:20:17'),
(776, 16, 1, '2026-06-03', 9, 'tersedia', '2026-06-01 11:23:35', '2026-06-01 11:48:32'),
(777, 16, 1, '2026-06-03', 10, 'tersedia', '2026-06-01 11:23:35', '2026-06-01 11:48:32'),
(778, 16, 1, '2026-06-03', 11, 'tersedia', '2026-06-01 11:23:35', '2026-06-01 11:48:32'),
(779, 15, 1, '2026-06-02', 15, 'dibooking', '2026-06-01 11:41:13', '2026-06-01 11:41:13'),
(780, 15, 1, '2026-06-02', 16, 'dibooking', '2026-06-01 11:41:13', '2026-06-01 11:41:13'),
(781, 10, 1, '2026-06-09', 9, 'dibooking', '2026-06-02 10:18:55', '2026-06-02 10:18:55'),
(782, 10, 1, '2026-06-09', 10, 'dibooking', '2026-06-02 10:18:55', '2026-06-02 10:18:55'),
(783, 18, 1, '2026-06-11', 9, 'dibooking', '2026-06-04 03:45:07', '2026-06-04 03:45:07'),
(784, 18, 1, '2026-06-11', 10, 'dibooking', '2026-06-04 03:45:07', '2026-06-04 03:45:07'),
(785, 18, 1, '2026-06-11', 11, 'dibooking', '2026-06-04 03:45:07', '2026-06-04 03:45:07'),
(786, 1, 1, '2026-06-09', 8, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(787, 1, 2, '2026-06-09', 8, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(788, 1, 3, '2026-06-09', 8, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(789, 1, 4, '2026-06-09', 8, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(790, 1, 5, '2026-06-09', 8, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(791, 1, 6, '2026-06-09', 8, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(792, 1, 1, '2026-06-09', 9, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(793, 1, 2, '2026-06-09', 9, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(794, 1, 3, '2026-06-09', 9, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(795, 1, 4, '2026-06-09', 9, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(796, 1, 5, '2026-06-09', 9, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(797, 1, 6, '2026-06-09', 9, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(798, 1, 1, '2026-06-09', 10, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(799, 1, 2, '2026-06-09', 10, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(800, 1, 3, '2026-06-09', 10, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(801, 1, 4, '2026-06-09', 10, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(802, 1, 5, '2026-06-09', 10, 'tutup', '2026-06-04 04:15:10', '2026-06-04 04:15:10'),
(803, 1, 6, '2026-06-09', 10, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(804, 1, 1, '2026-06-09', 11, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(805, 1, 2, '2026-06-09', 11, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(806, 1, 3, '2026-06-09', 11, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(807, 1, 4, '2026-06-09', 11, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(808, 1, 5, '2026-06-09', 11, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(809, 1, 6, '2026-06-09', 11, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(810, 1, 1, '2026-06-09', 12, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(811, 1, 2, '2026-06-09', 12, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(812, 1, 3, '2026-06-09', 12, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(813, 1, 4, '2026-06-09', 12, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(814, 1, 5, '2026-06-09', 12, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(815, 1, 6, '2026-06-09', 12, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(816, 1, 1, '2026-06-09', 13, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(817, 1, 2, '2026-06-09', 13, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(818, 1, 3, '2026-06-09', 13, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(819, 1, 4, '2026-06-09', 13, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(820, 1, 5, '2026-06-09', 13, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(821, 1, 6, '2026-06-09', 13, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(822, 1, 1, '2026-06-09', 14, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(823, 1, 2, '2026-06-09', 14, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(824, 1, 3, '2026-06-09', 14, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(825, 1, 4, '2026-06-09', 14, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(826, 1, 5, '2026-06-09', 14, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(827, 1, 6, '2026-06-09', 14, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(828, 1, 1, '2026-06-09', 15, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(829, 1, 2, '2026-06-09', 15, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(830, 1, 3, '2026-06-09', 15, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(831, 1, 4, '2026-06-09', 15, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(832, 1, 5, '2026-06-09', 15, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(833, 1, 6, '2026-06-09', 15, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(834, 1, 1, '2026-06-09', 16, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(835, 1, 2, '2026-06-09', 16, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(836, 1, 3, '2026-06-09', 16, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(837, 1, 4, '2026-06-09', 16, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(838, 1, 5, '2026-06-09', 16, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(839, 1, 6, '2026-06-09', 16, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(840, 1, 1, '2026-06-09', 17, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(841, 1, 2, '2026-06-09', 17, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(842, 1, 3, '2026-06-09', 17, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(843, 1, 4, '2026-06-09', 17, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(844, 1, 5, '2026-06-09', 17, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(845, 1, 6, '2026-06-09', 17, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(846, 1, 1, '2026-06-09', 18, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(847, 1, 2, '2026-06-09', 18, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(848, 1, 3, '2026-06-09', 18, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(849, 1, 4, '2026-06-09', 18, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(850, 1, 5, '2026-06-09', 18, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(851, 1, 6, '2026-06-09', 18, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(852, 1, 1, '2026-06-09', 19, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(853, 1, 2, '2026-06-09', 19, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(854, 1, 3, '2026-06-09', 19, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(855, 1, 4, '2026-06-09', 19, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(856, 1, 5, '2026-06-09', 19, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(857, 1, 6, '2026-06-09', 19, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(858, 1, 1, '2026-06-09', 20, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(859, 1, 2, '2026-06-09', 20, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(860, 1, 3, '2026-06-09', 20, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(861, 1, 4, '2026-06-09', 20, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(862, 1, 5, '2026-06-09', 20, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(863, 1, 6, '2026-06-09', 20, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(864, 1, 1, '2026-06-09', 21, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(865, 1, 2, '2026-06-09', 21, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(866, 1, 3, '2026-06-09', 21, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(867, 1, 4, '2026-06-09', 21, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(868, 1, 5, '2026-06-09', 21, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(869, 1, 6, '2026-06-09', 21, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(870, 1, 1, '2026-06-09', 22, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(871, 1, 2, '2026-06-09', 22, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(872, 1, 3, '2026-06-09', 22, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(873, 1, 4, '2026-06-09', 22, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(874, 1, 5, '2026-06-09', 22, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(875, 1, 6, '2026-06-09', 22, 'tutup', '2026-06-04 04:15:11', '2026-06-04 04:15:11'),
(876, 1, 1, '2026-06-12', 8, 'dibooking', '2026-06-04 07:25:13', '2026-06-04 07:25:13'),
(877, 1, 1, '2026-06-12', 9, 'dibooking', '2026-06-04 07:25:13', '2026-06-04 07:25:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'player',
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `sport_preference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skill_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_partner` tinyint(1) NOT NULL DEFAULT '0',
  `points` int NOT NULL DEFAULT '0',
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `gender`, `avatar_profile`, `cover_photo`, `phone`, `bio`, `sport_preference`, `skill_level`, `open_partner`, `points`, `city`, `address`) VALUES
(3, 'Codex Player', NULL, 'codex.player@example.com', NULL, '$2y$12$0sgNeT6Aeya1NCmxMbULlODaYodWI/JptanY3CVkPxXBJKXF7JbuG', NULL, '2026-05-03 23:55:24', '2026-05-03 23:55:24', 'player', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(4, 'nasywaaaa', 'wawaa111', 'wawates2@gmail.com', NULL, '$2y$12$LgvSl3DLgK29WQeEcnpDXe3JUu0DQorqu2CGTWaFcqIzSLiCsKrH.', NULL, '2026-05-05 04:06:47', '2026-05-30 10:08:07', 'player', 'perempuan', 'avatars/in3n74w55S9IpVvaJHYNrxF7GyWVVdWqCQG8JoGA.png', 'covers/D7Z8mS9r1mAohWamOT1Yn5HWtNwdmwCG3PvUEG3F.jpg', '+62 878-9851-2191', 'Ini cuma test', 'Badminton, Renang', 'pemula', 1, 0, 'Suarabaya', NULL),
(5, 'owner11', 'owneerr11', 'owner@gmail.com', NULL, '$2y$12$dkxyq2xslAgESqrnAh9XJemvQYy.J7DXRCh7m.1JBbmNXfx2dwYi2', NULL, '2026-05-05 04:13:01', '2026-05-05 04:13:01', 'owner', NULL, 'profil1.png', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(6, 'namtan', 'namtan88', 'namtan@gmail.com', NULL, '$2y$12$r1JHa4IqaPnspEDwnwF4G.k4DXop2ON3nPbQsyKJrbMEc8f.N6leK', NULL, '2026-05-05 04:25:37', '2026-05-29 05:27:50', 'player', 'laki-laki', 'avatars/jhBFRlj32maC1JDzRwMys8N7QtPpqJTQksik74Z6.jpg', 'covers/vtaPHm2h89gce2ukd89yPA5DurrWoOc0zU2KyjjZ.jpg', '081213563244', 'Futsal Pride', 'Futsal', 'pemula', 1, 0, 'Malang', NULL),
(7, 'nahda', 'nahhhdaa1', 'test@gmail.com', NULL, '$2y$12$YYQo3wSe77qPFiucsxnUQ.rFnEoSV6rtSPXqIZlzc7XO/BGtVMVEa', NULL, '2026-05-05 19:22:49', '2026-05-05 19:22:49', 'owner', NULL, 'profil1.png', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(8, 'Test User', NULL, 'test@example.com', NULL, '$2y$12$LQgEfl2QlmvLEEkAZkQjpuky23x39OHIbKDQsyShq3Sh48bfUh.zy', NULL, '2026-05-07 19:12:59', '2026-05-07 19:12:59', 'player', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(9, 'Owner Swipe', NULL, 'owner.swipe@example.com', NULL, '$2y$12$J0xaCv3X3q6Yp/zqMi4HFuA2NIg1utcec.qIx1Ml85R/R1.76K7ES', NULL, '2026-05-07 19:12:59', '2026-06-01 16:51:04', 'owner', NULL, NULL, NULL, '08123456789', NULL, NULL, NULL, 0, 0, NULL, 'Jakarta Selatan, DKI Jakarta'),
(10, 'Rio', NULL, 'rio.swipe@example.com', NULL, '$2y$12$nCP.SqreYnBmPTf5zzwbTOfWWcTlEJ5C822PEKcpIAerw6kET7tsW', NULL, '2026-05-07 19:12:59', '2026-05-07 19:12:59', 'player', 'laki-laki', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(11, 'Dito', NULL, 'dito.swipe@example.com', NULL, '$2y$12$FxZG0Ph5gyXW/UC2WrVyGuLgazYnAZKJ5530aAjY33RWKF2bgZ1Ry', NULL, '2026-05-07 19:12:59', '2026-05-07 19:12:59', 'player', 'laki-laki', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(12, 'Ayu', 'ayuanda', 'ayu.swipe@example.com', NULL, '$2y$12$KK9w.yIw9qcwJUgdzlZlfeKIUD6Hpdb4n22i7S2lOeRjyLJbjRz2u', NULL, '2026-05-07 19:12:59', '2026-05-30 10:01:04', 'player', 'perempuan', NULL, NULL, '+6298764335788', 'Testing', NULL, 'pemula', 1, 0, 'Malang', NULL),
(13, 'Nia', NULL, 'nia.swipe@example.com', NULL, '$2y$12$hrgRc4vOvK/AN.ExRQY9SuK5Ny/ArksjOXnrp2S5axj4YrD3gXnU.', NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00', 'player', 'perempuan', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(14, 'Rian', NULL, 'rian.swipe@example.com', NULL, '$2y$12$ejxQ7t/uzAfSjv1KnBzgAe3s0v0G4IFaq2g9UdI.5LHmf2Y4pYO2y', NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00', 'player', 'laki-laki', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(15, 'Rafi', NULL, 'rafi.swipe@example.com', NULL, '$2y$12$m.WNZZFIOVdgaZIdef08E.fqo.e28n3AETDKjK2u6KD6zmY2SNoma', NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00', 'player', 'laki-laki', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(16, 'Anya', NULL, 'anya.swipe@example.com', NULL, '$2y$12$QdbAbtZPhLbpkRybFKNlNet4FFVrOer9HPSHrZVkM/7rqo4RHMq5y', NULL, '2026-05-07 19:13:00', '2026-05-07 19:13:00', 'player', 'perempuan', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(17, 'Bimo', 'bimbim', 'bimo.swipe@example.com', NULL, '$2y$12$MVYg3ilWZKIRuHZavgiUCuvylx.54hOrdVUfJ0c0H7Y5Ixn3aYqwi', NULL, '2026-05-07 19:13:00', '2026-05-30 17:25:58', 'player', 'laki-laki', NULL, NULL, '+6297766566879', 'Test', NULL, NULL, 1, 0, 'Malang', NULL),
(18, 'Sasa', 'sasalemon3', 'sasa.swipe@example.com', NULL, '$2y$12$WbnqYmBKZ3xqozjMS0iQJOIWPcNODGNfMjv.TO3loOCMD5rJ9FjJi', NULL, '2026-05-08 01:55:46', '2026-05-31 13:57:17', 'player', 'perempuan', NULL, NULL, '+6298764335788', 'Cerita tentang aku yang manis dan lucu!', NULL, NULL, 1, 0, 'Bandung', NULL),
(19, 'Tyo', NULL, 'tyo.swipe@example.com', NULL, '$2y$12$Tdp5ywdQslNhtal9YixeseW4diOpCGRI6OWLm.preTeRQUDp3tzBC', NULL, '2026-05-08 01:55:46', '2026-05-08 01:55:46', 'player', 'laki-laki', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(20, 'Lina', 'linalinja', 'lina.swipe@example.com', NULL, '$2y$12$c54gkGcByDvGZKDN.LhXwuqK5xIC4gcELgE2H6Y7DIaJEPW4MQF2i', NULL, '2026-05-08 01:55:46', '2026-05-31 13:58:13', 'player', 'perempuan', NULL, NULL, '+6298764335788', 'Temennya Ayu sama Sasa', NULL, NULL, 1, 0, 'Jakarta', NULL),
(21, 'Damar', NULL, 'damar.swipe@example.com', NULL, '$2y$12$ZzFM4YZlvlxKH7nASM4OfeQxymo6iImT1XSaZdIflbQsXFaDtNIjS', NULL, '2026-05-08 01:55:47', '2026-05-08 01:55:47', 'player', 'laki-laki', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(22, 'Admin Spies Sport', 'admin', 'admin@gmail.com', NULL, '$2y$12$GURSn3HLb57M43vjbsqtR.1MW4hF8ad8KuJCk/sAsAR/iUmeNzUiO', NULL, '2026-05-20 01:07:01', '2026-05-20 01:15:42', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(23, 'Aldy Syahputra', 'all1dy', 'aldy@gmail.com', NULL, '$2y$12$0wjuAzFB5MGW9WWJ3Bp2Te2APgNEBuy5Rk5s1IMZHkxcrXwFDpVVO', NULL, '2026-05-20 23:44:49', '2026-05-20 23:44:49', 'owner', NULL, 'profil1.png', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(24, 'Owner Test', 'owner_test', 'owner.test@example.com', NULL, '$2y$12$8VNjH6j26toRarh0wkDMW.vW1vivYQpRyw9qdSQtToI07xvZFVhIG', NULL, '2026-05-25 04:21:53', '2026-05-25 04:21:53', 'owner', NULL, NULL, NULL, '081234567890', NULL, 'futsal', NULL, 0, 0, NULL, NULL),
(25, 'Admin Test', 'admin_test', 'admin.test@example.com', NULL, '$2y$12$5kkleKrJ3vkxVFu6BLRKo./wBxeEI9uWEHgmfAfI9MuF6VIA5yi4.', NULL, '2026-05-25 04:28:52', '2026-05-25 04:28:52', 'admin', 'laki-laki', NULL, NULL, '081299988877', NULL, 'futsal', NULL, 0, 0, NULL, NULL),
(27, 'Nasywa', 'n@sywa', 'nasywa@example.com', NULL, '$2y$12$k8bC4m7rRFSx332QZnxMSe4uqTihDgRKzrqD86kbZUNdZUykVDp.m', NULL, '2026-05-25 04:34:17', '2026-05-25 04:34:17', 'owner', NULL, NULL, NULL, '082314567323', NULL, NULL, NULL, 0, 0, NULL, NULL),
(28, 'Owner Demo', NULL, 'owner.demo@test.com', '2026-05-25 14:41:39', '$2y$12$V3Mc1QlRN/u6gtnoJrlDCezY.naGCmOWh3m4//xlwyRAwz3B7T0LS', NULL, '2026-05-25 14:41:39', '2026-05-25 14:41:39', 'owner', NULL, NULL, NULL, '081234567890', NULL, NULL, NULL, 0, 0, NULL, NULL),
(29, 'Player Demo', NULL, 'player.demo@test.com', '2026-05-25 14:41:39', '$2y$12$eWLyzI5ars/2ZrsX9uotwOC2RAGynttk8cPcijo8gW3yp9UzDpxVm', NULL, '2026-05-25 14:41:39', '2026-05-25 14:41:39', 'player', NULL, NULL, NULL, '085712345678', NULL, NULL, NULL, 0, 0, NULL, NULL),
(30, 'Najwa Viola H', 'viola11', 'viola@example.com', NULL, '$2y$12$TKPEvlVH4GAY3pRaPuoabOTeUeobyc5mRd9/01Oy12.RhZu1.aDbK', NULL, '2026-05-26 12:20:30', '2026-05-29 05:17:35', 'player', 'perempuan', 'avatars/1AJWOoZpvKqO2XxfQllto4qicwf6GsRaFvdbSL3m.jpg', 'covers/l04XJC86YVcw5B8F1mGpEwPwFFrdaCRkqiz5SGss.png', '081231451223', 'Aku suka main yang pake raket hehe', 'Padel, Tenis, Badminton', 'pemula', 1, 0, 'Jakarta', NULL),
(31, 'Rifky', 'rifky', 'rifky@example.com', NULL, '$2y$12$qA/.5QLQSDYG/TfdvJjuEeDSENC3WOh.l7TSaLSOcg2BKUiiz7y32', NULL, '2026-05-27 06:17:11', '2026-05-27 06:38:17', 'player', 'laki-laki', 'profil1.png', NULL, '081217003513', NULL, 'Baseball, Futsal', NULL, 0, 0, NULL, NULL),
(32, 'Lysel', 'lysel1', 'lysel@example.com', NULL, '$2y$12$rhSBI.XDyV038g3vjg1/xeHSofvMubj6wRQk01/qfwEw6RoeZc0vG', NULL, '2026-05-27 06:20:15', '2026-05-30 11:34:31', 'player', 'perempuan', 'profil2.png', NULL, '+6298764335788', 'Halo aku sukanya berenang sih', NULL, 'pemula', 1, 0, 'Bandung', NULL),
(33, 'Andra', 'andra1', 'andra@example.com', NULL, '$2y$12$kAFVbQAdjZR0ue6BSsQl5eVDDYOpthcVNtwALNDxTZy36SEPZjBUK', NULL, '2026-05-29 14:28:52', '2026-05-31 13:21:23', 'player', 'laki-laki', 'profil1.png', NULL, '+6297766566879', 'Rugby lovers!', NULL, NULL, 1, 0, 'Surabaya', NULL),
(34, 'Santoso Anjay', 'sancakep', 'sancaular@example.com', NULL, '$2y$12$5Qv35SHVLQiKdsPca2lQyOr2Q3Pb18ygIYukSeHtEg9gVahuFdKg2', NULL, '2026-06-04 03:41:40', '2026-06-04 03:41:40', 'owner', NULL, 'profil1.png', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_field_date_start_idx` (`field_id`,`date`,`start_time`),
  ADD KEY `bookings_field_status_idx` (`field_id`,`status`),
  ADD KEY `bookings_date_idx` (`date`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `communities_created_by_foreign` (`created_by`);

--
-- Indexes for table `community_members`
--
ALTER TABLE `community_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `community_members_community_id_user_id_unique` (`community_id`,`user_id`),
  ADD KEY `community_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discounts_code_unique` (`code`),
  ADD KEY `discounts_owner_id_foreign` (`owner_id`);

--
-- Indexes for table `discount_field`
--
ALTER TABLE `discount_field`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discount_field_discount_id_field_id_unique` (`discount_id`,`field_id`),
  ADD KEY `discount_field_field_id_foreign` (`field_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_id_field_id_unique` (`user_id`,`field_id`),
  ADD KEY `favorites_field_id_foreign` (`field_id`);

--
-- Indexes for table `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fields_owner_id_foreign` (`owner_id`),
  ADD KEY `fields_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `holidays_field_id_date_unique` (`field_id`,`date`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenances`
--
ALTER TABLE `maintenances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenances_field_id_foreign` (`field_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matches_field_id_foreign` (`field_id`),
  ADD KEY `matches_created_by_foreign` (`created_by`);

--
-- Indexes for table `match_players`
--
ALTER TABLE `match_players`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `match_players_match_id_user_id_unique` (`match_id`,`user_id`),
  ADD KEY `match_players_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_field_id_foreign` (`field_id`),
  ADD KEY `reviews_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slots_field_court_date_hour_unique` (`field_id`,`court_number`,`date`,`hour`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `community_members`
--
ALTER TABLE `community_members`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `discount_field`
--
ALTER TABLE `discount_field`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `fields`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenances`
--
ALTER TABLE `maintenances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `match_players`
--
ALTER TABLE `match_players`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=637;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=878;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `communities`
--
ALTER TABLE `communities`
  ADD CONSTRAINT `communities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_members`
--
ALTER TABLE `community_members`
  ADD CONSTRAINT `community_members_community_id_foreign` FOREIGN KEY (`community_id`) REFERENCES `communities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discount_field`
--
ALTER TABLE `discount_field`
  ADD CONSTRAINT `discount_field_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discount_field_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fields`
--
ALTER TABLE `fields`
  ADD CONSTRAINT `fields_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fields_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `holidays`
--
ALTER TABLE `holidays`
  ADD CONSTRAINT `holidays_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenances`
--
ALTER TABLE `maintenances`
  ADD CONSTRAINT `maintenances_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `matches_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `match_players`
--
ALTER TABLE `match_players`
  ADD CONSTRAINT `match_players_match_id_foreign` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `match_players_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `slots`
--
ALTER TABLE `slots`
  ADD CONSTRAINT `slots_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
