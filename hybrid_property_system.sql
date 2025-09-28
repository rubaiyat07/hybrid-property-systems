-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2025 at 09:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hybrid_property_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `license_no` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `user_id`, `commission_rate`, `license_no`, `created_at`, `updated_at`) VALUES
(1, 7, 5.00, 'LIC-ALE4986', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(2, 7, 5.00, 'LIC-ALE2020', '2025-09-27 00:21:22', '2025-09-27 00:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `buyers`
--

CREATE TABLE `buyers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `contact_info` text DEFAULT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buyers`
--

INSERT INTO `buyers` (`id`, `user_id`, `contact_info`, `preferences`, `created_at`, `updated_at`) VALUES
(1, 8, 'Phone: 01744444444', '\"{\\\"type\\\":\\\"house\\\",\\\"budget\\\":\\\"5000000\\\"}\"', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(2, 8, 'Phone: 01744444444', '\"{\\\"type\\\":\\\"house\\\",\\\"budget\\\":\\\"5000000\\\"}\"', '2025-09-27 00:21:22', '2025-09-27 00:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `property_id`, `category`, `amount`, `description`, `date`, `created_at`, `updated_at`) VALUES
(1, 1, 'maintenance', 5000.00, 'Maintenance cost', '2025-09-17', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(2, 1, 'maintenance', 5000.00, 'Maintenance cost', '2025-09-27', '2025-09-27 00:21:23', '2025-09-27 00:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lease_id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('unpaid','paid','overdue') NOT NULL DEFAULT 'unpaid',
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('new','contacted','interested','closed') NOT NULL DEFAULT 'new',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `buyer_id`, `property_id`, `assigned_to`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 7, 'new', 'Interested in viewing the property.', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(2, 1, 1, 7, 'new', 'Interested in viewing the property.', '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(3, 2, 2, NULL, 'new', 'Interested in viewing the property.', '2025-09-27 00:21:23', '2025-09-27 00:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `leases`
--

CREATE TABLE `leases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `rent_amount` decimal(10,2) NOT NULL,
  `deposit` decimal(10,2) DEFAULT NULL,
  `status` enum('active','expired','terminated') NOT NULL DEFAULT 'active',
  `document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenance_requests`
--

INSERT INTO `maintenance_requests` (`id`, `tenant_id`, `unit_id`, `description`, `priority`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Leaky faucet in kitchen', 'medium', 'pending', '2025-09-27 00:21:22', '2025-09-27 00:21:22'),
(2, 2, 2, 'Leaky faucet in kitchen', 'medium', 'pending', '2025-09-27 00:21:22', '2025-09-27 00:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `content`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Hello, this is a test message.', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(2, 2, 1, 'Hello, this is a test message.', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(3, 3, 1, 'Hello, this is a test message.', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(4, 4, 1, 'Hello, this is a test message.', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(5, 5, 1, 'Hello, this is a test message.', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(6, 6, 1, 'Hello, this is a test message.', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(7, 7, 1, 'Hello, this is a test message.', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(8, 8, 1, 'Hello, this is a test message.', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(9, 9, 1, 'Hello, this is a test message.', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(10, 1, 1, 'Hello, this is a test message.', NULL, '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(11, 2, 1, 'Hello, this is a test message.', NULL, '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(12, 3, 1, 'Hello, this is a test message.', NULL, '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(13, 4, 1, 'Hello, this is a test message.', NULL, '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(14, 5, 1, 'Hello, this is a test message.', NULL, '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(15, 6, 1, 'Hello, this is a test message.', NULL, '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(16, 7, 1, 'Hello, this is a test message.', NULL, '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(17, 8, 1, 'Hello, this is a test message.', NULL, '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(18, 9, 1, 'Hello, this is a test message.', NULL, '2025-09-27 00:21:23', '2025-09-27 00:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_08_30_181704_create_permission_tables', 1),
(6, '2025_08_31_155038_create_properties_table', 1),
(7, '2025_08_31_155100_create_units_table', 1),
(8, '2025_08_31_155122_create_property_images_table', 1),
(9, '2025_08_31_155201_create_tenants_table', 1),
(10, '2025_08_31_155232_create_leases_table', 1),
(11, '2025_08_31_155503_create_payments_table', 1),
(12, '2025_08_31_155524_create_invoices_table', 1),
(13, '2025_08_31_155546_create_maintenance_requests_table', 1),
(14, '2025_08_31_155623_create_work_orders_table', 1),
(15, '2025_08_31_155651_create_expenses_table', 1),
(16, '2025_08_31_155713_create_reports_table', 1),
(17, '2025_08_31_155742_create_notifications_table', 1),
(18, '2025_08_31_155808_create_messages_table', 1),
(19, '2025_08_31_155829_create_buyers_table', 1),
(20, '2025_08_31_155846_create_leads_table', 1),
(21, '2025_08_31_155905_create_agents_table', 1),
(22, '2025_08_31_155946_create_transactions_table', 1),
(23, '2025_08_31_160017_create_plots_table', 1),
(24, '2025_08_31_160332_create_tenant_screenings_table', 1),
(25, '2025_08_31_160410_create_tenant_references_table', 1),
(26, '2025_08_31_160511_create_tenant_employment_table', 1),
(27, '2025_09_04_034321_create_property_documents_table', 1),
(28, '2025_09_04_034405_create_property_taxes_table', 1),
(29, '2025_09_04_034412_create_property_bills_table', 1),
(30, '2025_09_16_015059_add_property_registration_columns', 1),
(31, '2025_09_16_054701_alter_type_column_in_properties_table', 1),
(32, '2025_09_16_211309_add_availability_status_to_properties_table', 1),
(33, '2025_09_16_221705_add_due_date_to_payments_table', 1),
(34, '2025_09_17_000000_add_commission_amount_to_transactions_table', 1),
(35, '2025_09_17_041548_add_profile_fields_to_users_table', 1),
(36, '2025_09_17_042451_add_profile_fields_to_users_table', 1),
(37, '2025_09_17_045320_add_document_fields_to_tenant_screenings_table', 1),
(38, '2025_09_17_045338_add_document_fields_to_tenant_screenings_table', 1),
(39, '2025_01_20_000000_add_listing_fields_to_units_table', 2),
(40, '2025_01_20_000001_create_unit_inquiries_table', 2),
(41, '2025_01_21_000000_create_tenant_leads_table', 2),
(42, '2025_09_20_170711_create_property_facilities_table', 2),
(43, '2025_09_20_170712_create_property_transfers_table', 2),
(44, '2025_09_20_170713_create_property_transfer_documents_table', 2),
(45, '2025_09_22_214321_add_listing_fields_to_units_table', 2),
(46, '2025_09_24_034141_add_agent_id_to_properties_table', 3),
(47, '2024_09_27_070000_recreate_status_column_in_unit_inquiries_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 7),
(5, 'App\\Models\\User', 8),
(6, 'App\\Models\\User', 9);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning') NOT NULL DEFAULT 'info',
  `status` enum('unread','read') NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(2, 2, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(3, 3, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(4, 4, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(5, 5, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(6, 6, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(7, 7, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(8, 8, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(9, 9, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(10, 1, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(11, 2, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(12, 3, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(13, 4, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(14, 5, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(15, 6, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(16, 7, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(17, 8, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-27 00:21:23', '2025-09-27 00:21:23'),
(18, 9, 'Welcome', 'Welcome to the property management system.', 'info', 'unread', '2025-09-27 00:21:23', '2025-09-27 00:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lease_id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `due_date` date NOT NULL,
  `date` date NOT NULL,
  `method` varchar(255) DEFAULT NULL,
  `status` enum('paid','pending','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage properties', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14'),
(2, 'manage tenants', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14'),
(3, 'manage leases', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14'),
(4, 'manage payments', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14'),
(5, 'approve documents', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plots`
--

CREATE TABLE `plots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `plot_number` varchar(255) NOT NULL,
  `size` decimal(10,2) DEFAULT NULL,
  `status` enum('available','sold') NOT NULL DEFAULT 'available',
  `map_coordinates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`map_coordinates`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `type` enum('apartment','house','condo','townhouse','residential','commercial','land','plot','other') NOT NULL,
  `status` enum('rent','sale') NOT NULL DEFAULT 'rent',
  `availability_status` enum('active','inactive','maintenance') NOT NULL DEFAULT 'inactive',
  `registration_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `registration_notes` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `price_or_rent` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `owner_id`, `agent_id`, `name`, `address`, `city`, `state`, `zip_code`, `type`, `status`, `availability_status`, `registration_status`, `description`, `image`, `registration_notes`, `approved_at`, `approved_by`, `price_or_rent`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 'Sunny Apartments', '123 Main St', 'Dhaka', 'Dhaka', '1200', 'apartment', 'rent', 'active', 'approved', 'A beautiful apartment complex with modern amenities.', '/storage/properties/KgEprj3JlnsIcNgvD3pcojYarBWTHiupSHbMrnLE.jpg', NULL, '2025-09-22 21:38:43', 1, 50000.00, '2025-09-17 01:23:15', '2025-09-22 21:38:43'),
(2, 2, NULL, 'Green Villa', '456 Oak Ave', 'Chittagong', 'Chittagong', '4000', 'house', 'sale', 'inactive', 'rejected', 'Spacious villa with garden.', NULL, 'Incomplete information\r\n\r\n• Invalid images\r\n\r\n• Missing required documents', NULL, 1, 5000000.00, '2025-09-17 01:23:15', '2025-09-22 21:39:01'),
(3, 2, NULL, 'Commercial Plaza', '789 Business Rd', 'Sylhet', 'Sylhet', '3100', 'commercial', 'rent', 'inactive', 'pending', 'Prime commercial space for retail.', NULL, NULL, NULL, NULL, 100000.00, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(4, 3, NULL, 'Maya Bari', '1375/4, South Dania, Goal Barir Mor, Shahi Masjid Road, Dhaka-1236', 'Dhaka', 'Dhaka Division', '1236', 'house', 'rent', 'active', 'approved', '2 Storied building with a broad roof open for the tenants also.', '/storage/properties/JuukP4m7RgXIOaaoaGB8zCe9Go3Tpr3e7bKwJ5dv.jpg', NULL, '2025-09-26 21:07:10', 1, 0.00, '2025-09-23 21:44:05', '2025-09-26 21:07:10'),
(5, 2, NULL, 'Sunny Apartments', '123 Main St', 'Dhaka', 'Dhaka', '1200', 'apartment', 'rent', 'inactive', 'pending', 'A beautiful apartment complex with modern amenities.', NULL, NULL, NULL, NULL, 50000.00, '2025-09-27 00:21:22', '2025-09-27 00:21:22'),
(6, 2, NULL, 'Green Villa', '456 Oak Ave', 'Chittagong', 'Chittagong', '4000', 'house', 'sale', 'inactive', 'pending', 'Spacious villa with garden.', NULL, NULL, NULL, NULL, 5000000.00, '2025-09-27 00:21:22', '2025-09-27 00:21:22'),
(7, 2, NULL, 'Commercial Plaza', '789 Business Rd', 'Sylhet', 'Sylhet', '3100', 'commercial', 'rent', 'inactive', 'pending', 'Prime commercial space for retail.', NULL, NULL, NULL, NULL, 100000.00, '2025-09-27 00:21:22', '2025-09-27 00:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `property_bills`
--

CREATE TABLE `property_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `bill_type` enum('electricity','water','gas','internet','other') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','paid','overdue') NOT NULL DEFAULT 'pending',
  `receipt_path` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_documents`
--

CREATE TABLE `property_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `doc_type` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_facilities`
--

CREATE TABLE `property_facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'amenity',
  `description` text DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_images`
--

INSERT INTO `property_images` (`id`, `property_id`, `file_path`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 1, '/storage/property_gallery/zg82UOwDwpmoCq6WjplomJxokjiYoEhDqWFfMR5p.jpg', 1, '2025-09-21 21:32:35', '2025-09-21 21:32:35'),
(2, 1, '/storage/property_gallery/H5RVE88YyyaHHd8QCASm99XdbdvinfomeXqGjoPv.jpg', 0, '2025-09-21 21:32:35', '2025-09-21 21:32:35'),
(3, 1, '/storage/property_gallery/z4tBWgnP1kLfWtskR1gancTPhsenxoY0Ts4NdcvW.jpg', 0, '2025-09-21 21:32:35', '2025-09-21 21:32:35'),
(4, 4, '/storage/property_gallery/6w9GgIy7tsuoPBKYEf9Yl74fVK5rSQ2h71umuBdu.jpg', 1, '2025-09-23 21:44:35', '2025-09-23 21:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `property_taxes`
--

CREATE TABLE `property_taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `year` year(4) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','paid','overdue') NOT NULL DEFAULT 'pending',
  `receipt_path` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_transfers`
--

CREATE TABLE `property_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `current_owner_id` bigint(20) UNSIGNED NOT NULL,
  `proposed_buyer_id` bigint(20) UNSIGNED NOT NULL,
  `transfer_type` enum('sale','lease_transfer','ownership_transfer') NOT NULL,
  `proposed_price` decimal(15,2) DEFAULT NULL,
  `transfer_date` date NOT NULL,
  `terms_conditions` text NOT NULL,
  `status` enum('pending','accepted','rejected','completed','cancelled') NOT NULL DEFAULT 'pending',
  `initiated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `buyer_response_at` timestamp NULL DEFAULT NULL,
  `buyer_response_notes` text DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `completion_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_transfer_documents`
--

CREATE TABLE `property_transfer_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_transfer_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('income','occupancy','sales') NOT NULL,
  `period` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `type`, `period`, `file_path`, `created_at`, `updated_at`) VALUES
(1, 'income', 'Monthly Income Report', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(2, 'income', 'Monthly Income Report', NULL, '2025-09-27 00:21:23', '2025-09-27 00:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14'),
(2, 'Landlord', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14'),
(3, 'Tenant', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14'),
(4, 'Agent', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14'),
(5, 'Buyer', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14'),
(6, 'Maintenance', 'web', '2025-09-17 01:23:14', '2025-09-17 01:23:14');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `is_screened` tinyint(1) NOT NULL DEFAULT 0,
  `move_in_date` date DEFAULT NULL,
  `move_out_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `user_id`, `emergency_contact`, `is_screened`, `move_in_date`, `move_out_date`, `created_at`, `updated_at`) VALUES
(1, 4, '01700000000', 1, '2025-06-17', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(2, 5, '01700000000', 1, '2025-06-17', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(3, 6, '01700000000', 1, '2025-06-17', NULL, '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(4, 4, '01700000000', 1, '2025-06-27', NULL, '2025-09-27 00:21:22', '2025-09-27 00:21:22'),
(5, 5, '01700000000', 1, '2025-06-27', NULL, '2025-09-27 00:21:22', '2025-09-27 00:21:22'),
(6, 6, '01700000000', 1, '2025-06-27', NULL, '2025-09-27 00:21:22', '2025-09-27 00:21:22'),
(7, 10, NULL, 0, '2025-09-27', NULL, '2025-09-27 00:45:42', '2025-09-27 00:45:42');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_employment`
--

CREATE TABLE `tenant_employment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `employer_name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `monthly_income` decimal(10,2) DEFAULT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_leads`
--

CREATE TABLE `tenant_leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `property_id` bigint(20) UNSIGNED DEFAULT NULL,
  `preferred_move_in_date` date DEFAULT NULL,
  `budget_range` varchar(255) DEFAULT NULL,
  `group_size` int(11) NOT NULL DEFAULT 1,
  `message` text DEFAULT NULL,
  `status` enum('new','contacted','qualified','converted','rejected','closed') NOT NULL DEFAULT 'new',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `source` enum('website','referral','social_media','advertising','agent','other') NOT NULL DEFAULT 'website',
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_references`
--

CREATE TABLE `tenant_references` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `reference_name` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `contact_info` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_screenings`
--

CREATE TABLE `tenant_screenings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `report_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `commission_amount` decimal(12,2) DEFAULT NULL,
  `payment_status` enum('pending','paid','partial') NOT NULL DEFAULT 'pending',
  `agreement_path` varchar(255) DEFAULT NULL,
  `payment_milestones` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_milestones`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `property_id`, `buyer_id`, `agent_id`, `amount`, `commission_amount`, `payment_status`, `agreement_path`, `payment_milestones`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 50000.00, NULL, 'paid', NULL, '\"{\\\"initial\\\":500000,\\\"final\\\":500000}\"', '2025-09-17 01:23:15', '2025-09-17 01:23:15'),
(2, 1, 1, 1, 50000.00, NULL, 'paid', NULL, '\"{\\\"initial\\\":500000,\\\"final\\\":500000}\"', '2025-09-27 00:21:22', '2025-09-27 00:21:22'),
(3, 2, 2, 2, 5000000.00, NULL, 'paid', NULL, '\"{\\\"initial\\\":500000,\\\"final\\\":500000}\"', '2025-09-27 00:21:22', '2025-09-27 00:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `unit_number` varchar(255) NOT NULL,
  `floor` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `room_type` varchar(255) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `rent_amount` decimal(10,2) DEFAULT NULL,
  `deposit_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('vacant','occupied') NOT NULL DEFAULT 'vacant',
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `description` text DEFAULT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `property_id`, `unit_number`, `floor`, `size`, `room_type`, `bedrooms`, `bathrooms`, `rent_amount`, `deposit_amount`, `status`, `is_published`, `features`, `description`, `photos`, `created_at`, `updated_at`) VALUES
(1, 4, '202', 'Ground Floor', '1600 sq ft', NULL, 3, 2, 19700.00, NULL, 'vacant', 0, '[\"Parking\",\"Water-supply\",\"Maintenace\"]', NULL, NULL, '2025-09-26 21:08:32', '2025-09-26 21:08:32'),
(2, 1, '203', '3rd Floor', '1200 sq ft', NULL, 2, 1, 12000.00, NULL, 'vacant', 0, '[]', NULL, NULL, '2025-09-26 21:11:39', '2025-09-26 21:11:39');

-- --------------------------------------------------------

--
-- Table structure for table `unit_inquiries`
--

CREATE TABLE `unit_inquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','responded','approved','leased','closed') NOT NULL DEFAULT 'pending',
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `inquirer_name` varchar(255) NOT NULL,
  `inquirer_email` varchar(255) NOT NULL,
  `inquirer_phone` varchar(255) DEFAULT NULL,
  `inquiry_type` enum('general_inquiry','booking_request','viewing_request') NOT NULL,
  `message` text DEFAULT NULL,
  `preferred_viewing_date` date DEFAULT NULL,
  `preferred_viewing_time` time DEFAULT NULL,
  `response` text DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unit_inquiries`
--

INSERT INTO `unit_inquiries` (`id`, `status`, `unit_id`, `inquirer_name`, `inquirer_email`, `inquirer_phone`, `inquiry_type`, `message`, `preferred_viewing_date`, `preferred_viewing_time`, `response`, `responded_at`, `created_at`, `updated_at`) VALUES
(11, 'pending', 2, 'Jane Ava', 'tenant@example.com', '01722222222', 'booking_request', 'I need to move in with my family. So I am looking for a rental space that will be near my workplace and family-friendly.', '2025-10-01', NULL, NULL, NULL, '2025-09-26 22:10:57', '2025-09-26 23:53:09'),
(14, 'pending', 2, 'Sheehab Raihan', 'sheehan9010@gmail.com', '09283975894', 'booking_request', 'I need a rental space for my family', '2025-10-01', NULL, NULL, NULL, '2025-09-26 22:46:37', '2025-09-27 01:03:13'),
(15, 'pending', 2, 'Jane Ava', 'tenant@example.com', '01722222222', 'booking_request', 'I need to move in with my family. So I am looking for a rental space that will be near my workplace and family-friendly.', '2025-10-01', NULL, NULL, NULL, '2025-09-27 00:35:47', '2025-09-27 01:01:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `profile_photo` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `documents_verified` tinyint(1) NOT NULL DEFAULT 0,
  `screening_verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `phone`, `password`, `status`, `profile_photo`, `remember_token`, `created_at`, `updated_at`, `bio`, `phone_verified`, `documents_verified`, `screening_verified`) VALUES
(1, 'Admin User', 'admin@example.com', NULL, '01700000000', '$2y$10$LiPduU8ExMY0o7O6yzeD2emg1uAS8EKF2S199qMJj0xPRUFAy7ijS', 'active', NULL, NULL, '2025-09-17 01:23:14', '2025-09-27 00:21:22', NULL, 0, 0, 0),
(2, 'John Landlord', 'landlord@example.com', NULL, '01711111111', '$2y$10$FuQ9I37GoV6fWoggjTMtueLc4t9JzOe6Nf6zdahskP3UiVBvQTVkW', 'active', NULL, NULL, '2025-09-17 01:23:14', '2025-09-27 00:21:22', NULL, 0, 0, 0),
(3, 'Larry Landlord', 'landlord2@example.com', NULL, '01711114311', '$2y$10$9oJ/UJ9bLWRuqBWjpP0AdeEnI.bpn6QmadW019D6npxc/bqu.cw8W', 'active', NULL, NULL, '2025-09-17 01:23:14', '2025-09-27 00:21:22', NULL, 0, 0, 0),
(4, 'Jane Tenant', 'tenant@example.com', NULL, '01722222222', '$2y$10$CrQfqxkV2AlD7OC2HaCTVedFpgedfn9wkGLsfkMizHKrfHd7ak4FC', 'active', NULL, NULL, '2025-09-17 01:23:15', '2025-09-27 00:21:22', NULL, 0, 0, 0),
(5, 'Austin Tenant', 'tenant2@example.com', NULL, '01722222223', '$2y$10$NPHbT0zF0IOyCyrNolL2ue65RHD5yencg8/vF9HYU1/3H9tJFQwwW', 'active', NULL, NULL, '2025-09-17 01:23:15', '2025-09-27 00:21:22', NULL, 0, 0, 0),
(6, 'Margarette Tenant', 'tenant3@example.com', NULL, '01722222224', '$2y$10$5AQyetLQXucD96R8iS9UBeV9G5oiUxwczMSAexs2PH31wzfIG6xKK', 'active', NULL, NULL, '2025-09-17 01:23:15', '2025-09-27 00:21:22', NULL, 0, 0, 0),
(7, 'Alex Agent', 'agent@example.com', NULL, '01733333333', '$2y$10$V2soD10MqG3ODz9dkZreQePht4r5IYC7Kxdh4qFM9r0sabuTKMuYS', 'active', NULL, NULL, '2025-09-17 01:23:15', '2025-09-27 00:21:22', NULL, 0, 0, 0),
(8, 'Brian Buyer', 'buyer@example.com', NULL, '01744444444', '$2y$10$iXxqIfrRdExALyo43NyUU.p.HxvNzGonq0u1KyFuxeIpQ/.idpuV.', 'active', NULL, NULL, '2025-09-17 01:23:15', '2025-09-27 00:21:22', NULL, 0, 0, 0),
(9, 'Mark Maintenance', 'maintenance@example.com', NULL, '01755555555', '$2y$10$FHmfWKBXzAy5DANavRc7zuCtNGj1oBsPWnrc4u9s0Z8/9aTSS8VqO', 'active', NULL, NULL, '2025-09-17 01:23:15', '2025-09-27 00:21:22', NULL, 0, 0, 0),
(10, 'Sheehab Raihan', 'sheehan9010@gmail.com', NULL, '', '$2y$10$fvLA1wwZxvhmM1FWpr5KvOu3bZNPL6B6NBaly8/J8rrSEmSdpLdwu', 'active', NULL, NULL, '2025-09-27 00:45:42', '2025-09-27 00:45:42', NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `work_orders`
--

CREATE TABLE `work_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agents_user_id_foreign` (`user_id`);

--
-- Indexes for table `buyers`
--
ALTER TABLE `buyers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyers_user_id_foreign` (`user_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_property_id_foreign` (`property_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_lease_id_foreign` (`lease_id`),
  ADD KEY `invoices_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_buyer_id_foreign` (`buyer_id`),
  ADD KEY `leads_property_id_foreign` (`property_id`);

--
-- Indexes for table `leases`
--
ALTER TABLE `leases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leases_tenant_id_foreign` (`tenant_id`),
  ADD KEY `leases_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_requests_tenant_id_foreign` (`tenant_id`),
  ADD KEY `maintenance_requests_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_lease_id_foreign` (`lease_id`),
  ADD KEY `payments_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plots`
--
ALTER TABLE `plots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plots_property_id_foreign` (`property_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `properties_owner_id_foreign` (`owner_id`),
  ADD KEY `properties_approved_by_foreign` (`approved_by`),
  ADD KEY `properties_agent_id_foreign` (`agent_id`);

--
-- Indexes for table `property_bills`
--
ALTER TABLE `property_bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_bills_property_id_foreign` (`property_id`);

--
-- Indexes for table `property_documents`
--
ALTER TABLE `property_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_documents_property_id_foreign` (`property_id`);

--
-- Indexes for table `property_facilities`
--
ALTER TABLE `property_facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_facilities_property_id_category_index` (`property_id`,`category`),
  ADD KEY `property_facilities_is_available_index` (`is_available`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_images_property_id_foreign` (`property_id`);

--
-- Indexes for table `property_taxes`
--
ALTER TABLE `property_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_taxes_property_id_foreign` (`property_id`);

--
-- Indexes for table `property_transfers`
--
ALTER TABLE `property_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_transfers_property_id_status_index` (`property_id`,`status`),
  ADD KEY `property_transfers_current_owner_id_status_index` (`current_owner_id`,`status`),
  ADD KEY `property_transfers_proposed_buyer_id_status_index` (`proposed_buyer_id`,`status`);

--
-- Indexes for table `property_transfer_documents`
--
ALTER TABLE `property_transfer_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_transfer_documents_property_transfer_id_index` (`property_transfer_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenants_user_id_foreign` (`user_id`);

--
-- Indexes for table `tenant_employment`
--
ALTER TABLE `tenant_employment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_employment_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `tenant_leads`
--
ALTER TABLE `tenant_leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_leads_unit_id_foreign` (`unit_id`),
  ADD KEY `tenant_leads_property_id_foreign` (`property_id`),
  ADD KEY `tenant_leads_status_index` (`status`),
  ADD KEY `tenant_leads_priority_index` (`priority`),
  ADD KEY `tenant_leads_source_index` (`source`),
  ADD KEY `tenant_leads_created_at_index` (`created_at`),
  ADD KEY `tenant_leads_assigned_to_index` (`assigned_to`);

--
-- Indexes for table `tenant_references`
--
ALTER TABLE `tenant_references`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_references_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `tenant_screenings`
--
ALTER TABLE `tenant_screenings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_screenings_tenant_id_foreign` (`tenant_id`),
  ADD KEY `tenant_screenings_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_property_id_foreign` (`property_id`),
  ADD KEY `transactions_buyer_id_foreign` (`buyer_id`),
  ADD KEY `transactions_agent_id_foreign` (`agent_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `units_property_id_foreign` (`property_id`);

--
-- Indexes for table `unit_inquiries`
--
ALTER TABLE `unit_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_inquiries_unit_id_foreign` (`unit_id`),
  ADD KEY `unit_inquiries_status_created_at_index` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- Indexes for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_orders_request_id_foreign` (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `buyers`
--
ALTER TABLE `buyers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leases`
--
ALTER TABLE `leases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plots`
--
ALTER TABLE `plots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `property_bills`
--
ALTER TABLE `property_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_documents`
--
ALTER TABLE `property_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_facilities`
--
ALTER TABLE `property_facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `property_taxes`
--
ALTER TABLE `property_taxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_transfers`
--
ALTER TABLE `property_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_transfer_documents`
--
ALTER TABLE `property_transfer_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tenant_employment`
--
ALTER TABLE `tenant_employment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_leads`
--
ALTER TABLE `tenant_leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_references`
--
ALTER TABLE `tenant_references`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_screenings`
--
ALTER TABLE `tenant_screenings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `unit_inquiries`
--
ALTER TABLE `unit_inquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `work_orders`
--
ALTER TABLE `work_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agents`
--
ALTER TABLE `agents`
  ADD CONSTRAINT `agents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `buyers`
--
ALTER TABLE `buyers`
  ADD CONSTRAINT `buyers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `leases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `buyers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leads_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leases`
--
ALTER TABLE `leases`
  ADD CONSTRAINT `leases_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leases_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD CONSTRAINT `maintenance_requests_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_requests_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `leases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plots`
--
ALTER TABLE `plots`
  ADD CONSTRAINT `plots_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `properties_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `properties_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_bills`
--
ALTER TABLE `property_bills`
  ADD CONSTRAINT `property_bills_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_documents`
--
ALTER TABLE `property_documents`
  ADD CONSTRAINT `property_documents_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_facilities`
--
ALTER TABLE `property_facilities`
  ADD CONSTRAINT `property_facilities_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_images`
--
ALTER TABLE `property_images`
  ADD CONSTRAINT `property_images_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_taxes`
--
ALTER TABLE `property_taxes`
  ADD CONSTRAINT `property_taxes_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_transfers`
--
ALTER TABLE `property_transfers`
  ADD CONSTRAINT `property_transfers_current_owner_id_foreign` FOREIGN KEY (`current_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_transfers_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_transfers_proposed_buyer_id_foreign` FOREIGN KEY (`proposed_buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_transfer_documents`
--
ALTER TABLE `property_transfer_documents`
  ADD CONSTRAINT `property_transfer_documents_property_transfer_id_foreign` FOREIGN KEY (`property_transfer_id`) REFERENCES `property_transfers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenants`
--
ALTER TABLE `tenants`
  ADD CONSTRAINT `tenants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_employment`
--
ALTER TABLE `tenant_employment`
  ADD CONSTRAINT `tenant_employment_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_leads`
--
ALTER TABLE `tenant_leads`
  ADD CONSTRAINT `tenant_leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tenant_leads_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tenant_leads_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tenant_references`
--
ALTER TABLE `tenant_references`
  ADD CONSTRAINT `tenant_references_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_screenings`
--
ALTER TABLE `tenant_screenings`
  ADD CONSTRAINT `tenant_screenings_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tenant_screenings_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `buyers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `unit_inquiries`
--
ALTER TABLE `unit_inquiries`
  ADD CONSTRAINT `unit_inquiries_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD CONSTRAINT `work_orders_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `maintenance_requests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
