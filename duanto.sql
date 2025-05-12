-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 16, 2025 at 06:42 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `duanto`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'CD', 'DS', '2025-04-07 19:03:05', '2025-04-07 19:03:05'),
(2, 'DS', 'CCD', '2025-04-07 19:03:10', '2025-04-07 19:03:10'),
(3, 'Poka Furniture', NULL, '2025-04-08 19:02:58', '2025-04-08 19:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `variant_id`, `created_at`, `updated_at`) VALUES
(18, 2, 2, 9, 4, '2025-04-08 21:43:29', '2025-04-08 21:43:29');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'AB', 'DSFDSFDSFSDFD', '2025-04-07 19:02:49', '2025-04-07 19:02:49'),
(2, 'AC', NULL, '2025-04-07 19:02:54', '2025-04-07 19:02:54'),
(3, 'Ghế', NULL, '2025-04-08 19:02:39', '2025-04-08 19:02:39'),
(4, 'Ghế', NULL, '2025-04-08 19:02:39', '2025-04-08 19:02:39');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'đen', '2025-04-07 19:01:42', '2025-04-07 19:01:42'),
(2, 'trắng', '2025-04-07 19:01:47', '2025-04-07 19:01:47');

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
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `star` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `product_id`, `order_id`, `star`, `content`, `created_at`, `updated_at`) VALUES
(3, 2, 2, NULL, 4, 's', '2025-04-08 20:57:20', '2025-04-08 20:57:20'),
(4, 2, 3, NULL, 5, 'sád', '2025-04-08 21:06:19', '2025-04-08 21:06:19'),
(5, 2, 2, 3, 5, 'ok', '2025-04-08 21:19:34', '2025-04-08 21:19:34'),
(7, 1, 2, 4, 5, 'sản phẩm ok', '2025-04-09 07:29:44', '2025-04-09 07:29:44');

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
(55, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(56, '2014_10_12_100000_create_password_resets_table', 1),
(57, '2019_08_19_000000_create_failed_jobs_table', 1),
(58, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(59, '2025_02_21_234011_create_shippings_table', 1),
(60, '2025_02_22_145643_create_categories_table', 1),
(61, '2025_02_22_152432_create_users_table', 1),
(62, '2025_02_22_153645_create_brands_table', 1),
(63, '2025_02_22_153653_create_colors_table', 1),
(64, '2025_02_22_153658_create_sizes_table', 1),
(65, '2025_02_22_153702_create_products_table', 1),
(66, '2025_02_22_153707_create_product_variants_table', 1),
(67, '2025_02_22_153713_create_carts_table', 1),
(68, '2025_02_22_153718_create_orders_table', 1),
(69, '2025_02_22_153722_create_order_details_table', 1),
(70, '2025_02_22_153729_create_feedbacks_table', 1),
(71, '2025_03_26_193931_create_transactions_table', 1),
(72, '2025_03_30_212848_create_new_table', 1),
(73, '2025_04_09_154339_create_vouchers_table', 2),
(74, '2025_04_09_170633_add_voucher_fields_to_orders_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` enum('pending','completed','cancelled','shipping') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total` int NOT NULL,
  `voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` int UNSIGNED NOT NULL DEFAULT '0',
  `consignee_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consignee_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consignee_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('cod','vnpay') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_id` bigint UNSIGNED NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subdistrict` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `total`, `voucher_code`, `discount_amount`, `consignee_name`, `consignee_phone`, `transaction_id`, `consignee_address`, `payment_method`, `shipping_fee`, `shipping_id`, `city`, `email`, `subdistrict`, `created_at`, `updated_at`) VALUES
(1, 1, 'cancelled', 1030000, NULL, 0, 'Trung Kiên Trịnh', '0353174409', '17440804881', 'Mỹ Đình Pearl', 'cod', '30000.00', 1, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-07 19:48:08', '2025-04-07 19:54:56'),
(2, 1, 'cancelled', 3050000, NULL, 0, 'Trung Kiên Trịnh', '0353174409', '17440807101', 'HÀ Nội', 'vnpay', '50000.00', 2, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-07 19:52:21', '2025-04-09 07:30:21'),
(3, 2, 'completed', 1330000, NULL, 0, 'kientrinh', '0932802432', '17441714182', '12hjdasas', 'cod', '30000.00', 1, 'Hà Nội', 'kien16@gmail.com', 'Hà Đông', '2025-04-08 21:03:38', '2025-04-08 21:16:19'),
(4, 1, 'completed', 3030000, NULL, 0, 'Trung Kiên Trịnh', '0353174409', '17442086941', 'Mỹ Đình Pearl', 'vnpay', '30000.00', 1, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-09 07:25:43', '2025-04-09 07:29:10'),
(5, 1, 'cancelled', 1330000, NULL, 0, 'Trung Kiên Trịnh', '0353174409', '17442090881', 'Xuân giang', 'cod', '30000.00', 1, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-09 07:31:28', '2025-04-09 07:31:52'),
(6, 1, 'completed', 1350000, NULL, 0, 'Trung Kiên Trịnh', '0353174409', '17442092141', 'addsdsa', 'cod', '50000.00', 2, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-09 07:33:34', '2025-04-09 07:34:11'),
(7, 1, 'pending', 7990000, 'M01', 0, 'Trung Kiên Trịnh', '0353174409', '17442191461', 'Mỹ Đình Pearl', 'cod', '30000.00', 1, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-09 10:19:06', '2025-04-09 10:19:06'),
(8, 1, 'pending', 1330000, 'Mi1', 0, 'Trung Kiên Trịnh', '0353174409', '17442202921', 'HÀ Nội', 'cod', '30000.00', 1, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-09 10:38:12', '2025-04-09 10:38:12'),
(14, 1, 'pending', 1000000, 'Mi1', 500000, 'Trung Kiên Trịnh', '0353174409', '17442217631', 'Xuân giang', 'cod', '50000.00', 2, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-09 11:02:43', '2025-04-09 11:02:43'),
(15, 1, 'pending', 1050000, 'Mi1', 500000, 'Trung Kiên Trịnh', '0353174409', '17442225301', 'Mỹ Đình Pearl', 'vnpay', '50000.00', 2, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-09 11:16:04', '2025-04-09 11:16:04'),
(16, 1, 'pending', 550000, NULL, 0, 'Trung Kiên Trịnh', '0353174409', '17442226331', 'Hà Nội', 'cod', '50000.00', 2, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-09 11:17:13', '2025-04-09 11:17:13'),
(17, 1, 'pending', 530000, NULL, 0, 'Trung Kiên Trịnh', '0353174409', '17442226821', 'Mễ trì', 'vnpay', '30000.00', 1, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-09 11:18:30', '2025-04-09 11:18:30'),
(18, 1, 'pending', 510000, NULL, 0, 'Trung Kiên Trịnh', '0353174409', '17442284171', 'Hà Nội', 'cod', '50000.00', 2, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-09 12:53:37', '2025-04-09 12:53:37'),
(19, 1, 'pending', 482400, 'Mi1', 27600, 'Trung Kiên Trịnh', '0353174409', '17448061271', 'Mễ trì', 'cod', '50000.00', 2, 'Hà Nội', 'kien98798@gmail.com', 'Cầu Giấy', '2025-04-16 05:22:07', '2025-04-16 05:22:07');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `variant_id` int NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `variant_id`, `product_id`, `price`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 500000, 1, '2025-04-07 19:48:08', '2025-04-07 19:48:08'),
(2, 1, 3, 1, 500000, 1, '2025-04-07 19:48:08', '2025-04-07 19:48:08'),
(3, 2, 2, 1, 500000, 5, '2025-04-07 19:52:21', '2025-04-07 19:52:21'),
(4, 2, 1, 1, 500000, 1, '2025-04-07 19:52:21', '2025-04-07 19:52:21'),
(5, 3, 4, 2, 1300000, 1, '2025-04-08 21:03:38', '2025-04-08 21:03:38'),
(6, 4, 4, 2, 1500000, 2, '2025-04-09 07:25:43', '2025-04-09 07:25:43'),
(7, 5, 4, 2, 1300000, 1, '2025-04-09 07:31:28', '2025-04-09 07:31:28'),
(8, 6, 4, 2, 1300000, 1, '2025-04-09 07:33:34', '2025-04-09 07:33:34'),
(9, 7, 4, 2, 1300000, 5, '2025-04-09 10:19:06', '2025-04-09 10:19:06'),
(10, 7, 1, 1, 500000, 1, '2025-04-09 10:19:06', '2025-04-09 10:19:06'),
(11, 7, 5, 4, 460000, 1, '2025-04-09 10:19:06', '2025-04-09 10:19:06'),
(12, 7, 6, 4, 500000, 1, '2025-04-09 10:19:06', '2025-04-09 10:19:06'),
(13, 8, 4, 2, 1300000, 1, '2025-04-09 10:38:12', '2025-04-09 10:38:12'),
(14, 14, 1, 1, 500000, 1, '2025-04-09 11:02:43', '2025-04-09 11:02:43'),
(15, 14, 2, 1, 450000, 1, '2025-04-09 11:02:43', '2025-04-09 11:02:43'),
(16, 14, 6, 4, 500000, 1, '2025-04-09 11:02:43', '2025-04-09 11:02:43'),
(17, 15, 6, 4, 500000, 3, '2025-04-09 11:16:04', '2025-04-09 11:16:04'),
(18, 16, 3, 1, 500000, 1, '2025-04-09 11:17:13', '2025-04-09 11:17:13'),
(19, 17, 1, 1, 500000, 1, '2025-04-09 11:18:30', '2025-04-09 11:18:30'),
(20, 18, 5, 4, 460000, 1, '2025-04-09 12:53:37', '2025-04-09 12:53:37'),
(21, 19, 5, 4, 460000, 1, '2025-04-16 05:22:07', '2025-04-16 05:22:07');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `user_id`, `payment_method`, `amount`, `status`, `transaction_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'cod', '1030000.00', 'failed', '17440804881', '2025-04-07 19:48:08', '2025-04-07 19:54:56'),
(2, 2, 1, 'vnpay', '3050000.00', 'failed', '17440807101', '2025-04-07 19:52:21', '2025-04-09 07:30:21'),
(3, 3, 2, 'cod', '1330000.00', 'success', '17441714182', '2025-04-08 21:03:38', '2025-04-08 21:16:19'),
(4, 4, 1, 'vnpay', '3030000.00', 'success', '17442086941', '2025-04-09 07:25:43', '2025-04-09 07:25:43'),
(5, 5, 1, 'cod', '1330000.00', 'failed', '17442090881', '2025-04-09 07:31:28', '2025-04-09 07:31:52'),
(6, 6, 1, 'cod', '1350000.00', 'success', '17442092141', '2025-04-09 07:33:34', '2025-04-09 07:34:11'),
(7, 7, 1, 'cod', '7990000.00', 'pending', '17442191461', '2025-04-09 10:19:06', '2025-04-09 10:19:06'),
(8, 8, 1, 'cod', '1330000.00', 'pending', '17442202921', '2025-04-09 10:38:12', '2025-04-09 10:38:12'),
(9, 14, 1, 'cod', '1000000.00', 'pending', '17442217631', '2025-04-09 11:02:43', '2025-04-09 11:02:43'),
(10, 15, 1, 'vnpay', '1050000.00', 'success', '17442225301', '2025-04-09 11:16:04', '2025-04-09 11:16:04'),
(11, 16, 1, 'cod', '550000.00', 'pending', '17442226331', '2025-04-09 11:17:13', '2025-04-09 11:17:13'),
(12, 17, 1, 'vnpay', '530000.00', 'success', '17442226821', '2025-04-09 11:18:30', '2025-04-09 11:18:30'),
(13, 18, 1, 'cod', '510000.00', 'pending', '17442284171', '2025-04-09 12:53:37', '2025-04-09 12:53:37'),
(14, 19, 1, 'cod', '482400.00', 'pending', '17448061271', '2025-04-16 05:22:07', '2025-04-16 05:22:07');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `price_sale` decimal(15,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `brand_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `price_sale`, `image`, `product_detail`, `category_id`, `brand_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'product 4', 'sdfdshfdshsfbfshbsfbhfbđhbhfbfsddđ.ksfddddddddddddddddddddddddddddddddđ', '500000.00', '400000.00', 'products/OdnRPx9gzhFgbKZHcq6yVRmafH6MtZyGUZLDwpei.jpg', NULL, 1, 1, '2025-04-07 19:13:45', '2025-04-08 19:18:35', NULL),
(2, 'Ghế Cà phê Decor G-PK19', 'Ghế Cà phê Decor G-PK19\r\nThông số kỹ thuật ghế G-PK19\r\n\r\nKích thước: 45x45x82cm\r\nMàu sắc: đa dạng, đặt theo yêu cầu phối 3d\r\nChất liệu: Tựa ghế gỗ ash sơn đen mờ, mặt tựa mây lưới, đệm bọc da (vải), khung thép sơn tĩnh điện đen\r\nỨng dụng: bàn ghế cafe, ghế trà sữa', '1500000.00', '1200000.00', 'products/1mCKylixjEJeDbtQIAHMJnCgYCG106eqDKpDT340.jpg', NULL, 3, 3, '2025-04-08 19:05:33', '2025-04-08 19:05:33', NULL),
(3, 'TrinhTrungKien04', 'ầdfdsfsdf', '2900000.00', '2500000.00', 'products/AwJb2vAOiafCM5PWYIFmB3roDbyG8BRmTNhdN1sr.jpg', NULL, 3, 2, '2025-04-08 19:23:59', '2025-04-08 19:23:59', NULL),
(4, 'Máy tính', 'aerewe', '500000.00', '450000.00', 'products/HIBUpmc29aD87sj8Df7TIwJG156xcPOeitKsuk77.jpg', NULL, 3, 3, '2025-04-09 07:26:44', '2025-04-09 07:26:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `color_id` bigint UNSIGNED DEFAULT NULL,
  `size_id` bigint UNSIGNED DEFAULT NULL,
  `variation_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `stock` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `color_id`, `size_id`, `variation_name`, `sku`, `price`, `image`, `stock`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 1, 'STO1', 'ND1', '500000.00', 'product_variants/r7JojWoEL0TyB9bBf6NGsBICvO8oiPsDWNeaaDb9.png', 2, '2025-04-07 19:14:55', '2025-04-09 11:18:30', NULL),
(2, 1, 2, 2, 'STO2', 'ND2', '450000.00', 'product_variants/G5DxcmMBu5h1TQtv8NPU8Dw2VjyNd9zFGAUKNdme.png', 9, '2025-04-07 19:16:12', '2025-04-09 11:02:43', NULL),
(3, 1, 1, 3, 'ád', 'dsadsa', '500000.00', 'product_variants/TPLOslCIyZWpMbtb2qw53ITBW3hytVTlTGk2Afo4.png', 0, '2025-04-07 19:17:46', '2025-04-09 11:17:13', NULL),
(4, 2, 2, 4, 'Ghế CF1', 'CF1', '1300000.00', 'product_variants/JlmbPjuPnIoxshBc0xjlPLG5vmiy86BKhywcSChm.jpg', 0, '2025-04-08 19:07:35', '2025-04-09 10:38:12', NULL),
(5, 4, 1, 4, 'Dg1', 'D1', '460000.00', 'product_variants/s1ANmK468LejfivxmmP8PC4BGt9HmpscL1OLIspk.jpg', 7, '2025-04-09 07:27:34', '2025-04-16 05:22:07', NULL),
(6, 4, 2, 5, 'Gh1', 'G1', '500000.00', 'product_variants/Nkd3r74tndGTUyxp9ZutUxYzVwJ7C1mYLAJptI5w.jpg', 5, '2025-04-09 07:28:07', '2025-04-09 11:16:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shippings`
--

CREATE TABLE `shippings` (
  `id` bigint UNSIGNED NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shippings`
--

INSERT INTO `shippings` (`id`, `method`, `fee`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Vận chuyển tiêu chuẩn', 30000, 'Giao hàng trong 3-5 ngày làm việc.', NULL, NULL),
(2, 'Vận chuyển nhanh', 50000, 'Giao hàng trong 1-2 ngày làm việc.', NULL, NULL),
(3, 'Nhận tại cửa hàng', 0, 'Đến cửa hàng để nhận hàng.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'TO', '2025-04-07 19:02:21', '2025-04-07 19:02:21'),
(2, 'VỪA', '2025-04-07 19:02:31', '2025-04-07 19:02:31'),
(3, 'NHỎ', '2025-04-07 19:02:35', '2025-04-07 19:02:35'),
(4, '45x15x82', '2025-04-08 19:06:30', '2025-04-08 19:06:30'),
(5, '46x50x85', '2025-04-08 19:06:45', '2025-04-08 19:06:45');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'vnpay',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `response_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `order_id`, `transaction_id`, `amount`, `payment_method`, `status`, `response_code`, `response_message`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '17440807101', '3000000.00', 'vnpay', 'success', '00', 'Thanh toán thành công', '2025-04-07 19:52:21', '2025-04-07 19:52:21'),
(2, 1, 4, '17442086941', '3000000.00', 'vnpay', 'success', '00', 'Thanh toán thành công', '2025-04-09 07:25:43', '2025-04-09 07:25:43'),
(3, 1, 15, '17442225301', '1500000.00', 'vnpay', 'success', '00', 'Thanh toán thành công', '2025-04-09 11:16:04', '2025-04-09 11:16:04'),
(4, 1, 17, '17442226821', '500000.00', 'vnpay', 'success', '00', 'Thanh toán thành công', '2025-04-09 11:18:30', '2025-04-09 11:18:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `role`, `avatar`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'kien98798@gmail.com', '$2y$12$9ixk3ZwAKFTm0IKai.kibOFfhZe/b2EK2V2DIEUDRlIFa0f9tB/Zu', 'Trung Kiên Trịnh', 'admin', NULL, NULL, '2025-04-07 19:00:42', '2025-04-07 19:00:42'),
(2, 'kien16@gmail.com', '$2y$12$v/5BZ2DhI0Yok6dobDnRaOKArxPmkndqJU0155D1/dzz7L.aQTPB2', 'kientrinh', 'user', NULL, NULL, '2025-04-08 19:09:22', '2025-04-08 19:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used` int NOT NULL DEFAULT '0',
  `starts_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `type`, `value`, `min_order_amount`, `usage_limit`, `used`, `starts_at`, `expires_at`, `is_active`, `created_at`, `updated_at`) VALUES
(10, 'Mi1', 'percent', '6.00', '50000.00', 4, 2, '2025-04-09 02:24:00', '2025-05-01 02:24:00', 1, '2025-04-09 12:24:08', '2025-04-16 05:22:07'),
(11, 'M01', 'fixed', '3000.00', '500000.00', 3, 0, '2025-04-09 02:24:00', '2025-04-23 02:24:00', 1, '2025-04-09 12:24:36', '2025-04-09 12:24:36'),
(12, 'gf', 'fixed', '566666.00', '9999999.00', 8, 0, '2025-04-08 02:24:00', '2025-04-26 02:24:00', 0, '2025-04-09 12:25:01', '2025-04-09 12:25:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedbacks_user_id_foreign` (`user_id`),
  ADD KEY `feedbacks_product_id_foreign` (`product_id`),
  ADD KEY `feedbacks_order_id_foreign` (`order_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_transaction_id_unique` (`transaction_id`),
  ADD KEY `orders_shipping_id_foreign` (`shipping_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`),
  ADD KEY `product_variants_color_id_foreign` (`color_id`),
  ADD KEY `product_variants_size_id_foreign` (`size_id`);

--
-- Indexes for table `shippings`
--
ALTER TABLE `shippings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_transaction_id_unique` (`transaction_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_order_id_foreign` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `shippings`
--
ALTER TABLE `shippings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_shipping_id_foreign` FOREIGN KEY (`shipping_id`) REFERENCES `shippings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variants_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
