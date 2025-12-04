-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2025 a las 19:40:08
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `chorely_tfg`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendars`
--

CREATE TABLE `calendars` (
  `id` int(11) NOT NULL,
  `flat_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `month_start` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `calendars`
--

INSERT INTO `calendars` (`id`, `flat_id`, `name`, `month_start`, `created_by`, `created_at`) VALUES
(18, 9, 'Calendario Diciembre', '2025-12-01', 14, '2025-11-26 18:39:05'),
(19, 9, 'Calendario Diciembre - February 2026', '2026-02-01', 14, '2025-11-26 18:42:19'),
(20, 9, 'Calendario Diciembre - February 2026 - November 2025', '2025-11-01', 14, '2025-11-26 18:43:16'),
(21, 9, 'Calendario Diciembre - February 2026 - November 2025 - October 2025', '2025-10-01', 14, '2025-11-26 19:14:50'),
(22, 10, 'Calendario Prueba', '2025-12-01', 16, '2025-11-27 17:29:57'),
(23, 10, 'Calendario Prueba', '2025-12-01', 16, '2025-11-27 17:30:02'),
(24, 10, 'Clonado - January 2026', '2026-01-01', 16, '2025-11-27 17:34:09'),
(25, 10, 'Clonado - April 2026', '2026-04-01', 16, '2025-11-27 17:52:36'),
(26, 11, 'Navidaaaad', '2026-01-01', 16, '2025-11-27 18:10:16'),
(27, 12, 'jdfhjek', '2026-02-01', 16, '2025-11-27 18:25:17'),
(28, 12, 'Clonado - April 2026', '2026-04-01', 16, '2025-11-27 19:35:43'),
(29, 13, 'Piso de prueba', '2025-12-01', 22, '2025-11-28 15:52:37'),
(30, 13, 'Piso de prueba', '2025-12-01', 22, '2025-11-28 15:52:40'),
(31, 13, 'Clonado - January 2026', '2026-01-01', 22, '2025-11-28 15:57:28'),
(32, 14, 'Calendario Enero', '2026-01-01', 21, '2025-12-01 18:07:26'),
(33, 14, 'Calendario Enero', '2026-01-01', 21, '2025-12-01 18:07:31'),
(34, 14, 'Clonado - March 2026', '2026-03-01', 21, '2025-12-01 21:00:38'),
(35, 15, 'Podcast \'Los Ponces Hermanos\'', '2025-12-01', 23, '2025-12-02 19:14:00'),
(36, 15, 'Clonado - December 2001', '2001-12-01', 23, '2025-12-02 19:18:19'),
(37, 16, 'Dicember', '2024-12-01', 21, '2025-12-02 21:08:44'),
(38, 16, 'Dicember', '2024-12-01', 21, '2025-12-02 21:09:00'),
(39, 16, 'Clonado - May 2026', '2026-05-01', 21, '2025-12-02 21:10:12'),
(40, 16, 'Clonado - August 2026', '2026-08-01', 21, '2025-12-02 22:53:12'),
(41, 16, 'Auto - January 2025', '2025-01-01', 14, '2025-12-02 23:05:14'),
(42, 16, 'Auto - February 2025', '2025-02-01', 14, '2025-12-02 23:05:15'),
(43, 16, 'Auto - March 2025', '2025-03-01', 14, '2025-12-02 23:05:15'),
(44, 16, 'Auto - April 2025', '2025-04-01', 14, '2025-12-02 23:05:16'),
(45, 16, 'Auto - June 2025', '2025-06-01', 14, '2025-12-02 23:05:17'),
(46, 16, 'Auto - July 2025', '2025-07-01', 14, '2025-12-02 23:05:18'),
(47, 16, 'Auto - August 2025', '2025-08-01', 14, '2025-12-02 23:05:18'),
(48, 16, 'Auto - September 2025', '2025-09-01', 14, '2025-12-02 23:05:19'),
(49, 16, 'Auto - November 2025', '2025-11-01', 14, '2025-12-02 23:05:20'),
(50, 16, 'Auto - December 2025', '2025-12-01', 14, '2025-12-02 23:05:21'),
(51, 16, 'Auto - October 2025', '2025-10-01', 14, '2025-12-02 23:06:03'),
(52, 17, 'Calendario Definitivo', '2025-12-01', 14, '2025-12-03 12:40:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` int(11) NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `assigned_user_id` int(11) DEFAULT NULL,
  `event_date` date NOT NULL,
  `start_time` time DEFAULT '09:00:00',
  `end_time` time DEFAULT '10:00:00',
  `status` enum('pending','done') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `color` varchar(20) DEFAULT '#3788d8',
  `all_day` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `calendar_events`
--

INSERT INTO `calendar_events` (`id`, `calendar_id`, `task_id`, `assigned_user_id`, `event_date`, `start_time`, `end_time`, `status`, `notes`, `created_at`, `updated_at`, `color`, `all_day`) VALUES
(41, 18, 54, 14, '2025-12-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:40:03', '2025-11-26 17:40:03', '#3788d8', 0),
(42, 18, 54, 14, '2025-12-12', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:40:08', '2025-11-26 17:40:08', '#3788d8', 0),
(43, 18, 52, 15, '2025-12-10', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:40:12', '2025-11-26 17:40:12', '#3788d8', 0),
(44, 18, 53, 15, '2025-12-16', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:40:19', '2025-11-26 17:40:19', '#3788d8', 0),
(45, 18, 51, 14, '2025-12-09', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:40:31', '2025-11-26 17:40:31', '#3788d8', 0),
(46, 18, 51, 15, '2025-12-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:40:39', '2025-11-26 17:40:39', '#3788d8', 0),
(47, 19, 54, 14, '2026-02-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:42:19', '2025-11-26 17:42:19', '#3788d8', 0),
(48, 19, 54, 14, '2026-02-12', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:42:19', '2025-11-26 17:42:19', '#3788d8', 0),
(49, 19, 52, 15, '2026-02-10', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:42:19', '2025-11-26 17:42:19', '#3788d8', 0),
(50, 19, 53, 15, '2026-02-16', '09:00:00', '10:00:00', 'done', NULL, '2025-11-26 17:42:19', '2025-11-26 17:43:05', '#3788d8', 0),
(51, 19, 51, 14, '2026-02-09', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:42:19', '2025-11-26 17:42:19', '#3788d8', 0),
(52, 19, 51, 15, '2026-02-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:42:19', '2025-11-26 17:42:19', '#3788d8', 0),
(53, 20, 54, 14, '2025-11-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:43:16', '2025-11-26 17:43:16', '#3788d8', 0),
(54, 20, 54, 14, '2025-11-12', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:43:16', '2025-11-26 17:43:16', '#3788d8', 0),
(55, 20, 52, 15, '2025-11-10', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:43:16', '2025-11-26 17:43:16', '#3788d8', 0),
(56, 20, 53, 15, '2025-11-16', '09:00:00', '10:00:00', 'done', NULL, '2025-11-26 17:43:16', '2025-11-26 17:43:16', '#3788d8', 0),
(57, 20, 51, 14, '2025-11-09', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:43:16', '2025-11-26 17:43:16', '#3788d8', 0),
(58, 20, 51, 15, '2025-11-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 17:43:16', '2025-11-26 17:43:16', '#3788d8', 0),
(59, 20, 50, 14, '2025-12-01', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:13:45', '2025-11-26 18:13:45', '#3788d8', 0),
(60, 20, 52, 15, '2025-12-04', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:13:49', '2025-11-26 18:13:49', '#3788d8', 0),
(61, 20, 52, 14, '2025-12-01', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:13:54', '2025-11-26 18:13:54', '#3788d8', 0),
(62, 20, 54, 15, '2025-12-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:02', '2025-11-26 18:14:02', '#3788d8', 0),
(63, 20, 51, 15, '2025-12-03', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:08', '2025-11-26 18:14:08', '#3788d8', 0),
(64, 21, 54, 14, '2025-10-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(65, 21, 54, 14, '2025-10-12', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(66, 21, 52, 15, '2025-10-10', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(67, 21, 53, 15, '2025-10-16', '09:00:00', '10:00:00', 'done', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(68, 21, 51, 14, '2025-10-09', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(69, 21, 51, 15, '2025-10-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(70, 21, 50, 14, '2025-10-01', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(71, 21, 52, 15, '2025-10-04', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(72, 21, 52, 14, '2025-10-01', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(73, 21, 54, 15, '2025-10-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(74, 21, 51, 15, '2025-10-03', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:14:50', '2025-11-26 18:14:50', '#3788d8', 0),
(75, 21, 54, 14, '2025-12-16', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:15:43', '2025-11-26 18:15:43', '#3788d8', 0),
(76, 21, 52, 14, '2025-12-11', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:15:45', '2025-11-26 18:15:45', '#3788d8', 0),
(77, 20, 53, 14, '2026-01-05', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:30:55', '2025-11-26 18:30:55', '#3788d8', 0),
(78, 20, 54, 14, '2026-01-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:30:59', '2025-11-26 18:30:59', '#3788d8', 0),
(79, 20, 53, 15, '2026-01-06', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:31:03', '2025-11-26 18:31:03', '#3788d8', 0),
(80, 20, 53, 15, '2026-01-05', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-26 18:31:07', '2025-11-26 18:31:07', '#3788d8', 0),
(81, 22, 59, 17, '2025-12-10', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:32:24', '2025-11-27 16:32:24', '#3788d8', 0),
(82, 22, 59, 17, '2025-12-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:32:28', '2025-11-27 16:32:28', '#3788d8', 0),
(83, 22, 55, 14, '2025-12-01', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:32:47', '2025-11-27 16:32:47', '#3788d8', 0),
(84, 22, 57, 17, '2025-12-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:32:54', '2025-11-27 16:32:54', '#3788d8', 0),
(85, 24, 59, 17, '2026-01-10', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:34:09', '2025-11-27 16:34:09', '#3788d8', 0),
(86, 24, 59, 17, '2026-01-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:34:09', '2025-11-27 16:34:09', '#3788d8', 0),
(87, 24, 55, 14, '2026-01-01', '09:00:00', '10:00:00', 'done', NULL, '2025-11-27 16:34:09', '2025-11-27 16:34:33', '#3788d8', 0),
(88, 24, 57, 17, '2026-01-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:34:09', '2025-11-27 16:34:09', '#3788d8', 0),
(89, 24, 59, 17, '2026-01-13', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:34:38', '2025-11-27 16:34:38', '#3788d8', 0),
(90, 24, 55, 14, '2026-03-10', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:51:02', '2025-11-27 16:51:02', '#3788d8', 0),
(91, 25, 59, 17, '2026-04-10', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:52:36', '2025-11-27 16:52:36', '#3788d8', 0),
(92, 25, 59, 17, '2026-04-08', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:52:36', '2025-11-27 16:52:36', '#3788d8', 0),
(93, 25, 55, 14, '2026-04-01', '09:00:00', '10:00:00', 'done', NULL, '2025-11-27 16:52:36', '2025-11-27 16:52:36', '#3788d8', 0),
(94, 25, 57, 17, '2026-04-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:52:36', '2025-11-27 16:52:36', '#3788d8', 0),
(95, 25, 59, 17, '2026-04-13', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:52:36', '2025-11-27 16:52:36', '#3788d8', 0),
(96, 25, 55, 14, '2026-04-10', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 16:52:36', '2025-11-27 16:52:36', '#3788d8', 0),
(97, 27, 67, 20, '2026-02-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 17:26:27', '2025-11-27 17:26:27', '#3788d8', 0),
(98, 27, 72, 19, '2026-02-04', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 17:33:13', '2025-11-27 17:33:27', '#3788d8', 0),
(99, 27, 69, 19, '2026-02-06', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 17:33:36', '2025-11-27 17:33:36', '#3788d8', 0),
(100, 27, 72, 20, '2026-02-17', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 17:50:24', '2025-11-27 17:50:24', '#3788d8', 0),
(101, 27, 68, 19, '2026-02-19', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 17:50:33', '2025-11-28 18:33:06', '#3788d8', 0),
(102, 27, 72, 19, '2026-02-11', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 17:58:05', '2025-11-27 17:58:05', '#3788d8', 0),
(103, 27, 67, 20, '2026-03-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 18:35:03', '2025-11-27 18:35:03', '#3788d8', 0),
(104, 27, 69, 19, '2026-03-03', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 18:35:08', '2025-11-27 18:35:08', '#3788d8', 0),
(105, 28, 67, 20, '2026-04-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 18:35:43', '2025-11-27 18:35:43', '#3788d8', 0),
(106, 28, 72, 19, '2026-04-04', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 18:35:43', '2025-11-27 18:35:43', '#3788d8', 0),
(107, 28, 69, 19, '2026-04-06', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 18:35:43', '2025-11-27 18:35:43', '#3788d8', 0),
(108, 28, 72, 20, '2026-04-17', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 18:35:43', '2025-11-27 18:35:43', '#3788d8', 0),
(109, 28, 68, 19, '2026-04-19', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 18:35:43', '2025-11-27 18:35:43', '#3788d8', 0),
(110, 28, 72, 19, '2026-04-11', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-27 18:35:43', '2025-11-27 18:35:43', '#3788d8', 0),
(111, 29, 74, 14, '2025-12-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 14:53:26', '2025-11-28 14:53:50', '#3788d8', 0),
(112, 29, 78, 17, '2025-12-04', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 14:53:34', '2025-11-28 14:53:34', '#3788d8', 0),
(113, 29, 77, 17, '2025-12-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 14:53:59', '2025-11-28 14:53:59', '#3788d8', 0),
(114, 31, 74, 14, '2026-01-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 14:57:28', '2025-11-28 14:57:28', '#3788d8', 0),
(115, 31, 78, 17, '2026-01-04', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 14:57:28', '2025-11-28 14:57:28', '#3788d8', 0),
(116, 31, 77, 17, '2026-01-02', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 14:57:28', '2025-11-28 14:57:28', '#3788d8', 0),
(117, 31, 74, 14, '2026-01-06', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 14:58:45', '2025-11-28 14:58:45', '#3788d8', 0),
(118, 31, 75, 14, '2026-01-07', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 15:00:02', '2025-11-28 15:00:07', '#3788d8', 0),
(119, 31, 78, 14, '2026-01-07', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 15:00:14', '2025-11-28 15:00:14', '#3788d8', 0),
(120, 31, 73, 17, '2026-01-07', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 15:00:22', '2025-11-28 15:00:22', '#3788d8', 0),
(121, 31, 76, 17, '2026-01-07', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 15:00:28', '2025-11-28 15:00:28', '#3788d8', 0),
(122, 31, 75, 14, '2026-02-11', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 15:32:11', '2025-11-28 15:32:11', '#3788d8', 0),
(123, 31, 76, 14, '2025-12-10', '09:00:00', '10:00:00', 'pending', NULL, '2025-11-28 15:32:34', '2025-11-28 15:32:34', '#3788d8', 0),
(124, 27, 72, 19, '2026-02-09', '10:00:00', '11:00:00', 'pending', NULL, '2025-11-28 18:32:56', '2025-11-28 18:32:56', '#3788d8', 0),
(125, 27, 67, 19, '2026-02-26', '12:00:00', '13:00:00', 'pending', NULL, '2025-11-28 18:33:23', '2025-11-28 18:33:23', '#3788d8', 0),
(126, 27, 68, 19, '2026-02-27', '12:00:00', '13:00:00', 'pending', NULL, '2025-11-28 18:34:09', '2025-11-28 18:34:09', '#3788d8', 0),
(127, 27, 67, 19, '2026-03-12', '10:00:00', '11:00:00', 'pending', NULL, '2025-11-28 19:13:58', '2025-11-28 19:13:58', '#3788d8', 0),
(128, 33, 80, 15, '2026-01-05', '12:00:00', '13:15:00', 'pending', NULL, '2025-12-01 18:36:30', '2025-12-01 18:36:30', NULL, 0),
(129, 33, 84, 14, '2026-01-05', '12:00:00', '13:15:00', 'pending', NULL, '2025-12-01 18:36:30', '2025-12-01 18:36:30', NULL, 0),
(130, 33, 79, 14, '2026-01-08', '12:00:00', '13:00:00', 'pending', NULL, '2025-12-01 18:36:30', '2025-12-01 18:36:30', NULL, 0),
(131, 33, 82, 15, '2026-01-06', '15:00:00', '16:00:00', 'pending', NULL, '2025-12-01 18:36:30', '2025-12-01 18:36:30', NULL, 0),
(132, 34, 80, 15, '2026-03-05', '12:00:00', '13:15:00', 'pending', NULL, '2025-12-01 20:00:38', '2025-12-01 20:00:38', NULL, 1),
(133, 34, 84, 14, '2026-03-05', '12:00:00', '13:15:00', 'pending', NULL, '2025-12-01 20:00:38', '2025-12-01 20:00:38', NULL, 1),
(134, 34, 79, 14, '2026-03-08', '12:00:00', '13:00:00', 'pending', NULL, '2025-12-01 20:00:38', '2025-12-01 20:00:38', NULL, 1),
(135, 34, 82, 15, '2026-03-06', '15:00:00', '16:00:00', 'pending', NULL, '2025-12-01 20:00:38', '2025-12-01 20:00:38', NULL, 1),
(136, 35, 85, 15, '2025-12-01', NULL, NULL, 'pending', NULL, '2025-12-02 18:17:05', '2025-12-02 18:17:05', NULL, 0),
(137, 35, 86, 14, '2025-12-01', NULL, NULL, 'pending', NULL, '2025-12-02 18:17:05', '2025-12-02 18:17:05', NULL, 0),
(138, 35, 90, 14, '2025-12-17', NULL, NULL, 'pending', NULL, '2025-12-02 18:17:05', '2025-12-02 18:17:05', NULL, 0),
(139, 35, 89, 14, '2025-12-16', NULL, NULL, 'pending', NULL, '2025-12-02 18:17:05', '2025-12-02 18:17:05', NULL, 0),
(140, 36, 85, 15, '2001-12-01', NULL, NULL, 'pending', NULL, '2025-12-02 18:18:19', '2025-12-02 18:18:19', NULL, 1),
(141, 36, 86, 14, '2001-12-01', NULL, NULL, 'pending', NULL, '2025-12-02 18:18:19', '2025-12-02 18:18:19', NULL, 1),
(142, 36, 90, 14, '2001-12-17', NULL, NULL, 'pending', NULL, '2025-12-02 18:18:19', '2025-12-02 18:18:19', NULL, 1),
(143, 36, 89, 14, '2001-12-16', NULL, NULL, 'pending', NULL, '2025-12-02 18:18:19', '2025-12-02 18:18:19', NULL, 1),
(144, 38, 94, 14, '2024-12-10', '12:00:00', '12:30:00', 'done', NULL, '2025-12-02 20:09:47', '2025-12-02 20:09:47', NULL, 0),
(145, 38, 96, 15, '2024-12-12', '12:00:00', '13:30:00', 'pending', NULL, '2025-12-02 20:09:47', '2025-12-02 20:09:47', NULL, 0),
(148, 39, 95, 14, '2026-06-01', '11:00:00', '12:00:00', 'pending', NULL, '2025-12-02 21:51:49', '2025-12-02 21:51:49', NULL, 0),
(149, 40, 94, 14, '2026-08-10', '12:00:00', '12:30:00', 'done', NULL, '2025-12-02 21:53:12', '2025-12-02 21:53:12', NULL, 1),
(150, 40, 96, 15, '2026-08-12', '12:00:00', '13:30:00', 'pending', NULL, '2025-12-02 21:53:12', '2025-12-02 21:53:12', NULL, 1),
(151, 45, 94, 15, '2026-01-05', '11:00:00', '12:00:00', 'pending', NULL, '2025-12-02 22:05:43', '2025-12-02 22:05:43', NULL, 0),
(152, 45, 92, 14, '2025-06-02', '14:52:00', '15:53:00', 'pending', NULL, '2025-12-03 10:50:50', '2025-12-03 10:50:50', NULL, 0),
(153, 52, 98, NULL, '2025-12-03', NULL, NULL, 'pending', NULL, '2025-12-03 12:18:07', '2025-12-03 12:18:07', '#3788d8', 1),
(154, 52, 102, NULL, '2025-12-09', NULL, NULL, 'pending', NULL, '2025-12-03 12:18:16', '2025-12-03 12:18:16', '#3788d8', 1),
(155, 26, 62, 14, '2025-12-31', '23:00:00', '23:30:00', 'pending', NULL, '2025-12-03 20:19:19', '2025-12-03 20:19:19', '#3788d8', 0),
(156, 26, 65, 18, '2026-02-02', '11:30:00', '12:00:00', 'pending', NULL, '2025-12-03 20:56:41', '2025-12-03 20:56:41', NULL, 1),
(157, 26, 63, 14, '2026-02-04', '23:00:00', '23:30:00', 'pending', NULL, '2025-12-03 20:56:41', '2025-12-03 20:56:41', NULL, 1),
(158, 26, 65, 14, '2026-01-06', '07:00:00', '09:12:00', 'pending', NULL, '2025-12-03 21:45:33', '2025-12-03 21:45:33', NULL, 1),
(159, 26, 65, 18, '2026-03-02', '11:30:00', '12:00:00', 'pending', NULL, '2025-12-03 21:46:05', '2025-12-03 21:46:05', NULL, 1),
(160, 26, 63, 14, '2026-03-04', '23:00:00', '23:30:00', 'pending', NULL, '2025-12-03 21:46:05', '2025-12-03 21:46:05', NULL, 1),
(161, 23, 55, 17, '2027-02-01', '09:15:00', '10:15:00', 'pending', NULL, '2025-12-04 07:04:40', '2025-12-04 07:04:40', NULL, 1),
(162, 23, 60, 14, '2027-02-01', '09:15:00', '12:15:00', 'pending', NULL, '2025-12-04 07:04:40', '2025-12-04 07:04:40', NULL, 1),
(163, 23, 55, 14, '2027-02-02', '18:00:00', '18:30:00', 'pending', NULL, '2025-12-04 07:04:40', '2025-12-04 07:04:40', NULL, 1),
(167, 23, 56, 14, '2027-01-03', '15:00:00', '16:00:00', 'pending', NULL, '2025-12-04 07:11:02', '2025-12-04 07:11:02', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendar_historial`
--

CREATE TABLE `calendar_historial` (
  `id` int(11) NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `flat_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`snapshot`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `versiones` enum('Borrador','Definitivo') NOT NULL DEFAULT 'Borrador'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `calendar_historial`
--

INSERT INTO `calendar_historial` (`id`, `calendar_id`, `flat_id`, `year`, `month`, `snapshot`, `created_at`, `versiones`) VALUES
(8, 18, 9, 2025, 12, '[{\"task_id\":54,\"assigned_user_id\":14,\"event_date\":\"2025-12-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":54,\"assigned_user_id\":14,\"event_date\":\"2025-12-12\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":15,\"event_date\":\"2025-12-10\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":53,\"assigned_user_id\":15,\"event_date\":\"2025-12-16\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":14,\"event_date\":\"2025-12-09\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":15,\"event_date\":\"2025-12-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-26 17:41:24', 'Borrador'),
(9, 19, 9, 2026, 2, '[{\"task_id\":54,\"assigned_user_id\":14,\"source_date\":\"2025-12-08\",\"target_date\":\"2026-02-08\",\"status\":\"pending\"},{\"task_id\":54,\"assigned_user_id\":14,\"source_date\":\"2025-12-12\",\"target_date\":\"2026-02-12\",\"status\":\"pending\"},{\"task_id\":52,\"assigned_user_id\":15,\"source_date\":\"2025-12-10\",\"target_date\":\"2026-02-10\",\"status\":\"pending\"},{\"task_id\":53,\"assigned_user_id\":15,\"source_date\":\"2025-12-16\",\"target_date\":\"2026-02-16\",\"status\":\"pending\"},{\"task_id\":51,\"assigned_user_id\":14,\"source_date\":\"2025-12-09\",\"target_date\":\"2026-02-09\",\"status\":\"pending\"},{\"task_id\":51,\"assigned_user_id\":15,\"source_date\":\"2025-12-08\",\"target_date\":\"2026-02-08\",\"status\":\"pending\"}]', '2025-11-26 17:42:19', 'Borrador'),
(10, 20, 9, 2025, 11, '[{\"task_id\":54,\"assigned_user_id\":14,\"source_date\":\"2026-02-08\",\"target_date\":\"2025-11-08\",\"status\":\"pending\"},{\"task_id\":54,\"assigned_user_id\":14,\"source_date\":\"2026-02-12\",\"target_date\":\"2025-11-12\",\"status\":\"pending\"},{\"task_id\":52,\"assigned_user_id\":15,\"source_date\":\"2026-02-10\",\"target_date\":\"2025-11-10\",\"status\":\"pending\"},{\"task_id\":53,\"assigned_user_id\":15,\"source_date\":\"2026-02-16\",\"target_date\":\"2025-11-16\",\"status\":\"done\"},{\"task_id\":51,\"assigned_user_id\":14,\"source_date\":\"2026-02-09\",\"target_date\":\"2025-11-09\",\"status\":\"pending\"},{\"task_id\":51,\"assigned_user_id\":15,\"source_date\":\"2026-02-08\",\"target_date\":\"2025-11-08\",\"status\":\"pending\"}]', '2025-11-26 17:43:16', 'Borrador'),
(11, 20, 9, 2025, 11, '[{\"task_id\":54,\"assigned_user_id\":14,\"event_date\":\"2025-11-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":54,\"assigned_user_id\":14,\"event_date\":\"2025-11-12\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":15,\"event_date\":\"2025-11-10\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":53,\"assigned_user_id\":15,\"event_date\":\"2025-11-16\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"done\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":14,\"event_date\":\"2025-11-09\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":15,\"event_date\":\"2025-11-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":50,\"assigned_user_id\":14,\"event_date\":\"2025-12-01\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":15,\"event_date\":\"2025-12-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":14,\"event_date\":\"2025-12-01\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":54,\"assigned_user_id\":15,\"event_date\":\"2025-12-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":15,\"event_date\":\"2025-12-03\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-26 18:14:17', 'Borrador'),
(12, 21, 9, 2025, 10, '[{\"task_id\":54,\"assigned_user_id\":14,\"source_date\":\"2025-11-08\",\"target_date\":\"2025-10-08\",\"status\":\"pending\"},{\"task_id\":54,\"assigned_user_id\":14,\"source_date\":\"2025-11-12\",\"target_date\":\"2025-10-12\",\"status\":\"pending\"},{\"task_id\":52,\"assigned_user_id\":15,\"source_date\":\"2025-11-10\",\"target_date\":\"2025-10-10\",\"status\":\"pending\"},{\"task_id\":53,\"assigned_user_id\":15,\"source_date\":\"2025-11-16\",\"target_date\":\"2025-10-16\",\"status\":\"done\"},{\"task_id\":51,\"assigned_user_id\":14,\"source_date\":\"2025-11-09\",\"target_date\":\"2025-10-09\",\"status\":\"pending\"},{\"task_id\":51,\"assigned_user_id\":15,\"source_date\":\"2025-11-08\",\"target_date\":\"2025-10-08\",\"status\":\"pending\"},{\"task_id\":50,\"assigned_user_id\":14,\"source_date\":\"2025-12-01\",\"target_date\":\"2025-10-01\",\"status\":\"pending\"},{\"task_id\":52,\"assigned_user_id\":15,\"source_date\":\"2025-12-04\",\"target_date\":\"2025-10-04\",\"status\":\"pending\"},{\"task_id\":52,\"assigned_user_id\":14,\"source_date\":\"2025-12-01\",\"target_date\":\"2025-10-01\",\"status\":\"pending\"},{\"task_id\":54,\"assigned_user_id\":15,\"source_date\":\"2025-12-02\",\"target_date\":\"2025-10-02\",\"status\":\"pending\"},{\"task_id\":51,\"assigned_user_id\":15,\"source_date\":\"2025-12-03\",\"target_date\":\"2025-10-03\",\"status\":\"pending\"}]', '2025-11-26 18:14:50', 'Borrador'),
(13, 21, 9, 2025, 10, '[{\"task_id\":54,\"assigned_user_id\":14,\"event_date\":\"2025-10-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":54,\"assigned_user_id\":14,\"event_date\":\"2025-10-12\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":15,\"event_date\":\"2025-10-10\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":53,\"assigned_user_id\":15,\"event_date\":\"2025-10-16\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"done\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":14,\"event_date\":\"2025-10-09\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":15,\"event_date\":\"2025-10-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":50,\"assigned_user_id\":14,\"event_date\":\"2025-10-01\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":15,\"event_date\":\"2025-10-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":14,\"event_date\":\"2025-10-01\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":54,\"assigned_user_id\":15,\"event_date\":\"2025-10-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":15,\"event_date\":\"2025-10-03\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":54,\"assigned_user_id\":14,\"event_date\":\"2025-12-16\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":14,\"event_date\":\"2025-12-11\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-26 18:15:48', 'Borrador'),
(14, 20, 9, 2025, 11, '[{\"task_id\":54,\"assigned_user_id\":14,\"event_date\":\"2025-11-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":54,\"assigned_user_id\":14,\"event_date\":\"2025-11-12\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":15,\"event_date\":\"2025-11-10\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":53,\"assigned_user_id\":15,\"event_date\":\"2025-11-16\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"done\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":14,\"event_date\":\"2025-11-09\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":15,\"event_date\":\"2025-11-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":50,\"assigned_user_id\":14,\"event_date\":\"2025-12-01\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":15,\"event_date\":\"2025-12-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":52,\"assigned_user_id\":14,\"event_date\":\"2025-12-01\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":54,\"assigned_user_id\":15,\"event_date\":\"2025-12-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":51,\"assigned_user_id\":15,\"event_date\":\"2025-12-03\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":53,\"assigned_user_id\":14,\"event_date\":\"2026-01-05\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":54,\"assigned_user_id\":14,\"event_date\":\"2026-01-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":53,\"assigned_user_id\":15,\"event_date\":\"2026-01-06\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":53,\"assigned_user_id\":15,\"event_date\":\"2026-01-05\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-26 18:31:10', 'Borrador'),
(15, 22, 10, 2025, 12, '[{\"task_id\":59,\"assigned_user_id\":17,\"event_date\":\"2025-12-10\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":59,\"assigned_user_id\":17,\"event_date\":\"2025-12-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":55,\"assigned_user_id\":14,\"event_date\":\"2025-12-01\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":57,\"assigned_user_id\":17,\"event_date\":\"2025-12-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-27 16:33:03', 'Borrador'),
(16, 24, 10, 2026, 1, '[{\"task_id\":59,\"assigned_user_id\":17,\"event_date\":\"2025-12-10\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":59,\"assigned_user_id\":17,\"event_date\":\"2025-12-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":55,\"assigned_user_id\":14,\"event_date\":\"2025-12-01\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":57,\"assigned_user_id\":17,\"event_date\":\"2025-12-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-27 16:34:09', 'Borrador'),
(17, 24, 10, 2026, 1, '[{\"task_id\":59,\"assigned_user_id\":17,\"event_date\":\"2026-01-10\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":59,\"assigned_user_id\":17,\"event_date\":\"2026-01-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":55,\"assigned_user_id\":14,\"event_date\":\"2026-01-01\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"done\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":57,\"assigned_user_id\":17,\"event_date\":\"2026-01-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":59,\"assigned_user_id\":17,\"event_date\":\"2026-01-13\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":55,\"assigned_user_id\":14,\"event_date\":\"2026-03-10\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-27 16:51:13', 'Borrador'),
(18, 25, 10, 2026, 4, '[{\"task_id\":59,\"assigned_user_id\":17,\"event_date\":\"2026-01-10\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":59,\"assigned_user_id\":17,\"event_date\":\"2026-01-08\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":55,\"assigned_user_id\":14,\"event_date\":\"2026-01-01\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"done\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":57,\"assigned_user_id\":17,\"event_date\":\"2026-01-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":59,\"assigned_user_id\":17,\"event_date\":\"2026-01-13\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":55,\"assigned_user_id\":14,\"event_date\":\"2026-03-10\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-27 16:52:36', 'Borrador'),
(19, 27, 12, 2026, 2, '[{\"task_id\":67,\"assigned_user_id\":20,\"event_date\":\"2026-02-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":19,\"event_date\":\"2026-02-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":69,\"assigned_user_id\":19,\"event_date\":\"2026-02-06\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":20,\"event_date\":\"2026-02-17\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":68,\"assigned_user_id\":19,\"event_date\":\"2026-02-19\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-27 17:50:37', 'Borrador'),
(20, 27, 12, 2026, 2, '[{\"task_id\":67,\"assigned_user_id\":20,\"event_date\":\"2026-02-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":19,\"event_date\":\"2026-02-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":69,\"assigned_user_id\":19,\"event_date\":\"2026-02-06\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":20,\"event_date\":\"2026-02-17\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":68,\"assigned_user_id\":19,\"event_date\":\"2026-02-19\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":19,\"event_date\":\"2026-02-11\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-27 17:58:09', 'Borrador'),
(21, 27, 12, 2026, 2, '[{\"task_id\":67,\"assigned_user_id\":20,\"event_date\":\"2026-02-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":19,\"event_date\":\"2026-02-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":69,\"assigned_user_id\":19,\"event_date\":\"2026-02-06\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":20,\"event_date\":\"2026-02-17\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":68,\"assigned_user_id\":19,\"event_date\":\"2026-02-19\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":19,\"event_date\":\"2026-02-11\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-27 18:14:41', 'Borrador'),
(22, 27, 12, 2026, 2, '[{\"task_id\":67,\"assigned_user_id\":20,\"event_date\":\"2026-02-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":19,\"event_date\":\"2026-02-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":69,\"assigned_user_id\":19,\"event_date\":\"2026-02-06\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":20,\"event_date\":\"2026-02-17\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":68,\"assigned_user_id\":19,\"event_date\":\"2026-02-19\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":19,\"event_date\":\"2026-02-11\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":67,\"assigned_user_id\":20,\"event_date\":\"2026-03-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":69,\"assigned_user_id\":19,\"event_date\":\"2026-03-03\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-27 18:35:13', 'Borrador'),
(23, 28, 12, 2026, 4, '[{\"task_id\":67,\"assigned_user_id\":20,\"event_date\":\"2026-02-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":19,\"event_date\":\"2026-02-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":69,\"assigned_user_id\":19,\"event_date\":\"2026-02-06\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":20,\"event_date\":\"2026-02-17\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":68,\"assigned_user_id\":19,\"event_date\":\"2026-02-19\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":72,\"assigned_user_id\":19,\"event_date\":\"2026-02-11\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-27 18:35:43', 'Borrador'),
(24, 29, 13, 2025, 12, '[{\"task_id\":74,\"assigned_user_id\":14,\"event_date\":\"2025-12-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":78,\"assigned_user_id\":17,\"event_date\":\"2025-12-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":77,\"assigned_user_id\":17,\"event_date\":\"2025-12-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-28 14:56:19', 'Borrador'),
(25, 31, 13, 2026, 1, '[{\"task_id\":74,\"assigned_user_id\":14,\"event_date\":\"2025-12-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":78,\"assigned_user_id\":17,\"event_date\":\"2025-12-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":77,\"assigned_user_id\":17,\"event_date\":\"2025-12-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-28 14:57:28', 'Borrador'),
(26, 31, 13, 2026, 1, '[{\"task_id\":74,\"assigned_user_id\":14,\"event_date\":\"2026-01-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":78,\"assigned_user_id\":17,\"event_date\":\"2026-01-04\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":77,\"assigned_user_id\":17,\"event_date\":\"2026-01-02\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":74,\"assigned_user_id\":14,\"event_date\":\"2026-01-06\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":75,\"assigned_user_id\":14,\"event_date\":\"2026-01-07\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":78,\"assigned_user_id\":14,\"event_date\":\"2026-01-07\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":73,\"assigned_user_id\":17,\"event_date\":\"2026-01-07\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false},{\"task_id\":76,\"assigned_user_id\":17,\"event_date\":\"2026-01-07\",\"start_time\":\"09:00:00\",\"end_time\":\"10:00:00\",\"status\":\"pending\",\"notes\":null,\"color\":\"#3788d8\",\"all_day\":false}]', '2025-11-28 15:10:16', 'Borrador'),
(27, 33, 14, 2026, 1, '[]', '2025-12-01 17:56:20', 'Borrador'),
(28, 33, 14, 2026, 1, '[]', '2025-12-01 17:57:05', 'Borrador'),
(29, 33, 14, 2026, 1, '[{\"id\":\"temp-minjqvqb-1vo\",\"calendar_id\":33,\"task_id\":\"80\",\"assigned_user_id\":\"15\",\"event_date\":\"2026-01-05\",\"start_time\":\"12:00\",\"end_time\":\"13:15\",\"status\":\"pending\",\"title\":\"12:00 Sacar la basura - Roberto\"},{\"id\":\"temp-minjsmpt-6as\",\"calendar_id\":33,\"task_id\":\"84\",\"assigned_user_id\":\"14\",\"event_date\":\"2026-01-05\",\"start_time\":\"12:00\",\"end_time\":\"13:15\",\"status\":\"pending\",\"title\":\"12:00 Hacer las habitaciones - Rub\\u00e9n Ponce\"},{\"id\":\"temp-minjtjle-2wj\",\"calendar_id\":33,\"task_id\":\"79\",\"assigned_user_id\":\"14\",\"event_date\":\"2026-01-08\",\"start_time\":\"12:00\",\"end_time\":\"13:00\",\"status\":\"pending\",\"title\":\"12:00 Limpiar los ba\\u00f1os - Rub\\u00e9n Ponce\"}]', '2025-12-01 18:35:32', 'Borrador'),
(30, 33, 14, 2026, 1, '[{\"id\":\"temp-minjqvqb-1vo\",\"calendar_id\":33,\"task_id\":\"80\",\"assigned_user_id\":\"15\",\"event_date\":\"2026-01-05\",\"start_time\":\"12:00\",\"end_time\":\"13:15\",\"status\":\"pending\",\"title\":\"12:00 Sacar la basura - Roberto\"},{\"id\":\"temp-minjsmpt-6as\",\"calendar_id\":33,\"task_id\":\"84\",\"assigned_user_id\":\"14\",\"event_date\":\"2026-01-05\",\"start_time\":\"12:00\",\"end_time\":\"13:15\",\"status\":\"pending\",\"title\":\"12:00 Hacer las habitaciones - Rub\\u00e9n Ponce\"},{\"id\":\"temp-minjtjle-2wj\",\"calendar_id\":33,\"task_id\":\"79\",\"assigned_user_id\":\"14\",\"event_date\":\"2026-01-08\",\"start_time\":\"12:00\",\"end_time\":\"13:00\",\"status\":\"pending\",\"title\":\"12:00 Limpiar los ba\\u00f1os - Rub\\u00e9n Ponce\"},{\"id\":\"temp-minjusuy-3t7\",\"calendar_id\":33,\"task_id\":\"82\",\"assigned_user_id\":\"15\",\"event_date\":\"2026-01-06\",\"start_time\":\"15:00\",\"end_time\":\"16:00\",\"status\":\"pending\",\"title\":\"15:00 Limpiar la cocina - Roberto\"}]', '2025-12-01 18:36:30', 'Definitivo'),
(31, 34, 14, 2026, 3, '[{\"id\":\"temp-minjqvqb-1vo\",\"calendar_id\":33,\"task_id\":\"80\",\"assigned_user_id\":\"15\",\"event_date\":\"2026-01-05\",\"start_time\":\"12:00\",\"end_time\":\"13:15\",\"status\":\"pending\",\"title\":\"12:00 Sacar la basura - Roberto\"},{\"id\":\"temp-minjsmpt-6as\",\"calendar_id\":33,\"task_id\":\"84\",\"assigned_user_id\":\"14\",\"event_date\":\"2026-01-05\",\"start_time\":\"12:00\",\"end_time\":\"13:15\",\"status\":\"pending\",\"title\":\"12:00 Hacer las habitaciones - Rub\\u00e9n Ponce\"},{\"id\":\"temp-minjtjle-2wj\",\"calendar_id\":33,\"task_id\":\"79\",\"assigned_user_id\":\"14\",\"event_date\":\"2026-01-08\",\"start_time\":\"12:00\",\"end_time\":\"13:00\",\"status\":\"pending\",\"title\":\"12:00 Limpiar los ba\\u00f1os - Rub\\u00e9n Ponce\"},{\"id\":\"temp-minjusuy-3t7\",\"calendar_id\":33,\"task_id\":\"82\",\"assigned_user_id\":\"15\",\"event_date\":\"2026-01-06\",\"start_time\":\"15:00\",\"end_time\":\"16:00\",\"status\":\"pending\",\"title\":\"15:00 Limpiar la cocina - Roberto\"}]', '2025-12-01 20:00:38', 'Borrador'),
(32, 35, 15, 2025, 12, '[{\"id\":\"temp-mioyisom-5w6\",\"calendar_id\":35,\"task_id\":\"85\",\"assigned_user_id\":\"15\",\"event_date\":\"2025-12-01\",\"start_time\":null,\"end_time\":null,\"status\":\"pending\",\"title\":\"Limpiar los ba\\u00f1os - Roberto\"},{\"id\":\"temp-mioyja81-30c\",\"calendar_id\":35,\"task_id\":\"86\",\"assigned_user_id\":\"14\",\"event_date\":\"2025-12-01\",\"start_time\":null,\"end_time\":null,\"status\":\"pending\",\"title\":\"Sacar la basura - Rub\\u00e9n Ponce\"},{\"id\":\"temp-mioykkst-5v8\",\"calendar_id\":35,\"task_id\":\"90\",\"assigned_user_id\":\"14\",\"event_date\":\"2025-12-17\",\"start_time\":null,\"end_time\":null,\"status\":\"pending\",\"title\":\"Hacer las habitaciones - Rub\\u00e9n Ponce\"},{\"id\":\"temp-mioykqgv-66x\",\"calendar_id\":35,\"task_id\":\"89\",\"assigned_user_id\":\"14\",\"event_date\":\"2025-12-16\",\"start_time\":null,\"end_time\":null,\"status\":\"pending\",\"title\":\"Barrer la casa - Rub\\u00e9n Ponce\"}]', '2025-12-02 18:17:05', 'Definitivo'),
(33, 36, 15, 2001, 12, '[{\"id\":\"temp-mioyisom-5w6\",\"calendar_id\":35,\"task_id\":\"85\",\"assigned_user_id\":\"15\",\"event_date\":\"2025-12-01\",\"start_time\":null,\"end_time\":null,\"status\":\"pending\",\"title\":\"Limpiar los ba\\u00f1os - Roberto\"},{\"id\":\"temp-mioyja81-30c\",\"calendar_id\":35,\"task_id\":\"86\",\"assigned_user_id\":\"14\",\"event_date\":\"2025-12-01\",\"start_time\":null,\"end_time\":null,\"status\":\"pending\",\"title\":\"Sacar la basura - Rub\\u00e9n Ponce\"},{\"id\":\"temp-mioykkst-5v8\",\"calendar_id\":35,\"task_id\":\"90\",\"assigned_user_id\":\"14\",\"event_date\":\"2025-12-17\",\"start_time\":null,\"end_time\":null,\"status\":\"pending\",\"title\":\"Hacer las habitaciones - Rub\\u00e9n Ponce\"},{\"id\":\"temp-mioykqgv-66x\",\"calendar_id\":35,\"task_id\":\"89\",\"assigned_user_id\":\"14\",\"event_date\":\"2025-12-16\",\"start_time\":null,\"end_time\":null,\"status\":\"pending\",\"title\":\"Barrer la casa - Rub\\u00e9n Ponce\"}]', '2025-12-02 18:18:19', 'Borrador'),
(34, 38, 16, 2024, 12, '[{\"id\":\"temp-mip2mag2-67h\",\"calendar_id\":38,\"task_id\":\"94\",\"assigned_user_id\":\"14\",\"event_date\":\"2024-12-10\",\"start_time\":\"12:00\",\"end_time\":\"12:30\",\"status\":\"done\",\"title\":\"12:00 Limpiar la cocina - Rub\\u00e9n Ponce\"},{\"id\":\"temp-mip2mp3s-5qp\",\"calendar_id\":38,\"task_id\":\"96\",\"assigned_user_id\":\"15\",\"event_date\":\"2024-12-12\",\"start_time\":\"12:00\",\"end_time\":\"13:30\",\"status\":\"pending\",\"title\":\"12:00 Hacer las habitaciones - Roberto\"}]', '2025-12-02 20:09:47', 'Definitivo'),
(35, 39, 16, 2026, 5, '[{\"id\":\"temp-mip2mag2-67h\",\"calendar_id\":38,\"task_id\":\"94\",\"assigned_user_id\":\"14\",\"event_date\":\"2024-12-10\",\"start_time\":\"12:00\",\"end_time\":\"12:30\",\"status\":\"done\",\"title\":\"12:00 Limpiar la cocina - Rub\\u00e9n Ponce\"},{\"id\":\"temp-mip2mp3s-5qp\",\"calendar_id\":38,\"task_id\":\"96\",\"assigned_user_id\":\"15\",\"event_date\":\"2024-12-12\",\"start_time\":\"12:00\",\"end_time\":\"13:30\",\"status\":\"pending\",\"title\":\"12:00 Hacer las habitaciones - Roberto\"}]', '2025-12-02 20:10:12', 'Borrador'),
(36, 39, 16, 2026, 5, '[{\"id\":\"temp-mip69xhq-4h5\",\"calendar_id\":39,\"task_id\":\"95\",\"assigned_user_id\":\"14\",\"event_date\":\"2026-06-01\",\"start_time\":\"11:00\",\"end_time\":\"12:00\",\"status\":\"pending\",\"title\":\"11:00 Barrer la casa - Rub\\u00e9n Ponce\"}]', '2025-12-02 21:51:49', 'Definitivo'),
(37, 40, 16, 2026, 8, '[{\"id\":\"temp-mip2mag2-67h\",\"calendar_id\":38,\"task_id\":\"94\",\"assigned_user_id\":\"14\",\"event_date\":\"2024-12-10\",\"start_time\":\"12:00\",\"end_time\":\"12:30\",\"status\":\"done\",\"title\":\"12:00 Limpiar la cocina - Rub\\u00e9n Ponce\"},{\"id\":\"temp-mip2mp3s-5qp\",\"calendar_id\":38,\"task_id\":\"96\",\"assigned_user_id\":\"15\",\"event_date\":\"2024-12-12\",\"start_time\":\"12:00\",\"end_time\":\"13:30\",\"status\":\"pending\",\"title\":\"12:00 Hacer las habitaciones - Roberto\"}]', '2025-12-02 21:53:12', 'Borrador'),
(38, 45, 16, 2025, 6, '[{\"id\":\"temp-mip6rsvr-552\",\"calendar_id\":45,\"task_id\":\"94\",\"assigned_user_id\":\"15\",\"event_date\":\"2026-01-05\",\"start_time\":\"11:00\",\"end_time\":\"12:00\",\"status\":\"pending\",\"title\":\"11:00 Limpiar la cocina - Roberto\"}]', '2025-12-02 22:05:43', 'Definitivo'),
(39, 45, 16, 2025, 6, '[{\"id\":\"temp-mipy3hor-38i\",\"calendar_id\":45,\"task_id\":\"92\",\"assigned_user_id\":\"14\",\"event_date\":\"2025-06-02\",\"start_time\":\"14:52\",\"end_time\":\"15:53\",\"status\":\"pending\",\"title\":\"14:52 Sacar la basura - Rub\\u00e9n Ponce\"}]', '2025-12-03 10:50:50', 'Definitivo'),
(40, 26, 11, 2026, 2, '[{\"id\":\"temp-miqjq1d6-691\",\"calendar_id\":26,\"task_id\":65,\"assigned_user_id\":\"18\",\"event_date\":\"2026-02-02\",\"start_time\":\"11:30\",\"end_time\":\"12:00\",\"status\":\"pending\",\"title\":\"11:30 Barrer la casa - Manolin\"},{\"id\":\"temp-miqjqcal-3wq\",\"calendar_id\":26,\"task_id\":63,\"assigned_user_id\":\"14\",\"event_date\":\"2026-02-04\",\"start_time\":\"23:00\",\"end_time\":\"23:30\",\"status\":\"pending\",\"title\":\"23:00 Fregar los platos - Rub\\u00e9n Ponce\"}]', '2025-12-03 20:56:41', 'Definitivo'),
(41, 26, 11, 2026, 1, '[{\"id\":\"temp-miqlhkxn-3sr\",\"calendar_id\":26,\"task_id\":65,\"assigned_user_id\":\"14\",\"event_date\":\"2026-01-06\",\"start_time\":\"07:00\",\"end_time\":\"09:12\",\"status\":\"pending\",\"title\":\"07:00 Barrer la casa - Rub\\u00e9n Ponce\"}]', '2025-12-03 21:45:33', 'Definitivo'),
(42, 26, 11, 2026, 3, '[{\"id\":\"temp-miqjq1d6-691\",\"calendar_id\":26,\"task_id\":65,\"assigned_user_id\":\"18\",\"event_date\":\"2026-02-02\",\"start_time\":\"11:30\",\"end_time\":\"12:00\",\"status\":\"pending\",\"title\":\"11:30 Barrer la casa - Manolin\"},{\"id\":\"temp-miqjqcal-3wq\",\"calendar_id\":26,\"task_id\":63,\"assigned_user_id\":\"14\",\"event_date\":\"2026-02-04\",\"start_time\":\"23:00\",\"end_time\":\"23:30\",\"status\":\"pending\",\"title\":\"23:00 Fregar los platos - Rub\\u00e9n Ponce\"}]', '2025-12-03 21:46:05', 'Borrador'),
(43, 23, 10, 2027, 2, '[{\"id\":\"temp-mir4u869-2yj\",\"calendar_id\":23,\"task_id\":55,\"assigned_user_id\":\"17\",\"event_date\":\"2027-02-01\",\"start_time\":\"09:15\",\"end_time\":\"10:15\",\"status\":\"pending\",\"title\":\"09:15 Limpiar los ba\\u00f1os - Rosa\"},{\"id\":\"temp-mir4uncd-6h4\",\"calendar_id\":23,\"task_id\":60,\"assigned_user_id\":\"14\",\"event_date\":\"2027-02-01\",\"start_time\":\"09:15\",\"end_time\":\"12:15\",\"status\":\"pending\",\"title\":\"09:15 Hacer las habitaciones - Rub\\u00e9n Ponce\"},{\"id\":\"temp-mir4v4oz-5ra\",\"calendar_id\":23,\"task_id\":55,\"assigned_user_id\":\"14\",\"event_date\":\"2027-02-02\",\"start_time\":\"18:00\",\"end_time\":\"18:30\",\"status\":\"pending\",\"title\":\"18:00 Limpiar los ba\\u00f1os - Rub\\u00e9n Ponce\"}]', '2025-12-04 07:04:40', 'Definitivo'),
(44, 23, 10, 2027, 1, '[{\"id\":\"temp-mir4u869-2yj\",\"calendar_id\":23,\"task_id\":55,\"assigned_user_id\":\"17\",\"event_date\":\"2027-02-01\",\"start_time\":\"09:15\",\"end_time\":\"10:15\",\"status\":\"pending\",\"title\":\"09:15 Limpiar los ba\\u00f1os - Rosa\"},{\"id\":\"temp-mir4uncd-6h4\",\"calendar_id\":23,\"task_id\":60,\"assigned_user_id\":\"14\",\"event_date\":\"2027-02-01\",\"start_time\":\"09:15\",\"end_time\":\"12:15\",\"status\":\"pending\",\"title\":\"09:15 Hacer las habitaciones - Rub\\u00e9n Ponce\"},{\"id\":\"temp-mir4v4oz-5ra\",\"calendar_id\":23,\"task_id\":55,\"assigned_user_id\":\"14\",\"event_date\":\"2027-02-02\",\"start_time\":\"18:00\",\"end_time\":\"18:30\",\"status\":\"pending\",\"title\":\"18:00 Limpiar los ba\\u00f1os - Rub\\u00e9n Ponce\"}]', '2025-12-04 07:05:22', 'Borrador'),
(45, 23, 10, 2027, 1, '[{\"id\":\"temp-mir5p1jy-7k2\",\"calendar_id\":23,\"task_id\":56,\"assigned_user_id\":\"14\",\"event_date\":\"2027-01-03\",\"start_time\":\"15:00\",\"end_time\":\"16:00\",\"status\":\"pending\",\"title\":\"15:00 Sacar la basura - Rub\\u00e9n Ponce\"}]', '2025-12-04 07:11:02', 'Definitivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flats`
--

CREATE TABLE `flats` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `flats`
--

INSERT INTO `flats` (`id`, `name`, `description`, `created_at`) VALUES
(9, 'Piso Navidad', NULL, '2025-11-26 18:39:05'),
(10, 'Pisito', NULL, '2025-11-27 17:29:57'),
(11, 'Christmas', NULL, '2025-11-27 18:10:16'),
(12, 'gegrgtr', NULL, '2025-11-27 18:25:17'),
(13, 'Piso', NULL, '2025-11-28 15:52:37'),
(14, 'Piso compartido', NULL, '2025-12-01 18:07:25'),
(15, 'Grabaciones', NULL, '2025-12-02 19:14:00'),
(16, 'Dicipiso', NULL, '2025-12-02 21:08:44'),
(17, 'Calendario de Navidad', NULL, '2025-12-03 12:40:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flat_members`
--

CREATE TABLE `flat_members` (
  `id` int(11) NOT NULL,
  `flat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(50) DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `flat_members`
--

INSERT INTO `flat_members` (`id`, `flat_id`, `user_id`, `role`, `created_at`) VALUES
(18, 9, 14, 'member', '2025-11-26 18:39:05'),
(19, 9, 15, 'member', '2025-11-26 18:39:06'),
(20, 10, 14, 'member', '2025-11-27 17:29:57'),
(21, 10, 17, 'member', '2025-11-27 17:29:58'),
(22, 11, 14, 'member', '2025-11-27 18:10:16'),
(23, 11, 18, 'member', '2025-11-27 18:10:17'),
(24, 12, 19, 'member', '2025-11-27 18:25:17'),
(25, 12, 20, 'member', '2025-11-27 18:25:18'),
(26, 13, 14, 'member', '2025-11-28 15:52:38'),
(27, 13, 17, 'member', '2025-11-28 15:52:38'),
(28, 14, 14, 'member', '2025-12-01 18:07:28'),
(29, 14, 15, 'member', '2025-12-01 18:07:28'),
(30, 15, 15, 'member', '2025-12-02 19:14:00'),
(31, 15, 14, 'member', '2025-12-02 19:14:00'),
(32, 16, 14, 'member', '2025-12-02 21:09:00'),
(33, 16, 15, 'member', '2025-12-02 21:09:00'),
(34, 17, 24, 'member', '2025-12-03 12:40:04'),
(35, 17, 25, 'member', '2025-12-03 12:40:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `flat_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tasks`
--

INSERT INTO `tasks` (`id`, `flat_id`, `name`, `description`, `created_at`) VALUES
(49, 9, 'Limpiar los baños', NULL, '2025-11-26 18:39:05'),
(50, 9, 'Sacar la basura', NULL, '2025-11-26 18:39:05'),
(51, 9, 'Fregar los platos', NULL, '2025-11-26 18:39:05'),
(52, 9, 'Limpiar la cocina', NULL, '2025-11-26 18:39:05'),
(53, 9, 'Barrer la casa', NULL, '2025-11-26 18:39:05'),
(54, 9, 'Hacer las habitaciones', NULL, '2025-11-26 18:39:05'),
(55, 10, 'Limpiar los baños', NULL, '2025-11-27 17:29:57'),
(56, 10, 'Sacar la basura', NULL, '2025-11-27 17:29:57'),
(57, 10, 'Fregar los platos', NULL, '2025-11-27 17:29:57'),
(58, 10, 'Limpiar la cocina', NULL, '2025-11-27 17:29:57'),
(59, 10, 'Barrer la casa', NULL, '2025-11-27 17:29:57'),
(60, 10, 'Hacer las habitaciones', NULL, '2025-11-27 17:29:57'),
(61, 11, 'Limpiar los baños', NULL, '2025-11-27 18:10:16'),
(62, 11, 'Sacar la basura', NULL, '2025-11-27 18:10:16'),
(63, 11, 'Fregar los platos', NULL, '2025-11-27 18:10:16'),
(64, 11, 'Limpiar la cocina', NULL, '2025-11-27 18:10:16'),
(65, 11, 'Barrer la casa', NULL, '2025-11-27 18:10:16'),
(66, 11, 'Hacer las habitaciones', NULL, '2025-11-27 18:10:16'),
(67, 12, 'Limpiar los baños', NULL, '2025-11-27 18:25:17'),
(68, 12, 'Sacar la basura', NULL, '2025-11-27 18:25:17'),
(69, 12, 'Fregar los platos', NULL, '2025-11-27 18:25:17'),
(70, 12, 'Limpiar la cocina', NULL, '2025-11-27 18:25:17'),
(71, 12, 'Barrer la casa', NULL, '2025-11-27 18:25:17'),
(72, 12, 'Hacer las habitaciones', NULL, '2025-11-27 18:25:17'),
(73, 13, 'Limpiar los baños', NULL, '2025-11-28 15:52:37'),
(74, 13, 'Sacar la basura', NULL, '2025-11-28 15:52:37'),
(75, 13, 'Fregar los platos', NULL, '2025-11-28 15:52:37'),
(76, 13, 'Limpiar la cocina', NULL, '2025-11-28 15:52:37'),
(77, 13, 'Barrer la casa', NULL, '2025-11-28 15:52:37'),
(78, 13, 'Hacer las habitaciones', NULL, '2025-11-28 15:52:37'),
(79, 14, 'Limpiar los baños', NULL, '2025-12-01 18:07:26'),
(80, 14, 'Sacar la basura', NULL, '2025-12-01 18:07:26'),
(81, 14, 'Fregar los platos', NULL, '2025-12-01 18:07:26'),
(82, 14, 'Limpiar la cocina', NULL, '2025-12-01 18:07:26'),
(83, 14, 'Barrer la casa', NULL, '2025-12-01 18:07:26'),
(84, 14, 'Hacer las habitaciones', NULL, '2025-12-01 18:07:26'),
(85, 15, 'Limpiar los baños', NULL, '2025-12-02 19:14:00'),
(86, 15, 'Sacar la basura', NULL, '2025-12-02 19:14:00'),
(87, 15, 'Fregar los platos', NULL, '2025-12-02 19:14:00'),
(88, 15, 'Limpiar la cocina', NULL, '2025-12-02 19:14:00'),
(89, 15, 'Barrer la casa', NULL, '2025-12-02 19:14:00'),
(90, 15, 'Hacer las habitaciones', NULL, '2025-12-02 19:14:00'),
(91, 16, 'Limpiar los baños', NULL, '2025-12-02 21:08:44'),
(92, 16, 'Sacar la basura', NULL, '2025-12-02 21:08:44'),
(93, 16, 'Fregar los platos', NULL, '2025-12-02 21:08:44'),
(94, 16, 'Limpiar la cocina', NULL, '2025-12-02 21:08:44'),
(95, 16, 'Barrer la casa', NULL, '2025-12-02 21:08:44'),
(96, 16, 'Hacer las habitaciones', NULL, '2025-12-02 21:08:44'),
(97, 17, 'Limpiar los baños', NULL, '2025-12-03 12:40:04'),
(98, 17, 'Sacar la basura', NULL, '2025-12-03 12:40:04'),
(99, 17, 'Fregar los platos', NULL, '2025-12-03 12:40:04'),
(100, 17, 'Limpiar la cocina', NULL, '2025-12-03 12:40:04'),
(101, 17, 'Barrer la casa', NULL, '2025-12-03 12:40:04'),
(102, 17, 'Hacer las habitaciones', NULL, '2025-12-03 12:40:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `apellido`, `email`, `password`, `created_at`, `updated_at`) VALUES
(14, 'Rubén', 'Ponce', 'rubenponcelopez.afd@gmail.com', '$2y$12$CYvuMYnFWCH3tUToMqFYeOKs14sWToN5XuaM1brkHHB.sA4QTlUPq', '2025-11-26 17:37:43', '2025-11-26 17:37:43'),
(15, 'Roberto', '', 'roberto+1764182345@local.test', '$2y$12$KqeL2n8dHghkA.uJvi/MJusFOan.Qhdf9SyJIphotcYtnyyMQ1mN6', '2025-11-26 17:39:06', '2025-11-26 17:39:06'),
(16, 'Ruben', 'Ponce', 'ruben.ponce.dev@gmail.com', '$2y$12$w0sQFN0H80ekiAEen4YEkulyaHZ.r1koSNrptGgsTBt/spuvRluOa', '2025-11-27 16:28:41', '2025-11-27 16:28:41'),
(17, 'Rosa', '', 'rosa+1764264597@local.test', '$2y$12$gTRJf8/R70srLRhDyfGHSuKZYxqy6UfSOVjm2ZdwST7tPFfNAc3d6', '2025-11-27 16:29:58', '2025-11-27 16:29:58'),
(18, 'Manolin', '', 'manolin+1764267016@local.test', '$2y$12$Z2Yyq90udoi6cszGcl8usOlOJdk83eCWFc2oGKrWqfqQgqMopnELC', '2025-11-27 17:10:17', '2025-11-27 17:10:17'),
(19, 'Anacardo', '', 'anacardo+1764267917@local.test', '$2y$12$eCv5F34wjbnwkSQHrsTP/OqMCwnsceUNHcbSM4sZjHmF25SMr/lGC', '2025-11-27 17:25:17', '2025-11-27 17:25:17'),
(20, 'Manolo', '', 'manolo+1764267917@local.test', '$2y$12$6VbaBa93NSpNjGXMIzM5geOOvOWsqthi4qZuCMl7uFHKlJMUOJuzi', '2025-11-27 17:25:18', '2025-11-27 17:25:18'),
(21, 'Ruben', 'dsgf', 'rubenponce6@hotmail.com', '$2y$12$mQigcpBxdNdwdMxbWd/1puyORTIwB9dBb.vJMBhgSq5IXldVZWweO', '2025-11-28 14:49:09', '2025-11-28 14:49:09'),
(22, 'Ruben', 'dsgf', 'rubenponce6+1@hotmail.com', '$2y$12$n9U2tvq4aJ1FLkKrD7m5L.5s4akqWZbL6YMoqZUMQzE9N2tTxXeq.', '2025-11-28 14:50:04', '2025-11-28 14:50:04'),
(23, 'Roberto', 'Ponce', 'rponce1148@gmail.com', '$2y$12$dobYqhx3nzgiRRlds2ZKMezffcv8oF/wp57yzVA0iRza0dFo39uN2', '2025-12-02 18:11:57', '2025-12-02 18:11:57'),
(24, 'Emilio', '', 'emilio+1764765604@local.test', '$2y$12$X3jTDdRN4jAEpcL1ogEQJeEZ/eFNQXCccpgkGqgodoYfBq2RioI26', '2025-12-03 11:40:04', '2025-12-03 11:40:04'),
(25, 'Belén', '', 'belen+1764765604@local.test', '$2y$12$AvkrfTyVnbZ816iBhfJCxOjtsFyOlQX9ptdG2QoRbUZhEpNSTZkt6', '2025-12-03 11:40:05', '2025-12-03 11:40:05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calendars`
--
ALTER TABLE `calendars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cal_flat` (`flat_id`),
  ADD KEY `fk_cal_user` (`created_by`);

--
-- Indices de la tabla `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_event_cal` (`calendar_id`),
  ADD KEY `fk_event_task` (`task_id`),
  ADD KEY `fk_event_user` (`assigned_user_id`);

--
-- Indices de la tabla `calendar_historial`
--
ALTER TABLE `calendar_historial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hist_cal` (`calendar_id`),
  ADD KEY `fk_hist_flat` (`flat_id`);

--
-- Indices de la tabla `flats`
--
ALTER TABLE `flats`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `flat_members`
--
ALTER TABLE `flat_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_flat` (`flat_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indices de la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_task_flat` (`flat_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calendars`
--
ALTER TABLE `calendars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT de la tabla `calendar_historial`
--
ALTER TABLE `calendar_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `flats`
--
ALTER TABLE `flats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `flat_members`
--
ALTER TABLE `flat_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calendars`
--
ALTER TABLE `calendars`
  ADD CONSTRAINT `fk_cal_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cal_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD CONSTRAINT `fk_event_cal` FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_event_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_event_user` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `calendar_historial`
--
ALTER TABLE `calendar_historial`
  ADD CONSTRAINT `fk_hist_cal` FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_hist_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `flat_members`
--
ALTER TABLE `flat_members`
  ADD CONSTRAINT `fk_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_task_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
