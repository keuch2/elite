-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 04-05-2025 a las 18:53:46
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `elite`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `drop_column_if_exists` (IN `table_name` VARCHAR(64), IN `column_name` VARCHAR(64))   BEGIN
    IF EXISTS (
        SELECT * FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = table_name
        AND COLUMN_NAME = column_name
    ) THEN
        SET @query = CONCAT('ALTER TABLE ', table_name, ' DROP COLUMN ', column_name);
        PREPARE stmt FROM @query;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anthropometric_data`
--

CREATE TABLE `anthropometric_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `athlete_id` bigint(20) UNSIGNED NOT NULL,
  `standing_height` decimal(5,2) DEFAULT NULL,
  `sitting_height` decimal(5,2) DEFAULT NULL,
  `wingspan` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `cormic_index` decimal(5,2) DEFAULT NULL,
  `phv` decimal(5,2) DEFAULT NULL,
  `skinfold_sum` decimal(5,2) DEFAULT NULL,
  `fat_mass_percentage` decimal(5,2) DEFAULT NULL,
  `fat_mass_kg` decimal(5,2) DEFAULT NULL,
  `muscle_mass_percentage` decimal(5,2) DEFAULT NULL,
  `muscle_mass_kg` decimal(5,2) DEFAULT NULL,
  `residual_mass_percentage` decimal(5,2) DEFAULT NULL,
  `residual_mass_kg` decimal(5,2) DEFAULT NULL,
  `bone_mass_percentage` decimal(5,2) DEFAULT NULL,
  `bone_mass_kg` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `athletes`
--

CREATE TABLE `athletes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `evaluation_date` date NOT NULL,
  `age` int(11) DEFAULT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `sport` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `institution_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `athlete_profile_id` bigint(20) UNSIGNED DEFAULT NULL,
  `evaluation_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `athletes`
--

INSERT INTO `athletes` (`id`, `evaluation_date`, `age`, `grade`, `sport`, `category`, `institution_id`, `created_at`, `updated_at`, `athlete_profile_id`, `evaluation_id`) VALUES
(1, '2025-01-15', 19, '12th', 'Soccer', 'Under-20', 1, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 1, 'eval-a1e1'),
(2, '2025-01-15', 18, '12th', 'Volleyball', 'Junior', 1, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 2, 'eval-a2e1'),
(3, '2025-01-20', 17, '11th', 'Basketball', 'Youth', 2, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 3, 'eval-a3e1'),
(4, '2025-01-20', 20, 'University', 'Swimming', 'Senior', 2, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 4, 'eval-a4e1'),
(5, '2025-01-25', 18, '12th', 'Track and Field', 'Under-20', 3, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 5, 'eval-a5e1'),
(6, '2025-01-25', 19, 'University', 'Gymnastics', 'Elite', 3, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 6, 'eval-a6e1'),
(7, '2025-02-05', 17, '11th', 'Tennis', 'Junior', 4, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 7, 'eval-a7e1'),
(8, '2025-02-05', 20, 'University', 'Rowing', 'Senior', 4, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 8, 'eval-a8e1'),
(9, '2025-02-10', 18, '12th', 'Boxing', 'Light Weight', 5, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 9, 'eval-a9e1'),
(10, '2025-02-10', 19, 'University', 'Fencing', 'Senior', 5, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 10, 'eval-a10e1'),
(11, '2025-04-15', 20, 'University', 'Soccer', 'Under-21', 1, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 1, 'eval-a1e2'),
(12, '2025-04-20', 18, '12th', 'Basketball', 'Senior', 2, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 3, 'eval-a3e2'),
(13, '2025-04-25', 19, 'University', 'Track and Field', 'Under-21', 3, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 5, 'eval-a5e2'),
(14, '2025-04-30', 18, '12th', 'Tennis', 'Senior', 4, '2025-04-25 20:21:40', '2025-04-25 20:21:40', 7, 'eval-a7e2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `athletes_temp`
--

CREATE TABLE `athletes_temp` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `identity_document` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `evaluation_date` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `sport` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `institution_id` bigint(20) UNSIGNED DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `tutor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `athlete_profiles`
--

CREATE TABLE `athlete_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` enum('m','f','other') DEFAULT NULL,
  `identity_document` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `institution_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tutor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `athlete_profiles`
--

INSERT INTO `athlete_profiles` (`id`, `first_name`, `last_name`, `gender`, `identity_document`, `birth_date`, `institution_id`, `tutor_id`, `father_name`, `mother_name`, `created_at`, `updated_at`) VALUES
(1, 'Carlos', 'Mendoza', 'm', 'ID12345678', '2005-06-15', 1, 1, 'Roberto Mendoza', 'Maria Mendoza', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(2, 'Sofia', 'Garcia', 'f', 'ID23456789', '2006-03-22', 1, 2, 'Miguel Garcia', 'Ana Garcia', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(3, 'Juan', 'Rodriguez', 'm', 'ID34567890', '2007-11-10', 2, 3, 'Pedro Rodriguez', 'Carmen Rodriguez', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(4, 'Isabella', 'Martinez', 'f', 'ID45678901', '2004-09-05', 2, 4, 'Jose Martinez', 'Laura Martinez', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(5, 'Diego', 'Hernandez', 'm', 'ID56789012', '2006-07-18', 3, 5, 'Antonio Hernandez', 'Elena Hernandez', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(6, 'Valentina', 'Lopez', 'f', 'ID67890123', '2005-01-27', 3, NULL, 'Francisco Lopez', 'Isabella Lopez', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(7, 'Mateo', 'Gonzalez', 'm', 'ID78901234', '2007-05-03', 4, NULL, 'Alberto Gonzalez', 'Sofia Gonzalez', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(8, 'Camila', 'Torres', 'f', 'ID89012345', '2004-12-11', 4, NULL, 'Eduardo Torres', 'Valentina Torres', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(9, 'Santiago', 'Perez', 'm', 'ID90123456', '2006-08-29', 5, NULL, 'Rafael Perez', 'Gabriela Perez', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(10, 'Lucia', 'Sanchez', 'f', 'ID01234567', '2005-04-14', 5, NULL, 'Manuel Sanchez', 'Carolina Sanchez', '2025-04-25 20:21:40', '2025-04-25 20:21:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institutions`
--

CREATE TABLE `institutions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `representative_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `institutions`
--

INSERT INTO `institutions` (`id`, `name`, `created_at`, `updated_at`, `address`, `phone`, `email`, `representative_name`) VALUES
(1, 'Olympic Training Center', '2025-04-25 20:21:40', '2025-04-25 20:21:40', '123 Olympic Way, Sports City', '555-123-4567', 'contact@olympictraining.com', 'Dr. Michael Rodriguez'),
(2, 'City Sports Academy', '2025-04-25 20:21:40', '2025-04-25 20:21:40', '456 Championship Blvd, Metro City', '555-234-5678', 'info@citysportsacademy.com', 'Prof. Sarah Johnson'),
(3, 'National Athletics Institute', '2025-04-25 20:21:40', '2025-04-25 20:21:40', '789 Victory Lane, Capital City', '555-345-6789', 'support@nationalathletics.org', 'Coach David Thompson'),
(4, 'Elite Performance Center', '2025-04-25 20:21:40', '2025-04-25 20:21:40', '101 Gold Medal Road, Excellence Town', '555-456-7890', 'admin@eliteperformance.net', 'Dr. Lisa Martinez'),
(5, 'Regional Sports Development Hub', '2025-04-25 20:21:40', '2025-04-25 20:21:40', '202 Competition Street, Sport Valley', '555-567-8901', 'contact@regionalsports.org', 'Director Carlos Vega');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jumpability`
--

CREATE TABLE `jumpability` (
  `id` int(11) NOT NULL,
  `athlete_id` bigint(20) UNSIGNED NOT NULL,
  `test_type` enum('Abalakov','CMJ','CMJ_Unipodal_Right','CMJ_Unipodal_Left','Deep_Jump_30cm','Deep_Jump_45cm','Deep_Jump_60cm','Deep_Jump_75cm') NOT NULL,
  `height_cm` decimal(5,2) DEFAULT NULL,
  `impulse` decimal(8,2) DEFAULT NULL,
  `max_propulsive_force` decimal(8,2) DEFAULT NULL,
  `right_propulsive_asymmetry` decimal(5,2) DEFAULT NULL,
  `left_propulsive_asymmetry` decimal(5,2) DEFAULT NULL,
  `max_braking_force` decimal(8,2) DEFAULT NULL,
  `right_braking_asymmetry` decimal(5,2) DEFAULT NULL,
  `left_braking_asymmetry` decimal(5,2) DEFAULT NULL,
  `max_landing_force` decimal(8,2) DEFAULT NULL,
  `right_landing_asymmetry` decimal(5,2) DEFAULT NULL,
  `left_landing_asymmetry` decimal(5,2) DEFAULT NULL,
  `rsi` decimal(5,2) DEFAULT NULL,
  `ground_contact_time` decimal(5,2) DEFAULT NULL,
  `flight_time` decimal(5,2) DEFAULT NULL,
  `category_avg_height` decimal(5,2) DEFAULT NULL,
  `category_high_height` decimal(5,2) DEFAULT NULL,
  `category_low_height` decimal(5,2) DEFAULT NULL,
  `category_avg_impulse` decimal(5,2) DEFAULT NULL,
  `category_std_dev_height` decimal(5,2) DEFAULT NULL,
  `category_std_dev_impulse` decimal(5,2) DEFAULT NULL,
  `category_max_propulsive_force` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `jumpability`
--

INSERT INTO `jumpability` (`id`, `athlete_id`, `test_type`, `height_cm`, `impulse`, `max_propulsive_force`, `right_propulsive_asymmetry`, `left_propulsive_asymmetry`, `max_braking_force`, `right_braking_asymmetry`, `left_braking_asymmetry`, `max_landing_force`, `right_landing_asymmetry`, `left_landing_asymmetry`, `rsi`, `ground_contact_time`, `flight_time`, `category_avg_height`, `category_high_height`, `category_low_height`, `category_avg_impulse`, `category_std_dev_height`, `category_std_dev_impulse`, `category_max_propulsive_force`, `created_at`, `updated_at`) VALUES
(1, 1, 'Abalakov', 45.80, 250.30, 2250.50, 3.20, -3.20, 1850.20, 2.50, -2.50, 3100.50, 4.10, -4.10, NULL, NULL, 0.61, 42.50, 48.00, 36.00, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(2, 1, 'CMJ', 42.30, 235.70, 2150.80, 2.80, -2.80, 1750.60, 2.20, -2.20, 2950.80, 3.80, -3.80, NULL, NULL, 0.58, 40.20, 45.50, 34.50, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(3, 3, 'Abalakov', 50.20, 275.60, 2400.30, 2.60, -2.60, 1950.40, 2.00, -2.00, 3250.70, 3.50, -3.50, NULL, NULL, 0.64, 48.50, 53.50, 42.50, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(4, 3, 'CMJ', 46.80, 260.40, 2300.10, 2.20, -2.20, 1850.90, 1.80, -1.80, 3150.20, 3.20, -3.20, NULL, NULL, 0.62, 45.00, 51.00, 39.50, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(5, 5, 'CMJ_Unipodal_Right', 28.50, 175.20, 1850.60, NULL, NULL, 1450.20, NULL, NULL, 2450.30, NULL, NULL, NULL, NULL, 0.48, 26.50, 31.00, 22.00, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(6, 5, 'CMJ_Unipodal_Left', 26.80, 168.70, 1800.40, NULL, NULL, 1400.70, NULL, NULL, 2400.90, NULL, NULL, NULL, NULL, 0.47, 26.50, 31.00, 22.00, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(7, 7, 'Deep_Jump_30cm', 38.50, 220.30, 2050.70, 3.80, -3.80, 1650.50, 3.20, -3.20, 2750.40, 4.50, -4.50, 1.85, 0.21, 0.56, 36.00, 41.50, 30.50, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(8, 7, 'Deep_Jump_45cm', 36.20, 210.80, 2000.30, 4.20, -4.20, 1600.90, 3.60, -3.60, 2800.20, 5.00, -5.00, 1.65, 0.22, 0.54, 33.50, 39.00, 28.00, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(9, 9, 'Deep_Jump_60cm', 33.80, 200.50, 1950.20, 4.80, -4.80, 1550.40, 4.00, -4.00, 2850.60, 5.50, -5.50, 1.45, 0.23, 0.51, 31.00, 36.50, 26.00, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(10, 9, 'Deep_Jump_75cm', 30.50, 190.20, 1900.80, 5.20, -5.20, 1500.70, 4.40, -4.40, 2900.30, 6.00, -6.00, 1.25, 0.24, 0.48, 28.50, 34.00, 23.50, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laterality`
--

CREATE TABLE `laterality` (
  `id` int(11) NOT NULL,
  `athlete_id` bigint(20) UNSIGNED NOT NULL,
  `eye` varchar(50) DEFAULT NULL,
  `shoulder` varchar(50) DEFAULT NULL,
  `hand` varchar(50) DEFAULT NULL,
  `hip` varchar(50) DEFAULT NULL,
  `foot` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `laterality`
--

INSERT INTO `laterality` (`id`, `athlete_id`, `eye`, `shoulder`, `hand`, `hip`, `foot`, `created_at`, `updated_at`) VALUES
(1, 1, 'right', 'right', 'right', 'right', 'right', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(2, 2, 'right', 'right', 'right', 'left', 'left', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(3, 3, 'left', 'left', 'left', 'left', 'left', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(4, 4, 'right', 'right', 'left', 'right', 'right', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(5, 5, 'left', 'right', 'right', 'right', 'right', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(6, 6, 'right', 'right', 'right', 'right', 'left', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(7, 7, 'left', 'left', 'left', 'right', 'left', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(8, 8, 'right', 'right', 'left', 'left', 'left', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(9, 9, 'right', 'left', 'left', 'left', 'left', '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(10, 10, 'left', 'right', 'right', 'left', 'right', '2025-04-25 20:21:40', '2025-04-25 20:21:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(5, '0001_01_01_000000_create_users_table', 1),
(6, '0001_01_01_000001_create_cache_table', 1),
(7, '0001_01_01_000002_create_jobs_table', 1),
(8, '2025_04_03_210148_create_report_configs_table', 1),
(9, '2025_04_17_211414_create_athlete_profiles_table', 2),
(10, '2025_04_17_211807_create_institutions_table', 2),
(11, '2025_04_17_211836_create_tutors_table', 2),
(12, '2025_04_17_232359_modify_athletes_temp_table', 3),
(13, '2025_04_17_211852_create_athletes_table', 4),
(14, '2025_04_17_211948_create_anthropometric_data_table', 4),
(15, '2025_04_17_233106_create_missing_athletes_table', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mobility`
--

CREATE TABLE `mobility` (
  `id` int(11) NOT NULL,
  `athlete_id` bigint(20) UNSIGNED NOT NULL,
  `sit_and_reach` decimal(5,2) DEFAULT NULL,
  `right_ankle_mobility` decimal(5,2) DEFAULT NULL,
  `left_ankle_mobility` decimal(5,2) DEFAULT NULL,
  `right_anterior_motor_control` decimal(5,2) DEFAULT NULL,
  `left_anterior_motor_control` decimal(5,2) DEFAULT NULL,
  `right_posterolateral_motor_control` decimal(5,2) DEFAULT NULL,
  `left_posterolateral_motor_control` decimal(5,2) DEFAULT NULL,
  `right_posteromedial_motor_control` decimal(5,2) DEFAULT NULL,
  `left_posteromedial_motor_control` decimal(5,2) DEFAULT NULL,
  `right_shoulder_mobility` decimal(5,2) DEFAULT NULL,
  `left_shoulder_mobility` decimal(5,2) DEFAULT NULL,
  `right_thoracic_mobility` decimal(5,2) DEFAULT NULL,
  `left_thoracic_mobility` decimal(5,2) DEFAULT NULL,
  `snatch_squat` decimal(5,2) DEFAULT NULL,
  `right_hurdle_step` decimal(5,2) DEFAULT NULL,
  `left_hurdle_step` decimal(5,2) DEFAULT NULL,
  `right_inline_lunge` decimal(5,2) DEFAULT NULL,
  `left_inline_lunge` decimal(5,2) DEFAULT NULL,
  `right_straight_leg_raise` decimal(5,2) DEFAULT NULL,
  `left_straight_leg_raise` decimal(5,2) DEFAULT NULL,
  `trunk_stability_extension` decimal(5,2) DEFAULT NULL,
  `right_rotation_stability` decimal(5,2) DEFAULT NULL,
  `left_rotation_stability` decimal(5,2) DEFAULT NULL,
  `right_internal_hip_rotation` decimal(5,2) DEFAULT NULL,
  `left_internal_hip_rotation` decimal(5,2) DEFAULT NULL,
  `right_external_hip_rotation` decimal(5,2) DEFAULT NULL,
  `left_external_hip_rotation` decimal(5,2) DEFAULT NULL,
  `sit_and_reach_category_avg` decimal(5,2) DEFAULT NULL,
  `sit_and_reach_category_high` decimal(5,2) DEFAULT NULL,
  `sit_and_reach_category_low` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `mobility`
--

INSERT INTO `mobility` (`id`, `athlete_id`, `sit_and_reach`, `right_ankle_mobility`, `left_ankle_mobility`, `right_anterior_motor_control`, `left_anterior_motor_control`, `right_posterolateral_motor_control`, `left_posterolateral_motor_control`, `right_posteromedial_motor_control`, `left_posteromedial_motor_control`, `right_shoulder_mobility`, `left_shoulder_mobility`, `right_thoracic_mobility`, `left_thoracic_mobility`, `snatch_squat`, `right_hurdle_step`, `left_hurdle_step`, `right_inline_lunge`, `left_inline_lunge`, `right_straight_leg_raise`, `left_straight_leg_raise`, `trunk_stability_extension`, `right_rotation_stability`, `left_rotation_stability`, `right_internal_hip_rotation`, `left_internal_hip_rotation`, `right_external_hip_rotation`, `left_external_hip_rotation`, `sit_and_reach_category_avg`, `sit_and_reach_category_high`, `sit_and_reach_category_low`, `created_at`, `updated_at`) VALUES
(1, 1, 12.50, 40.20, 39.80, 72.50, 71.80, 88.30, 87.50, 95.20, 94.80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(2, 2, 18.30, 42.50, 42.10, 75.30, 74.50, 90.20, 89.60, 98.50, 97.20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(3, 3, 10.80, 38.70, 38.20, 70.20, 69.50, 86.50, 85.80, 93.40, 92.80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(4, 4, 15.60, 41.30, 40.80, 73.80, 73.20, 89.10, 88.50, 96.70, 95.50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(5, 5, 13.20, 39.50, 39.00, 71.50, 71.00, 87.20, 86.80, 94.30, 93.50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(6, 6, 19.50, 43.20, 42.50, 76.50, 75.80, 91.50, 90.80, 99.20, 98.50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(7, 7, 11.50, 39.00, 38.50, 71.00, 70.50, 86.80, 86.20, 93.80, 93.20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(8, 8, 16.80, 42.00, 41.50, 74.50, 74.00, 90.00, 89.50, 97.50, 97.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(9, 9, 14.20, 40.50, 40.00, 72.80, 72.20, 88.50, 88.00, 95.80, 95.20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(10, 10, 17.50, 42.80, 42.20, 75.80, 75.20, 91.00, 90.50, 98.80, 98.20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `neuro_cognitive_motor`
--

CREATE TABLE `neuro_cognitive_motor` (
  `id` int(11) NOT NULL,
  `athlete_id` bigint(20) UNSIGNED NOT NULL,
  `oculo_manual_reaction` decimal(5,2) DEFAULT NULL,
  `oculo_podal_reaction` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `neuro_cognitive_motor`
--

INSERT INTO `neuro_cognitive_motor` (`id`, `athlete_id`, `oculo_manual_reaction`, `oculo_podal_reaction`, `created_at`, `updated_at`) VALUES
(1, 1, 0.25, 0.32, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(2, 2, 0.23, 0.30, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(3, 3, 0.28, 0.35, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(4, 4, 0.22, 0.29, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(5, 5, 0.26, 0.33, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(6, 6, 0.21, 0.28, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(7, 7, 0.27, 0.34, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(8, 8, 0.24, 0.31, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(9, 9, 0.26, 0.33, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(10, 10, 0.23, 0.30, '2025-04-25 20:21:40', '2025-04-25 20:21:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `athlete_id` bigint(20) UNSIGNED NOT NULL,
  `template_id` bigint(20) UNSIGNED DEFAULT NULL,
  `report_data` longtext DEFAULT NULL CHECK (json_valid(`report_data`)),
  `file_path` varchar(255) DEFAULT NULL,
  `sent_to_tutor` tinyint(1) DEFAULT 0,
  `sent_to_institution` tinyint(1) DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `reports`
--

INSERT INTO `reports` (`id`, `athlete_id`, `template_id`, `report_data`, `file_path`, `sent_to_tutor`, `sent_to_institution`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, 2, '{\"nombre\":\"Sofia\",\"apellido\":\"Garcia\",\"documento_de_identidad\":\"ID23456789\",\"fecha_de_nacimiento\":\"2006-03-22\",\"institucion\":\"Olympic Training Center\",\"talla_parado\":\"123\",\"masa_adiposa_en_kg\":\"123\",\"masa_muscular_en_kg\":\"123\",\"masa_residual_en_kg\":\"123\",\"masa_osea_en_kg\":\"123\"}', NULL, 0, 0, 3, '2025-05-04 19:45:38', '2025-05-04 19:45:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `report_configs`
--

CREATE TABLE `report_configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`fields`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `report_configs`
--

INSERT INTO `report_configs` (`id`, `name`, `fields`, `created_at`, `updated_at`) VALUES
(1, 'Reporte Básico', '[\"nombre\",\"apellido\",\"documento_de_identidad\",\"fecha_de_nacimiento\",\"institucion\",\"talla_parado\",\"talla_sentado\",\"envergadura\",\"peso\",\"indice_cormico\",\"phv\",\"sumatoria_de_pliegues\",\"masa_adiposa_en_porcentaje\",\"masa_adiposa_en_kg\",\"masa_muscular_en_porcentaje\",\"masa_muscular_en_kg\",\"masa_residual_en_porcentaje\",\"masa_residual_en_kg\",\"masa_osea_en_porcentaje\",\"masa_osea_en_kg\",\"masa_de_la_piel_en_porcentaje\",\"masa_de_la_piel_en_kg\",\"circunferencia_muslo_medio_derecha\",\"circunferencia_muslo_medio_izquierda\",\"circunferencia_vasto_interno_derecha\",\"circunferencia_vasto_interno_izquierda\",\"pliegue_anterior_muslo_derecho\",\"pliegue_anterior_muslo_izquierdo\",\"pliegue_posterior_muslo_derecho\",\"pliegue_posterior_muslo_izquierdo\",\"sit_and_reach\",\"movilidad_tobillo_derecha\",\"movilidad_tobillo_izquierda\",\"control_motor_anterior_derecha\",\"control_motor_anterior_izquierda\",\"control_motor_postero_lateral_derecha\",\"control_motor_postero_lateral_izquierda\",\"control_motor_postero_medial_derecha\",\"control_motor_postero_medial_izquierda\",\"movilidad_de_hombro_derecha\",\"movilidad_de_hombro_izquierda\",\"movilidad_toracica_derecha\",\"movilidad_toracica_izquierda\",\"sentadilla_arranque\",\"paso_con_obstaculo_derecha\",\"paso_con_obstaculo_izquierda\",\"desplante_en_linea_derecha\",\"desplante_en_linea_izquierda\",\"elevacion_de_pierna_recta_derecha\",\"elevacion_de_pierna_recta_izquierda\",\"estabilidad_de_tronco_en_extension\",\"estabilidad_en_rotacion_derecha\",\"estabilidad_en_rotacion_izquierda\",\"rotacion_de_cadera_interna_derecha\",\"rotacion_de_cadera_interna_izquierda\",\"rotacion_de_cadera_externa_derecha\",\"rotacion_de_cadera_externa_izquierda\",\"sit_and_reach_promedio_categoria\",\"sit_and_reach_mas_alto_categoria\",\"sit_and_reach_mme_mas_bajo_categoria\",\"agarre\",\"agarre_alto_categoria\",\"agarre_promedio_categoria\",\"agarre_bajo_categoria\",\"lanzar\",\"atrapar\",\"conduccion_manos\",\"conduccion_pies\",\"equilibrarse\",\"rodar\",\"saltar\",\"correr\"]', '2025-05-04 19:00:50', '2025-05-04 19:00:50'),
(2, 'Reporte Corto', '[\"nombre\",\"apellido\",\"documento_de_identidad\",\"fecha_de_nacimiento\",\"institucion\",\"talla_parado\",\"masa_adiposa_en_kg\",\"masa_muscular_en_kg\",\"masa_residual_en_kg\",\"masa_osea_en_kg\"]', '2025-05-04 19:01:57', '2025-05-04 19:01:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('FoG3s6shVxyAvdyph2CYZr9YR8Ll0Xw3JLJouqI4', 3, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidFZNYVQ4OWxKckw5d1F5S1RURmxtbnZwZzM0VkwyVUdBTUdQeXJqbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTQ6Imh0dHA6Ly9sb2NhbGhvc3QvZWxpdGUtc3BvcnRzLXRyYWNrZXIvcHVibGljL3JlcG9ydHMvMSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1746377569);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `strength`
--

CREATE TABLE `strength` (
  `id` int(11) NOT NULL,
  `athlete_id` bigint(20) UNSIGNED NOT NULL,
  `grip_strength` decimal(5,2) DEFAULT NULL,
  `grip_strength_category_high` decimal(5,2) DEFAULT NULL,
  `grip_strength_category_avg` decimal(5,2) DEFAULT NULL,
  `grip_strength_category_low` decimal(5,2) DEFAULT NULL,
  `push_ups` int(11) DEFAULT NULL,
  `push_ups_category_avg` int(11) DEFAULT NULL,
  `push_ups_category_high` int(11) DEFAULT NULL,
  `push_ups_category_low` int(11) DEFAULT NULL,
  `pull_ups` int(11) DEFAULT NULL,
  `pull_ups_category_avg` int(11) DEFAULT NULL,
  `pull_ups_category_high` int(11) DEFAULT NULL,
  `pull_ups_category_low` int(11) DEFAULT NULL,
  `inverted_row` int(11) DEFAULT NULL,
  `inverted_row_category_avg` int(11) DEFAULT NULL,
  `inverted_row_category_high` int(11) DEFAULT NULL,
  `inverted_row_category_low` int(11) DEFAULT NULL,
  `medicine_ball_throw_distance` decimal(5,2) DEFAULT NULL,
  `medicine_ball_throw_category_avg` decimal(5,2) DEFAULT NULL,
  `medicine_ball_throw_category_high` decimal(5,2) DEFAULT NULL,
  `medicine_ball_throw_category_low` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `strength`
--

INSERT INTO `strength` (`id`, `athlete_id`, `grip_strength`, `grip_strength_category_high`, `grip_strength_category_avg`, `grip_strength_category_low`, `push_ups`, `push_ups_category_avg`, `push_ups_category_high`, `push_ups_category_low`, `pull_ups`, `pull_ups_category_avg`, `pull_ups_category_high`, `pull_ups_category_low`, `inverted_row`, `inverted_row_category_avg`, `inverted_row_category_high`, `inverted_row_category_low`, `medicine_ball_throw_distance`, `medicine_ball_throw_category_avg`, `medicine_ball_throw_category_high`, `medicine_ball_throw_category_low`, `created_at`, `updated_at`) VALUES
(1, 1, 45.50, 50.00, 42.00, 35.00, 32, 25, 35, 15, 12, 8, 15, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(2, 2, 32.80, 42.00, 35.00, 28.00, 18, 15, 25, 8, 5, 4, 8, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(3, 3, 48.20, 52.00, 45.00, 38.00, 35, 28, 38, 18, 15, 10, 18, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(4, 4, 34.50, 44.00, 37.00, 30.00, 20, 18, 28, 10, 6, 5, 10, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(5, 5, 46.80, 51.00, 44.00, 37.00, 30, 26, 36, 16, 14, 9, 16, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(6, 6, 33.50, 43.00, 36.00, 29.00, 22, 17, 27, 9, 7, 5, 9, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(7, 7, 47.50, 51.50, 44.50, 37.50, 33, 27, 37, 17, 13, 9, 17, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(8, 8, 35.20, 44.50, 37.50, 30.50, 21, 18, 28, 10, 8, 6, 11, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(9, 9, 46.20, 50.50, 43.50, 36.50, 31, 26, 36, 16, 10, 8, 15, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(10, 10, 34.00, 43.50, 36.50, 29.50, 19, 16, 26, 9, 4, 3, 7, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 20:21:40', '2025-04-25 20:21:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutors`
--

CREATE TABLE `tutors` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `relationship` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `tutors`
--

INSERT INTO `tutors` (`id`, `first_name`, `last_name`, `email`, `phone_number`, `created_at`, `updated_at`, `relationship`) VALUES
(1, 'Roberto', 'Mendoza', 'roberto.mendoza@example.com', '555-111-2222', '2025-04-25 20:21:40', '2025-04-25 20:21:40', NULL),
(2, 'Ana', 'Garcia', 'ana.garcia@example.com', '555-222-3333', '2025-04-25 20:21:40', '2025-04-25 20:21:40', NULL),
(3, 'Pedro', 'Rodriguez', 'pedro.rodriguez@example.com', '555-333-4444', '2025-04-25 20:21:40', '2025-04-25 20:21:40', NULL),
(4, 'Laura', 'Martinez', 'laura.martinez@example.com', '555-444-5555', '2025-04-25 20:21:40', '2025-04-25 20:21:40', NULL),
(5, 'Antonio', 'Hernandez', 'antonio.hernandez@example.com', '555-555-6666', '2025-04-25 20:21:40', '2025-04-25 20:21:40', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Admin', 'admin@elite.com', NULL, '$2y$12$I4KI1FBVVsXAUJcNGeYz6et8wpVOTGVj9GcHcNeA3f3pOoue25Q8q', NULL, '2025-04-17 22:56:34', '2025-04-17 22:56:34'),
(4, 'boris', 'keuch2@gmail.com', NULL, '$2y$12$Hk0X0P2tLB6yILWaZra3ce6FqNS1nH2vVBDNO8XdQK6aWRFAW9B46', NULL, '2025-04-18 02:43:13', '2025-04-18 02:43:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `velocity`
--

CREATE TABLE `velocity` (
  `id` int(11) NOT NULL,
  `athlete_id` bigint(20) UNSIGNED NOT NULL,
  `distance_m` int(11) NOT NULL,
  `time_seconds` decimal(5,2) DEFAULT NULL,
  `speed_kmh` decimal(5,2) DEFAULT NULL,
  `speed_ms` decimal(5,2) DEFAULT NULL,
  `category_avg_time` decimal(5,2) DEFAULT NULL,
  `category_high_time` decimal(5,2) DEFAULT NULL,
  `category_low_time` decimal(5,2) DEFAULT NULL,
  `category_avg_kmh` decimal(5,2) DEFAULT NULL,
  `category_high_kmh` decimal(5,2) DEFAULT NULL,
  `category_low_kmh` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Volcado de datos para la tabla `velocity`
--

INSERT INTO `velocity` (`id`, `athlete_id`, `distance_m`, `time_seconds`, `speed_kmh`, `speed_ms`, `category_avg_time`, `category_high_time`, `category_low_time`, `category_avg_kmh`, `category_high_kmh`, `category_low_kmh`, `created_at`, `updated_at`) VALUES
(1, 1, 20, 3.25, 22.15, 6.15, 3.45, 3.10, 3.80, 20.87, 23.23, 18.95, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(2, 1, 40, 5.85, 24.62, 6.84, 6.15, 5.65, 6.65, 23.41, 25.49, 21.65, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(3, 2, 20, 3.65, 19.73, 5.48, 3.75, 3.45, 4.05, 19.20, 20.87, 17.78, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(4, 2, 40, 6.42, 22.43, 6.23, 6.65, 6.20, 7.10, 21.65, 23.23, 20.28, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(5, 3, 20, 3.18, 22.64, 6.29, 3.40, 3.05, 3.75, 21.18, 23.61, 19.20, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(6, 3, 40, 5.72, 25.17, 6.99, 6.05, 5.55, 6.55, 23.80, 25.95, 21.98, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(7, 4, 20, 3.58, 20.11, 5.59, 3.70, 3.40, 4.00, 19.46, 21.18, 18.00, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(8, 4, 40, 6.38, 22.57, 6.27, 6.60, 6.15, 7.05, 21.82, 23.41, 20.43, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(9, 5, 20, 3.22, 22.36, 6.21, 3.45, 3.10, 3.80, 20.87, 23.23, 18.95, '2025-04-25 20:21:40', '2025-04-25 20:21:40'),
(10, 5, 40, 5.80, 24.83, 6.90, 6.15, 5.65, 6.65, 23.41, 25.49, 21.65, '2025-04-25 20:21:40', '2025-04-25 20:21:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anthropometric_data`
--
ALTER TABLE `anthropometric_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `athlete_id` (`athlete_id`);

--
-- Indices de la tabla `athletes`
--
ALTER TABLE `athletes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_evaluation_id` (`evaluation_id`),
  ADD KEY `idx_athlete_profile_id` (`athlete_profile_id`);

--
-- Indices de la tabla `athletes_temp`
--
ALTER TABLE `athletes_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `athlete_profiles`
--
ALTER TABLE `athlete_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `institutions`
--
ALTER TABLE `institutions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `jumpability`
--
ALTER TABLE `jumpability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jumpability_athlete` (`athlete_id`);

--
-- Indices de la tabla `laterality`
--
ALTER TABLE `laterality`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_laterality_athlete` (`athlete_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mobility`
--
ALTER TABLE `mobility`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mobility_athlete` (`athlete_id`);

--
-- Indices de la tabla `neuro_cognitive_motor`
--
ALTER TABLE `neuro_cognitive_motor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_neuro_athlete` (`athlete_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reports_athlete` (`athlete_id`);

--
-- Indices de la tabla `report_configs`
--
ALTER TABLE `report_configs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `report_configs_name_unique` (`name`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `strength`
--
ALTER TABLE `strength`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_strength_athlete` (`athlete_id`);

--
-- Indices de la tabla `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `velocity`
--
ALTER TABLE `velocity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_velocity_athlete` (`athlete_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anthropometric_data`
--
ALTER TABLE `anthropometric_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `athletes`
--
ALTER TABLE `athletes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `athletes_temp`
--
ALTER TABLE `athletes_temp`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `athlete_profiles`
--
ALTER TABLE `athlete_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `institutions`
--
ALTER TABLE `institutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jumpability`
--
ALTER TABLE `jumpability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `laterality`
--
ALTER TABLE `laterality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `mobility`
--
ALTER TABLE `mobility`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `neuro_cognitive_motor`
--
ALTER TABLE `neuro_cognitive_motor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `report_configs`
--
ALTER TABLE `report_configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `strength`
--
ALTER TABLE `strength`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `velocity`
--
ALTER TABLE `velocity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
