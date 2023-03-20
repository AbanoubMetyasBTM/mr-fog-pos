-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 20, 2023 at 10:08 AM
-- Server version: 5.7.41-0ubuntu0.18.04.1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `canda_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branch_id` int(11) NOT NULL,
  `cash_wallet_id` int(11) NOT NULL,
  `debit_card_wallet_id` int(11) NOT NULL,
  `credit_card_wallet_id` int(11) NOT NULL,
  `cheque_wallet_id` int(11) NOT NULL,
  `tax_group_id` int(11) DEFAULT NULL,
  `branch_api_access_token` varchar(300) NOT NULL,
  `branch_name` varchar(300) NOT NULL,
  `branch_country` varchar(300) NOT NULL,
  `branch_img_obj` varchar(300) DEFAULT NULL,
  `branch_currency` varchar(300) NOT NULL COMMENT 'USD, CAD',
  `branch_timezone` varchar(100) NOT NULL,
  `branch_taxes` text NOT NULL,
  `first_day_of_the_week` varchar(20) NOT NULL COMMENT 'اول يوم في اسبوع العمل',
  `return_policy_days` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `cash_wallet_id`, `debit_card_wallet_id`, `credit_card_wallet_id`, `cheque_wallet_id`, `tax_group_id`, `branch_api_access_token`, `branch_name`, `branch_country`, `branch_img_obj`, `branch_currency`, `branch_timezone`, `branch_taxes`, `first_day_of_the_week`, `return_policy_days`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 14, 15, 16, 17, 1, '14231112132', 'canda branch', 'CA', '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', 'USD', 'Africa/Cairo', '[{\"id\":\"1\",\"branch_tax_label\":\"ضريبة قيمة مضافة\",\"branch_tax_percent\":\"14\"},{\"id\":\"2\",\"branch_tax_label\":\"ضريبة بواخة\",\"branch_tax_percent\":\"10\"}]', 'saturday', 15, '2022-11-29 10:01:35', '2023-02-12 10:26:43', NULL),
(2, 21, 22, 23, 24, 1, '', 'second branch', 'CL', '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', 'USD', 'Europe/Andorra', '[{\"id\":\"1\",\"branch_tax_label\":\"Eiusmod quae sint ra\",\"branch_tax_percent\":\"70\"}]', 'saturday', 0, '2022-12-07 00:04:41', '2023-02-21 15:44:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branch_inventory`
--

CREATE TABLE `branch_inventory` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `is_main_inventory` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branch_inventory`
--

INSERT INTO `branch_inventory` (`id`, `branch_id`, `inventory_id`, `is_main_inventory`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `branch_prices`
--

CREATE TABLE `branch_prices` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `sku_id` int(11) NOT NULL,
  `online_item_price` decimal(10,2) NOT NULL,
  `online_box_price` decimal(10,2) NOT NULL,
  `item_retailer_price` decimal(10,2) NOT NULL,
  `item_wholesaler_price` decimal(10,2) NOT NULL,
  `box_retailer_price` decimal(10,2) NOT NULL,
  `box_wholesaler_price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branch_prices`
--

INSERT INTO `branch_prices` (`id`, `branch_id`, `pro_id`, `sku_id`, `online_item_price`, `online_box_price`, `item_retailer_price`, `item_wholesaler_price`, `box_retailer_price`, `box_wholesaler_price`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, 1, '100.00', '1200.00', '100.00', '90.00', '1200.00', '1080.00', 1, '2022-11-29 10:06:17', '2022-11-29 10:06:17', NULL),
(2, 1, 2, 2, '200.00', '2400.00', '200.00', '180.00', '2400.00', '2160.00', 1, '2022-11-29 10:06:17', '2022-11-29 10:06:17', NULL),
(3, 2, 2, 1, '170.00', '2040.00', '170.00', '153.00', '2040.00', '1836.00', 1, '2022-12-29 04:10:29', '2022-12-29 04:10:29', NULL),
(4, 2, 2, 2, '340.00', '4080.00', '340.00', '306.00', '4080.00', '3672.00', 1, '2022-12-29 04:10:29', '2022-12-29 04:10:29', NULL),
(5, 1, 2, 3, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-02-27 13:54:20', '2023-02-27 13:54:20', NULL),
(6, 1, 2, 4, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-02-27 13:54:20', '2023-02-27 13:54:20', NULL),
(7, 1, 2, 5, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-02-27 13:54:20', '2023-02-27 13:54:20', NULL),
(8, 1, 2, 6, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-02-27 13:54:20', '2023-02-27 13:54:20', NULL),
(9, 1, 3, 7, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-02-27 17:25:41', '2023-03-01 22:15:33', NULL),
(10, 1, 3, 8, '10.00', '120.00', '10.00', '9.00', '120.00', '111.00', 1, '2023-02-27 17:25:41', '2023-02-27 17:43:43', NULL),
(11, 1, 3, 9, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-02-27 17:25:41', '2023-02-27 17:25:41', NULL),
(12, 1, 3, 10, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-03-01 22:14:59', '2023-03-01 22:14:59', NULL),
(13, 1, 3, 11, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-03-01 22:14:59', '2023-03-01 22:14:59', NULL),
(14, 1, 3, 12, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-03-01 22:14:59', '2023-03-01 22:14:59', NULL),
(15, 1, 3, 13, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-03-01 22:14:59', '2023-03-01 22:14:59', NULL),
(16, 1, 3, 14, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-03-01 22:14:59', '2023-03-01 22:14:59', NULL),
(17, 1, 3, 15, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, '2023-03-01 22:14:59', '2023-03-01 22:14:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_img_obj` varchar(300) NOT NULL,
  `brand_name` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_img_obj`, `brand_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/1d4d55bf3ed1653c8986e831f5635ad5.jpg\"}', '{\"en\":\"MrFog\"}', '2022-09-18 09:56:15', '2022-10-03 21:19:51', NULL),
(2, '{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/ff3a2e890983708742f549ed83bc4ed7.jpg\"}', '{\"en\":\"anoher brand\"}', '2022-10-13 01:26:04', '2022-10-13 01:26:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `cat_name` text NOT NULL,
  `cat_img_obj` varchar(300) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `parent_id`, `cat_name`, `cat_img_obj`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, '{\"en\":\"Cloths\"}', '', NULL, '2022-09-14 16:11:49', NULL),
(2, 1, '{\"en\":\"sub at cloths\"}', '', '2022-09-14 16:14:57', '2022-09-14 16:14:57', NULL),
(3, 1, '{\"en\":\"another sub\"}', '', '2023-02-28 17:39:28', '2023-02-28 17:39:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `points_wallet_id` int(11) NOT NULL,
  `tax_group_id` int(11) DEFAULT NULL,
  `client_type` varchar(50) NOT NULL COMMENT 'retailer, wholesaler',
  `client_name` text NOT NULL,
  `client_email` varchar(300) NOT NULL,
  `client_phone` varchar(300) NOT NULL,
  `client_total_orders_count` int(11) NOT NULL,
  `client_total_orders_amount` decimal(10,2) NOT NULL,
  `client_total_loyal_points` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `user_id`, `branch_id`, `wallet_id`, `points_wallet_id`, `tax_group_id`, `client_type`, `client_name`, `client_email`, `client_phone`, `client_total_orders_count`, `client_total_orders_amount`, `client_total_loyal_points`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 0, 1, 0, NULL, 'retailer', 'abanoub-retailer', 'a@a.com', '1222', 0, '0.00', 0, '2022-09-18 09:47:41', '2022-11-29 09:54:21', '2022-11-29 09:54:21'),
(2, NULL, 0, 2, 0, NULL, 'wholesaler', 'abanoub as wholesaler', 'a@a.com', '123', 0, '0.00', 0, '2022-09-18 09:48:23', '2022-11-29 09:54:24', '2022-11-29 09:54:24'),
(3, NULL, 0, 6, 0, NULL, 'retailer', 'Scarlett Mcbride', 'nawot@mailinator.com', '+1 (781) 727-8398', 0, '0.00', 0, '2022-10-11 19:41:44', '2022-11-29 09:54:28', '2022-11-29 09:54:28'),
(4, NULL, 0, 13, 0, NULL, 'retailer', 'general client', 'c@c.com', '1', 0, '0.00', 0, '2022-11-29 09:54:45', '2022-11-29 09:54:45', NULL),
(5, NULL, 0, 20, 0, NULL, 'wholesaler', 'abanoub as wholesaler', 'abanoub.metyas.btm@gmail.com', '01200941060', 0, '0.00', 0, '2022-11-29 10:34:53', '2022-11-29 10:34:53', NULL),
(6, NULL, 1, 26, 29, 2, 'retailer', 'retailer client', 'a@a.com', '012', 13, '2470.64', 0, '2023-01-05 11:11:33', '2023-02-28 23:36:10', NULL),
(7, NULL, 1, 27, 28, NULL, 'wholesaler', 'wholesaler client', 'a@a.com', '013', 8, '2162.40', 0, '2023-02-12 09:50:17', '2023-03-03 00:12:19', NULL),
(8, NULL, 2, 30, 31, 1, 'wholesaler', 'April Tyson', 'vogihod@mailinator.com', '012', 0, '0.00', 0, '2023-02-21 15:10:41', '2023-02-21 15:10:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_addresses`
--

CREATE TABLE `client_addresses` (
  `add_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `add_email` varchar(300) NOT NULL,
  `add_country` varchar(300) NOT NULL,
  `add_city` varchar(300) NOT NULL,
  `add_street` varchar(300) NOT NULL,
  `add_type` varchar(300) NOT NULL COMMENT 'home or work',
  `add_tel_country_code` varchar(300) NOT NULL,
  `add_tel_number` varchar(300) NOT NULL,
  `add_post_code` varchar(300) NOT NULL,
  `add_notes` varchar(300) NOT NULL,
  `add_lat` varchar(300) NOT NULL,
  `add_lng` varchar(300) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `client_orders`
--

CREATE TABLE `client_orders` (
  `client_order_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `client_user_id` int(11) DEFAULT NULL COMMENT 'store in case order online',
  `employee_id` int(11) DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL,
  `register_session_id` int(11) DEFAULT NULL,
  `total_items_cost` decimal(10,2) NOT NULL,
  `wallet_paid_amount` decimal(10,2) DEFAULT NULL,
  `gift_card_id` int(11) DEFAULT NULL,
  `gift_card_paid_amount` decimal(10,2) DEFAULT NULL,
  `cash_paid_amount` decimal(10,2) DEFAULT NULL,
  `debit_card_paid_amount` decimal(10,2) DEFAULT NULL,
  `debit_card_receipt_img_obj` varchar(300) DEFAULT NULL,
  `credit_card_paid_amount` decimal(10,2) DEFAULT NULL,
  `credit_card_receipt_img_obj` varchar(300) DEFAULT NULL,
  `cheque_paid_amount` decimal(10,2) DEFAULT NULL,
  `cheque_card_receipt_img_obj` varchar(300) DEFAULT NULL,
  `used_coupon_id` int(11) DEFAULT NULL,
  `used_coupon_value` decimal(10,2) NOT NULL,
  `used_points_redeem_points` int(11) NOT NULL,
  `used_points_redeem_money` decimal(10,2) NOT NULL,
  `can_not_return_items` tinyint(1) NOT NULL,
  `wallet_return_amount` decimal(10,2) DEFAULT NULL,
  `gift_card_return_amount` decimal(10,2) DEFAULT NULL,
  `cash_return_amount` decimal(10,2) DEFAULT NULL,
  `debit_card_return_amount` decimal(10,2) DEFAULT NULL,
  `credit_card_return_amount` decimal(10,2) DEFAULT NULL,
  `cheque_return_amount` decimal(10,2) DEFAULT NULL,
  `total_return_amount` decimal(10,2) NOT NULL,
  `total_paid_amount` decimal(10,2) NOT NULL,
  `total_taxes` text NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `order_status` varchar(300) NOT NULL COMMENT 'pending, accepted, done, rejected, cancelled',
  `order_type` varchar(50) NOT NULL COMMENT 'online, offline',
  `order_timezone` varchar(100) NOT NULL,
  `pick_up_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client_orders`
--

INSERT INTO `client_orders` (`client_order_id`, `branch_id`, `client_id`, `client_user_id`, `employee_id`, `register_id`, `register_session_id`, `total_items_cost`, `wallet_paid_amount`, `gift_card_id`, `gift_card_paid_amount`, `cash_paid_amount`, `debit_card_paid_amount`, `debit_card_receipt_img_obj`, `credit_card_paid_amount`, `credit_card_receipt_img_obj`, `cheque_paid_amount`, `cheque_card_receipt_img_obj`, `used_coupon_id`, `used_coupon_value`, `used_points_redeem_points`, `used_points_redeem_money`, `can_not_return_items`, `wallet_return_amount`, `gift_card_return_amount`, `cash_return_amount`, `debit_card_return_amount`, `credit_card_return_amount`, `cheque_return_amount`, `total_return_amount`, `total_paid_amount`, `total_taxes`, `total_cost`, `order_status`, `order_type`, `order_timezone`, `pick_up_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 4, NULL, 1, 1, 1, '100.00', '0.00', NULL, NULL, '100.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '100.00', '', '100.00', 'done', 'offline', '', NULL, '2022-11-29 10:20:20', '2022-11-29 10:20:20', NULL),
(2, 1, 4, NULL, 1, 1, 1, '300.00', '0.00', 2, '100.00', '200.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '300.00', '', '300.00', 'done', 'offline', '', NULL, '2022-11-29 10:34:25', '2022-11-29 10:34:25', NULL),
(3, 1, 5, NULL, 1, 1, 1, '90.00', '0.00', NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '', '90.00', 'done', 'offline', '', NULL, '2022-11-29 10:35:26', '2022-11-29 10:35:26', NULL),
(4, 1, 4, NULL, 1, 1, 1, '100.00', '0.00', NULL, NULL, '100.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, '0.00', '0.00', '100.00', '0.00', '0.00', '0.00', '100.00', '100.00', '', '100.00', 'done', 'offline', '', '2022-12-09', '2022-11-29 10:36:16', '2022-11-29 10:40:38', NULL),
(5, 1, 6, NULL, 3, 1, 1, '180.00', NULL, NULL, NULL, '180.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '180.00', '[]', '180.00', 'done', 'offline', 'Africa/Cairo', NULL, '2023-01-30 22:02:56', '2023-01-31 22:02:56', NULL),
(6, 1, 6, NULL, 3, 1, 1, '111.60', NULL, NULL, NULL, '111.60', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '111.60', '[{\"id\":\"1\",\"branch_tax_label\":\"ضريبة قيمة مضافة\",\"branch_tax_percent\":\"14\"},{\"id\":\"2\",\"branch_tax_label\":\"ضريبة بواخة\",\"branch_tax_percent\":\"10\"}]', '111.60', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-12 12:25:34', '2023-02-12 12:25:34', NULL),
(7, 1, 6, NULL, 3, 1, 1, '111.60', NULL, NULL, NULL, '111.60', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '111.60', '[{\"id\":\"1\",\"branch_tax_label\":\"ضريبة قيمة مضافة\",\"branch_tax_percent\":\"14\"},{\"id\":\"2\",\"branch_tax_label\":\"ضريبة بواخة\",\"branch_tax_percent\":\"10\"}]', '111.60', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-12 12:39:59', '2023-02-27 19:37:12', NULL),
(8, 1, 6, NULL, 3, 1, 1, '248.00', NULL, NULL, NULL, '248.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '248.00', '[{\"id\":\"1\",\"branch_tax_label\":\"ضريبة قيمة مضافة\",\"branch_tax_percent\":\"14\"},{\"id\":\"2\",\"branch_tax_label\":\"ضريبة بواخة\",\"branch_tax_percent\":\"10\"}]', '248.00', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-12 12:41:03', '2023-02-27 19:37:12', NULL),
(9, 1, 6, NULL, 3, 1, 1, '248.00', NULL, NULL, NULL, '223.20', '0.00', NULL, '0.00', NULL, '0.00', NULL, 1, '10.00', 0, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '223.20', '[{\"id\":\"1\",\"branch_tax_label\":\"ضريبة قيمة مضافة\",\"branch_tax_percent\":\"14\"},{\"id\":\"2\",\"branch_tax_label\":\"ضريبة بواخة\",\"branch_tax_percent\":\"10\"}]', '223.20', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-12 12:51:26', '2023-02-27 19:37:13', NULL),
(10, 1, 6, NULL, 3, 1, 1, '111.60', NULL, NULL, NULL, '100.44', '0.00', NULL, '0.00', NULL, '0.00', NULL, 1, '10.00', 0, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '100.44', '[{\"id\":\"1\",\"branch_tax_label\":\"ضريبة قيمة مضافة\",\"branch_tax_percent\":\"14\"},{\"id\":\"2\",\"branch_tax_label\":\"ضريبة بواخة\",\"branch_tax_percent\":\"10\"}]', '100.44', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-12 12:52:23', '2023-02-27 19:37:13', NULL),
(11, 1, 6, NULL, 3, 1, 1, '248.00', NULL, NULL, NULL, '238.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, 2, '10.00', 0, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '238.00', '[{\"id\":\"1\",\"branch_tax_label\":\"ضريبة قيمة مضافة\",\"branch_tax_percent\":\"14\"},{\"id\":\"2\",\"branch_tax_label\":\"ضريبة بواخة\",\"branch_tax_percent\":\"10\"}]', '238.00', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-12 12:54:32', '2023-02-27 19:37:13', NULL),
(12, 1, 6, NULL, 3, 1, 1, '248.00', NULL, NULL, NULL, '198.20', '0.00', NULL, '0.00', NULL, '0.00', NULL, 1, '10.00', 500, '25.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '198.20', '[{\"id\":\"1\",\"branch_tax_label\":\"ضريبة قيمة مضافة\",\"branch_tax_percent\":\"14\"},{\"id\":\"2\",\"branch_tax_label\":\"ضريبة بواخة\",\"branch_tax_percent\":\"10\"}]', '198.20', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-12 13:05:16', '2023-02-27 19:37:13', NULL),
(13, 1, 6, NULL, 3, 1, 1, '248.00', NULL, NULL, NULL, '213.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, 2, '10.00', 500, '25.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '213.00', '[{\"id\":\"1\",\"branch_tax_label\":\"ضريبة قيمة مضافة\",\"branch_tax_percent\":\"14\"},{\"id\":\"2\",\"branch_tax_label\":\"ضريبة بواخة\",\"branch_tax_percent\":\"10\"}]', '213.00', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-12 13:06:40', '2023-02-27 19:37:13', NULL),
(14, 1, 6, NULL, 3, 1, 1, '111.60', NULL, NULL, NULL, '50.00', '61.60', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '111.60', '[{\"id\":\"1\",\"branch_tax_label\":\"ضريبة قيمة مضافة\",\"branch_tax_percent\":\"14\"},{\"id\":\"2\",\"branch_tax_label\":\"ضريبة بواخة\",\"branch_tax_percent\":\"10\"}]', '111.60', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-12 13:11:36', '2023-02-27 19:37:13', NULL),
(15, 1, 6, NULL, 3, 1, 1, '300.00', NULL, NULL, NULL, '300.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '300.00', '[{\"id\":\"1\",\"tax_label\":\"50 percent\",\"tax_percent\":\"50\"}]', '300.00', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-15 14:01:40', '2023-03-16 14:26:00', NULL),
(16, 1, 6, NULL, 3, 1, 1, '135.00', NULL, NULL, NULL, '135.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '135.00', '[{\"id\":\"1\",\"tax_label\":\"50 percent\",\"tax_percent\":\"50\"}]', '135.00', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-21 15:52:29', '2023-03-16 14:26:00', NULL),
(17, 1, 6, NULL, 3, 1, 1, '300.00', NULL, NULL, NULL, '300.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '300.00', '[{\"id\":\"1\",\"tax_label\":\"50 percent\",\"tax_percent\":\"50\"}]', '300.00', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-02-28 23:36:10', '2023-03-16 14:26:00', NULL),
(18, 1, 7, NULL, 3, 1, 1, '446.40', NULL, NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '600.00', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-03-01 16:45:26', '2023-03-01 16:45:26', NULL),
(19, 1, 7, NULL, 3, 1, 1, '223.20', NULL, NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '223.20', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-03-01 17:48:42', '2023-03-01 17:48:42', NULL),
(20, 1, 7, NULL, 3, 1, 1, '223.20', NULL, NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '223.20', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-03-01 18:07:09', '2023-03-01 18:07:09', NULL),
(21, 1, 7, NULL, 3, 1, 1, '223.20', NULL, NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '223.20', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-03-01 18:08:26', '2023-03-01 18:08:26', NULL),
(22, 1, 7, NULL, 3, 1, 1, '223.20', NULL, NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '223.20', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-03-01 18:08:59', '2023-03-01 18:08:59', NULL),
(23, 1, 7, NULL, 3, 1, 1, '223.20', NULL, NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '223.20', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-03-01 18:09:28', '2023-03-01 18:09:28', NULL),
(24, 1, 7, NULL, 3, 1, 1, '223.20', NULL, NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '223.20', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-03-01 18:10:37', '2023-03-01 18:10:37', NULL),
(25, 1, 7, NULL, 3, 1, 1, '223.20', NULL, NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '223.20', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-03-01 18:14:07', '2023-03-01 18:14:07', NULL),
(26, 1, 7, NULL, 3, 1, 1, '446.40', NULL, NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, '446.40', '0.00', '0.00', '0.00', '0.00', '0.00', '446.40', '0.00', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '446.40', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-03-02 04:35:42', '2023-03-02 04:22:46', NULL),
(27, 1, 7, NULL, 3, 1, 1, '446.40', NULL, NULL, NULL, '0.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, NULL, '0.00', 0, '0.00', 0, '446.40', '0.00', '0.00', '0.00', '0.00', '0.00', '446.40', '0.00', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '446.40', 'done', 'offline', 'Africa/Cairo', '0000-00-00', '2023-03-02 05:23:32', '2023-03-03 00:12:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_order_items`
--

CREATE TABLE `client_order_items` (
  `id` int(11) NOT NULL,
  `operation_type` varchar(50) NOT NULL COMMENT 'buy or return',
  `client_order_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `pro_sku_id` int(11) NOT NULL,
  `item_type` varchar(50) NOT NULL COMMENT 'box or item',
  `order_quantity` int(11) NOT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  `item_cost` decimal(10,2) NOT NULL,
  `total_items_cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client_order_items`
--

INSERT INTO `client_order_items` (`id`, `operation_type`, `client_order_id`, `pro_id`, `pro_sku_id`, `item_type`, `order_quantity`, `promotion_id`, `item_cost`, `total_items_cost`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'buy', 1, 2, 1, 'item', 1, NULL, '100.00', '100.00', '2022-11-29 10:20:20', NULL, NULL),
(2, 'buy', 2, 2, 1, 'item', 1, NULL, '100.00', '100.00', '2022-11-29 10:34:25', NULL, NULL),
(3, 'buy', 2, 2, 2, 'item', 1, NULL, '200.00', '200.00', '2022-11-29 10:34:25', NULL, NULL),
(4, 'buy', 3, 2, 1, 'item', 1, NULL, '90.00', '90.00', '2022-11-29 10:35:26', NULL, NULL),
(5, 'buy', 4, 2, 1, 'item', 1, NULL, '100.00', '100.00', '2022-11-29 10:36:16', NULL, NULL),
(6, 'return', 4, 2, 1, 'item', 1, NULL, '100.00', '100.00', '2022-11-29 10:40:38', NULL, NULL),
(7, 'buy', 5, 2, 2, 'item', 1, NULL, '180.00', '180.00', '2023-01-31 22:02:56', NULL, NULL),
(8, 'buy', 6, 2, 1, 'item', 1, 1, '111.60', '111.60', '2023-02-12 12:25:34', NULL, NULL),
(9, 'buy', 7, 2, 1, 'item', 1, 1, '111.60', '111.60', '2023-02-12 12:39:59', NULL, NULL),
(10, 'buy', 8, 2, 2, 'item', 1, NULL, '248.00', '248.00', '2023-02-12 12:41:03', NULL, NULL),
(11, 'buy', 9, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-02-12 12:51:26', NULL, NULL),
(12, 'buy', 10, 2, 1, 'item', 1, 1, '100.44', '100.44', '2023-02-12 12:52:23', NULL, NULL),
(13, 'buy', 11, 2, 2, 'item', 1, NULL, '238.00', '238.00', '2023-02-12 12:54:32', NULL, NULL),
(14, 'buy', 12, 2, 2, 'item', 1, NULL, '198.20', '198.20', '2023-02-12 13:05:16', NULL, NULL),
(15, 'buy', 13, 2, 2, 'item', 1, NULL, '213.00', '213.00', '2023-02-12 13:06:40', NULL, NULL),
(16, 'buy', 14, 2, 1, 'item', 1, 1, '111.60', '111.60', '2023-02-12 13:11:36', NULL, NULL),
(17, 'buy', 15, 2, 2, 'item', 1, NULL, '300.00', '300.00', '2023-02-15 14:01:40', NULL, NULL),
(18, 'buy', 16, 2, 1, 'item', 1, 1, '135.00', '135.00', '2023-02-21 15:52:29', NULL, NULL),
(19, 'buy', 17, 2, 2, 'item', 1, NULL, '300.00', '300.00', '2023-02-28 23:36:10', NULL, NULL),
(20, 'buy', 18, 2, 2, 'item', 2, NULL, '223.20', '446.40', '2023-03-01 16:45:26', NULL, NULL),
(21, 'buy', 19, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-01 17:48:42', NULL, NULL),
(22, 'buy', 20, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-01 18:07:09', NULL, NULL),
(23, 'buy', 21, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-01 18:08:26', NULL, NULL),
(24, 'buy', 22, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-01 18:08:59', NULL, NULL),
(25, 'buy', 23, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-01 18:09:28', NULL, NULL),
(26, 'buy', 24, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-01 18:10:37', NULL, NULL),
(27, 'return', 24, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-01 17:13:42', NULL, NULL),
(28, 'buy', 25, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-01 18:14:07', NULL, NULL),
(29, 'return', 25, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-01 17:14:22', NULL, NULL),
(30, 'buy', 26, 2, 2, 'item', 2, NULL, '223.20', '446.40', '2023-03-02 04:35:42', NULL, NULL),
(31, 'return', 26, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-02 04:21:10', NULL, NULL),
(32, 'return', 26, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-02 04:22:46', NULL, NULL),
(33, 'buy', 27, 2, 2, 'item', 2, NULL, '223.20', '446.40', '2023-03-02 05:23:32', NULL, NULL),
(34, 'return', 27, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-02 04:25:43', NULL, NULL),
(35, 'return', 27, 2, 2, 'item', 1, NULL, '223.20', '223.20', '2023-03-03 00:12:19', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_wishlist`
--

CREATE TABLE `client_wishlist` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `coupon_title` text NOT NULL,
  `coupon_code` varchar(300) NOT NULL,
  `coupon_start_date` datetime NOT NULL,
  `coupon_end_date` datetime NOT NULL,
  `coupon_code_type` varchar(50) NOT NULL COMMENT 'value or percent',
  `coupon_code_value` decimal(10,2) NOT NULL,
  `coupon_is_active` tinyint(1) NOT NULL,
  `coupon_limited_number` int(11) NOT NULL,
  `coupon_used_times` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`coupon_id`, `branch_id`, `coupon_title`, `coupon_code`, `coupon_start_date`, `coupon_end_date`, `coupon_code_type`, `coupon_code_value`, `coupon_is_active`, `coupon_limited_number`, `coupon_used_times`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, '{\"en\":\"coup\"}', '2022newchris', '2023-02-08 13:42:00', '2023-03-09 13:42:00', 'percent', '10.00', 1, 10, 3, '2023-02-12 11:42:33', '2023-02-12 13:05:16', NULL),
(2, 1, '{\"en\":\"coup22\"}', '2022newchris2', '2023-02-01 13:54:00', '2023-03-08 13:54:00', 'value', '10.00', 1, 10, 2, '2023-02-12 11:54:11', '2023-02-12 13:06:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `currency_id` int(11) NOT NULL,
  `currency_img_obj` varchar(300) NOT NULL,
  `currency_name` varchar(300) NOT NULL,
  `currency_code` varchar(50) NOT NULL,
  `currency_rate` decimal(10,2) NOT NULL,
  `currency_is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currency_id`, `currency_img_obj`, `currency_name`, `currency_code`, `currency_rate`, `currency_is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', 'eur', 'EUR', '1.00', 1, NULL, '2022-09-17 14:20:18', NULL),
(2, '{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5c0c9770084fc033f9cd11830aa11994.jpg\"}', 'EGP', 'EGP', '19.29', 1, '2022-09-17 14:15:02', '2022-09-17 14:20:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_action_log`
--

CREATE TABLE `employee_action_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module` varchar(50) NOT NULL,
  `action_url` varchar(500) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `old_obj` text NOT NULL,
  `request_headers` text NOT NULL,
  `request_body` text NOT NULL,
  `log_desc` varchar(300) NOT NULL,
  `logged_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee_action_log`
--

INSERT INTO `employee_action_log` (`id`, `user_id`, `module`, `action_url`, `action_type`, `old_obj`, `request_headers`, `request_body`, `log_desc`, `logged_at`) VALUES
(1, 1, 'branches', 'http://localhost/work/sites/canda_erp/admin/branches/save/1', 'update', '{\"branch_id\":1,\"cash_wallet_id\":14,\"debit_card_wallet_id\":15,\"credit_card_wallet_id\":16,\"cheque_wallet_id\":17,\"tax_group_id\":1,\"branch_api_access_token\":\"14231112132\",\"branch_name\":\"canda branch\",\"branch_country\":\"CA\",\"branch_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"\"},\"branch_currency\":\"USD\",\"branch_timezone\":\"Africa\\/Cairo\",\"branch_taxes\":\"[{\\\"id\\\":\\\"1\\\",\\\"branch_tax_label\\\":\\\"\\u0636\\u0631\\u064a\\u0628\\u0629 \\u0642\\u064a\\u0645\\u0629 \\u0645\\u0636\\u0627\\u0641\\u0629\\\",\\\"branch_tax_percent\\\":\\\"14\\\"},{\\\"id\\\":\\\"2\\\",\\\"branch_tax_label\\\":\\\"\\u0636\\u0631\\u064a\\u0628\\u0629 \\u0628\\u0648\\u0627\\u062e\\u0629\\\",\\\"branch_tax_percent\\\":\\\"10\\\"}]\",\"first_day_of_the_week\":\"saturday\",\"return_policy_days\":15,\"created_at\":\"2022-11-29 12:01:35\",\"updated_at\":\"2023-02-12 12:26:43\",\"deleted_at\":null,\"old_paths\":{\"branch_img_obj\":\"\"}}', '{}', '{\"branch_country\":\"CA\",\"tax_group_id\":\"1\",\"branch_timezone\":\"Africa\\/Cairo\",\"first_day_of_the_week\":\"saturday\",\"branch_name\":\"canda branch\",\"return_policy_days\":\"15\",\"undefined\":\"save\",\"branch_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"\\\"}\"}', '', '2023-02-20 14:31:58'),
(2, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"6\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"1\"],\"item_type\":[\"item\"],\"order_quantity\":[\"1\"],\"item_total_cost\":[\"135\"],\"promo_id\":{\"1\":\"1\"},\"total_items_cost\":\"135.00\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"135\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"135.00\",\"paid_amount\":\"135\",\"undefined\":\"save\"}', '', '2023-02-21 16:52:14'),
(3, 1, 'branches', 'http://localhost/work/sites/canda_erp/admin/branches/save/2', 'update', '{\"branch_id\":2,\"cash_wallet_id\":21,\"debit_card_wallet_id\":22,\"credit_card_wallet_id\":23,\"cheque_wallet_id\":24,\"tax_group_id\":1,\"branch_api_access_token\":\"\",\"branch_name\":\"second brand\",\"branch_country\":\"CL\",\"branch_img_obj\":null,\"branch_currency\":\"USD\",\"branch_timezone\":\"\",\"branch_taxes\":\"[{\\\"id\\\":\\\"1\\\",\\\"branch_tax_label\\\":\\\"Eiusmod quae sint ra\\\",\\\"branch_tax_percent\\\":\\\"70\\\"}]\",\"first_day_of_the_week\":\"\",\"return_policy_days\":0,\"created_at\":\"2022-12-07 02:04:41\",\"updated_at\":\"2022-12-07 02:04:41\",\"deleted_at\":null,\"old_paths\":{\"branch_img_obj\":\"\"}}', '{}', '{\"branch_country\":\"CL\",\"tax_group_id\":\"1\",\"branch_timezone\":\"Europe\\/Andorra\",\"first_day_of_the_week\":\"saturday\",\"branch_name\":\"second branch\",\"return_policy_days\":0,\"undefined\":\"save\",\"branch_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"\\\"}\"}', '', '2023-02-21 17:44:29'),
(4, 1, 'employee_details', 'http://localhost/work/sites/canda_erp/admin/employees/save', '', '\"\"', '{}', '{\"user_role\":\"branch_admin\",\"branch_id\":\"2\",\"is_active\":\"0\",\"employee_working_days\":\"[\\\"sunday\\\",\\\"monday\\\",\\\"thursday\\\",\\\"friday\\\"]\",\"full_name\":\"Tyler Maddox\",\"email\":\"sozepyf@mailinator.com\",\"password\":\"Pa$$w0rd!\",\"phone_code\":\"+1 (836) 937-9621\",\"phone\":\"+1 (753) 486-9153\",\"hour_price\":\"224\",\"employee_required_working_hours_per_day\":\"2\",\"employee_should_start_work_at\":\"5:44 PM\",\"employee_should_end_work_at\":\"5:44 PM\",\"employee_overtime_hour_rate\":\"37\",\"employee_vacation_hour_rate\":\"80\",\"employee_sick_vacation_max_requests\":\"89\",\"employee_vacation_max_requests\":\"78\",\"employee_delay_requests_max_requests\":\"85\",\"employee_early_requests_max_requests\":\"35\",\"undefined\":\"save\",\"logo_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"\\\"}\",\"employee_id\":7}', '', '2023-02-21 17:45:21'),
(5, 7, 'clients', 'http://localhost/work/sites/canda_erp/admin/clients/save', '', '\"\"', '{}', '{\"client_type\":\"wholesaler\",\"tax_group_id\":\"1\",\"branch_id\":2,\"client_name\":\"April Tyson\",\"client_email\":\"vogihod@mailinator.com\",\"client_phone\":\"+1 (797) 951-8921\",\"undefined\":\"save\",\"wallet_id\":30,\"points_wallet_id\":31}', '', '2023-02-21 17:10:41'),
(6, 7, 'clients', 'http://localhost/work/sites/canda_erp/admin/clients/save/8', 'update', '{\"client_id\":8,\"user_id\":null,\"branch_id\":2,\"wallet_id\":30,\"points_wallet_id\":31,\"tax_group_id\":1,\"client_type\":\"wholesaler\",\"client_name\":\"April Tyson\",\"client_email\":\"vogihod@mailinator.com\",\"client_phone\":\"+1 (797) 951-8921\",\"client_total_orders_count\":0,\"client_total_orders_amount\":\"0.00\",\"client_total_loyal_points\":0,\"created_at\":\"2023-02-21 17:10:41\",\"updated_at\":\"2023-02-21 17:10:41\",\"deleted_at\":null,\"old_paths\":{}}', '{}', '{\"client_type\":\"wholesaler\",\"tax_group_id\":\"1\",\"branch_id\":\"2\",\"client_name\":\"April Tyson\",\"client_email\":\"vogihod@mailinator.com\",\"client_phone\":\"012\",\"undefined\":\"save\"}', '', '2023-02-21 17:10:57'),
(7, 1, 'products', 'http://localhost/work/sites/canda_erp/admin/products/save/2', 'update', '{\"pro_id\":2,\"cat_id\":2,\"brand_id\":1,\"pro_name\":{\"en\":\"vape type 1\"},\"pro_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"\"},\"pro_slider\":{\"slider_objs\":[],\"other_fields\":[]},\"pro_desc\":{\"en\":\"vape type 1\"},\"standard_box_quantity\":12,\"created_at\":\"2022-11-29 12:03:42\",\"updated_at\":\"2022-11-29 12:03:42\",\"deleted_at\":null,\"pro_name_en\":\"vape type 1\",\"pro_desc_en\":\"vape type 1\",\"old_paths\":{\"pro_img_obj\":\"\"}}', '{}', '{\"cat_id\":\"2\",\"brand_id\":\"1\",\"standard_box_quantity\":\"12\",\"pro_name_en\":\"vape type 1\",\"pro_desc_en\":\"vape type 1\",\"json_values_of_sliderpro_slider_file\":\"[]\",\"new_variant_values_for_old_items_1\":[\"\"],\"new_variant_name\":[\"flavor\"],\"new_variant_values_0\":[\"\",\"banana\",\"mango\"],\"undefined\":\"save\",\"pro_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"\\\"}\",\"pro_slider\":\"{\\\"slider_objs\\\":[],\\\"other_fields\\\":[]}\",\"pro_name\":\"{\\\"en\\\":\\\"vape type 1\\\"}\",\"pro_desc\":\"{\\\"en\\\":\\\"vape type 1\\\"}\"}', '', '2023-02-27 15:53:20'),
(8, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/new_accept_item', 'update', '{\"ps_id\":3,\"pro_id\":2,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"1,3\",\"ps_selected_variant_type_values_text\":\"100 ml-banana\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 15:53:20\",\"updated_at\":\"2023-02-27 15:53:20\",\"deleted_at\":null}', '{}', '{\"item_id\":\"3\",\"accept_url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/new_accept_item\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"is_active\",\"accept\":\"1\",\"acceptersdata\":\"[\\\"<i class=\'fa fa-times\'><\\\\\\/i>\\\",\\\"<i class=\'fa fa-check\'><\\\\\\/i>\\\"]\",\"item_primary_col\":\"ps_id\",\"display_block\":\"\",\"func_after_edit\":\"\"}', '', '2023-02-27 15:54:11'),
(9, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/new_accept_item', 'update', '{\"ps_id\":4,\"pro_id\":2,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"1,4\",\"ps_selected_variant_type_values_text\":\"100 ml-mango\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 15:53:20\",\"updated_at\":\"2023-02-27 15:53:20\",\"deleted_at\":null}', '{}', '{\"item_id\":\"4\",\"accept_url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/new_accept_item\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"is_active\",\"accept\":\"1\",\"acceptersdata\":\"[\\\"<i class=\'fa fa-times\'><\\\\\\/i>\\\",\\\"<i class=\'fa fa-check\'><\\\\\\/i>\\\"]\",\"item_primary_col\":\"ps_id\",\"display_block\":\"\",\"func_after_edit\":\"\"}', '', '2023-02-27 15:54:13'),
(10, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/new_accept_item', 'update', '{\"ps_id\":5,\"pro_id\":2,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"2,3\",\"ps_selected_variant_type_values_text\":\"200 ml-banana\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 15:53:20\",\"updated_at\":\"2023-02-27 15:53:20\",\"deleted_at\":null}', '{}', '{\"item_id\":\"5\",\"accept_url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/new_accept_item\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"is_active\",\"accept\":\"1\",\"acceptersdata\":\"[\\\"<i class=\'fa fa-times\'><\\\\\\/i>\\\",\\\"<i class=\'fa fa-check\'><\\\\\\/i>\\\"]\",\"item_primary_col\":\"ps_id\",\"display_block\":\"\",\"func_after_edit\":\"\"}', '', '2023-02-27 15:54:14'),
(11, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/new_accept_item', 'update', '{\"ps_id\":6,\"pro_id\":2,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"2,4\",\"ps_selected_variant_type_values_text\":\"200 ml-mango\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 15:53:20\",\"updated_at\":\"2023-02-27 15:53:20\",\"deleted_at\":null}', '{}', '{\"item_id\":\"6\",\"accept_url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/new_accept_item\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"is_active\",\"accept\":\"1\",\"acceptersdata\":\"[\\\"<i class=\'fa fa-times\'><\\\\\\/i>\\\",\\\"<i class=\'fa fa-check\'><\\\\\\/i>\\\"]\",\"item_primary_col\":\"ps_id\",\"display_block\":\"\",\"func_after_edit\":\"\"}', '', '2023-02-27 15:54:15'),
(12, 1, 'products', 'http://localhost/work/sites/canda_erp/admin/products/save', '', '\"\"', '{}', '{\"cat_id\":\"2\",\"brand_id\":\"1\",\"standard_box_quantity\":\"12\",\"pro_name_en\":\"vape type 2\",\"pro_desc_en\":\"vape type 2\",\"json_values_of_sliderpro_slider_file\":\"[]\",\"new_variant_name\":[\"flavor\"],\"new_variant_values_0\":[\"\",\"strwberry\",\"mango\",\"vanilia\"],\"undefined\":\"save\",\"pro_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"\\\"}\",\"pro_slider\":\"{\\\"slider_objs\\\":[],\\\"other_fields\\\":[]}\",\"pro_name\":\"{\\\"en\\\":\\\"vape type 2\\\"}\",\"pro_desc\":\"{\\\"en\\\":\\\"vape type 2\\\"}\"}', '', '2023-02-27 19:18:54'),
(13, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":7,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"5\",\"ps_selected_variant_type_values_text\":\"strwberry\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:18:54\",\"deleted_at\":null}', '{}', '{\"item_id\":\"7\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_box_barcode\",\"value\":\"13909672\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-02-27 19:20:11'),
(14, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":7,\"pro_id\":3,\"ps_box_barcode\":\"13909672\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"5\",\"ps_selected_variant_type_values_text\":\"strwberry\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:20:11\",\"deleted_at\":null}', '{}', '{\"item_id\":\"7\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_item_barcode\",\"value\":\"11740986\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-02-27 19:20:12'),
(15, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":8,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"6\",\"ps_selected_variant_type_values_text\":\"mango\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:18:54\",\"deleted_at\":null}', '{}', '{\"item_id\":\"8\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_box_barcode\",\"value\":\"xd-123\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-02-27 19:25:07'),
(16, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":8,\"pro_id\":3,\"ps_box_barcode\":\"xd-123\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"6\",\"ps_selected_variant_type_values_text\":\"mango\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:25:07\",\"deleted_at\":null}', '{}', '{\"item_id\":\"8\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_item_barcode\",\"value\":\"94935400\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-02-27 19:25:12'),
(17, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":9,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"7\",\"ps_selected_variant_type_values_text\":\"vanilia\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:18:54\",\"deleted_at\":null}', '{}', '{\"item_id\":\"9\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_box_barcode\",\"value\":\"19132061\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-02-27 19:25:13'),
(18, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":9,\"pro_id\":3,\"ps_box_barcode\":\"19132061\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"7\",\"ps_selected_variant_type_values_text\":\"vanilia\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:25:13\",\"deleted_at\":null}', '{}', '{\"item_id\":\"9\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_item_barcode\",\"value\":\"49160887\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-02-27 19:25:14'),
(19, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/new_accept_item', 'update', '{\"ps_id\":7,\"pro_id\":3,\"ps_box_barcode\":\"13909672\",\"ps_item_barcode\":\"11740986\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"5\",\"ps_selected_variant_type_values_text\":\"strwberry\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:20:12\",\"deleted_at\":null}', '{}', '{\"item_id\":\"7\",\"accept_url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/new_accept_item\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"is_active\",\"accept\":\"1\",\"acceptersdata\":\"[\\\"<i class=\'fa fa-times\'><\\\\\\/i>\\\",\\\"<i class=\'fa fa-check\'><\\\\\\/i>\\\"]\",\"item_primary_col\":\"ps_id\",\"display_block\":\"\",\"func_after_edit\":\"\"}', '', '2023-02-27 19:25:36'),
(20, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/new_accept_item', 'update', '{\"ps_id\":8,\"pro_id\":3,\"ps_box_barcode\":\"xd-123\",\"ps_item_barcode\":\"94935400\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"6\",\"ps_selected_variant_type_values_text\":\"mango\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:25:12\",\"deleted_at\":null}', '{}', '{\"item_id\":\"8\",\"accept_url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/new_accept_item\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"is_active\",\"accept\":\"1\",\"acceptersdata\":\"[\\\"<i class=\'fa fa-times\'><\\\\\\/i>\\\",\\\"<i class=\'fa fa-check\'><\\\\\\/i>\\\"]\",\"item_primary_col\":\"ps_id\",\"display_block\":\"\",\"func_after_edit\":\"\"}', '', '2023-02-27 19:25:36'),
(21, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/new_accept_item', 'update', '{\"ps_id\":9,\"pro_id\":3,\"ps_box_barcode\":\"19132061\",\"ps_item_barcode\":\"49160887\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"7\",\"ps_selected_variant_type_values_text\":\"vanilia\",\"is_active\":0,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:25:14\",\"deleted_at\":null}', '{}', '{\"item_id\":\"9\",\"accept_url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/new_accept_item\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"is_active\",\"accept\":\"1\",\"acceptersdata\":\"[\\\"<i class=\'fa fa-times\'><\\\\\\/i>\\\",\\\"<i class=\'fa fa-check\'><\\\\\\/i>\\\"]\",\"item_primary_col\":\"ps_id\",\"display_block\":\"\",\"func_after_edit\":\"\"}', '', '2023-02-27 19:25:38'),
(22, 1, 'branch_prices', 'http://localhost/work/sites/canda_erp/admin/branches-prices/update-branch-prices', 'general-self-edit', '{\"id\":10,\"branch_id\":1,\"pro_id\":3,\"sku_id\":8,\"online_item_price\":\"0.00\",\"online_box_price\":\"0.00\",\"item_retailer_price\":\"0.00\",\"item_wholesaler_price\":\"0.00\",\"box_retailer_price\":\"0.00\",\"box_wholesaler_price\":\"0.00\",\"is_active\":1,\"created_at\":\"2023-02-27 19:25:41\",\"updated_at\":\"2023-02-27 19:25:41\",\"deleted_at\":null}', '{}', '{\"item_id\":\"10\",\"table_name\":\"App\\\\models\\\\branch\\\\branch_prices_m\",\"field_name\":\"online_item_price\",\"value\":\"10\",\"input_type\":\"number\",\"row_primary_col\":\"id\",\"func_after_edit\":\"\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/branches-prices\\/update-branch-prices\"}', '', '2023-02-27 19:43:22'),
(23, 1, 'branch_prices', 'http://localhost/work/sites/canda_erp/admin/branches-prices/update-branch-prices', 'general-self-edit', '{\"id\":10,\"branch_id\":1,\"pro_id\":3,\"sku_id\":8,\"online_item_price\":\"10.00\",\"online_box_price\":\"0.00\",\"item_retailer_price\":\"0.00\",\"item_wholesaler_price\":\"0.00\",\"box_retailer_price\":\"0.00\",\"box_wholesaler_price\":\"0.00\",\"is_active\":1,\"created_at\":\"2023-02-27 19:25:41\",\"updated_at\":\"2023-02-27 19:43:22\",\"deleted_at\":null}', '{}', '{\"item_id\":\"10\",\"table_name\":\"App\\\\models\\\\branch\\\\branch_prices_m\",\"field_name\":\"online_box_price\",\"value\":\"120\",\"input_type\":\"number\",\"row_primary_col\":\"id\",\"func_after_edit\":\"\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/branches-prices\\/update-branch-prices\"}', '', '2023-02-27 19:43:25'),
(24, 1, 'branch_prices', 'http://localhost/work/sites/canda_erp/admin/branches-prices/update-branch-prices', 'general-self-edit', '{\"id\":10,\"branch_id\":1,\"pro_id\":3,\"sku_id\":8,\"online_item_price\":\"10.00\",\"online_box_price\":\"120.00\",\"item_retailer_price\":\"0.00\",\"item_wholesaler_price\":\"0.00\",\"box_retailer_price\":\"0.00\",\"box_wholesaler_price\":\"0.00\",\"is_active\":1,\"created_at\":\"2023-02-27 19:25:41\",\"updated_at\":\"2023-02-27 19:43:25\",\"deleted_at\":null}', '{}', '{\"item_id\":\"10\",\"table_name\":\"App\\\\models\\\\branch\\\\branch_prices_m\",\"field_name\":\"item_retailer_price\",\"value\":\"10\",\"input_type\":\"number\",\"row_primary_col\":\"id\",\"func_after_edit\":\"\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/branches-prices\\/update-branch-prices\"}', '', '2023-02-27 19:43:33'),
(25, 1, 'branch_prices', 'http://localhost/work/sites/canda_erp/admin/branches-prices/update-branch-prices', 'general-self-edit', '{\"id\":10,\"branch_id\":1,\"pro_id\":3,\"sku_id\":8,\"online_item_price\":\"10.00\",\"online_box_price\":\"120.00\",\"item_retailer_price\":\"10.00\",\"item_wholesaler_price\":\"0.00\",\"box_retailer_price\":\"0.00\",\"box_wholesaler_price\":\"0.00\",\"is_active\":1,\"created_at\":\"2023-02-27 19:25:41\",\"updated_at\":\"2023-02-27 19:43:33\",\"deleted_at\":null}', '{}', '{\"item_id\":\"10\",\"table_name\":\"App\\\\models\\\\branch\\\\branch_prices_m\",\"field_name\":\"item_wholesaler_price\",\"value\":\"9\",\"input_type\":\"number\",\"row_primary_col\":\"id\",\"func_after_edit\":\"\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/branches-prices\\/update-branch-prices\"}', '', '2023-02-27 19:43:36'),
(26, 1, 'branch_prices', 'http://localhost/work/sites/canda_erp/admin/branches-prices/update-branch-prices', 'general-self-edit', '{\"id\":10,\"branch_id\":1,\"pro_id\":3,\"sku_id\":8,\"online_item_price\":\"10.00\",\"online_box_price\":\"120.00\",\"item_retailer_price\":\"10.00\",\"item_wholesaler_price\":\"9.00\",\"box_retailer_price\":\"0.00\",\"box_wholesaler_price\":\"0.00\",\"is_active\":1,\"created_at\":\"2023-02-27 19:25:41\",\"updated_at\":\"2023-02-27 19:43:36\",\"deleted_at\":null}', '{}', '{\"item_id\":\"10\",\"table_name\":\"App\\\\models\\\\branch\\\\branch_prices_m\",\"field_name\":\"box_retailer_price\",\"value\":\"120\",\"input_type\":\"number\",\"row_primary_col\":\"id\",\"func_after_edit\":\"\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/branches-prices\\/update-branch-prices\"}', '', '2023-02-27 19:43:39'),
(27, 1, 'branch_prices', 'http://localhost/work/sites/canda_erp/admin/branches-prices/update-branch-prices', 'general-self-edit', '{\"id\":10,\"branch_id\":1,\"pro_id\":3,\"sku_id\":8,\"online_item_price\":\"10.00\",\"online_box_price\":\"120.00\",\"item_retailer_price\":\"10.00\",\"item_wholesaler_price\":\"9.00\",\"box_retailer_price\":\"120.00\",\"box_wholesaler_price\":\"0.00\",\"is_active\":1,\"created_at\":\"2023-02-27 19:25:41\",\"updated_at\":\"2023-02-27 19:43:39\",\"deleted_at\":null}', '{}', '{\"item_id\":\"10\",\"table_name\":\"App\\\\models\\\\branch\\\\branch_prices_m\",\"field_name\":\"box_wholesaler_price\",\"value\":\"111\",\"input_type\":\"number\",\"row_primary_col\":\"id\",\"func_after_edit\":\"\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/branches-prices\\/update-branch-prices\"}', '', '2023-02-27 19:43:43'),
(28, 1, 'transactions-log', 'http://localhost/work/sites/canda_erp/admin/transactions-log/withdraw-money/irma%20carter/25', 'withdraw-Money', '{\"wallet_id\":25,\"wallet_amount\":\"1050.00\",\"created_at\":\"2022-12-29 05:10:01\",\"updated_at\":\"2023-02-27 20:22:00\",\"deleted_at\":null}', '{}', '{\"money_amount\":\"1050.00\",\"admin_notes\":\"\\u064a\\u0634\\u0633\\u064a\\u0634\\u064a\",\"undefined\":\"Save\"}', '', '2023-02-27 20:37:05'),
(29, 1, 'products', 'http://localhost/work/sites/canda_erp/admin/products/save/3', 'update', '{\"pro_id\":3,\"cat_id\":2,\"brand_id\":1,\"pro_name\":{\"en\":\"vape type 2\"},\"pro_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"\"},\"pro_slider\":{\"slider_objs\":[],\"other_fields\":[]},\"pro_desc\":{\"en\":\"vape type 2\"},\"standard_box_quantity\":12,\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:18:54\",\"deleted_at\":null,\"pro_name_en\":\"vape type 2\",\"pro_desc_en\":\"vape type 2\",\"old_paths\":{\"pro_img_obj\":\"\"}}', '{}', '{\"cat_id\":\"2\",\"brand_id\":\"2\",\"standard_box_quantity\":\"12\",\"pro_name_en\":\"vape type 2\",\"pro_desc_en\":\"vape type 2\",\"json_values_of_sliderpro_slider_file\":\"[]\",\"new_variant_values_for_old_items_3\":[\"\"],\"new_variant_name\":[\"\"],\"new_variant_values_0\":[\"\"],\"undefined\":\"save\",\"pro_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"\\\"}\",\"pro_slider\":\"{\\\"slider_objs\\\":[],\\\"other_fields\\\":[]}\",\"pro_name\":\"{\\\"en\\\":\\\"vape type 2\\\"}\",\"pro_desc\":\"{\\\"en\\\":\\\"vape type 2\\\"}\"}', '', '2023-02-28 19:39:00'),
(30, 1, 'categories', 'http://localhost/work/sites/canda_erp/admin/categories/save', '', '\"\"', '{}', '{\"parent_id\":\"1\",\"cat_name_en\":\"another sub\",\"undefined\":\"save\",\"cat_name\":\"{\\\"en\\\":\\\"another sub\\\"}\"}', '', '2023-02-28 19:39:28'),
(31, 1, 'products', 'http://localhost/work/sites/canda_erp/admin/products/save/3', 'update', '{\"pro_id\":3,\"cat_id\":2,\"brand_id\":2,\"pro_name\":{\"en\":\"vape type 2\"},\"pro_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"\"},\"pro_slider\":{\"slider_objs\":[],\"other_fields\":[]},\"pro_desc\":{\"en\":\"vape type 2\"},\"standard_box_quantity\":12,\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-28 19:39:00\",\"deleted_at\":null,\"pro_name_en\":\"vape type 2\",\"pro_desc_en\":\"vape type 2\",\"old_paths\":{\"pro_img_obj\":\"\"}}', '{}', '{\"cat_id\":\"3\",\"brand_id\":\"2\",\"standard_box_quantity\":\"12\",\"pro_name_en\":\"vape type 2\",\"pro_desc_en\":\"vape type 2\",\"json_values_of_sliderpro_slider_file\":\"[]\",\"new_variant_values_for_old_items_3\":[\"\"],\"new_variant_name\":[\"\"],\"new_variant_values_0\":[\"\"],\"undefined\":\"save\",\"pro_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"\\\"}\",\"pro_slider\":\"{\\\"slider_objs\\\":[],\\\"other_fields\\\":[]}\",\"pro_name\":\"{\\\"en\\\":\\\"vape type 2\\\"}\",\"pro_desc\":\"{\\\"en\\\":\\\"vape type 2\\\"}\"}', '', '2023-02-28 19:39:38'),
(32, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"6\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"1\"],\"item_total_cost\":[\"300\"],\"total_items_cost\":\"300.00\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"300\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"300.00\",\"paid_amount\":\"300\",\"undefined\":\"save\"}', '', '2023-03-01 00:36:04'),
(33, 1, 'suppliers-orders', 'http://localhost/work/sites/canda_erp/admin/suppliers-orders/make-order-done/19', 'make-Order-Done', '\"\"', '{}', '{\"order_id\":\"19\",\"supplier_id\":1,\"total_items_cost\":50,\"paid_amount\":0}', '', '2023-03-01 05:21:53'),
(34, 1, 'suppliers-orders', 'http://localhost/work/sites/canda_erp/admin/suppliers-orders/return-items/19', 'return-Order-Items', '\"\"', '{}', '{\"item_ids\":[\"12\"],\"want_return_qty\":[\"1\"],\"returned_amount\":\"\",\"received_amount\":\"\"}', '', '2023-03-01 05:22:14'),
(35, 1, 'suppliers-orders', 'http://localhost/work/sites/canda_erp/admin/suppliers-orders/make-order-done/20', 'make-Order-Done', '\"\"', '{}', '{\"order_id\":\"20\",\"supplier_id\":1,\"total_items_cost\":100,\"paid_amount\":0}', '', '2023-03-01 05:29:40'),
(36, 1, 'suppliers-orders', 'http://localhost/work/sites/canda_erp/admin/suppliers-orders/return-items/20', 'return-Order-Items', '\"\"', '{}', '{\"item_ids\":[\"14\"],\"want_return_qty\":[\"1\"],\"returned_amount\":\"\",\"received_amount\":\"\"}', '', '2023-03-01 05:29:58'),
(37, 1, 'suppliers-orders', 'http://localhost/work/sites/canda_erp/admin/suppliers-orders/return-items/20', 'return-Order-Items', '\"\"', '{}', '{\"item_ids\":[\"14\"],\"want_return_qty\":[\"1\"],\"returned_amount\":\"\",\"received_amount\":\"\"}', '', '2023-03-01 05:33:28'),
(38, 1, 'suppliers-orders', 'http://localhost/work/sites/canda_erp/admin/suppliers-orders/make-order-done/21', 'make-Order-Done', '\"\"', '{}', '{\"order_id\":\"21\",\"supplier_id\":1,\"total_items_cost\":100,\"paid_amount\":0}', '', '2023-03-01 05:33:57'),
(39, 1, 'suppliers-orders', 'http://localhost/work/sites/canda_erp/admin/suppliers-orders/return-items/21', 'return-Order-Items', '\"\"', '{}', '{\"item_ids\":[\"17\"],\"want_return_qty\":[\"2\"],\"returned_amount\":\"\",\"received_amount\":\"\"}', '', '2023-03-01 05:35:57'),
(40, 1, 'suppliers-orders', 'http://localhost/work/sites/canda_erp/admin/suppliers-orders/cancel-order/22', 'cancel-Order', '\"\"', '{}', '[]', '', '2023-03-01 05:37:43'),
(41, 1, 'suppliers-orders', 'http://localhost/work/sites/canda_erp/admin/suppliers-orders/make-order-done/23', 'make-Order-Done', '\"\"', '{}', '{\"order_id\":\"23\",\"supplier_id\":1,\"total_items_cost\":100,\"paid_amount\":0}', '', '2023-03-01 05:38:36'),
(42, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"7\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"2\"],\"item_total_cost\":[\"300\"],\"total_items_cost\":\"600.00\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"600.00\",\"paid_amount\":\"0\",\"undefined\":\"save\"}', '', '2023-03-01 17:45:12'),
(43, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"7\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"1\"],\"item_total_cost\":[\"223.2\"],\"total_items_cost\":\"223.20\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"223.20\",\"paid_amount\":\"0\",\"undefined\":\"save\"}', '', '2023-03-01 18:48:39'),
(44, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"7\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"1\"],\"item_total_cost\":[\"223.2\"],\"total_items_cost\":\"223.20\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"223.20\",\"paid_amount\":\"0\",\"undefined\":\"save\"}', '', '2023-03-01 19:07:08'),
(45, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"7\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"1\"],\"item_total_cost\":[\"223.2\"],\"total_items_cost\":\"223.20\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"223.20\",\"paid_amount\":\"0\",\"undefined\":\"save\"}', '', '2023-03-01 19:08:25'),
(46, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"7\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"1\"],\"item_total_cost\":[\"223.2\"],\"total_items_cost\":\"223.20\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"223.20\",\"paid_amount\":\"0\",\"undefined\":\"save\"}', '', '2023-03-01 19:08:57'),
(47, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"7\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"1\"],\"item_total_cost\":[\"223.2\"],\"total_items_cost\":\"223.20\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"223.20\",\"paid_amount\":\"0\",\"undefined\":\"save\"}', '', '2023-03-01 19:09:26'),
(48, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"7\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"1\"],\"item_total_cost\":[\"223.2\"],\"total_items_cost\":\"223.20\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"223.20\",\"paid_amount\":\"0\",\"undefined\":\"save\"}', '', '2023-03-01 19:10:35'),
(49, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"7\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"1\"],\"item_total_cost\":[\"223.2\"],\"total_items_cost\":\"223.20\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"-223.20\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"223.20\",\"paid_amount\":\"0\",\"undefined\":\"save\"}', '', '2023-03-01 19:14:07'),
(50, 1, 'products', 'http://localhost/work/sites/canda_erp/admin/products/save/3', 'update', '{\"pro_id\":3,\"cat_id\":3,\"brand_id\":2,\"pro_name\":{\"en\":\"vape type 2\"},\"pro_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"\"},\"pro_slider\":{\"slider_objs\":[],\"other_fields\":[]},\"pro_desc\":{\"en\":\"vape type 2\"},\"standard_box_quantity\":12,\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-28 19:39:38\",\"deleted_at\":null,\"pro_name_en\":\"vape type 2\",\"pro_desc_en\":\"vape type 2\",\"old_paths\":{\"pro_img_obj\":\"\"}}', '{}', '{\"cat_id\":\"3\",\"brand_id\":\"2\",\"standard_box_quantity\":\"12\",\"pro_name_en\":\"vape type 2\",\"pro_desc_en\":\"vape type 2\",\"json_values_of_sliderpro_slider_file\":\"[]\",\"new_variant_values_for_old_items_3\":[\"\"],\"new_variant_name\":[\"mg\"],\"new_variant_values_0\":[\"\",\"3\",\"6\"],\"undefined\":\"save\",\"pro_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"\\\"}\",\"pro_slider\":\"{\\\"slider_objs\\\":[],\\\"other_fields\\\":[]}\",\"pro_name\":\"{\\\"en\\\":\\\"vape type 2\\\"}\",\"pro_desc\":\"{\\\"en\\\":\\\"vape type 2\\\"}\"}', '', '2023-03-02 00:13:53'),
(51, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":14,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"7,8\",\"ps_selected_variant_type_values_text\":\"vanilia-3\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:13:53\",\"deleted_at\":null}', '{}', '{\"item_id\":\"14\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_box_barcode\",\"value\":\"87008843\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:22'),
(52, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":14,\"pro_id\":3,\"ps_box_barcode\":\"87008843\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"7,8\",\"ps_selected_variant_type_values_text\":\"vanilia-3\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:14:22\",\"deleted_at\":null}', '{}', '{\"item_id\":\"14\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_item_barcode\",\"value\":\"17786553\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:23'),
(53, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":15,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"7,9\",\"ps_selected_variant_type_values_text\":\"vanilia-6\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:13:53\",\"deleted_at\":null}', '{}', '{\"item_id\":\"15\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_box_barcode\",\"value\":\"74768733\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:24'),
(54, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":15,\"pro_id\":3,\"ps_box_barcode\":\"74768733\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"7,9\",\"ps_selected_variant_type_values_text\":\"vanilia-6\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:14:24\",\"deleted_at\":null}', '{}', '{\"item_id\":\"15\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_item_barcode\",\"value\":\"104026208\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:24'),
(55, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":13,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"6,9\",\"ps_selected_variant_type_values_text\":\"mango-6\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:13:53\",\"deleted_at\":null}', '{}', '{\"item_id\":\"13\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_item_barcode\",\"value\":\"88354623\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:25'),
(56, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":13,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"88354623\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"6,9\",\"ps_selected_variant_type_values_text\":\"mango-6\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:14:25\",\"deleted_at\":null}', '{}', '{\"item_id\":\"13\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_box_barcode\",\"value\":\"24690372\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:26'),
(57, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":12,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"6,8\",\"ps_selected_variant_type_values_text\":\"mango-3\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:13:53\",\"deleted_at\":null}', '{}', '{\"item_id\":\"12\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_item_barcode\",\"value\":\"35099284\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:27'),
(58, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":12,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"35099284\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"6,8\",\"ps_selected_variant_type_values_text\":\"mango-3\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:14:27\",\"deleted_at\":null}', '{}', '{\"item_id\":\"12\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_box_barcode\",\"value\":\"33898504\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:28'),
(59, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":11,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"5,9\",\"ps_selected_variant_type_values_text\":\"strwberry-6\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:13:53\",\"deleted_at\":null}', '{}', '{\"item_id\":\"11\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_item_barcode\",\"value\":\"56504809\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:28'),
(60, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":11,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"56504809\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"5,9\",\"ps_selected_variant_type_values_text\":\"strwberry-6\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:14:28\",\"deleted_at\":null}', '{}', '{\"item_id\":\"11\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_box_barcode\",\"value\":\"79334787\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:29'),
(61, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":10,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"5,8\",\"ps_selected_variant_type_values_text\":\"strwberry-3\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:13:53\",\"deleted_at\":null}', '{}', '{\"item_id\":\"10\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_item_barcode\",\"value\":\"24405235\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:30'),
(62, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":10,\"pro_id\":3,\"ps_box_barcode\":\"\",\"ps_item_barcode\":\"24405235\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"5,8\",\"ps_selected_variant_type_values_text\":\"strwberry-3\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-03-02 00:13:53\",\"updated_at\":\"2023-03-02 00:14:30\",\"deleted_at\":null}', '{}', '{\"item_id\":\"10\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_box_barcode\",\"value\":\"40119174\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:31');
INSERT INTO `employee_action_log` (`id`, `user_id`, `module`, `action_url`, `action_type`, `old_obj`, `request_headers`, `request_body`, `log_desc`, `logged_at`) VALUES
(63, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/admin/products-sku/edit-sku', 'general-self-edit', '{\"ps_id\":9,\"pro_id\":3,\"ps_box_barcode\":\"19132061\",\"ps_item_barcode\":\"49160887\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"7\",\"ps_selected_variant_type_values_text\":\"vanilia\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:25:38\",\"deleted_at\":null}', '{}', '{\"item_id\":\"9\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"ps_item_barcode\",\"value\":\"73569172\",\"input_type\":\"text\",\"row_primary_col\":\"ps_id\",\"func_after_edit\":\"generate_barcode\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products-sku\\/edit-sku\"}', '', '2023-03-02 00:14:32'),
(64, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/new_accept_item', 'update', '{\"ps_id\":9,\"pro_id\":3,\"ps_box_barcode\":\"19132061\",\"ps_item_barcode\":\"73569172\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"7\",\"ps_selected_variant_type_values_text\":\"vanilia\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-03-02 00:14:32\",\"deleted_at\":null}', '{}', '{\"item_id\":\"9\",\"accept_url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/new_accept_item\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"is_active\",\"accept\":\"0\",\"acceptersdata\":\"[\\\"<i class=\'fa fa-times\'><\\\\\\/i>\\\",\\\"<i class=\'fa fa-check\'><\\\\\\/i>\\\"]\",\"item_primary_col\":\"ps_id\",\"display_block\":\"\",\"func_after_edit\":\"\"}', '', '2023-03-02 00:14:34'),
(65, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/new_accept_item', 'update', '{\"ps_id\":8,\"pro_id\":3,\"ps_box_barcode\":\"xd-123\",\"ps_item_barcode\":\"94935400\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"6\",\"ps_selected_variant_type_values_text\":\"mango\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:25:36\",\"deleted_at\":null}', '{}', '{\"item_id\":\"8\",\"accept_url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/new_accept_item\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"is_active\",\"accept\":\"0\",\"acceptersdata\":\"[\\\"<i class=\'fa fa-times\'><\\\\\\/i>\\\",\\\"<i class=\'fa fa-check\'><\\\\\\/i>\\\"]\",\"item_primary_col\":\"ps_id\",\"display_block\":\"\",\"func_after_edit\":\"\"}', '', '2023-03-02 00:14:36'),
(66, 1, 'product_skus', 'http://localhost/work/sites/canda_erp/new_accept_item', 'update', '{\"ps_id\":7,\"pro_id\":3,\"ps_box_barcode\":\"13909672\",\"ps_item_barcode\":\"11740986\",\"ps_item_retailer_price\":\"0.00\",\"ps_item_wholesaler_price\":\"0.00\",\"ps_box_retailer_price\":\"0.00\",\"ps_box_wholesaler_price\":\"0.00\",\"ps_selected_variant_type_values\":\"5\",\"ps_selected_variant_type_values_text\":\"strwberry\",\"is_active\":1,\"use_default_images\":1,\"ps_img_obj\":\"\",\"ps_slider\":\"\",\"created_at\":\"2023-02-27 19:18:54\",\"updated_at\":\"2023-02-27 19:25:36\",\"deleted_at\":null}', '{}', '{\"item_id\":\"7\",\"accept_url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/new_accept_item\",\"table_name\":\"App\\\\models\\\\product\\\\product_skus_m\",\"field_name\":\"is_active\",\"accept\":\"0\",\"acceptersdata\":\"[\\\"<i class=\'fa fa-times\'><\\\\\\/i>\\\",\\\"<i class=\'fa fa-check\'><\\\\\\/i>\\\"]\",\"item_primary_col\":\"ps_id\",\"display_block\":\"\",\"func_after_edit\":\"\"}', '', '2023-03-02 00:14:38'),
(67, 1, 'branch_prices', 'http://localhost/work/sites/canda_erp/admin/branches-prices/update-branch-prices', 'general-self-edit', '{\"id\":9,\"branch_id\":1,\"pro_id\":3,\"sku_id\":7,\"online_item_price\":\"0.00\",\"online_box_price\":\"0.00\",\"item_retailer_price\":\"0.00\",\"item_wholesaler_price\":\"0.00\",\"box_retailer_price\":\"0.00\",\"box_wholesaler_price\":\"0.00\",\"is_active\":1,\"created_at\":\"2023-02-27 19:25:41\",\"updated_at\":\"2023-02-27 19:25:41\",\"deleted_at\":null}', '{}', '{\"item_id\":\"9\",\"table_name\":\"App\\\\models\\\\branch\\\\branch_prices_m\",\"field_name\":\"online_item_price\",\"value\":\"100\",\"input_type\":\"number\",\"row_primary_col\":\"id\",\"func_after_edit\":\"\",\"url\":\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/branches-prices\\/update-branch-prices\"}', '', '2023-03-02 00:15:33'),
(68, 3, 'transactions-log', 'http://localhost/work/sites/canda_erp/admin/transactions-log/deposit-money/wholesaler%20client/27', 'deposit-Money', '{\"wallet_id\":27,\"wallet_amount\":\"-446.40\",\"created_at\":\"2023-02-12 11:50:17\",\"updated_at\":\"2023-03-01 20:14:07\",\"deleted_at\":null}', '{}', '{\"money_amount\":\"446.40\",\"admin_notes\":\"dsa\",\"undefined\":\"Save\"}', '', '2023-03-02 05:34:41'),
(69, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"7\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"2\"],\"item_total_cost\":[\"223.2\"],\"total_items_cost\":\"446.40\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"446.40\",\"paid_amount\":\"0\",\"undefined\":\"save\"}', '', '2023-03-02 05:35:41'),
(70, 3, 'client-orders', 'http://localhost/work/sites/canda_erp/admin/clients-orders/add-order', 'add-Order', '\"\"', '{}', '{\"client_id\":\"7\",\"order_status\":\"done\",\"pick_up_date\":\"\",\"product_sku\":[\"2\"],\"item_type\":[\"item\"],\"order_quantity\":[\"2\"],\"item_total_cost\":[\"223.2\"],\"total_items_cost\":\"446.40\",\"selected_redeem\":\"0\",\"available_amount_in_wallet\":\"0.00\",\"gift_card\":\"\",\"coupon_code\":\"\",\"cash_paid_amount\":\"\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"total_cost\":\"446.40\",\"paid_amount\":\"0\",\"undefined\":\"save\"}', '', '2023-03-02 06:23:32'),
(71, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save', '', '\"\"', '{}', '{\"is_active\":\"0\",\"template_title\":\"Sint molestiae quia\",\"template_text_color\":\"Dignissimos consequa\",\"undefined\":\"save\",\"small_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/52a434965609787aa6e4e3a4d5436fcc.png\\\"}\",\"small_img_obj_file\":{}}', '', '2023-03-12 06:47:42'),
(72, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":null,\"template_text_positions\":\"\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":0,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-12 06:47:42\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"\"}}', '{}', '{\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_text_color\":\"Dignissimos consequa\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/212ddc3e8e24d919f1754407bfe220d5.png\\\"}\",\"template_bg_img_obj_file\":{}}', '', '2023-03-12 06:48:24'),
(73, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/212ddc3e8e24d919f1754407bfe220d5.png\"},\"template_text_positions\":\"\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-12 06:48:24\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/212ddc3e8e24d919f1754407bfe220d5.png\"}}', '{}', '{\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_text_color\":\"Dignissimos consequa\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\",\"template_bg_img_obj_file\":{}}', '', '2023-03-12 09:46:18'),
(74, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"},\"template_text_positions\":\"\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-12 09:46:18\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}}', '{}', '{\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_text_color\":\"Dignissimos consequa\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"undefined\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\"}', '', '2023-03-12 10:03:20'),
(75, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"},\"template_text_positions\":\"\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-12 09:46:18\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}}', '{}', '{\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_text_color\":\"Dignissimos consequa\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"undefined\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\"}', '', '2023-03-12 10:03:20'),
(76, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"},\"template_text_positions\":\"\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-12 09:46:18\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 354px; top: 71px;\\\",\\\"expiration_date\\\":\\\"left: 170px; top: 102px;\\\",\\\"card_number\\\":\\\"left: 27px; top: 190px;\\\",\\\"card_amount\\\":\\\"left: 114px; top: 42px;\\\"}\",\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_text_color\":\"Dignissimos consequa\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\"}', '', '2023-03-12 11:02:53'),
(77, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"},\"template_text_positions\":\"{\\\"title\\\":\\\"left: 354px; top: 71px;\\\",\\\"expiration_date\\\":\\\"left: 170px; top: 102px;\\\",\\\"card_number\\\":\\\"left: 27px; top: 190px;\\\",\\\"card_amount\\\":\\\"left: 114px; top: 42px;\\\"}\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-12 11:02:53\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"card_amount\\\":\\\"left: 428.986px; top: 36.9745px; color: rgb(255, 0, 0);\\\"}\",\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_text_color\":\"Dignissimos consequa\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\"}', '', '2023-03-12 11:04:38'),
(78, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save', '', '\"\"', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 75.9432px; top: 18.9347px; font-size: 35px; color: rgb(218, 171, 41);\\\",\\\"expiration_date\\\":\\\"left: 387.957px; top: 201px; color: rgb(255, 255, 255); font-size: 36px;\\\",\\\"card_number\\\":\\\"left: 173.98px; top: 186.974px; color: rgb(255, 255, 255); font-size: 18px;\\\",\\\"card_amount\\\":\\\"left: 317.977px; top: 43.9687px; color: rgb(255, 255, 255); font-size: 46px;\\\"}\",\"is_active\":\"1\",\"template_title\":\"new gift card\",\"template_text_color\":\"sdda\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\\\"}\",\"template_bg_img_obj_file\":{}}', '', '2023-03-13 04:42:20'),
(79, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/3', 'update', '{\"template_id\":3,\"template_title\":\"new gift card\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\"},\"template_text_positions\":\"{\\\"title\\\":\\\"left: 75.9432px; top: 18.9347px; font-size: 35px; color: rgb(218, 171, 41);\\\",\\\"expiration_date\\\":\\\"left: 387.957px; top: 201px; color: rgb(255, 255, 255); font-size: 36px;\\\",\\\"card_number\\\":\\\"left: 173.98px; top: 186.974px; color: rgb(255, 255, 255); font-size: 18px;\\\",\\\"card_amount\\\":\\\"left: 317.977px; top: 43.9687px; color: rgb(255, 255, 255); font-size: 46px;\\\"}\",\"template_text_color\":\"sdda\",\"is_active\":1,\"created_at\":\"2023-03-13 04:42:20\",\"updated_at\":\"2023-03-13 04:42:20\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 76.929px; top: 22.9261px; font-size: 32px; color: rgb(218, 171, 41);\\\",\\\"expiration_date\\\":\\\"left: 370.955px; top: 197.994px; color: rgb(255, 255, 255); font-size: 36px;\\\",\\\"card_number\\\":\\\"left: 173.969px; top: 187.955px; color: rgb(255, 255, 255); font-size: 16px;\\\",\\\"card_amount\\\":\\\"left: 301.969px; top: 45.9631px; color: rgb(255, 255, 255); font-size: 46px;\\\"}\",\"is_active\":\"1\",\"template_title\":\"new gift card\",\"template_text_color\":\"sdda\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\\\"}\"}', '', '2023-03-13 04:42:47'),
(80, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/3', 'update', '{\"template_id\":3,\"template_title\":\"new gift card\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\"},\"template_text_positions\":\"{\\\"title\\\":\\\"left: 76.929px; top: 22.9261px; font-size: 32px; color: rgb(218, 171, 41);\\\",\\\"expiration_date\\\":\\\"left: 370.955px; top: 197.994px; color: rgb(255, 255, 255); font-size: 36px;\\\",\\\"card_number\\\":\\\"left: 173.969px; top: 187.955px; color: rgb(255, 255, 255); font-size: 16px;\\\",\\\"card_amount\\\":\\\"left: 301.969px; top: 45.9631px; color: rgb(255, 255, 255); font-size: 46px;\\\"}\",\"template_text_color\":\"sdda\",\"is_active\":1,\"created_at\":\"2023-03-13 04:42:20\",\"updated_at\":\"2023-03-13 04:42:47\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 75.9176px; top: 21.9119px; font-size: 32px; color: rgb(218, 171, 41);\\\",\\\"expiration_date\\\":\\\"left: 370.955px; top: 197.994px; color: rgb(255, 255, 255); font-size: 36px;\\\",\\\"card_number\\\":\\\"left: 173.969px; top: 187.955px; font-size: 16px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 172px; top: 219px;\\\",\\\"card_amount\\\":\\\"left: 301.969px; top: 45.9631px; color: rgb(255, 255, 255); font-size: 46px;\\\"}\",\"is_active\":\"1\",\"template_title\":\"new gift card\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\\\"}\"}', '', '2023-03-13 04:48:12'),
(81, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"},\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"card_amount\\\":\\\"left: 428.986px; top: 36.9745px; color: rgb(255, 0, 0);\\\"}\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-12 11:04:38\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 117px; top: 76px;\\\",\\\"card_amount\\\":\\\"left: 428.986px; top: 36.9745px; color: rgb(255, 0, 0);\\\"}\",\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\"}', '', '2023-03-13 05:10:21'),
(82, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"},\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 117px; top: 76px;\\\",\\\"card_amount\\\":\\\"left: 428.986px; top: 36.9745px; color: rgb(255, 0, 0);\\\"}\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-13 05:10:21\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 117px; top: 76px; width: 210px; filter: invert(100%);\\\",\\\"card_amount\\\":\\\"left: 428.986px; top: 36.9745px; color: rgb(255, 0, 0);\\\"}\",\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\"}', '', '2023-03-13 05:12:22'),
(83, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"},\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 117px; top: 76px; width: 210px; filter: invert(100%);\\\",\\\"card_amount\\\":\\\"left: 428.986px; top: 36.9745px; color: rgb(255, 0, 0);\\\"}\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-13 05:12:22\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 152.989px; top: 79.9943px; width: 210px; filter: invert(100%);\\\",\\\"card_amount\\\":\\\"left: 428.986px; top: 36.9745px; color: rgb(255, 0, 0);\\\"}\",\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\"}', '', '2023-03-13 05:12:28'),
(84, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"},\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 152.989px; top: 79.9943px; width: 210px; filter: invert(100%);\\\",\\\"card_amount\\\":\\\"left: 428.986px; top: 36.9745px; color: rgb(255, 0, 0);\\\"}\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-13 05:12:28\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 154.983px; top: 77.9858px; width: 210px; filter: invert(100%);\\\",\\\"card_amount\\\":\\\"left: 428.986px; top: 36.9745px; color: rgb(255, 0, 0);\\\"}\",\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\"}', '', '2023-03-13 05:20:15'),
(85, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"},\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 154.983px; top: 77.9858px; width: 210px; filter: invert(100%);\\\",\\\"card_amount\\\":\\\"left: 428.986px; top: 36.9745px; color: rgb(255, 0, 0);\\\"}\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-13 05:20:15\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 154.983px; top: 77.9858px; width: 210px; filter: invert(100%);\\\",\\\"card_amount\\\":\\\"left: 405.972px; top: 27.9602px; color: rgb(255, 255, 255); font-size: 31px;\\\"}\",\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\"}', '', '2023-03-13 05:20:46'),
(86, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/2', 'update', '{\"template_id\":2,\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"},\"template_text_positions\":\"{\\\"title\\\":\\\"left: 33.9886px; top: 29.9915px; font-size: 22px;\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 154.983px; top: 77.9858px; width: 210px; filter: invert(100%);\\\",\\\"card_amount\\\":\\\"left: 405.972px; top: 27.9602px; color: rgb(255, 255, 255); font-size: 31px;\\\"}\",\"template_text_color\":\"Dignissimos consequa\",\"is_active\":1,\"created_at\":\"2023-03-12 06:47:42\",\"updated_at\":\"2023-03-13 05:20:46\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 32.9773px; top: 29.9858px; font-size: 22px; color: rgb(255, 255, 255);\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 164.972px; top: 80.983px; width: 210px; filter: invert(100%);\\\",\\\"card_amount\\\":\\\"left: 405.972px; top: 27.9602px; color: rgb(255, 255, 255); font-size: 31px;\\\"}\",\"is_active\":\"1\",\"template_title\":\"Sint molestiae quia\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/5ce452c1b016932af2fc488809742128.png\\\"}\"}', '', '2023-03-13 05:21:10'),
(87, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/1', 'update', '{\"template_id\":1,\"template_title\":\"main template\",\"template_bg_img_obj\":null,\"template_text_positions\":\"\",\"template_text_color\":\"\",\"is_active\":0,\"created_at\":null,\"updated_at\":null,\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 32.9773px; top: 29.9858px; font-size: 22px; color: rgb(255, 255, 255);\\\",\\\"expiration_date\\\":\\\"left: 37.9886px; top: 209.986px;\\\",\\\"card_number\\\":\\\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 164.972px; top: 80.983px; width: 210px; filter: invert(100%);\\\",\\\"card_amount\\\":\\\"left: 405.972px; top: 27.9602px; color: rgb(255, 255, 255); font-size: 31px;\\\"}\",\"is_active\":\"1\",\"template_title\":\"main template\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/fad84b154b35c85a648cb99b38546dfd.png\\\"}\",\"template_bg_img_obj_file\":{}}', '', '2023-03-13 06:10:11'),
(88, 3, 'gift-cards', 'http://localhost/work/sites/canda_erp/admin/gift-cards/add-card', 'add-Card', '\"\"', '{}', '{\"card_template_id\":\"3\",\"branch_id\":1,\"client_id\":\"7\",\"card_title\":\"happy fire date\",\"card_price\":\"500\",\"available_amount_in_wallet\":\"0.00\",\"cash_paid_amount\":\"500\",\"debit_card_paid_amount\":\"\",\"credit_card_paid_amount\":\"\",\"cheque_paid_amount\":\"\",\"undefined\":\"save\",\"wallet_id\":32,\"employee_id\":3,\"register_id\":1,\"register_session_id\":1,\"card_expiration_date\":\"2024-03-16T13:25:48.945125Z\",\"card_unique_number\":4956284274741563}', '', '2023-03-16 15:25:48'),
(89, 1, 'gift_card_templates', 'http://localhost/work/sites/canda_erp/admin/gift-card-templates/save/3', 'update', '{\"template_id\":3,\"template_title\":\"new gift card\",\"template_bg_img_obj\":{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\"},\"template_text_positions\":\"{\\\"title\\\":\\\"left: 75.9176px; top: 21.9119px; font-size: 32px; color: rgb(218, 171, 41);\\\",\\\"expiration_date\\\":\\\"left: 370.955px; top: 197.994px; color: rgb(255, 255, 255); font-size: 36px;\\\",\\\"card_number\\\":\\\"left: 173.969px; top: 187.955px; font-size: 16px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 172px; top: 219px;\\\",\\\"card_amount\\\":\\\"left: 301.969px; top: 45.9631px; color: rgb(255, 255, 255); font-size: 46px;\\\"}\",\"template_text_color\":\"sdda\",\"is_active\":1,\"created_at\":\"2023-03-13 04:42:20\",\"updated_at\":\"2023-03-13 04:48:12\",\"deleted_at\":null,\"old_paths\":{\"template_bg_img_obj\":\"uploads\\/all_imgs\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\"}}', '{}', '{\"template_text_positions\":\"{\\\"title\\\":\\\"left: 120.909px; top: 251.903px; font-size: 32px; color: rgb(218, 171, 41);\\\",\\\"expiration_date\\\":\\\"left: 371.952px; top: 196.983px; color: rgb(255, 255, 255); font-size: 36px;\\\",\\\"card_number\\\":\\\"left: 173.969px; top: 187.955px; font-size: 16px; color: rgb(255, 255, 255);\\\",\\\"barcode\\\":\\\"left: 172px; top: 219px;\\\",\\\"card_amount\\\":\\\"left: 304.96px; top: 49.9517px; color: rgb(255, 255, 255); font-size: 46px;\\\"}\",\"is_active\":\"1\",\"template_title\":\"new gift card\",\"template_bg_img_obj_filetitle\":\"\",\"template_bg_img_obj_filealt\":\"\",\"undefined\":\"save\",\"template_bg_img_obj\":\"{\\\"alt\\\":\\\"\\\",\\\"title\\\":\\\"\\\",\\\"path\\\":\\\"uploads\\\\\\/all_imgs\\\\\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\\\"}\"}', '', '2023-03-16 15:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `employee_details`
--

CREATE TABLE `employee_details` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_working_days` text NOT NULL,
  `employee_required_working_hours_per_day` int(11) NOT NULL,
  `hour_price` decimal(10,2) NOT NULL,
  `employee_overtime_hour_rate` decimal(10,2) NOT NULL COMMENT 'it could be 1.5 or 2',
  `employee_vacation_hour_rate` int(11) NOT NULL COMMENT 'if the emp works at vacation then his hour should be like 1.5 normal hour',
  `employee_should_start_work_at` time NOT NULL,
  `employee_should_end_work_at` time NOT NULL,
  `employee_sick_vacation_max_requests` int(11) NOT NULL,
  `employee_delay_requests_max_requests` int(11) NOT NULL,
  `employee_early_requests_max_requests` int(11) NOT NULL,
  `employee_vacation_max_requests` int(11) NOT NULL,
  `create_order_pin_number` varchar(250) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee_details`
--

INSERT INTO `employee_details` (`id`, `employee_id`, `employee_working_days`, `employee_required_working_hours_per_day`, `hour_price`, `employee_overtime_hour_rate`, `employee_vacation_hour_rate`, `employee_should_start_work_at`, `employee_should_end_work_at`, `employee_sick_vacation_max_requests`, `employee_delay_requests_max_requests`, `employee_early_requests_max_requests`, `employee_vacation_max_requests`, `create_order_pin_number`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '[\"tuesday\",\"friday\"]', 1, '85.00', '8.00', 17, '03:18:00', '03:18:00', 44, 11, 38, 16, '123', '2022-10-31 14:18:40', '2022-10-31 14:18:40', NULL),
(2, 3, '[\"wednesday\"]', 13, '88.00', '12.00', 42, '02:41:00', '02:41:00', 31, 85, 1, 59, '', '2022-12-01 13:41:32', '2022-12-01 16:25:30', NULL),
(3, 4, '[\"sunday\",\"monday\",\"tuesday\",\"wednesday\"]', 26, '62.00', '11.00', 35, '01:03:00', '01:03:00', 70, 78, 73, 6, '', '2022-12-07 00:03:30', '2022-12-07 00:03:30', NULL),
(4, 7, '[\"sunday\",\"monday\",\"thursday\",\"friday\"]', 2, '224.00', '37.00', 80, '05:44:00', '05:44:00', 89, 85, 35, 78, '', '2023-02-21 15:45:21', '2023-02-21 15:45:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_login_logout`
--

CREATE TABLE `employee_login_logout` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `work_date` date NOT NULL,
  `login_logout_times` text NOT NULL,
  `late_time_hours` time NOT NULL,
  `early_leave_hours` time NOT NULL,
  `should_work_hours` time NOT NULL,
  `working_hours` time NOT NULL,
  `remain_hours` time NOT NULL,
  `overtime_hours` time NOT NULL,
  `is_work_day` tinyint(1) NOT NULL COMMENT 'علشان نشوف ده يوم عمل ليه وﻻ اجازة',
  `work_day_is_general_holiday` tinyint(1) NOT NULL,
  `work_day_is_demanded_holiday` tinyint(1) NOT NULL COMMENT 'يوم تم طلبه اجازة',
  `work_day_has_early_leave` tinyint(1) NOT NULL,
  `work_day_has_delay_request` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee_login_logout`
--

INSERT INTO `employee_login_logout` (`id`, `employee_id`, `work_date`, `login_logout_times`, `late_time_hours`, `early_leave_hours`, `should_work_hours`, `working_hours`, `remain_hours`, `overtime_hours`, `is_work_day`, `work_day_is_general_holiday`, `work_day_is_demanded_holiday`, `work_day_has_early_leave`, `work_day_has_delay_request`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, '2022-12-01', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(2, 3, '2022-12-02', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(3, 3, '2022-12-03', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(4, 3, '2022-12-04', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(5, 3, '2022-12-05', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(6, 3, '2022-12-06', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(7, 3, '2022-12-07', '', '00:00:00', '00:00:00', '13:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(8, 3, '2022-12-08', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(9, 3, '2022-12-09', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(10, 3, '2022-12-10', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(11, 3, '2022-12-11', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(12, 3, '2022-12-12', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(13, 3, '2022-12-13', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(14, 3, '2022-12-14', '', '00:00:00', '00:00:00', '13:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(15, 3, '2022-12-15', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(16, 3, '2022-12-16', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(17, 3, '2022-12-17', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(18, 3, '2022-12-18', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(19, 3, '2022-12-19', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(20, 3, '2022-12-20', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(21, 3, '2022-12-21', '', '00:00:00', '00:00:00', '13:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(22, 3, '2022-12-22', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(23, 3, '2022-12-23', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(24, 3, '2022-12-24', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(25, 3, '2022-12-25', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(26, 3, '2022-12-26', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(27, 3, '2022-12-27', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(28, 3, '2022-12-28', '', '00:00:00', '00:00:00', '13:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 0, 0, 0, 0, '2022-12-29 12:15:50', NULL, NULL),
(29, 3, '2022-12-29', '[{\"login\":\"14:15:56\"}]', '11:34:56', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2022-12-29 12:15:50', '2022-12-29 12:15:56', NULL),
(30, 3, '2023-01-01', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-01-04 07:26:22', NULL, NULL),
(31, 3, '2023-01-02', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-01-04 07:26:22', NULL, NULL),
(32, 3, '2023-01-03', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-01-04 07:26:22', NULL, NULL),
(33, 3, '2023-01-04', '[{\"login\":\"09:26:49\"}]', '06:45:49', '00:00:00', '13:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 0, 0, 0, 0, '2023-01-04 07:26:22', '2023-01-04 07:26:49', NULL),
(34, 3, '2023-01-05', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-01-05 10:46:21', NULL, NULL),
(35, 3, '2023-02-01', '', '00:00:00', '00:00:00', '13:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(36, 3, '2023-02-02', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(37, 3, '2023-02-03', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(38, 3, '2023-02-04', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(39, 3, '2023-02-05', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(40, 3, '2023-02-06', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(41, 3, '2023-02-07', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(42, 3, '2023-02-08', '', '00:00:00', '00:00:00', '13:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(43, 3, '2023-02-09', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(44, 3, '2023-02-10', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(45, 3, '2023-02-11', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(46, 3, '2023-02-12', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(47, 3, '2023-02-13', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(48, 3, '2023-02-14', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(49, 3, '2023-02-15', '', '00:00:00', '00:00:00', '13:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(50, 3, '2023-02-16', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(51, 3, '2023-02-17', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(52, 3, '2023-02-18', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(53, 3, '2023-02-19', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(54, 3, '2023-02-20', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(55, 3, '2023-02-21', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(56, 3, '2023-02-22', '', '00:00:00', '00:00:00', '13:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL),
(57, 3, '2023-02-23', '', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 0, 0, 0, '2023-02-23 19:41:02', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_tasks`
--

CREATE TABLE `employee_tasks` (
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `task_title` varchar(500) NOT NULL,
  `task_desc` longtext NOT NULL,
  `task_deadline` datetime NOT NULL,
  `task_status` varchar(50) NOT NULL COMMENT 'pending, working, under_review, done',
  `task_slider` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee_tasks`
--

INSERT INTO `employee_tasks` (`task_id`, `employee_id`, `task_title`, `task_desc`, `task_deadline`, `task_status`, `task_slider`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'Numquam ut ut in omn', '<p>Qui est id digniss</p>', '2022-11-30 01:14:00', 'pending', '{\"slider_objs\":[],\"other_fields\":[]}', '2022-11-04 00:15:02', '2022-11-04 00:28:04', '2022-11-04 00:28:04'),
(2, 2, 'dasda', '<p>dadaad</p>', '2022-12-01 01:20:00', 'pending', '{\"slider_objs\":[{\"id\":1,\"path\":\"uploads\\/e13a6fd8af2ed17b07f99b25d53e1a45.png\",\"alt\":\"\",\"title\":\"\"},{\"id\":2,\"path\":\"uploads\\/3c701b785b72bcef96192f111cb21767.png\",\"alt\":\"\",\"title\":\"\"},{\"id\":3,\"path\":\"uploads\\/b3f101497950889c08eea75c93c7365d.png\",\"alt\":\"\",\"title\":\"\"},{\"id\":4,\"path\":\"uploads\\/b605aa35f4796e972f1dc35de0542a6b.png\",\"alt\":\"\",\"title\":\"\"},{\"id\":5,\"path\":\"uploads\\/3d21595748008b6ae056c10306eeabae.png\",\"alt\":\"\",\"title\":\"\"},{\"id\":6,\"path\":\"uploads\\/38ab70b728c4113a7aa496542b8e932e.png\",\"alt\":\"\",\"title\":\"\"},{\"id\":7,\"path\":\"uploads\\/628c64eeb60697037ae8e86bb4d55247.png\",\"alt\":\"\",\"title\":\"\"}],\"other_fields\":[]}', '2022-11-04 00:20:16', '2022-11-04 00:28:07', '2022-11-04 00:28:07'),
(3, 2, 'Quasi itaque quos in', '<p>Quasi itaque quos inQuasi itaque quos inQuasi itaque quos inQuasi itaque quos inQuasi itaque quos inQuasi itaque quos inQuasi itaque quos inQuasi itaque quos in</p>', '2022-11-30 01:30:00', 'pending', '{\"slider_objs\":[{\"id\":1,\"path\":\"uploads\\/98ea3081a313066eb197aea568834fd1.jpg\",\"alt\":\"\",\"title\":\"\"},{\"id\":2,\"path\":\"uploads\\/6d0d998dfb87730e2eee99585ac13046.jpg\",\"alt\":\"\",\"title\":\"\"},{\"id\":3,\"path\":\"uploads\\/d9a1b4018be2dde20f53e1522f253f9b.jpg\",\"alt\":\"\",\"title\":\"\"},{\"id\":4,\"path\":\"uploads\\/69dcb37dac3de3842c55d91226ddc9ad.jpg\",\"alt\":\"\",\"title\":\"\"},{\"id\":5,\"path\":\"uploads\\/0df4c3154d9fca1f2f9163714704507d.jpg\",\"alt\":\"\",\"title\":\"\"}],\"other_fields\":[]}', '2022-11-04 00:30:50', '2022-11-04 00:30:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_task_comments`
--

CREATE TABLE `employee_task_comments` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_comment` text NOT NULL,
  `comment_file` varchar(300) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee_task_comments`
--

INSERT INTO `employee_task_comments` (`id`, `task_id`, `user_id`, `task_comment`, `comment_file`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '<p>dasdaadda</p>', '{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/d93ddcd10724b23863ab8f7dad779614.png\"}', '2022-11-04 00:15:20', '2022-11-04 00:15:20', NULL),
(2, 1, 1, '<p>asdaadada</p>', '{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/006b947e90669a7ff4917844d7901927.png\"}', '2022-11-04 00:15:26', '2022-11-04 00:15:26', NULL),
(3, 1, 1, '<p>dasda</p>', '{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/7b3371a5bee6bd54169c39b8902344f3.png\"}', '2022-11-04 00:19:39', '2022-11-04 00:19:39', NULL),
(4, 3, 1, '<p>dasda aada</p>', '{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/a8e39b03464043d7ef0721f1be59175b.png\"}', '2022-11-04 00:31:16', '2022-11-04 00:31:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_warnings`
--

CREATE TABLE `employee_warnings` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `warning_desc` text NOT NULL,
  `warning_img_obj` varchar(300) NOT NULL,
  `warning_is_received` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` int(11) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` text NOT NULL,
  `exception` text NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, 'database', 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:17:\\\"info@auctions.com\\\";s:7:\\\"subject\\\";s:90:\\\"--canda-pos-Website-- source[failed-job] critical issue need to be fixed #2022-10-02 21:44\\\";s:4:\\\"body\\\";s:11800:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_pos\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_pos\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda-pos-Website-- source[failed-job] critical issue need to be fixed #2022-10-02 21:44\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Symfony\\\\Component\\\\Debug\\\\Exception\\\\FatalThrowableError: Argument 1 passed to App\\\\Listeners\\\\InventoryProducts\\\\RunAfterAddBrokenProductToInventory::handle() must be an instance of App\\\\Events\\\\InventoryProducts\\\\AddInvalidEntryProduct, instance of App\\\\Events\\\\InventoryProducts\\\\AddBrokenProductToInventory given, called in \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/CallQueuedListener.php on line 92 in \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/app\\/Listeners\\/InventoryProducts\\/RunAfterAddBrokenProductToInventory.php:14\\nStack trace:\\n#0 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Events\\/CallQueuedListener.php(92): App\\\\Listeners\\\\InventoryProducts\\\\RunAfterAddBrokenProductToInventory->handle()\\n#1 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Illuminate\\\\Events\\\\CallQueuedListener->handle()\\n#2 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(37): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#3 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(93): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#4 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(37): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#5 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(590): Illuminate\\\\Container\\\\BoundMethod::call()\\n#6 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(94): Illuminate\\\\Container\\\\Container->call()\\n#7 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(130): Illuminate\\\\Bus\\\\Dispatcher->Illuminate\\\\Bus\\\\{closure}()\\n#8 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(105): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#9 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Bus\\/Dispatcher.php(98): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#10 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(83): Illuminate\\\\Bus\\\\Dispatcher->dispatchNow()\\n#11 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(130): Illuminate\\\\Queue\\\\CallQueuedHandler->Illuminate\\\\Queue\\\\{closure}()\\n#12 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Pipeline\\/Pipeline.php(105): Illuminate\\\\Pipeline\\\\Pipeline->Illuminate\\\\Pipeline\\\\{closure}()\\n#13 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(85): Illuminate\\\\Pipeline\\\\Pipeline->then()\\n#14 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/CallQueuedHandler.php(59): Illuminate\\\\Queue\\\\CallQueuedHandler->dispatchThroughMiddleware()\\n#15 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Jobs\\/Job.php(88): Illuminate\\\\Queue\\\\CallQueuedHandler->call()\\n#16 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Worker.php(368): Illuminate\\\\Queue\\\\Jobs\\\\Job->fire()\\n#17 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Worker.php(314): Illuminate\\\\Queue\\\\Worker->process()\\n#18 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Worker.php(267): Illuminate\\\\Queue\\\\Worker->runJob()\\n#19 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Console\\/WorkCommand.php(112): Illuminate\\\\Queue\\\\Worker->runNextJob()\\n#20 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Console\\/WorkCommand.php(96): Illuminate\\\\Queue\\\\Console\\\\WorkCommand->runWorker()\\n#21 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Illuminate\\\\Queue\\\\Console\\\\WorkCommand->handle()\\n#22 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(37): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#23 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(93): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#24 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(37): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#25 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(590): Illuminate\\\\Container\\\\BoundMethod::call()\\n#26 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(134): Illuminate\\\\Container\\\\Container->call()\\n#27 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/symfony\\/console\\/Command\\/Command.php(255): Illuminate\\\\Console\\\\Command->execute()\\n#28 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(121): Symfony\\\\Component\\\\Console\\\\Command\\\\Command->run()\\n#29 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/symfony\\/console\\/Application.php(1009): Illuminate\\\\Console\\\\Command->run()\\n#30 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/symfony\\/console\\/Application.php(273): Symfony\\\\Component\\\\Console\\\\Application->doRunCommand()\\n#31 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/symfony\\/console\\/Application.php(149): Symfony\\\\Component\\\\Console\\\\Application->doRun()\\n#32 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Application.php(93): Symfony\\\\Component\\\\Console\\\\Application->run()\\n#33 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Console\\/Kernel.php(131): Illuminate\\\\Console\\\\Application->run()\\n#34 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/artisan(37): Illuminate\\\\Foundation\\\\Console\\\\Kernel->handle()\\n#35 {main}\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2022 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\sendEmail has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:632\nStack trace:\n#0 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(446): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(358): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts()\n#2 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(314): Illuminate\\Queue\\Worker->process()\n#3 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(267): Illuminate\\Queue\\Worker->runJob()\n#4 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob()\n#5 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#6 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#9 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#10 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Container.php(590): Illuminate\\Container\\BoundMethod::call()\n#11 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(134): Illuminate\\Container\\Container->call()\n#12 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute()\n#13 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#14 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(1009): Illuminate\\Console\\Command->run()\n#15 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand()\n#16 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun()\n#17 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Application.php(93): Symfony\\Component\\Console\\Application->run()\n#18 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(131): Illuminate\\Console\\Application->run()\n#19 /var/www/html/work/sites/canda_erp/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#20 {main}', '2023-02-12 12:26:25'),
(2, 'database', 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:17:\\\"info@auctions.com\\\";s:7:\\\"subject\\\";s:87:\\\"--canda pos Website-- source[Website] critical issue need to be fixed #2022-10-03 20:57\\\";s:4:\\\"body\\\";s:12315:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda pos Website-- source[Website] critical issue need to be fixed #2022-10-03 20:57\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    File : \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/app\\/Events\\/InventoryProducts\\/AddProductToInventory.php - Line : 20 - Error : Argument 5 passed to App\\\\Events\\\\InventoryProducts\\\\AddProductToInventory::__construct() must be of the type int, string given, called in \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/app\\/Http\\/Controllers\\/admin\\/InventoryProductsController.php on line 112\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Time<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            October 3, 2022, 5:57 pm<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            IP Address<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            ::1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            URL<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/inventories-products\\/add-product<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Method<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            POST<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Headers<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            {&quot;host&quot;:[&quot;localhost&quot;],&quot;connection&quot;:[&quot;keep-alive&quot;],&quot;content-length&quot;:[&quot;783&quot;],&quot;sec-ch-ua&quot;:[&quot;\\\\&quot;Google Chrome\\\\&quot;;v=\\\\&quot;105\\\\&quot;, \\\\&quot;Not)A;Brand\\\\&quot;;v=\\\\&quot;8\\\\&quot;, \\\\&quot;Chromium\\\\&quot;;v=\\\\&quot;105\\\\&quot;&quot;],&quot;accept&quot;:[&quot;*\\\\\\/*&quot;],&quot;content-type&quot;:[&quot;multipart\\\\\\/form-data; boundary=----WebKitFormBoundaryXYhsWoBFk9bahNXN&quot;],&quot;x-requested-with&quot;:[&quot;XMLHttpRequest&quot;],&quot;sec-ch-ua-mobile&quot;:[&quot;?0&quot;],&quot;user-agent&quot;:[&quot;Mozilla\\\\\\/5.0 (X11; Linux x86_64) AppleWebKit\\\\\\/537.36 (KHTML, like Gecko) Chrome\\\\\\/105.0.0.0 Safari\\\\\\/537.36&quot;],&quot;sec-ch-ua-platform&quot;:[&quot;\\\\&quot;Linux\\\\&quot;&quot;],&quot;origin&quot;:[&quot;http:\\\\\\/\\\\\\/localhost&quot;],&quot;sec-fetch-site&quot;:[&quot;same-origin&quot;],&quot;sec-fetch-mode&quot;:[&quot;cors&quot;],&quot;sec-fetch-dest&quot;:[&quot;empty&quot;],&quot;referer&quot;:[&quot;http:\\\\\\/\\\\\\/localhost\\\\\\/work\\\\\\/sites\\\\\\/canda_erp\\\\\\/admin\\\\\\/inventories-products\\\\\\/add-product&quot;],&quot;accept-encoding&quot;:[&quot;gzip, deflate, br&quot;],&quot;accept-language&quot;:[&quot;en-US,en;q=0.9,ar-EG;q=0.8,ar;q=0.7&quot;],&quot;cookie&quot;:[&quot;blueorange_site_cookie=E55lyecbt8URnUpdBJYpMdw3885v1oV56V0qRJOK; blueorange22api_session=KJD13jlBMwHalZHIp1soVwLvW8PA6LFC2VY72PWI; canda_pos_session=ar4A8RtPyHX8eBMwXbHG53fBAgxJcGw8VLHn90EP; XSRF-TOKEN=eyJpdiI6InNnWFliOWRFUm1OTWV0Nk9QRndETmc9PSIsInZhbHVlIjoiKzdkcjdIbFNnU0xlN1pjcU5RN2MrQ3BXUVZhS1R3Y3FPQzNBc2xFd2M3YTB6RHdFZWdxVTY5eUZIb1VWbWsyQXFadE5RTmdDZ3NNSHZDcHQwV0tJUm5jU1k2RkVwWmt5Mnl2cGJlOXZNS1ZKdlNaTkgrSTR1a0lqUGRLK0NjRWwiLCJtYWMiOiI2MTM0ZjMyNTMzMGFhYTY1NmRhOGZkZjJkOGZiMGMxYzhlOTIzYzFjMjc2NTdlODExNzJiZGEzZjAyYTU2MGUxIn0%3D&quot;]}<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Accept-Header<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            *\\/*<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Token<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Session-ID<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Input<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2022 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\sendEmail has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:632\nStack trace:\n#0 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(446): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(358): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts()\n#2 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(314): Illuminate\\Queue\\Worker->process()\n#3 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(267): Illuminate\\Queue\\Worker->runJob()\n#4 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob()\n#5 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#6 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#9 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#10 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Container.php(590): Illuminate\\Container\\BoundMethod::call()\n#11 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(134): Illuminate\\Container\\Container->call()\n#12 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute()\n#13 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#14 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(1009): Illuminate\\Console\\Command->run()\n#15 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand()\n#16 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun()\n#17 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Application.php(93): Symfony\\Component\\Console\\Application->run()\n#18 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(131): Illuminate\\Console\\Application->run()\n#19 /var/www/html/work/sites/canda_erp/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#20 {main}', '2023-02-12 12:31:24');
INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(3, 'database', 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:17:\\\"info@auctions.com\\\";s:7:\\\"subject\\\";s:87:\\\"--canda pos Website-- source[Website] critical issue need to be fixed #2022-10-03 23:16\\\";s:4:\\\"body\\\";s:12018:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda pos Website-- source[Website] critical issue need to be fixed #2022-10-03 23:16\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    File : \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/app\\/models\\/product\\/products_m.php - Line : 44 - Error : Return value of App\\\\models\\\\product\\\\products_m::getAllProducts() must be an instance of Illuminate\\\\Support\\\\Collection, instance of Illuminate\\\\Pagination\\\\LengthAwarePaginator returned\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Time<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            October 3, 2022, 8:16 pm<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            IP Address<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            ::1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            URL<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/products?load_inner=true<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Method<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            GET<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Headers<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            {&quot;host&quot;:[&quot;localhost&quot;],&quot;connection&quot;:[&quot;keep-alive&quot;],&quot;sec-ch-ua&quot;:[&quot;\\\\&quot;Google Chrome\\\\&quot;;v=\\\\&quot;105\\\\&quot;, \\\\&quot;Not)A;Brand\\\\&quot;;v=\\\\&quot;8\\\\&quot;, \\\\&quot;Chromium\\\\&quot;;v=\\\\&quot;105\\\\&quot;&quot;],&quot;accept&quot;:[&quot;*\\\\\\/*&quot;],&quot;x-requested-with&quot;:[&quot;XMLHttpRequest&quot;],&quot;sec-ch-ua-mobile&quot;:[&quot;?0&quot;],&quot;user-agent&quot;:[&quot;Mozilla\\\\\\/5.0 (X11; Linux x86_64) AppleWebKit\\\\\\/537.36 (KHTML, like Gecko) Chrome\\\\\\/105.0.0.0 Safari\\\\\\/537.36&quot;],&quot;sec-ch-ua-platform&quot;:[&quot;\\\\&quot;Linux\\\\&quot;&quot;],&quot;sec-fetch-site&quot;:[&quot;same-origin&quot;],&quot;sec-fetch-mode&quot;:[&quot;cors&quot;],&quot;sec-fetch-dest&quot;:[&quot;empty&quot;],&quot;referer&quot;:[&quot;http:\\\\\\/\\\\\\/localhost\\\\\\/work\\\\\\/sites\\\\\\/canda_erp\\\\\\/admin\\\\\\/admins\\\\\\/assign_permission\\\\\\/1&quot;],&quot;accept-encoding&quot;:[&quot;gzip, deflate, br&quot;],&quot;accept-language&quot;:[&quot;en-US,en;q=0.9,ar-EG;q=0.8,ar;q=0.7&quot;],&quot;cookie&quot;:[&quot;blueorange_site_cookie=E55lyecbt8URnUpdBJYpMdw3885v1oV56V0qRJOK; blueorange22api_session=KJD13jlBMwHalZHIp1soVwLvW8PA6LFC2VY72PWI; canda_pos_session=ar4A8RtPyHX8eBMwXbHG53fBAgxJcGw8VLHn90EP; XSRF-TOKEN=eyJpdiI6IkpFMU52RFRGWDVJbE12S3g5NjFWWnc9PSIsInZhbHVlIjoiNDQrZWszNzFiQXhUbE1MbExFcVJ1SUVnb05RaFN1NERyak5iZGg1WFVmblVzOUNvQVwvaWFPdm10UGhWVDdMNVl5dEhpMm5pZzB0M2NUalFNamF6ZDFkTStoSUFcL0MyV3AzQW1LRDVjeit3WUVYbDhJSWxjdXNKNWpzNitZXC9SOEYiLCJtYWMiOiI4MjRlNjg4M2E2NzkzZDgwODE3M2VhYTA4MzIwNzRiYTViOTA0YzE0M2U3MDJhMTZjM2MyOGNlNDQyNDA2ZmE3In0%3D&quot;]}<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Accept-Header<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            *\\/*<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Token<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Session-ID<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Input<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2022 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\sendEmail has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:632\nStack trace:\n#0 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(446): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(358): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts()\n#2 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(314): Illuminate\\Queue\\Worker->process()\n#3 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(267): Illuminate\\Queue\\Worker->runJob()\n#4 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob()\n#5 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#6 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#9 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#10 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Container.php(590): Illuminate\\Container\\BoundMethod::call()\n#11 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(134): Illuminate\\Container\\Container->call()\n#12 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute()\n#13 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#14 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(1009): Illuminate\\Console\\Command->run()\n#15 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand()\n#16 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun()\n#17 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Application.php(93): Symfony\\Component\\Console\\Application->run()\n#18 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(131): Illuminate\\Console\\Application->run()\n#19 /var/www/html/work/sites/canda_erp/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#20 {main}', '2023-02-12 12:33:20'),
(4, 'database', 'default', '{\"displayName\":\"App\\\\Jobs\\\\orders\\\\AddOrderJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\orders\\\\AddOrderJob\",\"command\":\"O:27:\\\"App\\\\Jobs\\\\orders\\\\AddOrderJob\\\":9:{s:5:\\\"attrs\\\";a:5:{s:7:\\\"request\\\";a:21:{s:9:\\\"client_id\\\";s:1:\\\"6\\\";s:12:\\\"order_status\\\";s:4:\\\"done\\\";s:12:\\\"pick_up_date\\\";s:0:\\\"\\\";s:11:\\\"product_sku\\\";a:1:{i:0;s:1:\\\"1\\\";}s:9:\\\"item_type\\\";a:1:{i:0;s:4:\\\"item\\\";}s:14:\\\"order_quantity\\\";a:1:{i:0;s:1:\\\"1\\\";}s:15:\\\"item_total_cost\\\";a:1:{i:0;s:5:\\\"111.6\\\";}s:8:\\\"promo_id\\\";a:1:{i:1;s:1:\\\"1\\\";}s:16:\\\"total_items_cost\\\";s:6:\\\"111.60\\\";s:15:\\\"selected_redeem\\\";s:1:\\\"0\\\";s:26:\\\"available_amount_in_wallet\\\";s:4:\\\"0.00\\\";s:9:\\\"gift_card\\\";s:0:\\\"\\\";s:11:\\\"coupon_code\\\";s:0:\\\"\\\";s:16:\\\"cash_paid_amount\\\";s:3:\\\"200\\\";s:22:\\\"debit_card_paid_amount\\\";s:0:\\\"\\\";s:23:\\\"credit_card_paid_amount\\\";s:0:\\\"\\\";s:18:\\\"cheque_paid_amount\\\";s:0:\\\"\\\";s:10:\\\"total_cost\\\";s:6:\\\"111.60\\\";s:11:\\\"paid_amount\\\";s:3:\\\"200\\\";s:6:\\\"_token\\\";s:40:\\\"0fY9SFhdIBC4VgjgsfeiwV0scc94qbQG1KaywnRd\\\";s:9:\\\"undefined\\\";s:4:\\\"save\\\";}s:17:\\\"current_branch_id\\\";i:1;s:11:\\\"register_id\\\";s:1:\\\"1\\\";s:19:\\\"register_session_id\\\";i:1;s:11:\\\"employee_id\\\";i:3;}s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\orders\\AddOrderJob has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:632\nStack trace:\n#0 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(446): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(358): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts()\n#2 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(314): Illuminate\\Queue\\Worker->process()\n#3 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(267): Illuminate\\Queue\\Worker->runJob()\n#4 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob()\n#5 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#6 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#9 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#10 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Container.php(590): Illuminate\\Container\\BoundMethod::call()\n#11 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(134): Illuminate\\Container\\Container->call()\n#12 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute()\n#13 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#14 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(1009): Illuminate\\Console\\Command->run()\n#15 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand()\n#16 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun()\n#17 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Application.php(93): Symfony\\Component\\Console\\Application->run()\n#18 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(131): Illuminate\\Console\\Application->run()\n#19 /var/www/html/work/sites/canda_erp/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#20 {main}', '2023-02-12 13:13:47'),
(7, 'database', 'default', '{\"displayName\":\"App\\\\Jobs\\\\orders\\\\AddOrderJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\orders\\\\AddOrderJob\",\"command\":\"O:27:\\\"App\\\\Jobs\\\\orders\\\\AddOrderJob\\\":9:{s:5:\\\"attrs\\\";a:5:{s:7:\\\"request\\\";a:21:{s:9:\\\"client_id\\\";s:1:\\\"6\\\";s:12:\\\"order_status\\\";s:4:\\\"done\\\";s:12:\\\"pick_up_date\\\";s:0:\\\"\\\";s:11:\\\"product_sku\\\";a:1:{i:0;s:1:\\\"1\\\";}s:9:\\\"item_type\\\";a:1:{i:0;s:4:\\\"item\\\";}s:14:\\\"order_quantity\\\";a:1:{i:0;s:1:\\\"1\\\";}s:15:\\\"item_total_cost\\\";a:1:{i:0;s:5:\\\"111.6\\\";}s:8:\\\"promo_id\\\";a:1:{i:1;s:1:\\\"1\\\";}s:16:\\\"total_items_cost\\\";s:6:\\\"111.60\\\";s:15:\\\"selected_redeem\\\";s:1:\\\"0\\\";s:26:\\\"available_amount_in_wallet\\\";s:4:\\\"0.00\\\";s:9:\\\"gift_card\\\";s:0:\\\"\\\";s:11:\\\"coupon_code\\\";s:0:\\\"\\\";s:16:\\\"cash_paid_amount\\\";s:3:\\\"500\\\";s:22:\\\"debit_card_paid_amount\\\";s:0:\\\"\\\";s:23:\\\"credit_card_paid_amount\\\";s:0:\\\"\\\";s:18:\\\"cheque_paid_amount\\\";s:0:\\\"\\\";s:10:\\\"total_cost\\\";s:6:\\\"111.60\\\";s:11:\\\"paid_amount\\\";s:3:\\\"500\\\";s:6:\\\"_token\\\";s:40:\\\"0fY9SFhdIBC4VgjgsfeiwV0scc94qbQG1KaywnRd\\\";s:9:\\\"undefined\\\";s:4:\\\"save\\\";}s:17:\\\"current_branch_id\\\";i:1;s:11:\\\"register_id\\\";s:1:\\\"1\\\";s:19:\\\"register_session_id\\\";i:1;s:11:\\\"employee_id\\\";i:3;}s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\orders\\AddOrderJob has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:632\nStack trace:\n#0 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(446): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(358): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts()\n#2 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(314): Illuminate\\Queue\\Worker->process()\n#3 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(267): Illuminate\\Queue\\Worker->runJob()\n#4 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob()\n#5 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#6 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#9 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#10 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Container.php(590): Illuminate\\Container\\BoundMethod::call()\n#11 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(134): Illuminate\\Container\\Container->call()\n#12 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute()\n#13 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#14 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(1009): Illuminate\\Console\\Command->run()\n#15 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand()\n#16 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun()\n#17 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Application.php(93): Symfony\\Component\\Console\\Application->run()\n#18 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(131): Illuminate\\Console\\Application->run()\n#19 /var/www/html/work/sites/canda_erp/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#20 {main}', '2023-02-15 13:23:07'),
(8, 'database', 'default', '{\"displayName\":\"App\\\\Jobs\\\\orders\\\\AddOrderJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\orders\\\\AddOrderJob\",\"command\":\"O:27:\\\"App\\\\Jobs\\\\orders\\\\AddOrderJob\\\":9:{s:5:\\\"attrs\\\";a:5:{s:7:\\\"request\\\";a:21:{s:9:\\\"client_id\\\";s:1:\\\"6\\\";s:12:\\\"order_status\\\";s:4:\\\"done\\\";s:12:\\\"pick_up_date\\\";s:0:\\\"\\\";s:11:\\\"product_sku\\\";a:1:{i:0;s:1:\\\"1\\\";}s:9:\\\"item_type\\\";a:1:{i:0;s:4:\\\"item\\\";}s:14:\\\"order_quantity\\\";a:1:{i:0;s:1:\\\"1\\\";}s:15:\\\"item_total_cost\\\";a:1:{i:0;s:5:\\\"111.6\\\";}s:8:\\\"promo_id\\\";a:1:{i:1;s:1:\\\"1\\\";}s:16:\\\"total_items_cost\\\";s:6:\\\"111.60\\\";s:15:\\\"selected_redeem\\\";s:1:\\\"0\\\";s:26:\\\"available_amount_in_wallet\\\";s:4:\\\"0.00\\\";s:9:\\\"gift_card\\\";s:0:\\\"\\\";s:11:\\\"coupon_code\\\";s:0:\\\"\\\";s:16:\\\"cash_paid_amount\\\";s:6:\\\"111.60\\\";s:22:\\\"debit_card_paid_amount\\\";s:0:\\\"\\\";s:23:\\\"credit_card_paid_amount\\\";s:0:\\\"\\\";s:18:\\\"cheque_paid_amount\\\";s:0:\\\"\\\";s:10:\\\"total_cost\\\";s:6:\\\"111.60\\\";s:11:\\\"paid_amount\\\";s:5:\\\"111.6\\\";s:6:\\\"_token\\\";s:40:\\\"0fY9SFhdIBC4VgjgsfeiwV0scc94qbQG1KaywnRd\\\";s:9:\\\"undefined\\\";s:4:\\\"save\\\";}s:17:\\\"current_branch_id\\\";i:1;s:11:\\\"register_id\\\";s:1:\\\"1\\\";s:19:\\\"register_session_id\\\";i:1;s:11:\\\"employee_id\\\";i:3;}s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\orders\\AddOrderJob has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:632\nStack trace:\n#0 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(446): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(358): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts()\n#2 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(314): Illuminate\\Queue\\Worker->process()\n#3 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(267): Illuminate\\Queue\\Worker->runJob()\n#4 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob()\n#5 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#6 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#9 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#10 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Container.php(590): Illuminate\\Container\\BoundMethod::call()\n#11 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(134): Illuminate\\Container\\Container->call()\n#12 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute()\n#13 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#14 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(1009): Illuminate\\Console\\Command->run()\n#15 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand()\n#16 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun()\n#17 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Application.php(93): Symfony\\Component\\Console\\Application->run()\n#18 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(131): Illuminate\\Console\\Application->run()\n#19 /var/www/html/work/sites/canda_erp/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#20 {main}', '2023-02-15 13:23:07'),
(9, 'database', 'default', '{\"displayName\":\"App\\\\Jobs\\\\orders\\\\AddOrderJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\orders\\\\AddOrderJob\",\"command\":\"O:27:\\\"App\\\\Jobs\\\\orders\\\\AddOrderJob\\\":9:{s:5:\\\"attrs\\\";a:5:{s:7:\\\"request\\\";a:20:{s:9:\\\"client_id\\\";s:1:\\\"6\\\";s:12:\\\"order_status\\\";s:4:\\\"done\\\";s:12:\\\"pick_up_date\\\";s:0:\\\"\\\";s:11:\\\"product_sku\\\";a:1:{i:0;s:1:\\\"2\\\";}s:9:\\\"item_type\\\";a:1:{i:0;s:4:\\\"item\\\";}s:14:\\\"order_quantity\\\";a:1:{i:0;s:1:\\\"1\\\";}s:15:\\\"item_total_cost\\\";a:1:{i:0;s:3:\\\"300\\\";}s:16:\\\"total_items_cost\\\";s:6:\\\"300.00\\\";s:15:\\\"selected_redeem\\\";s:1:\\\"0\\\";s:26:\\\"available_amount_in_wallet\\\";s:4:\\\"0.00\\\";s:9:\\\"gift_card\\\";s:0:\\\"\\\";s:11:\\\"coupon_code\\\";s:0:\\\"\\\";s:16:\\\"cash_paid_amount\\\";s:3:\\\"300\\\";s:22:\\\"debit_card_paid_amount\\\";s:0:\\\"\\\";s:23:\\\"credit_card_paid_amount\\\";s:0:\\\"\\\";s:18:\\\"cheque_paid_amount\\\";s:0:\\\"\\\";s:10:\\\"total_cost\\\";s:6:\\\"300.00\\\";s:11:\\\"paid_amount\\\";s:3:\\\"300\\\";s:6:\\\"_token\\\";s:40:\\\"0fY9SFhdIBC4VgjgsfeiwV0scc94qbQG1KaywnRd\\\";s:9:\\\"undefined\\\";s:4:\\\"save\\\";}s:17:\\\"current_branch_id\\\";i:1;s:11:\\\"register_id\\\";s:1:\\\"1\\\";s:19:\\\"register_session_id\\\";i:1;s:11:\\\"employee_id\\\";i:3;}s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Jobs\\orders\\AddOrderJob has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:632\nStack trace:\n#0 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(446): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(358): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts()\n#2 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(314): Illuminate\\Queue\\Worker->process()\n#3 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(267): Illuminate\\Queue\\Worker->runJob()\n#4 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob()\n#5 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#6 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#9 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#10 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Container.php(590): Illuminate\\Container\\BoundMethod::call()\n#11 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(134): Illuminate\\Container\\Container->call()\n#12 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute()\n#13 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#14 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(1009): Illuminate\\Console\\Command->run()\n#15 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand()\n#16 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun()\n#17 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Application.php(93): Symfony\\Component\\Console\\Application->run()\n#18 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(131): Illuminate\\Console\\Application->run()\n#19 /var/www/html/work/sites/canda_erp/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#20 {main}', '2023-02-15 13:54:16'),
(10, 'database', 'default', '{\"displayName\":\"App\\\\Listeners\\\\Wallets\\\\RunAfterWithdrawMoneyFromAgency\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":8:{s:5:\\\"class\\\";s:53:\\\"App\\\\Listeners\\\\Wallets\\\\RunAfterWithdrawMoneyFromAgency\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:42:\\\"App\\\\Events\\\\Wallets\\\\WithdrawMoneyFromWallet\\\":11:{s:7:\\\"adminId\\\";i:3;s:8:\\\"walletId\\\";i:27;s:15:\\\"walletOwnerName\\\";s:17:\\\"wholesaler client\\\";s:19:\\\"transactionCurrency\\\";s:3:\\\"USD\\\";s:11:\\\"moneyAmount\\\";d:0;s:5:\\\"notes\\\";s:119:\\\"has been withdrawn money (0) from\\n                                                (wholesaler client) for gift card (3)\\\";s:36:\\\"checkIfAmountGreaterThanWalletAmount\\\";b:0;s:9:\\\"sendEmail\\\";b:0;s:15:\\\"transactionType\\\";N;s:20:\\\"transactionMoneyType\\\";N;s:6:\\\"socket\\\";N;}}s:5:\\\"tries\\\";N;s:10:\\\"retryAfter\\\";N;s:9:\\\"timeoutAt\\\";N;s:7:\\\"timeout\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Listeners\\Wallets\\RunAfterWithdrawMoneyFromAgency has been attempted too many times or run too long. The job may have previously timed out. in /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php:632\nStack trace:\n#0 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(446): Illuminate\\Queue\\Worker->maxAttemptsExceededException()\n#1 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(358): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts()\n#2 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(314): Illuminate\\Queue\\Worker->process()\n#3 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(267): Illuminate\\Queue\\Worker->runJob()\n#4 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(112): Illuminate\\Queue\\Worker->runNextJob()\n#5 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(96): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#6 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#7 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Util.php(37): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#9 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#10 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Container/Container.php(590): Illuminate\\Container\\BoundMethod::call()\n#11 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(134): Illuminate\\Container\\Container->call()\n#12 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Command/Command.php(255): Illuminate\\Console\\Command->execute()\n#13 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run()\n#14 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(1009): Illuminate\\Console\\Command->run()\n#15 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand()\n#16 /var/www/html/work/sites/canda_erp/vendor/symfony/console/Application.php(149): Symfony\\Component\\Console\\Application->doRun()\n#17 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Console/Application.php(93): Symfony\\Component\\Console\\Application->run()\n#18 /var/www/html/work/sites/canda_erp/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(131): Illuminate\\Console\\Application->run()\n#19 /var/www/html/work/sites/canda_erp/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle()\n#20 {main}', '2023-03-16 14:27:31');

-- --------------------------------------------------------

--
-- Table structure for table `gift_cards`
--

CREATE TABLE `gift_cards` (
  `card_id` int(11) NOT NULL,
  `card_template_id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `register_id` int(11) NOT NULL,
  `register_session_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `card_title` varchar(300) NOT NULL,
  `card_unique_number` varchar(50) NOT NULL,
  `card_expiration_date` date NOT NULL,
  `card_price` decimal(10,2) NOT NULL,
  `wallet_paid_amount` decimal(10,2) NOT NULL,
  `cash_paid_amount` decimal(10,2) DEFAULT NULL,
  `debit_card_paid_amount` decimal(10,2) DEFAULT NULL,
  `debit_card_receipt_img_obj` varchar(300) DEFAULT NULL,
  `credit_card_paid_amount` decimal(10,2) DEFAULT NULL,
  `credit_card_receipt_img_obj` varchar(300) DEFAULT NULL,
  `cheque_paid_amount` decimal(10,2) DEFAULT NULL,
  `cheque_card_receipt_img_obj` varchar(300) DEFAULT NULL,
  `gift_card_timezone` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gift_cards`
--

INSERT INTO `gift_cards` (`card_id`, `card_template_id`, `wallet_id`, `branch_id`, `employee_id`, `register_id`, `register_session_id`, `client_id`, `card_title`, `card_unique_number`, `card_expiration_date`, `card_price`, `wallet_paid_amount`, `cash_paid_amount`, `debit_card_paid_amount`, `debit_card_receipt_img_obj`, `credit_card_paid_amount`, `credit_card_receipt_img_obj`, `cheque_paid_amount`, `cheque_card_receipt_img_obj`, `gift_card_timezone`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 18, 1, 1, 1, 1, 4, 'happy birthday', '3045791609008562', '2023-11-29', '100.00', '0.00', '100.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, '', '2022-11-29 10:14:52', '2022-11-29 10:14:52', NULL),
(2, 1, 19, 1, 1, 1, 1, 4, 'happy birthday', '7404762802669665', '2023-11-29', '100.00', '0.00', '0.00', '0.00', NULL, '100.00', NULL, '0.00', NULL, '', '2022-11-29 10:25:55', '2022-11-29 10:25:55', NULL),
(3, 3, 32, 1, 3, 1, 1, 7, 'happy fire date', '4956284274741563', '2024-03-16', '500.00', '0.00', '500.00', '0.00', NULL, '0.00', NULL, '0.00', NULL, 'Africa/Cairo', '2023-03-16 13:25:48', '2023-03-16 13:25:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gift_card_templates`
--

CREATE TABLE `gift_card_templates` (
  `template_id` int(11) NOT NULL,
  `template_title` varchar(300) NOT NULL,
  `template_bg_img_obj` varchar(500) NOT NULL,
  `template_text_positions` text NOT NULL,
  `template_text_color` varchar(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gift_card_templates`
--

INSERT INTO `gift_card_templates` (`template_id`, `template_title`, `template_bg_img_obj`, `template_text_positions`, `template_text_color`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'main template', '{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/fad84b154b35c85a648cb99b38546dfd.png\"}', '{\"title\":\"left: 32.9773px; top: 29.9858px; font-size: 22px; color: rgb(255, 255, 255);\",\"expiration_date\":\"left: 37.9886px; top: 209.986px;\",\"card_number\":\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\",\"barcode\":\"left: 164.972px; top: 80.983px; width: 210px; filter: invert(100%);\",\"card_amount\":\"left: 405.972px; top: 27.9602px; color: rgb(255, 255, 255); font-size: 31px;\"}', '', 1, NULL, '2023-03-13 04:10:11', NULL),
(2, 'Sint molestiae quia', '{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/5ce452c1b016932af2fc488809742128.png\"}', '{\"title\":\"left: 32.9773px; top: 29.9858px; font-size: 22px; color: rgb(255, 255, 255);\",\"expiration_date\":\"left: 37.9886px; top: 209.986px;\",\"card_number\":\"left: 38.9773px; top: 170.989px; font-size: 21px; color: rgb(255, 255, 255);\",\"barcode\":\"left: 164.972px; top: 80.983px; width: 210px; filter: invert(100%);\",\"card_amount\":\"left: 405.972px; top: 27.9602px; color: rgb(255, 255, 255); font-size: 31px;\"}', 'Dignissimos consequa', 1, '2023-03-12 04:47:42', '2023-03-13 03:21:10', NULL),
(3, 'new gift card', '{\"alt\":\"\",\"title\":\"\",\"path\":\"uploads\\/all_imgs\\/e0e6c13bb2efde6f0b2376e0e39af84b.jpg\"}', '{\"title\":\"left: 120.909px; top: 251.903px; font-size: 32px; color: rgb(218, 171, 41);\",\"expiration_date\":\"left: 371.952px; top: 196.983px; color: rgb(255, 255, 255); font-size: 36px;\",\"card_number\":\"left: 173.969px; top: 187.955px; font-size: 16px; color: rgb(255, 255, 255);\",\"barcode\":\"left: 172px; top: 219px;\",\"card_amount\":\"left: 304.96px; top: 49.9517px; color: rgb(255, 255, 255); font-size: 46px;\"}', 'sdda', 1, '2023-03-13 02:42:20', '2023-03-16 13:27:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hr_delay_early_requests`
--

CREATE TABLE `hr_delay_early_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `req_type` varchar(50) NOT NULL COMMENT 'delay_request, early_leave',
  `req_title` varchar(300) NOT NULL,
  `req_desc` text NOT NULL,
  `req_date` date NOT NULL,
  `req_wanted_time` time NOT NULL,
  `req_status` varchar(50) NOT NULL COMMENT 'pending, accepted, rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hr_holiday_requests`
--

CREATE TABLE `hr_holiday_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `req_type` varchar(300) NOT NULL COMMENT 'vacation, sick_holiday',
  `req_title` varchar(300) NOT NULL,
  `req_desc` text NOT NULL,
  `req_date` date NOT NULL,
  `req_status` varchar(50) NOT NULL COMMENT 'pending, accepted, rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hr_national_holidays`
--

CREATE TABLE `hr_national_holidays` (
  `id` int(11) NOT NULL,
  `country_name` varchar(300) NOT NULL,
  `holiday_title` varchar(300) NOT NULL,
  `holiday_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_national_holidays`
--

INSERT INTO `hr_national_holidays` (`id`, `country_name`, `holiday_title`, `holiday_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'EG', 'اجازة 6 اكتوبر', '2022-10-06', NULL, NULL, NULL),
(2, 'Egypt', '15', '2022-10-19', '2022-10-31 14:21:24', '2022-10-31 14:21:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hr_paycheck`
--

CREATE TABLE `hr_paycheck` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `p_year` int(11) NOT NULL,
  `p_month` int(11) NOT NULL,
  `p_weeks` varchar(50) NOT NULL COMMENT 'ex => 1,2,3,4',
  `p_should_work_hours` time NOT NULL,
  `p_total_worked_hours` time NOT NULL,
  `p_amount` decimal(10,2) NOT NULL,
  `p_is_received` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_log`
--

CREATE TABLE `inventory_log` (
  `id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `pro_sku_id` int(11) NOT NULL,
  `log_box_quantity` int(11) NOT NULL,
  `log_item_quantity` int(11) NOT NULL,
  `log_type` varchar(300) NOT NULL COMMENT 'order, transfer_to_another, broken_products, invalid_entry, add_inventory',
  `log_operation` varchar(50) NOT NULL COMMENT 'increase or decrease',
  `log_desc` text NOT NULL,
  `is_refunded` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory_log`
--

INSERT INTO `inventory_log` (`id`, `inventory_id`, `pro_id`, `pro_sku_id`, `log_box_quantity`, `log_item_quantity`, `log_type`, `log_operation`, `log_desc`, `is_refunded`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, 1, 10, 5, 'add_inventory', 'increase', 'add products to inventory', 0, '2022-11-29 10:06:51', '2022-11-29 10:06:51', NULL),
(2, 1, 2, 2, 10, 5, 'add_inventory', 'increase', 'add products to inventory', 0, '2022-11-29 10:07:17', '2022-11-29 10:07:17', NULL),
(3, 1, 2, 1, 0, 1, 'order', 'decrease', 'buy items of order (1) for client ( general client )', 0, '2022-11-29 10:20:21', '2022-11-29 10:20:21', NULL),
(4, 1, 2, 1, 0, 1, 'order', 'decrease', 'buy items of order (2) for client ( general client )', 0, '2022-11-29 10:34:28', '2022-11-29 10:34:28', NULL),
(5, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (2) for client ( general client )', 0, '2022-11-29 10:34:28', '2022-11-29 10:34:28', NULL),
(6, 1, 2, 1, 0, 1, 'order', 'decrease', 'buy items of order (3) for client ( abanoub as wholesaler )', 0, '2022-11-29 10:35:28', '2022-11-29 10:35:28', NULL),
(7, 1, 2, 1, 0, 1, 'order', 'decrease', 'buy items of order (4) for client ( general client )', 0, '2022-11-29 10:37:30', '2022-11-29 10:37:30', NULL),
(8, 1, 2, 1, 0, 1, 'add_inventory', 'increase', 'return order items of order (4) that belongs to the client (general client)', 0, '2022-11-29 10:40:41', '2022-11-29 10:40:41', NULL),
(9, 2, 2, 1, 1, 1, 'add_inventory', 'increase', 'add products to inventory', 0, '2022-12-07 00:40:23', '2022-12-07 00:40:23', NULL),
(10, 1, 2, 1, 1, 1, 'add_inventory', 'increase', 'add products to inventory', 0, '2022-12-07 00:59:50', '2022-12-07 00:59:50', NULL),
(11, 1, 2, 1, 1, 1, 'add_inventory', 'increase', 'add products to inventory', 0, '2022-12-07 01:00:10', '2022-12-07 01:00:10', NULL),
(12, 1, 2, 1, 1, 1, 'broken_products', 'decrease', 'add broken products', 0, '2022-12-07 01:01:31', '2022-12-07 01:01:31', NULL),
(13, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (5) for client ( retailer client )', 0, '2023-01-31 22:02:56', '2023-01-31 22:02:56', NULL),
(14, 1, 2, 1, 0, 1, 'order', 'decrease', 'buy items of order (6) for client ( retailer client )', 0, '2023-02-12 12:25:34', '2023-02-12 12:25:34', NULL),
(15, 1, 2, 1, 0, 1, 'order', 'decrease', 'buy items of order (7) for client ( retailer client )', 0, '2023-02-12 12:39:59', '2023-02-12 12:39:59', NULL),
(16, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (8) for client ( retailer client )', 0, '2023-02-12 12:41:03', '2023-02-12 12:41:03', NULL),
(17, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (9) for client ( retailer client )', 0, '2023-02-12 12:51:26', '2023-02-12 12:51:26', NULL),
(18, 1, 2, 1, 0, 1, 'order', 'decrease', 'buy items of order (10) for client ( retailer client )', 0, '2023-02-12 12:52:23', '2023-02-12 12:52:23', NULL),
(19, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (11) for client ( retailer client )', 0, '2023-02-12 12:54:32', '2023-02-12 12:54:32', NULL),
(20, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (12) for client ( retailer client )', 0, '2023-02-12 13:05:17', '2023-02-12 13:05:17', NULL),
(21, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (13) for client ( retailer client )', 0, '2023-02-12 13:06:40', '2023-02-12 13:06:40', NULL),
(22, 1, 2, 1, 0, 1, 'order', 'decrease', 'buy items of order (14) for client ( retailer client )', 0, '2023-02-12 13:11:36', '2023-02-12 13:11:36', NULL),
(23, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (15) for client ( retailer client )', 0, '2023-02-15 14:01:40', '2023-02-15 14:01:40', NULL),
(24, 1, 2, 1, 0, 1, 'order', 'decrease', 'buy items of order (16) for client ( retailer client )', 0, '2023-02-21 15:52:29', '2023-02-21 15:52:29', NULL),
(27, 1, 2, 1, 0, 10, 'add_inventory', 'increase', 'add products to inventory, this products from supplier ( bob the supplier) of order (3)', 0, '2023-02-27 01:11:17', '2023-02-27 01:11:17', NULL),
(28, 1, 3, 8, 0, 1, 'add_inventory', 'increase', 'add products to inventory, this products from supplier (bob the supplier) of order (11)', 0, '2023-02-27 18:19:44', '2023-02-27 18:19:44', NULL),
(29, 1, 2, 1, 0, 10, 'add_inventory', 'increase', 'add products to inventory, this products from supplier (Irma Carter) of order (12)', 0, '2023-02-27 18:22:00', '2023-02-27 18:22:00', NULL),
(30, 1, 3, 8, 0, 10, 'add_inventory', 'increase', 'add products to inventory, this products from supplier (bob the supplier) of order (15)', 0, '2023-02-27 18:38:21', '2023-02-27 18:38:21', NULL),
(31, 1, 2, 1, 0, 10, 'add_inventory', 'increase', 'add products to inventory, this products from supplier (bob the supplier) of order (16)', 0, '2023-02-27 18:38:46', '2023-02-27 18:38:46', NULL),
(32, 1, 2, 1, 0, 10, 'add_inventory', 'increase', 'add products to inventory, this products from supplier (Irma Carter) of order (17)', 0, '2023-02-27 18:40:10', '2023-02-27 18:40:10', NULL),
(33, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (17) for client ( retailer client )', 0, '2023-02-28 23:36:10', '2023-02-28 23:36:10', NULL),
(34, 1, 3, 7, 0, 1, 'add_inventory', 'increase', 'add products to inventory, this products from supplier (bob the supplier) of order (18)', 0, '2023-03-01 03:20:12', '2023-03-01 03:20:12', NULL),
(35, 1, 3, 7, 0, 1, 'add_inventory', 'increase', 'add products to inventory, this products from supplier (bob the supplier) of order (19)', 0, '2023-03-01 03:21:53', '2023-03-01 03:21:53', NULL),
(36, 1, 3, 7, 0, 1, 'return_order', 'decrease', 'return order items of order (19) to supplier (bob the supplier)', 0, '2023-03-01 04:22:14', '2023-03-01 04:22:14', NULL),
(37, 1, 3, 7, 0, 2, 'add_inventory', 'increase', 'add products to inventory, this products from supplier (bob the supplier) of order (20)', 0, '2023-03-01 03:29:40', '2023-03-01 03:29:40', NULL),
(38, 1, 3, 7, 0, 1, 'return_order', 'decrease', 'return order items of order (20) to supplier (bob the supplier)', 0, '2023-03-01 04:29:59', '2023-03-01 04:29:59', NULL),
(39, 1, 3, 7, 0, 1, 'return_order', 'decrease', 'return order items of order (20) to supplier (bob the supplier)', 0, '2023-03-01 04:33:30', '2023-03-01 04:33:30', NULL),
(40, 1, 3, 7, 0, 2, 'add_inventory', 'increase', 'add products to inventory, this products from supplier (bob the supplier) of order (21)', 0, '2023-03-01 03:33:57', '2023-03-01 03:33:57', NULL),
(41, 1, 3, 7, 0, 2, 'return_order', 'decrease', 'return order items of order (21) to supplier (bob the supplier)', 0, '2023-03-01 04:35:58', '2023-03-01 04:35:58', NULL),
(42, 1, 3, 7, 0, 2, 'add_inventory', 'increase', 'add products to inventory, this products from supplier (bob the supplier) of order (23)', 0, '2023-03-01 03:38:36', '2023-03-01 03:38:36', NULL),
(43, 1, 2, 2, 0, 2, 'order', 'decrease', 'buy items of order (18) for client ( wholesaler client )', 0, '2023-03-01 16:45:26', '2023-03-01 16:45:26', NULL),
(44, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (19) for client ( wholesaler client )', 0, '2023-03-01 17:48:42', '2023-03-01 17:48:42', NULL),
(45, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (20) for client ( wholesaler client )', 0, '2023-03-01 18:07:09', '2023-03-01 18:07:09', NULL),
(46, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (21) for client ( wholesaler client )', 0, '2023-03-01 18:08:26', '2023-03-01 18:08:26', NULL),
(47, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (22) for client ( wholesaler client )', 0, '2023-03-01 18:08:59', '2023-03-01 18:08:59', NULL),
(48, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (23) for client ( wholesaler client )', 0, '2023-03-01 18:09:28', '2023-03-01 18:09:28', NULL),
(49, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (24) for client ( wholesaler client )', 0, '2023-03-01 18:10:37', '2023-03-01 18:10:37', NULL),
(50, 1, 2, 2, 0, 1, 'add_inventory', 'increase', 'return order items of order (24) that belongs to the client (wholesaler client)', 0, '2023-03-01 18:13:44', '2023-03-01 18:13:44', NULL),
(51, 1, 2, 2, 0, 1, 'order', 'decrease', 'buy items of order (25) for client ( wholesaler client )', 0, '2023-03-01 18:14:07', '2023-03-01 18:14:07', NULL),
(52, 1, 2, 2, 0, 1, 'add_inventory', 'increase', 'return order items of order (25) that belongs to the client (wholesaler client)', 0, '2023-03-01 18:14:23', '2023-03-01 18:14:23', NULL),
(53, 1, 2, 2, 0, 2, 'order', 'decrease', 'buy items of order (26) for client ( wholesaler client )', 0, '2023-03-02 04:35:42', '2023-03-02 04:35:42', NULL),
(54, 1, 2, 2, 0, 1, 'add_inventory', 'increase', 'return order items of order (26)', 0, '2023-03-02 04:21:10', '2023-03-02 04:21:10', NULL),
(55, 1, 2, 2, 0, 1, 'add_inventory', 'increase', 'return order items of order (26)', 0, '2023-03-02 04:22:46', '2023-03-02 04:22:46', NULL),
(56, 1, 2, 2, 0, 2, 'order', 'decrease', 'buy items of order (27) for client ( wholesaler client )', 0, '2023-03-02 05:23:32', '2023-03-02 05:23:32', NULL),
(57, 1, 2, 2, 0, 1, 'add_inventory', 'increase', 'return order items of order (27)', 0, '2023-03-02 04:25:43', '2023-03-02 04:25:43', NULL),
(58, 1, 2, 2, 0, 1, 'add_inventory', 'increase', 'return order items of order (27)', 0, '2023-03-03 00:12:19', '2023-03-03 00:12:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_places`
--

CREATE TABLE `inventory_places` (
  `inv_id` int(11) NOT NULL,
  `inv_name` varchar(300) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory_places`
--

INSERT INTO `inventory_places` (`inv_id`, `inv_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'main inventory', '2022-09-18 09:53:38', '2022-09-18 09:53:38', NULL),
(2, 'المخزن اللي موجود في الهرم', '2022-09-26 20:30:12', '2022-09-26 20:30:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_products`
--

CREATE TABLE `inventory_products` (
  `ip_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `pro_sku_id` int(11) NOT NULL,
  `ip_box_quantity` int(11) NOT NULL,
  `ip_item_quantity` int(11) NOT NULL,
  `total_items_quantity` int(11) NOT NULL COMMENT 'عدد الكامل للقطع الموججودة في المخزن',
  `quantity_limit` int(11) NOT NULL COMMENT 'حد الطلب',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory_products`
--

INSERT INTO `inventory_products` (`ip_id`, `inventory_id`, `pro_id`, `pro_sku_id`, `ip_box_quantity`, `ip_item_quantity`, `total_items_quantity`, `quantity_limit`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, 1, 10, 170, 170, 5300, '2022-11-29 10:06:51', '2023-02-27 18:40:10', NULL),
(2, 1, 2, 2, 9, 109, 109, 300, '2022-11-29 10:07:17', '2023-03-03 00:12:19', NULL),
(3, 2, 2, 1, 1, 1, 13, 500, '2022-12-07 00:40:23', '2023-02-02 07:31:10', NULL),
(4, 1, 3, 8, 0, 11, 11, 0, '2023-02-27 18:19:44', '2023-02-27 18:38:21', NULL),
(5, 1, 3, 7, 0, 3, 3, 0, '2023-03-01 03:20:12', '2023-03-01 03:38:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` text NOT NULL,
  `attempts` tinyint(1) NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(83, 'default', '{\"displayName\":\"App\\\\Jobs\\\\orders\\\\ClientGetLoyaltyPoints\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\orders\\\\ClientGetLoyaltyPoints\",\"command\":\"O:38:\\\"App\\\\Jobs\\\\orders\\\\ClientGetLoyaltyPoints\\\":9:{s:5:\\\"attrs\\\";a:1:{s:7:\\\"orderId\\\";i:26;}s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:13:\\\"Carbon\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2023-03-17 06:35:42.460529\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:11:\\\"Asia\\/Riyadh\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1679024142, 1677728142),
(85, 'default', '{\"displayName\":\"App\\\\Jobs\\\\orders\\\\ClientGetLoyaltyPoints\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\orders\\\\ClientGetLoyaltyPoints\",\"command\":\"O:38:\\\"App\\\\Jobs\\\\orders\\\\ClientGetLoyaltyPoints\\\":9:{s:5:\\\"attrs\\\";a:1:{s:7:\\\"orderId\\\";i:27;}s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:13:\\\"Carbon\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2023-03-17 07:23:32.504173\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:11:\\\"Asia\\/Riyadh\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1679027012, 1677731012),
(86, 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:28:\\\"abanoub.metyas.btm@gmail.com\\\";s:7:\\\"subject\\\";s:88:\\\"--canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-10 08:08\\\";s:4:\\\"body\\\";s:12271:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-10 08:08\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    File : \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/app\\/Http\\/Controllers\\/admin\\/DashboardController.php - Line : 228 - Error : syntax error, unexpected \')\'\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Time<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            March 10, 2023, 5:08 am<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            IP Address<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            ::1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            URL<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/menu-reports<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Method<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            GET<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Headers<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            {&quot;host&quot;:[&quot;localhost&quot;],&quot;connection&quot;:[&quot;keep-alive&quot;],&quot;cache-control&quot;:[&quot;max-age=0&quot;],&quot;sec-ch-ua&quot;:[&quot;\\\\&quot;Not?A_Brand\\\\&quot;;v=\\\\&quot;8\\\\&quot;, \\\\&quot;Chromium\\\\&quot;;v=\\\\&quot;108\\\\&quot;, \\\\&quot;Google Chrome\\\\&quot;;v=\\\\&quot;108\\\\&quot;&quot;],&quot;sec-ch-ua-mobile&quot;:[&quot;?0&quot;],&quot;sec-ch-ua-platform&quot;:[&quot;\\\\&quot;Linux\\\\&quot;&quot;],&quot;upgrade-insecure-requests&quot;:[&quot;1&quot;],&quot;user-agent&quot;:[&quot;Mozilla\\\\\\/5.0 (X11; Linux x86_64) AppleWebKit\\\\\\/537.36 (KHTML, like Gecko) Chrome\\\\\\/108.0.0.0 Safari\\\\\\/537.36&quot;],&quot;accept&quot;:[&quot;text\\\\\\/html,application\\\\\\/xhtml+xml,application\\\\\\/xml;q=0.9,image\\\\\\/avif,image\\\\\\/webp,image\\\\\\/apng,*\\\\\\/*;q=0.8,application\\\\\\/signed-exchange;v=b3;q=0.9&quot;],&quot;sec-fetch-site&quot;:[&quot;same-origin&quot;],&quot;sec-fetch-mode&quot;:[&quot;navigate&quot;],&quot;sec-fetch-user&quot;:[&quot;?1&quot;],&quot;sec-fetch-dest&quot;:[&quot;document&quot;],&quot;referer&quot;:[&quot;http:\\\\\\/\\\\\\/localhost\\\\\\/work\\\\\\/sites\\\\\\/canda_erp\\\\\\/dev\\\\\\/dashboard&quot;],&quot;accept-encoding&quot;:[&quot;gzip, deflate, br&quot;],&quot;accept-language&quot;:[&quot;en-US,en;q=0.9,ar-EG;q=0.8,ar;q=0.7&quot;],&quot;cookie&quot;:[&quot;canda_pos_session=Ivq1EciIJkmrKRBLVosfARB2sgFElrYtFW8f4R7m; PPA_ID=21vfigcc0tt340unkppmdmfnm6; webfx-tree-cookie-persistence=wfxt-4+wfxt-6; PHPSESSID=dt00tn64algv2mli6s5po93nst; nonprofit_session=AxOjhJ0gfkOULjScI1D2J5GRdJ6MvCD3aTcJIg9s; XSRF-TOKEN=eyJpdiI6InJNd2JcLzBCN0VmUXlzdXN4WDZURFVBPT0iLCJ2YWx1ZSI6IjJFcGRYZDZcL2Y2ZDI4VzlpVUdcL0dBcHNOS05cL3JFNXhLS0t1WUtOMmpNbEFOb3N2XC8zVnRKbUpSU09SQ2lBa1lrVWlYcnlqVTEzNVdjdTRFelljTXpVZldQcWZMdkZIRmVSK1V2N0U2R3crcWZ2a3FQbWU0QkRQbjlVSHlvQVV4ayIsIm1hYyI6IjQ5NmE3MjJlNzM2MjRjMTJmYmI3MjlhMDU5MWMyN2RkMzUzYjY4ZDkxMTZiYTZiMTY3NjJhMzQ4Y2M3YzYwMzAifQ%3D%3D&quot;]}<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Accept-Header<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Token<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Session-ID<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Input<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2023 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1678424929, 1678424929),
(87, 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:28:\\\"abanoub.metyas.btm@gmail.com\\\";s:7:\\\"subject\\\";s:88:\\\"--canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-10 08:11\\\";s:4:\\\"body\\\";s:12269:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-10 08:11\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    File : \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/app\\/Http\\/Controllers\\/admin\\/DashboardController.php - Line : 225 - Error : syntax error, unexpected \')\'\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Time<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            March 10, 2023, 5:11 am<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            IP Address<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            ::1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            URL<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/menu-reports<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Method<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            GET<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Headers<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            {&quot;host&quot;:[&quot;localhost&quot;],&quot;connection&quot;:[&quot;keep-alive&quot;],&quot;cache-control&quot;:[&quot;max-age=0&quot;],&quot;sec-ch-ua&quot;:[&quot;\\\\&quot;Not?A_Brand\\\\&quot;;v=\\\\&quot;8\\\\&quot;, \\\\&quot;Chromium\\\\&quot;;v=\\\\&quot;108\\\\&quot;, \\\\&quot;Google Chrome\\\\&quot;;v=\\\\&quot;108\\\\&quot;&quot;],&quot;sec-ch-ua-mobile&quot;:[&quot;?0&quot;],&quot;sec-ch-ua-platform&quot;:[&quot;\\\\&quot;Linux\\\\&quot;&quot;],&quot;upgrade-insecure-requests&quot;:[&quot;1&quot;],&quot;user-agent&quot;:[&quot;Mozilla\\\\\\/5.0 (X11; Linux x86_64) AppleWebKit\\\\\\/537.36 (KHTML, like Gecko) Chrome\\\\\\/108.0.0.0 Safari\\\\\\/537.36&quot;],&quot;accept&quot;:[&quot;text\\\\\\/html,application\\\\\\/xhtml+xml,application\\\\\\/xml;q=0.9,image\\\\\\/avif,image\\\\\\/webp,image\\\\\\/apng,*\\\\\\/*;q=0.8,application\\\\\\/signed-exchange;v=b3;q=0.9&quot;],&quot;sec-fetch-site&quot;:[&quot;same-origin&quot;],&quot;sec-fetch-mode&quot;:[&quot;navigate&quot;],&quot;sec-fetch-user&quot;:[&quot;?1&quot;],&quot;sec-fetch-dest&quot;:[&quot;document&quot;],&quot;referer&quot;:[&quot;http:\\\\\\/\\\\\\/localhost\\\\\\/work\\\\\\/sites\\\\\\/canda_erp\\\\\\/admin\\\\\\/employees&quot;],&quot;accept-encoding&quot;:[&quot;gzip, deflate, br&quot;],&quot;accept-language&quot;:[&quot;en-US,en;q=0.9,ar-EG;q=0.8,ar;q=0.7&quot;],&quot;cookie&quot;:[&quot;canda_pos_session=Ivq1EciIJkmrKRBLVosfARB2sgFElrYtFW8f4R7m; PPA_ID=21vfigcc0tt340unkppmdmfnm6; webfx-tree-cookie-persistence=wfxt-4+wfxt-6; PHPSESSID=dt00tn64algv2mli6s5po93nst; nonprofit_session=AxOjhJ0gfkOULjScI1D2J5GRdJ6MvCD3aTcJIg9s; XSRF-TOKEN=eyJpdiI6IlVvZlpLblBKSythc1orblBUWTc3dHc9PSIsInZhbHVlIjoiMDNcLzBrSVJVRnZpVFZUMm1BZkNpcDVcL1Z1VUtLUlFtYzRMc2FHRnROOVhXWTZOWDBqc1ZzUlRXaWMxWXo4aVZpK3BZbHQxZ0hNWHplSzh6RUdLV2tFbkFQMDBSSnNRSDdxcHZQelJvVmZITCtYcEdBY1JuRDFwTjNOODJhNHVkVyIsIm1hYyI6ImEwZGU4ODlhZWJhZmM4NmIxYzgzNGMwYmUyZDNkZDEwMjU2NGM5YTY5M2JlNTFlZWNiNmM0ZGE1MWQ3MDA2MDUifQ%3D%3D&quot;]}<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Accept-Header<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Token<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Session-ID<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Input<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2023 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1678425062, 1678425062),
(88, 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:28:\\\"abanoub.metyas.btm@gmail.com\\\";s:7:\\\"subject\\\";s:88:\\\"--canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-10 07:16\\\";s:4:\\\"body\\\";s:12495:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-10 07:16\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    File : \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/storage\\/framework\\/views\\/67cd843aeb5965a1779b5f7ec02aff59708d52ec.php - Line : 111 - Error : syntax error, unexpected \')\' (View: \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/resources\\/views\\/admin\\/subviews\\/menu\\/reports_new_design.blade.php) (View: \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/resources\\/views\\/admin\\/subviews\\/menu\\/reports_new_design.blade.php)\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Time<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            March 10, 2023, 5:16 am<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            IP Address<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            ::1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            URL<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/menu-reports<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Method<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            GET<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Headers<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            {&quot;host&quot;:[&quot;localhost&quot;],&quot;connection&quot;:[&quot;keep-alive&quot;],&quot;cache-control&quot;:[&quot;max-age=0&quot;],&quot;sec-ch-ua&quot;:[&quot;\\\\&quot;Not?A_Brand\\\\&quot;;v=\\\\&quot;8\\\\&quot;, \\\\&quot;Chromium\\\\&quot;;v=\\\\&quot;108\\\\&quot;, \\\\&quot;Google Chrome\\\\&quot;;v=\\\\&quot;108\\\\&quot;&quot;],&quot;sec-ch-ua-mobile&quot;:[&quot;?0&quot;],&quot;sec-ch-ua-platform&quot;:[&quot;\\\\&quot;Linux\\\\&quot;&quot;],&quot;upgrade-insecure-requests&quot;:[&quot;1&quot;],&quot;user-agent&quot;:[&quot;Mozilla\\\\\\/5.0 (X11; Linux x86_64) AppleWebKit\\\\\\/537.36 (KHTML, like Gecko) Chrome\\\\\\/108.0.0.0 Safari\\\\\\/537.36&quot;],&quot;accept&quot;:[&quot;text\\\\\\/html,application\\\\\\/xhtml+xml,application\\\\\\/xml;q=0.9,image\\\\\\/avif,image\\\\\\/webp,image\\\\\\/apng,*\\\\\\/*;q=0.8,application\\\\\\/signed-exchange;v=b3;q=0.9&quot;],&quot;sec-fetch-site&quot;:[&quot;same-origin&quot;],&quot;sec-fetch-mode&quot;:[&quot;navigate&quot;],&quot;sec-fetch-user&quot;:[&quot;?1&quot;],&quot;sec-fetch-dest&quot;:[&quot;document&quot;],&quot;referer&quot;:[&quot;http:\\\\\\/\\\\\\/localhost\\\\\\/work\\\\\\/sites\\\\\\/canda_erp\\\\\\/admin\\\\\\/employees&quot;],&quot;accept-encoding&quot;:[&quot;gzip, deflate, br&quot;],&quot;accept-language&quot;:[&quot;en-US,en;q=0.9,ar-EG;q=0.8,ar;q=0.7&quot;],&quot;cookie&quot;:[&quot;canda_pos_session=Ivq1EciIJkmrKRBLVosfARB2sgFElrYtFW8f4R7m; PPA_ID=21vfigcc0tt340unkppmdmfnm6; webfx-tree-cookie-persistence=wfxt-4+wfxt-6; PHPSESSID=dt00tn64algv2mli6s5po93nst; nonprofit_session=AxOjhJ0gfkOULjScI1D2J5GRdJ6MvCD3aTcJIg9s; XSRF-TOKEN=eyJpdiI6IlhwVHZzSEx0SDJRSkREaU5TQUxGeHc9PSIsInZhbHVlIjoiOWdxcE9rcVEwMWMybmlLSHA0VkZRdXVEZVZ1S3YyMzFzXC9TQUZVd2dablZMTTJaa1E5TjRYZmZ6MytwcGF2MktRdEZmM1QrMHFNNzcydjhPTFphRFpOVmlxY0tOTkJHK1VXazlIeFZOTDMrdU9raGtvMUJtaEh2a2RNb2h1RnJRIiwibWFjIjoiODgxNDNiYjUwZGU2N2RkMDdmYWYwYzA3MzY0Mzc0NTNiYTk0ZGNmZDI1ZWQzNmU3NTdmZTdjZDRkNzczZjRiMCJ9&quot;]}<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Accept-Header<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Token<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Session-ID<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Input<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2023 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1678425418, 1678425418);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(89, 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:28:\\\"abanoub.metyas.btm@gmail.com\\\";s:7:\\\"subject\\\";s:88:\\\"--canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-12 11:16\\\";s:4:\\\"body\\\";s:12387:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-12 11:16\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    File : \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/storage\\/framework\\/views\\/621d4bbe00853336cc9e9f1250a9ce763408cb63.php - Line : 101 - Error : Trying to get property \'template_bg_img_obj\' of non-object (View: \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/resources\\/views\\/admin\\/subviews\\/gift_card_templates\\/save.blade.php) (View: \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/resources\\/views\\/admin\\/subviews\\/gift_card_templates\\/save.blade.php)\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Time<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            March 12, 2023, 9:16 am<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            IP Address<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            ::1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            URL<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/gift-card-templates\\/save<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Method<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            GET<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Headers<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            {&quot;host&quot;:[&quot;localhost&quot;],&quot;connection&quot;:[&quot;keep-alive&quot;],&quot;sec-ch-ua&quot;:[&quot;\\\\&quot;Not?A_Brand\\\\&quot;;v=\\\\&quot;8\\\\&quot;, \\\\&quot;Chromium\\\\&quot;;v=\\\\&quot;108\\\\&quot;, \\\\&quot;Google Chrome\\\\&quot;;v=\\\\&quot;108\\\\&quot;&quot;],&quot;sec-ch-ua-mobile&quot;:[&quot;?0&quot;],&quot;sec-ch-ua-platform&quot;:[&quot;\\\\&quot;Linux\\\\&quot;&quot;],&quot;upgrade-insecure-requests&quot;:[&quot;1&quot;],&quot;user-agent&quot;:[&quot;Mozilla\\\\\\/5.0 (X11; Linux x86_64) AppleWebKit\\\\\\/537.36 (KHTML, like Gecko) Chrome\\\\\\/108.0.0.0 Safari\\\\\\/537.36&quot;],&quot;accept&quot;:[&quot;text\\\\\\/html,application\\\\\\/xhtml+xml,application\\\\\\/xml;q=0.9,image\\\\\\/avif,image\\\\\\/webp,image\\\\\\/apng,*\\\\\\/*;q=0.8,application\\\\\\/signed-exchange;v=b3;q=0.9&quot;],&quot;sec-fetch-site&quot;:[&quot;none&quot;],&quot;sec-fetch-mode&quot;:[&quot;navigate&quot;],&quot;sec-fetch-user&quot;:[&quot;?1&quot;],&quot;sec-fetch-dest&quot;:[&quot;document&quot;],&quot;accept-encoding&quot;:[&quot;gzip, deflate, br&quot;],&quot;accept-language&quot;:[&quot;en-US,en;q=0.9,ar-EG;q=0.8,ar;q=0.7&quot;],&quot;cookie&quot;:[&quot;canda_pos_session=Ivq1EciIJkmrKRBLVosfARB2sgFElrYtFW8f4R7m; PPA_ID=21vfigcc0tt340unkppmdmfnm6; webfx-tree-cookie-persistence=wfxt-4+wfxt-6; PHPSESSID=dt00tn64algv2mli6s5po93nst; nonprofit_session=AxOjhJ0gfkOULjScI1D2J5GRdJ6MvCD3aTcJIg9s; XSRF-TOKEN=eyJpdiI6ImFUZ2tKdXlDYWpQUG12SW5GVEVXbWc9PSIsInZhbHVlIjoiWVRiSVJJaXlvNzBSQVY1WXFsRzZWOVBEM3g4WUlTNE13ak5hWXNLWDdHMFhLaDNVQW1xYnJ1U0ZRUFFQNWJ4bmtVSkFLMnU3b0t0QWRzdXdyZWthVDVLMjRSWDM0eTJTUGw2VVJ3WkE1azNCQ2h3MlwvaDZpNDE2ZDdmSUh3ZUtMIiwibWFjIjoiYzAwY2FkNjdkMGE4NzM4YTUzYjU0MGIxNWRlNTFjY2YyZDMzNjI0ZGJlOWU0YTE0NGU2NDU1YWY0NzQzMzgwNSJ9&quot;]}<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Accept-Header<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Token<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Session-ID<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Input<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2023 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1678612591, 1678612591),
(90, 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:28:\\\"abanoub.metyas.btm@gmail.com\\\";s:7:\\\"subject\\\";s:88:\\\"--canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-13 06:08\\\";s:4:\\\"body\\\";s:12470:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-13 06:08\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    File : \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/storage\\/framework\\/views\\/59998b6a3b7fb609341f33148aec5572541f9622.php - Line : 1 - Error : syntax error, unexpected \';\', expecting \')\' (View: \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/resources\\/views\\/admin\\/subviews\\/gift_cards\\/print_card.blade.php) (View: \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/resources\\/views\\/admin\\/subviews\\/gift_cards\\/print_card.blade.php)\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Time<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            March 13, 2023, 4:08 am<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            IP Address<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            ::1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            URL<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/gift-cards\\/print-card\\/2<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Method<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            GET<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Headers<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            {&quot;host&quot;:[&quot;localhost&quot;],&quot;connection&quot;:[&quot;keep-alive&quot;],&quot;sec-ch-ua&quot;:[&quot;\\\\&quot;Not?A_Brand\\\\&quot;;v=\\\\&quot;8\\\\&quot;, \\\\&quot;Chromium\\\\&quot;;v=\\\\&quot;108\\\\&quot;, \\\\&quot;Google Chrome\\\\&quot;;v=\\\\&quot;108\\\\&quot;&quot;],&quot;sec-ch-ua-mobile&quot;:[&quot;?0&quot;],&quot;sec-ch-ua-platform&quot;:[&quot;\\\\&quot;Linux\\\\&quot;&quot;],&quot;upgrade-insecure-requests&quot;:[&quot;1&quot;],&quot;user-agent&quot;:[&quot;Mozilla\\\\\\/5.0 (X11; Linux x86_64) AppleWebKit\\\\\\/537.36 (KHTML, like Gecko) Chrome\\\\\\/108.0.0.0 Safari\\\\\\/537.36&quot;],&quot;accept&quot;:[&quot;text\\\\\\/html,application\\\\\\/xhtml+xml,application\\\\\\/xml;q=0.9,image\\\\\\/avif,image\\\\\\/webp,image\\\\\\/apng,*\\\\\\/*;q=0.8,application\\\\\\/signed-exchange;v=b3;q=0.9&quot;],&quot;sec-fetch-site&quot;:[&quot;same-origin&quot;],&quot;sec-fetch-mode&quot;:[&quot;navigate&quot;],&quot;sec-fetch-user&quot;:[&quot;?1&quot;],&quot;sec-fetch-dest&quot;:[&quot;document&quot;],&quot;referer&quot;:[&quot;http:\\\\\\/\\\\\\/localhost\\\\\\/work\\\\\\/sites\\\\\\/canda_erp\\\\\\/admin\\\\\\/gift-cards&quot;],&quot;accept-encoding&quot;:[&quot;gzip, deflate, br&quot;],&quot;accept-language&quot;:[&quot;en-US,en;q=0.9,ar-EG;q=0.8,ar;q=0.7&quot;],&quot;cookie&quot;:[&quot;canda_pos_session=Ivq1EciIJkmrKRBLVosfARB2sgFElrYtFW8f4R7m; PPA_ID=21vfigcc0tt340unkppmdmfnm6; webfx-tree-cookie-persistence=wfxt-4+wfxt-6; PHPSESSID=dt00tn64algv2mli6s5po93nst; nonprofit_session=AxOjhJ0gfkOULjScI1D2J5GRdJ6MvCD3aTcJIg9s; XSRF-TOKEN=eyJpdiI6IkVVWjdJXC9wVlA5T1g0Mit3U3k0Q1pBPT0iLCJ2YWx1ZSI6IkluRDQycGRsd2lzUytcL090WUNzckg1eTZ2ajJYWEdMXC95UGYxYktVXC9pM3JsVlFWM2E5aHJVZ01QdTUzWWZKdHM5dGFQSUpLdzVvZHVwWHIyV1FhR2NvNHBsazlUY2hJS2RFT015Q1ZSSHdxb2NwWHR2ZEd4SitRdlJNNkQ0UDM1IiwibWFjIjoiZjJlMWViMGZiYzNhNmUxMmZhYTEyNzMxODM0NDRiMzk0N2U3MWE4MDk5MzFmMjcyNjM2NjdmYjMxMjBlNDVhZSJ9&quot;]}<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Accept-Header<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Token<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Session-ID<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Input<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2023 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1678680503, 1678680503),
(91, 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:28:\\\"abanoub.metyas.btm@gmail.com\\\";s:7:\\\"subject\\\";s:88:\\\"--canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-13 06:08\\\";s:4:\\\"body\\\";s:12522:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-13 06:08\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    File : \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/storage\\/framework\\/views\\/59998b6a3b7fb609341f33148aec5572541f9622.php - Line : 1 - Error : syntax error, unexpected \';\', expecting \')\' (View: \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/resources\\/views\\/admin\\/subviews\\/gift_cards\\/print_card.blade.php) (View: \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/resources\\/views\\/admin\\/subviews\\/gift_cards\\/print_card.blade.php)\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Time<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            March 13, 2023, 4:08 am<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            IP Address<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            ::1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            URL<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/gift-cards\\/print-card\\/2<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Method<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            GET<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Headers<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            {&quot;host&quot;:[&quot;localhost&quot;],&quot;connection&quot;:[&quot;keep-alive&quot;],&quot;cache-control&quot;:[&quot;max-age=0&quot;],&quot;sec-ch-ua&quot;:[&quot;\\\\&quot;Not?A_Brand\\\\&quot;;v=\\\\&quot;8\\\\&quot;, \\\\&quot;Chromium\\\\&quot;;v=\\\\&quot;108\\\\&quot;, \\\\&quot;Google Chrome\\\\&quot;;v=\\\\&quot;108\\\\&quot;&quot;],&quot;sec-ch-ua-mobile&quot;:[&quot;?0&quot;],&quot;sec-ch-ua-platform&quot;:[&quot;\\\\&quot;Linux\\\\&quot;&quot;],&quot;upgrade-insecure-requests&quot;:[&quot;1&quot;],&quot;user-agent&quot;:[&quot;Mozilla\\\\\\/5.0 (X11; Linux x86_64) AppleWebKit\\\\\\/537.36 (KHTML, like Gecko) Chrome\\\\\\/108.0.0.0 Safari\\\\\\/537.36&quot;],&quot;accept&quot;:[&quot;text\\\\\\/html,application\\\\\\/xhtml+xml,application\\\\\\/xml;q=0.9,image\\\\\\/avif,image\\\\\\/webp,image\\\\\\/apng,*\\\\\\/*;q=0.8,application\\\\\\/signed-exchange;v=b3;q=0.9&quot;],&quot;sec-fetch-site&quot;:[&quot;same-origin&quot;],&quot;sec-fetch-mode&quot;:[&quot;navigate&quot;],&quot;sec-fetch-user&quot;:[&quot;?1&quot;],&quot;sec-fetch-dest&quot;:[&quot;document&quot;],&quot;referer&quot;:[&quot;http:\\\\\\/\\\\\\/localhost\\\\\\/work\\\\\\/sites\\\\\\/canda_erp\\\\\\/admin\\\\\\/gift-cards&quot;],&quot;accept-encoding&quot;:[&quot;gzip, deflate, br&quot;],&quot;accept-language&quot;:[&quot;en-US,en;q=0.9,ar-EG;q=0.8,ar;q=0.7&quot;],&quot;cookie&quot;:[&quot;canda_pos_session=Ivq1EciIJkmrKRBLVosfARB2sgFElrYtFW8f4R7m; PPA_ID=21vfigcc0tt340unkppmdmfnm6; webfx-tree-cookie-persistence=wfxt-4+wfxt-6; PHPSESSID=dt00tn64algv2mli6s5po93nst; nonprofit_session=AxOjhJ0gfkOULjScI1D2J5GRdJ6MvCD3aTcJIg9s; XSRF-TOKEN=eyJpdiI6InZkTGZEWlwvK2lhZzRaSWtodHlPOTN3PT0iLCJ2YWx1ZSI6IjlIZzBXSG5mTUJ5YW5JSVhPcHNFRzdNWWR3UzZ1SEZzejY0TlpFdnpzNENqaXc4VVV0ejgzaktGTnFieEJpelwveVhBZjRTOWtlaWQxY3RBeTVVcWcyc1EzNG1TVGJGME90UlwvOFB1c1RYOVNQU2IrZmpzck82VUVnUE16aXc0MkIiLCJtYWMiOiI4ODI1ZjAxYzQ4ZjIzZGVmYzAzYWNiMWMyZTRlNDJhMWY5NzBlMzg5YmQ3N2MzZTU2ZTE4NjkzZTIyNWI3NzkwIn0%3D&quot;]}<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Accept-Header<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Token<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Session-ID<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Input<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2023 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1678680521, 1678680521);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(92, 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:28:\\\"abanoub.metyas.btm@gmail.com\\\";s:7:\\\"subject\\\";s:88:\\\"--canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-13 06:09\\\";s:4:\\\"body\\\";s:12524:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-13 06:09\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    File : \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/storage\\/framework\\/views\\/59998b6a3b7fb609341f33148aec5572541f9622.php - Line : 1 - Error : syntax error, unexpected \';\', expecting \')\' (View: \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/resources\\/views\\/admin\\/subviews\\/gift_cards\\/print_card.blade.php) (View: \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/resources\\/views\\/admin\\/subviews\\/gift_cards\\/print_card.blade.php)\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Time<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            March 13, 2023, 4:09 am<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            IP Address<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            ::1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            URL<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/gift-cards\\/print-card\\/2<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Method<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            GET<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Headers<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            {&quot;host&quot;:[&quot;localhost&quot;],&quot;connection&quot;:[&quot;keep-alive&quot;],&quot;cache-control&quot;:[&quot;max-age=0&quot;],&quot;sec-ch-ua&quot;:[&quot;\\\\&quot;Not?A_Brand\\\\&quot;;v=\\\\&quot;8\\\\&quot;, \\\\&quot;Chromium\\\\&quot;;v=\\\\&quot;108\\\\&quot;, \\\\&quot;Google Chrome\\\\&quot;;v=\\\\&quot;108\\\\&quot;&quot;],&quot;sec-ch-ua-mobile&quot;:[&quot;?0&quot;],&quot;sec-ch-ua-platform&quot;:[&quot;\\\\&quot;Linux\\\\&quot;&quot;],&quot;upgrade-insecure-requests&quot;:[&quot;1&quot;],&quot;user-agent&quot;:[&quot;Mozilla\\\\\\/5.0 (X11; Linux x86_64) AppleWebKit\\\\\\/537.36 (KHTML, like Gecko) Chrome\\\\\\/108.0.0.0 Safari\\\\\\/537.36&quot;],&quot;accept&quot;:[&quot;text\\\\\\/html,application\\\\\\/xhtml+xml,application\\\\\\/xml;q=0.9,image\\\\\\/avif,image\\\\\\/webp,image\\\\\\/apng,*\\\\\\/*;q=0.8,application\\\\\\/signed-exchange;v=b3;q=0.9&quot;],&quot;sec-fetch-site&quot;:[&quot;same-origin&quot;],&quot;sec-fetch-mode&quot;:[&quot;navigate&quot;],&quot;sec-fetch-user&quot;:[&quot;?1&quot;],&quot;sec-fetch-dest&quot;:[&quot;document&quot;],&quot;referer&quot;:[&quot;http:\\\\\\/\\\\\\/localhost\\\\\\/work\\\\\\/sites\\\\\\/canda_erp\\\\\\/admin\\\\\\/gift-cards&quot;],&quot;accept-encoding&quot;:[&quot;gzip, deflate, br&quot;],&quot;accept-language&quot;:[&quot;en-US,en;q=0.9,ar-EG;q=0.8,ar;q=0.7&quot;],&quot;cookie&quot;:[&quot;canda_pos_session=Ivq1EciIJkmrKRBLVosfARB2sgFElrYtFW8f4R7m; PPA_ID=21vfigcc0tt340unkppmdmfnm6; webfx-tree-cookie-persistence=wfxt-4+wfxt-6; PHPSESSID=dt00tn64algv2mli6s5po93nst; nonprofit_session=AxOjhJ0gfkOULjScI1D2J5GRdJ6MvCD3aTcJIg9s; XSRF-TOKEN=eyJpdiI6Im42SklJVlhGcjJlV2hHVE1ac2NYV2c9PSIsInZhbHVlIjoiUThKNjJJK0NGd1V0VVJlbWRIRDNIenV4ZUI5bE9jZlpIeEhCRk9Ya1pBXC96YXF2dGxkOGhKeHUxbUx2cFdUdWpkUU5COGFkY1NGQmwxM0dsdTh4dkg4Mk1JVkFhMzZIc3duYXU4b2JqS243b3BjbVJsZk8rRHBJTm5yNmRcL0pwQiIsIm1hYyI6IjlhZTMxOGJkM2NhZDcwOTlmYmM1OTUxYTU5YzNhY2YyYjA5YjZmZWI3Yjg3YjgyZmEwNWQ1NTY4YzU2Y2UyZjAifQ%3D%3D&quot;]}<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Accept-Header<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Token<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Session-ID<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Input<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2023 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1678680549, 1678680549),
(93, 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:28:\\\"abanoub.metyas.btm@gmail.com\\\";s:7:\\\"subject\\\";s:88:\\\"--canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-14 06:11\\\";s:4:\\\"body\\\";s:11504:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda pos Website-- source[Main POS] critical issue need to be fixed #2023-03-14 06:11\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    File : \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/app\\/Http\\/Controllers\\/DashboardController.php - Line : 38 - Error : Trying to get property \'user_type\' of non-object\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Time<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            March 14, 2023, 3:11 am<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            IP Address<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            ::1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            URL<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            http:\\/\\/localhost\\/work\\/sites\\/canda_erp\\/admin\\/gift-card-templates\\/save\\/1<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Method<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            GET<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Headers<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            {&quot;host&quot;:[&quot;localhost&quot;],&quot;connection&quot;:[&quot;keep-alive&quot;],&quot;sec-ch-ua&quot;:[&quot;\\\\&quot;Not?A_Brand\\\\&quot;;v=\\\\&quot;8\\\\&quot;, \\\\&quot;Chromium\\\\&quot;;v=\\\\&quot;108\\\\&quot;, \\\\&quot;Google Chrome\\\\&quot;;v=\\\\&quot;108\\\\&quot;&quot;],&quot;sec-ch-ua-mobile&quot;:[&quot;?0&quot;],&quot;sec-ch-ua-platform&quot;:[&quot;\\\\&quot;Linux\\\\&quot;&quot;],&quot;upgrade-insecure-requests&quot;:[&quot;1&quot;],&quot;user-agent&quot;:[&quot;Mozilla\\\\\\/5.0 (X11; Linux x86_64) AppleWebKit\\\\\\/537.36 (KHTML, like Gecko) Chrome\\\\\\/108.0.0.0 Safari\\\\\\/537.36&quot;],&quot;accept&quot;:[&quot;text\\\\\\/html,application\\\\\\/xhtml+xml,application\\\\\\/xml;q=0.9,image\\\\\\/avif,image\\\\\\/webp,image\\\\\\/apng,*\\\\\\/*;q=0.8,application\\\\\\/signed-exchange;v=b3;q=0.9&quot;],&quot;sec-fetch-site&quot;:[&quot;none&quot;],&quot;sec-fetch-mode&quot;:[&quot;navigate&quot;],&quot;sec-fetch-user&quot;:[&quot;?1&quot;],&quot;sec-fetch-dest&quot;:[&quot;document&quot;],&quot;accept-encoding&quot;:[&quot;gzip, deflate, br&quot;],&quot;accept-language&quot;:[&quot;en-US,en;q=0.9&quot;]}<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Accept-Header<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.9<\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Token<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Client-Session-ID<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                    <tr>\\n                        <th style=\\\"width: 14%; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                            Input<\\/th>\\n                        <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;\\\" bgcolor=\\\"#eee\\\">\\n                            <\\/td>\\n                    <\\/tr>\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2023 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1678763518, 1678763518),
(98, 'not_important_queue', '{\"displayName\":\"App\\\\Jobs\\\\sendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\sendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\sendEmail\\\":18:{s:5:\\\"email\\\";s:28:\\\"abanoub.metyas.btm@gmail.com\\\";s:7:\\\"subject\\\";s:90:\\\"--canda-pos-Website-- source[failed-job] critical issue need to be fixed #2023-03-16 16:27\\\";s:4:\\\"body\\\";s:9176:\\\"<!DOCTYPE html PUBLIC \\\"-\\/\\/W3C\\/\\/DTD XHTML 1.0 Transitional\\/\\/EN\\\" \\\"http:\\/\\/www.w3.org\\/TR\\/xhtml1\\/DTD\\/xhtml1-transitional.dtd\\\">\\n<html xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\" xmlns=\\\"http:\\/\\/www.w3.org\\/1999\\/xhtml\\\">\\n<head>\\n    <meta name=\\\"viewport\\\" content=\\\"width=device-width, initial-scale=1.0\\\"\\/>\\n    <meta http-equiv=\\\"Content-Type\\\" content=\\\"text\\/html; charset=UTF-8\\\"\\/>\\n    <title>Welcome to, canda_pos<\\/title>\\n<\\/head>\\n\\n<body style=\\\"width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n\\n<table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\" bgcolor=\\\"#F2F4F6\\\">\\n    <tr>\\n        <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n            <table width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;\\\">\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;\\\" align=\\\"center\\\">\\n                        <a href=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_pos\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;\\\">\\n                            <img\\n                                src=\\\"http:\\/\\/localhost\\/work\\/sites\\/canda_pos\\/public\\/images\\/no_image.png\\\"\\n                                 alt=\\\"Canda Pos\\\"\\n                                 title=\\\"Canda Pos\\\"\\n                                style=\\\"width: 200px; height: 70px; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; border: none;\\\"\\/>\\n                        <\\/a>\\n                    <\\/td>\\n                <\\/tr>\\n                <!-- Email Body -->\\n                <tr>\\n                    <td width=\\\"100%\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;\\\" bgcolor=\\\"#FFFFFF\\\">\\n                        \\n    <table align=\\\"center\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;\\\" bgcolor=\\\"#FFFFFF\\\" width=\\\"570px\\\">\\n        <!-- Body content -->\\n        <tr>\\n            <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">\\n                    --canda-pos-Website-- source[failed-job] critical issue need to be fixed #2023-03-16 16:27\\n                <\\/h1>\\n\\n                <p style=\\\"color: blue; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Illuminate\\\\Queue\\\\MaxAttemptsExceededException: App\\\\Listeners\\\\Wallets\\\\RunAfterWithdrawMoneyFromAgency has been attempted too many times or run too long. The job may have previously timed out. in \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Worker.php:632\\nStack trace:\\n#0 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Worker.php(446): Illuminate\\\\Queue\\\\Worker->maxAttemptsExceededException()\\n#1 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Worker.php(358): Illuminate\\\\Queue\\\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts()\\n#2 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Worker.php(314): Illuminate\\\\Queue\\\\Worker->process()\\n#3 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Worker.php(267): Illuminate\\\\Queue\\\\Worker->runJob()\\n#4 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Console\\/WorkCommand.php(112): Illuminate\\\\Queue\\\\Worker->runNextJob()\\n#5 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Queue\\/Console\\/WorkCommand.php(96): Illuminate\\\\Queue\\\\Console\\\\WorkCommand->runWorker()\\n#6 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(36): Illuminate\\\\Queue\\\\Console\\\\WorkCommand->handle()\\n#7 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Util.php(37): Illuminate\\\\Container\\\\BoundMethod::Illuminate\\\\Container\\\\{closure}()\\n#8 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(93): Illuminate\\\\Container\\\\Util::unwrapIfClosure()\\n#9 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/BoundMethod.php(37): Illuminate\\\\Container\\\\BoundMethod::callBoundMethod()\\n#10 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Container\\/Container.php(590): Illuminate\\\\Container\\\\BoundMethod::call()\\n#11 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(134): Illuminate\\\\Container\\\\Container->call()\\n#12 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/symfony\\/console\\/Command\\/Command.php(255): Illuminate\\\\Console\\\\Command->execute()\\n#13 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Command.php(121): Symfony\\\\Component\\\\Console\\\\Command\\\\Command->run()\\n#14 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/symfony\\/console\\/Application.php(1009): Illuminate\\\\Console\\\\Command->run()\\n#15 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/symfony\\/console\\/Application.php(273): Symfony\\\\Component\\\\Console\\\\Application->doRunCommand()\\n#16 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/symfony\\/console\\/Application.php(149): Symfony\\\\Component\\\\Console\\\\Application->doRun()\\n#17 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Console\\/Application.php(93): Symfony\\\\Component\\\\Console\\\\Application->run()\\n#18 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/vendor\\/laravel\\/framework\\/src\\/Illuminate\\/Foundation\\/Console\\/Kernel.php(131): Illuminate\\\\Console\\\\Application->run()\\n#19 \\/var\\/www\\/html\\/work\\/sites\\/canda_erp\\/artisan(37): Illuminate\\\\Foundation\\\\Console\\\\Kernel->handle()\\n#20 {main}\\n                <\\/p>\\n\\n                <br><br>\\n\\n                <h1 style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;\\\" align=\\\"left\\\">Request Details<\\/h1>\\n                <table style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box;\\\">\\n                    \\n                <\\/table>\\n\\n                <br><br>\\n\\n                <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;\\\" align=\\\"left\\\">\\n                    Cheers,<br>\\n                    Canda Pos\\n                    Team\\n                <\\/p>\\n            <\\/td>\\n        <\\/tr>\\n    <\\/table>\\n\\n                    <\\/td>\\n                <\\/tr>\\n                <tr>\\n                    <td style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;\\\">\\n                        <table align=\\\"center\\\" width=\\\"570\\\" cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;\\\">\\n                            <tr>\\n                                <td align=\\\"center\\\" style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;\\\">\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        \\u00a9 2023 Canda Pos All rights reserved.<\\/p>\\n                                    <p style=\\\"font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;\\\" align=\\\"center\\\">\\n                                        [Canda Pos, LLC]\\n                                    <\\/p>\\n                                <\\/td>\\n                            <\\/tr>\\n                        <\\/table>\\n                    <\\/td>\\n                <\\/tr>\\n            <\\/table>\\n        <\\/td>\\n    <\\/tr>\\n<\\/table>\\n<\\/body>\\n<\\/html>\\n\\\";s:4:\\\"from\\\";s:0:\\\"\\\";s:4:\\\"name\\\";s:0:\\\"\\\";s:8:\\\"reply_to\\\";s:0:\\\"\\\";s:13:\\\"reply_to_name\\\";s:0:\\\"\\\";s:12:\\\"smtp_setting\\\";s:0:\\\"\\\";s:6:\\\"langId\\\";N;s:9:\\\"langTitle\\\";N;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:19:\\\"not_important_queue\\\";s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1678973251, 1678973251);

-- --------------------------------------------------------

--
-- Table structure for table `langs`
--

CREATE TABLE `langs` (
  `lang_id` int(11) NOT NULL,
  `lang_title` varchar(20) NOT NULL,
  `lang_text` varchar(100) NOT NULL,
  `lang_is_rtl` tinyint(1) NOT NULL,
  `lang_is_active` tinyint(1) NOT NULL,
  `lang_is_default` tinyint(1) NOT NULL,
  `lang_img_obj` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `langs`
--

INSERT INTO `langs` (`lang_id`, `lang_title`, `lang_text`, `lang_is_rtl`, `lang_is_active`, `lang_is_default`, `lang_img_obj`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'en', 'english', 0, 1, 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_points_to_money`
--

CREATE TABLE `loyalty_points_to_money` (
  `id` int(11) NOT NULL,
  `money_currency` varchar(20) NOT NULL,
  `points_amount` int(11) NOT NULL,
  `reward_money` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `loyalty_points_to_money`
--

INSERT INTO `loyalty_points_to_money` (`id`, `money_currency`, `points_amount`, `reward_money`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'USD', 500, '25.00', '2023-02-12 12:02:57', '2023-02-12 12:02:57', NULL),
(2, 'USD', 1000, '100.00', '2023-02-12 12:03:07', '2023-02-12 12:03:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2022_10_05_212605_create_branch_inventory_table', 0),
(2, '2022_10_05_212605_create_branch_prices_table', 0),
(3, '2022_10_05_212605_create_branches_table', 0),
(4, '2022_10_05_212605_create_brands_table', 0),
(5, '2022_10_05_212605_create_cache_table', 0),
(6, '2022_10_05_212605_create_categories_table', 0),
(7, '2022_10_05_212605_create_client_addresses_table', 0),
(8, '2022_10_05_212605_create_client_loyal_points_log_table', 0),
(9, '2022_10_05_212605_create_client_order_items_table', 0),
(10, '2022_10_05_212605_create_client_orders_table', 0),
(11, '2022_10_05_212605_create_client_wishlist_table', 0),
(12, '2022_10_05_212605_create_clients_table', 0),
(13, '2022_10_05_212605_create_coupons_table', 0),
(14, '2022_10_05_212605_create_currencies_table', 0),
(15, '2022_10_05_212605_create_employee_action_log_table', 0),
(16, '2022_10_05_212605_create_employee_details_table', 0),
(17, '2022_10_05_212605_create_employee_login_logout_table', 0),
(18, '2022_10_05_212605_create_employee_task_comments_table', 0),
(19, '2022_10_05_212605_create_employee_tasks_table', 0),
(20, '2022_10_05_212605_create_employee_warnings_table', 0),
(21, '2022_10_05_212605_create_failed_jobs_table', 0),
(22, '2022_10_05_212605_create_hr_delay_early_requests_table', 0),
(23, '2022_10_05_212605_create_hr_demand_vacation_requests_table', 0),
(24, '2022_10_05_212605_create_hr_national_holidays_table', 0),
(25, '2022_10_05_212605_create_hr_paycheck_table', 0),
(26, '2022_10_05_212605_create_hr_sick_holidays_requests_table', 0),
(27, '2022_10_05_212605_create_inventory_log_table', 0),
(28, '2022_10_05_212605_create_inventory_places_table', 0),
(29, '2022_10_05_212605_create_inventory_products_table', 0),
(30, '2022_10_05_212605_create_jobs_table', 0),
(31, '2022_10_05_212605_create_langs_table', 0),
(32, '2022_10_05_212605_create_money_installments_table', 0),
(33, '2022_10_05_212605_create_notifications_table', 0),
(34, '2022_10_05_212605_create_permissions_table', 0),
(35, '2022_10_05_212605_create_product_promotions_table', 0),
(36, '2022_10_05_212605_create_product_reviews_table', 0),
(37, '2022_10_05_212605_create_product_skus_table', 0),
(38, '2022_10_05_212605_create_product_variant_type_values_table', 0),
(39, '2022_10_05_212605_create_product_variant_types_table', 0),
(40, '2022_10_05_212605_create_products_table', 0),
(41, '2022_10_05_212605_create_register_sessions_table', 0),
(42, '2022_10_05_212605_create_registers_table', 0),
(43, '2022_10_05_212605_create_sessions_table', 0),
(44, '2022_10_05_212605_create_supplier_order_items_table', 0),
(45, '2022_10_05_212605_create_supplier_orders_table', 0),
(46, '2022_10_05_212605_create_suppliers_table', 0),
(47, '2022_10_05_212605_create_support_messages_table', 0),
(48, '2022_10_05_212605_create_transactions_log_table', 0),
(49, '2022_10_05_212605_create_used_coupons_table', 0),
(50, '2022_10_05_212605_create_users_table', 0),
(51, '2022_10_05_212605_create_wallets_table', 0),
(52, '2022_10_05_212606_add_foreign_keys_to_clients_table', 0),
(53, '2022_10_05_212606_add_foreign_keys_to_product_skus_table', 0),
(54, '2022_10_05_212606_add_foreign_keys_to_product_variant_type_values_table', 0),
(55, '2022_10_05_212606_add_foreign_keys_to_product_variant_types_table', 0),
(56, '2022_10_05_212606_add_foreign_keys_to_products_table', 0),
(57, '2022_10_05_212606_add_foreign_keys_to_suppliers_table', 0),
(58, '2022_10_10_204231_create_hr_holiday_requests_table', 0),
(59, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(60, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(61, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(62, '2016_06_01_000004_create_oauth_clients_table', 1),
(63, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(64, '2022_10_06_195451_alter_mas3ood_2022_10_6_alter_money_installments_edit_col_should_recive_payment_at', 1),
(65, '2022_10_08_191621_alter_mas3ood_2022_10_8_alter_money_installments_edit_col_img_obj', 1),
(66, '2022_10_10_130336_db_alter_mets_product_skus_2022_10_10', 1),
(67, '2022_10_10_203914_db_alter_mets_product_promotions_2022_10_10', 1),
(68, '2022_10_10_204023_db_alter_mets_emp_details_2022_10_10', 1),
(69, '2022_10_10_204120_db_alter_mets_emp_tasks_2022_10_10', 1),
(70, '2022_10_11_031255_alter_mas3ood_2022_10_11_product_skus_add_col_ps_selected_variant_type_values_text', 2),
(71, '2022_10_12_021512_alter_mas3ood_2022_10_12_hr_national_holidays_edit_cols_names', 3),
(72, '2022_10_12_022853_alter_mas3ood_2022_10_12_hr_national_holidays_delete_col_holiday_year', 3),
(73, '2022_10_12_202912_alter_mas3ood_2022_10_12_hr_holidays_requests_edit_col_req_type', 3),
(74, '2022_10_13_141325_alter_mas3ood_2022_10_13_hr_holidays_requests_edit_col_req_is_accepted', 3),
(75, '2022_10_13_141610_alter_mas3ood_2022_10_13_hr_delay_early_requests_edit_col_req_is_accepted', 3),
(76, '2022_10_13_192730_alter_mas3ood_2022_10_13_employee_details_edit_cols', 3),
(77, '2022_10_17_161113_alter_mas3ood_2022_10_17_employee_tasks_edit_col_employee_id', 3),
(78, '2022_10_17_213451_alter_mas3ood_2022_10_17_employee_tasks__comments', 3),
(79, '2022_10_18_134127_alter_mas3ood_2022_10_18_employee_tasks', 3),
(80, '2022_10_20_192843_alter_mas3ood_2022_10_20_hr_holiday_requests_edit_col_is_aceepted', 4),
(81, '2022_10_20_193027_alter_mas3ood_2022_10_20_hr_delay_early_requests_edit_col_is_aceepted', 4),
(82, '2022_10_26_181323_alter_mas3ood_2022_10_26_edit_supplier_order', 5),
(83, '2022_11_02_203158_create_gift_card_templates_table', 0),
(84, '2022_11_02_203212_create_gift_cards_table', 0),
(85, '2022_11_02_203452_db_alters_mets_branches_2022_11_02', 6),
(86, '2022_11_06_144156_alter_mas3ood_2022_11_6_client_orders', 7),
(87, '2022_11_06_181614_alter_mas3ood_2022_11_6_coupons_col_branch_id', 7),
(88, '2022_11_14_165352_alter_mas3ood_2022_11_14_client_order', 7),
(89, '2022_11_17_234645_alter_mas3ood_2022_11_17_client_order', 7),
(90, '2022_11_18_205803_alter_mas3ood_2022_11_18_client_order_items', 7),
(91, '2022_11_19_025107_alter_mas3ood_2022_11_19_client_order', 7),
(92, '2022_11_22_192236_alter_mas3ood_2022_11_22_client_order', 7),
(93, '2022_12_07_040031_db_alters_mets_users_2022_12_07', 8),
(94, '2022_12_07_181409_alter_mas3ood_2022_12_7_clients', 9),
(95, '2022_12_08_163613_alter_mas3ood_2022_12_8_register_sessions', 9),
(96, '2022_12_08_210632_create_mas3ood_2022_12_8_register_session_logs', 9),
(97, '2022_12_08_213404_alter_mas3ood_2022_12_8_register_session_logs', 9),
(98, '2022_12_12_184212_alter_mas3ood_2022_12_12_add_currency_alters', 10),
(99, '2022_12_13_160937_alter_mas3ood_2022_12_13_alter_branches', 10),
(100, '2022_12_14_145331_alter_mas3ood_2022_12_14_alter_gift_cards', 10),
(101, '2022_12_14_221834_alter_mas3ood_2022_12_14_alter_branches', 10),
(102, '2022_12_15_203103_alter_mas3ood_2022_12_15_alter_clinet_orders', 10),
(103, '2022_12_23_181233_create_mas3ood_2022_12_23_add_new_employee_login_logout_tbl', 11),
(104, '2022_12_29_140158_alter_mas3ood_2022_12_29_hr_paycheck', 11),
(105, '2022_12_30_012433_create_settings_table', 12),
(106, '2023_01_03_012703_alter_mas3ood_2023_01_03_employee_details', 13),
(107, '2023_01_10_131051_alter_mas3ood_2023_01_10_employee_details', 14),
(108, '2023_01_11_042217_create_money_to_loyalty_points_table', 14),
(109, '2023_01_16_215618_db_alters_mets_2023_01_16_transactions_log', 14),
(110, '2023_01_18_181911_create_loyalty_points_to_money_table', 14),
(111, '2023_01_22_205638_db_alters_client_order_2023_01_21', 14),
(112, '2023_02_01_041148_db_alters_mets_client_orders_2023_01_31', 15),
(113, '2023_02_12_130518_db_alters_mets_branches_2023_02_12', 16),
(114, '2023_02_12_153139_create_employee_action_log_table', 16),
(115, '2023_02_15_135858_create_taxes_groups_table', 17),
(116, '2023_02_16_181323_alter_nobel_actions_log_2023_02_16', 18),
(117, '2023_02_20_151831_create_employee_action_log_table', 18),
(118, '2023_03_06_115801_add_columns_by_nobel_on_product_skus_table', 19);

-- --------------------------------------------------------

--
-- Table structure for table `money_installments`
--

CREATE TABLE `money_installments` (
  `id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `money_type` varchar(50) NOT NULL COMMENT 'owed || dept',
  `money_amount` decimal(10,2) NOT NULL,
  `should_receive_payment_at` date NOT NULL,
  `is_received` tinyint(1) NOT NULL,
  `img_obj` varchar(300) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `money_installments`
--

INSERT INTO `money_installments` (`id`, `wallet_id`, `money_type`, `money_amount`, `should_receive_payment_at`, `is_received`, `img_obj`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 'debt', '1000.00', '2023-01-01', 0, NULL, '2022-10-13 02:24:14', '2022-10-13 02:24:14', NULL),
(2, 1, 'owed', '333.33', '2022-11-01', 0, NULL, '2022-10-13 02:32:22', '2022-10-13 02:32:22', NULL),
(3, 1, 'owed', '333.33', '2022-12-01', 0, NULL, '2022-10-13 02:32:22', '2022-10-13 02:32:22', NULL),
(4, 1, 'owed', '333.33', '2023-01-01', 0, NULL, '2022-10-13 02:32:22', '2022-10-13 02:32:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `money_to_loyalty_points`
--

CREATE TABLE `money_to_loyalty_points` (
  `id` int(11) NOT NULL,
  `money_currency` varchar(20) NOT NULL,
  `money_amount` decimal(10,2) NOT NULL,
  `reward_points` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `money_to_loyalty_points`
--

INSERT INTO `money_to_loyalty_points` (`id`, `money_currency`, `money_amount`, `reward_points`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'USD', '100.00', 10, '2023-02-12 12:02:31', '2023-02-12 12:02:31', NULL),
(2, 'USD', '500.00', 50, '2023-02-12 12:02:46', '2023-02-12 12:02:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `not_type` varchar(50) NOT NULL,
  `target_id` int(11) NOT NULL,
  `not_title` varchar(300) NOT NULL,
  `is_seen` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `per_id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `page_name` varchar(300) NOT NULL,
  `show_action` tinyint(1) NOT NULL,
  `add_action` tinyint(1) NOT NULL,
  `edit_action` tinyint(1) NOT NULL,
  `delete_action` tinyint(1) NOT NULL,
  `additional_permissions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`per_id`, `user_id`, `page_name`, `show_action`, `add_action`, `edit_action`, `delete_action`, `additional_permissions`) VALUES
(166, 47, 'admin/users', 0, 0, 0, 0, 'null'),
(167, 47, 'admin/translate', 0, 0, 0, 0, 'null'),
(168, 47, 'admin/transfer_money', 0, 0, 0, 0, 'null'),
(169, 47, 'admin/support_messages', 0, 0, 0, 0, 'null'),
(170, 47, 'admin/site_content', 0, 0, 0, 0, '[\"can_edit_content\"]'),
(171, 47, 'admin/settings', 0, 0, 0, 0, 'null'),
(172, 47, 'admin/saved_hotels', 1, 0, 1, 0, 'null'),
(173, 47, 'admin/saved_flights', 1, 0, 1, 0, 'null'),
(174, 47, 'admin/reports', 0, 0, 0, 0, 'null'),
(175, 47, 'admin/pages', 1, 0, 1, 0, 'null'),
(176, 47, 'admin/languages', 0, 0, 0, 0, 'null'),
(177, 47, 'admin/hotel_bookings', 0, 0, 0, 0, 'null'),
(178, 47, 'admin/flight_bookings', 0, 0, 0, 0, 'null'),
(179, 47, 'admin/booking_settings', 0, 0, 0, 0, 'null'),
(180, 47, 'admin/booking', 0, 0, 0, 0, 'null'),
(181, 47, 'admin/bank_types', 0, 0, 0, 0, 'null'),
(182, 47, 'admin/bank_accounts', 0, 0, 0, 0, 'null'),
(183, 47, 'admin/articles', 0, 0, 0, 0, 'null'),
(184, 47, 'admin/article_categories', 0, 0, 0, 0, 'null'),
(185, 47, 'admin/agents', 0, 0, 0, 0, 'null'),
(186, 47, 'admin/admins', 0, 0, 0, 0, 'null'),
(209, 48, 'admin/users', 0, 0, 0, 0, 'null'),
(210, 48, 'admin/translate', 0, 0, 0, 0, 'null'),
(211, 48, 'admin/transfer_money', 0, 0, 0, 0, 'null'),
(212, 48, 'admin/support_messages', 0, 0, 0, 0, 'null'),
(213, 48, 'admin/site_content', 0, 0, 0, 0, '[\"can_edit_content\"]'),
(214, 48, 'admin/settings', 0, 0, 0, 0, 'null'),
(215, 48, 'admin/saved_hotels', 1, 0, 1, 0, 'null'),
(216, 48, 'admin/saved_flights', 1, 0, 1, 0, 'null'),
(217, 48, 'admin/reports', 0, 0, 0, 0, 'null'),
(218, 48, 'admin/pages', 1, 0, 1, 0, 'null'),
(219, 48, 'admin/languages', 0, 0, 0, 0, 'null'),
(220, 48, 'admin/hotel_bookings', 0, 0, 0, 0, 'null'),
(221, 48, 'admin/flight_bookings', 0, 0, 0, 0, 'null'),
(222, 48, 'admin/dashboard', 0, 0, 0, 0, 'null'),
(223, 48, 'admin/booking_settings', 0, 0, 0, 0, 'null'),
(224, 48, 'admin/booking', 0, 0, 0, 0, 'null'),
(225, 48, 'admin/bank_types', 0, 0, 0, 0, 'null'),
(226, 48, 'admin/bank_accounts', 0, 0, 0, 0, 'null'),
(227, 48, 'admin/articles', 0, 0, 0, 0, 'null'),
(228, 48, 'admin/article_categories', 0, 0, 0, 0, 'null'),
(229, 48, 'admin/agents', 0, 0, 0, 0, 'null'),
(230, 48, 'admin/admins', 0, 0, 0, 0, 'null'),
(231, 47, 'admin/search_log', 0, 0, 0, 0, 'null'),
(232, 47, 'admin/dashboard', 0, 0, 0, 0, 'null'),
(233, 48, 'admin/search_log', 0, 0, 0, 0, 'null'),
(304, 57, 'admin/users', 0, 0, 0, 0, '[\"show_users\"]'),
(305, 57, 'admin/translate', 0, 0, 0, 0, '[\"can_use_translate_module\"]'),
(306, 57, 'admin/transfer_money', 0, 0, 0, 0, '[\"show_log\",\"accept_transfer\",\"send_transfer\"]'),
(307, 57, 'admin/transactions_log', 1, 1, 1, 1, 'null'),
(308, 57, 'admin/support_messages', 0, 0, 0, 0, 'null'),
(309, 57, 'admin/site_content', 0, 0, 0, 0, 'null'),
(310, 57, 'admin/settings', 0, 0, 0, 0, '[\"can_edit_settings\"]'),
(311, 57, 'admin/search_log', 0, 0, 0, 0, '[\"hotels_agency_log\",\"flights_agency_log\",\"hotels_country_log\",\"flights_country_log\"]'),
(312, 57, 'admin/saved_hotels', 0, 0, 0, 0, 'null'),
(313, 57, 'admin/saved_flights', 0, 0, 0, 0, 'null'),
(314, 57, 'admin/reports', 0, 0, 0, 0, '[\"show_profit_report\"]'),
(315, 57, 'admin/pages', 1, 1, 1, 1, 'null'),
(316, 57, 'admin/languages', 1, 1, 1, 1, 'null'),
(317, 57, 'admin/hotel_bookings', 0, 0, 0, 0, '[\"show_bookings\",\"cancel_bookings\",\"recalc_booking_prices\"]'),
(318, 57, 'admin/flight_bookings', 0, 0, 0, 0, '[\"show_bookings\",\"cancel_bookings\",\"recalc_booking_prices\"]'),
(319, 57, 'admin/dashboard', 1, 1, 1, 1, 'null'),
(320, 57, 'admin/booking_settings', 1, 1, 1, 1, 'null'),
(321, 57, 'admin/booking', 0, 0, 0, 0, '[\"show_flight_bookings\",\"show_hotel_bookings\"]'),
(322, 57, 'admin/bank_types', 1, 1, 1, 1, 'null'),
(323, 57, 'admin/bank_accounts', 1, 1, 1, 1, 'null'),
(324, 57, 'admin/articles', 0, 0, 0, 0, 'null'),
(325, 57, 'admin/article_categories', 0, 0, 0, 0, 'null'),
(326, 57, 'admin/agents', 1, 1, 1, 1, '[\"transfer_money\",\"lend_money\"]'),
(327, 57, 'admin/admins', 1, 1, 1, 1, '[\"manage_permissions\"]'),
(328, 93, 'admin/users', 0, 0, 0, 0, '[\"show_users\"]'),
(329, 93, 'admin/translate', 0, 0, 0, 0, '[\"can_use_translate_module\"]'),
(330, 93, 'admin/transfer_money', 0, 0, 0, 0, '[\"show_log\",\"accept_transfer\",\"send_transfer\",\"delete_log\"]'),
(331, 93, 'admin/transactions_log', 1, 1, 1, 1, 'null'),
(332, 93, 'admin/support_messages', 1, 1, 1, 1, 'null'),
(333, 93, 'admin/site_content', 0, 0, 0, 0, '[\"can_edit_content\",\"copy_from_lang_to_another\"]'),
(334, 93, 'admin/settings', 0, 0, 0, 0, '[\"can_edit_settings\"]'),
(335, 93, 'admin/search_log', 0, 0, 0, 0, '[\"hotels_agency_log\",\"flights_agency_log\",\"hotels_country_log\",\"flights_country_log\"]'),
(336, 93, 'admin/saved_hotels', 1, 1, 1, 1, 'null'),
(337, 93, 'admin/saved_flights', 1, 1, 1, 1, 'null'),
(338, 93, 'admin/reports', 0, 0, 0, 0, '[\"show_profit_report\"]'),
(339, 93, 'admin/pages', 1, 1, 1, 1, 'null'),
(340, 93, 'admin/languages', 1, 1, 1, 1, 'null'),
(341, 93, 'admin/hotel_bookings', 0, 0, 0, 0, '[\"show_bookings\",\"cancel_bookings\",\"recalc_booking_prices\"]'),
(342, 93, 'admin/flight_bookings', 0, 0, 0, 0, '[\"show_bookings\",\"cancel_bookings\",\"recalc_booking_prices\"]'),
(343, 93, 'admin/dashboard', 1, 1, 1, 1, 'null'),
(344, 93, 'admin/booking_settings', 1, 1, 1, 1, 'null'),
(345, 93, 'admin/booking', 0, 0, 0, 0, '[\"show_flight_bookings\",\"show_hotel_bookings\"]'),
(346, 93, 'admin/bank_types', 1, 1, 1, 1, 'null'),
(347, 93, 'admin/bank_accounts', 1, 1, 1, 1, 'null'),
(348, 93, 'admin/articles', 1, 1, 1, 1, 'null'),
(349, 93, 'admin/article_categories', 1, 1, 1, 1, 'null'),
(350, 93, 'admin/agents', 1, 1, 1, 1, '[\"transfer_money\",\"lend_money\"]'),
(351, 93, 'admin/admins', 0, 0, 0, 0, 'null'),
(352, 127, 'admin/users', 0, 0, 0, 0, '[\"show_users\"]'),
(353, 127, 'admin/translate', 0, 0, 0, 0, '[\"can_use_translate_module\"]'),
(354, 127, 'admin/transfer_money', 0, 0, 0, 0, '[\"show_log\",\"accept_transfer\",\"send_transfer\",\"delete_log\"]'),
(355, 127, 'admin/transactions_log', 1, 1, 1, 1, 'null'),
(356, 127, 'admin/support_messages', 1, 1, 1, 1, 'null'),
(357, 127, 'admin/site_content', 0, 0, 0, 0, '[\"can_edit_content\",\"copy_from_lang_to_another\"]'),
(358, 127, 'admin/settings', 0, 0, 0, 0, '[\"can_edit_settings\"]'),
(359, 127, 'admin/search_log', 0, 0, 0, 0, '[\"hotels_agency_log\",\"flights_agency_log\",\"hotels_country_log\",\"flights_country_log\"]'),
(360, 127, 'admin/saved_hotels', 1, 1, 1, 1, 'null'),
(361, 127, 'admin/saved_flights', 1, 1, 1, 1, 'null'),
(362, 127, 'admin/reports', 0, 0, 0, 0, 'null'),
(363, 127, 'admin/pages', 1, 1, 1, 1, 'null'),
(364, 127, 'admin/languages', 1, 1, 1, 1, 'null'),
(365, 127, 'admin/hotel_bookings', 0, 0, 0, 0, '[\"show_bookings\",\"cancel_bookings\",\"recalc_booking_prices\"]'),
(366, 127, 'admin/flight_bookings', 0, 0, 0, 0, '[\"show_bookings\",\"cancel_bookings\",\"recalc_booking_prices\"]'),
(367, 127, 'admin/dashboard', 1, 1, 1, 1, 'null'),
(368, 127, 'admin/booking_settings', 1, 1, 1, 1, 'null'),
(369, 127, 'admin/booking', 0, 0, 0, 0, '[\"show_flight_bookings\",\"show_hotel_bookings\"]'),
(370, 127, 'admin/bank_types', 1, 1, 1, 1, 'null'),
(371, 127, 'admin/bank_accounts', 1, 1, 1, 1, 'null'),
(372, 127, 'admin/articles', 1, 1, 1, 1, 'null'),
(373, 127, 'admin/article_categories', 1, 1, 1, 1, 'null'),
(374, 127, 'admin/agents', 1, 1, 1, 1, '[\"transfer_money\",\"lend_money\"]'),
(375, 127, 'admin/admins', 0, 0, 0, 0, 'null'),
(402, 140, 'admin/users', 0, 0, 0, 0, '[\"show_users\"]'),
(403, 140, 'admin/translate', 0, 0, 0, 0, '[\"can_use_translate_module\"]'),
(404, 140, 'admin/transfer_money', 0, 0, 0, 0, '[\"show_log\",\"accept_transfer\",\"send_transfer\",\"delete_log\"]'),
(405, 140, 'admin/transactions_log', 1, 1, 1, 1, 'null'),
(406, 140, 'admin/tickets', 1, 1, 1, 1, '[\"pending_tickets\",\"open_tickets\",\"should_reply_tickets\",\"fast_ticket_reply\",\"all_tickets\"]'),
(407, 140, 'admin/ticket_fast_reply', 1, 1, 1, 1, 'null'),
(408, 140, 'admin/support_messages', 1, 1, 1, 1, 'null'),
(409, 140, 'admin/site_content', 0, 0, 0, 0, '[\"can_edit_content\",\"copy_from_lang_to_another\"]'),
(410, 140, 'admin/settings', 0, 0, 0, 0, '[\"can_edit_settings\"]'),
(411, 140, 'admin/search_log', 0, 0, 0, 0, '[\"hotels_agency_log\",\"flights_agency_log\",\"hotels_country_log\",\"flights_country_log\"]'),
(412, 140, 'admin/saved_hotels', 1, 1, 1, 1, 'null'),
(413, 140, 'admin/saved_flights', 1, 1, 1, 1, 'null'),
(414, 140, 'admin/reports', 0, 0, 0, 0, 'null'),
(415, 140, 'admin/pages', 1, 1, 1, 1, 'null'),
(416, 140, 'admin/languages', 1, 1, 1, 1, 'null'),
(417, 140, 'admin/hotel_bookings', 0, 0, 0, 0, '[\"show_bookings\",\"cancel_bookings\",\"recalc_booking_prices\"]'),
(418, 140, 'admin/flight_bookings', 0, 0, 0, 0, '[\"show_bookings\",\"cancel_bookings\",\"recalc_booking_prices\"]'),
(419, 140, 'admin/dashboard', 1, 1, 1, 1, 'null'),
(420, 140, 'admin/booking_settings', 1, 1, 1, 1, 'null'),
(421, 140, 'admin/booking', 0, 0, 0, 0, '[\"show_flight_bookings\",\"show_hotel_bookings\"]'),
(422, 140, 'admin/bank_types', 1, 1, 1, 1, 'null'),
(423, 140, 'admin/bank_accounts', 1, 1, 1, 1, 'null'),
(424, 140, 'admin/articles', 1, 1, 1, 1, 'null'),
(425, 140, 'admin/article_categories', 1, 1, 1, 1, 'null'),
(426, 140, 'admin/agents', 1, 1, 1, 1, 'null'),
(427, 140, 'admin/admins', 1, 1, 1, 1, 'null'),
(428, 127, 'admin/tickets', 1, 1, 1, 1, '[\"pending_tickets\",\"open_tickets\",\"should_reply_tickets\",\"fast_ticket_reply\",\"all_tickets\"]'),
(429, 127, 'admin/ticket_fast_reply', 1, 1, 1, 1, 'null'),
(456, 57, 'admin/tickets', 0, 0, 0, 0, 'null'),
(457, 57, 'admin/ticket_fast_reply', 0, 0, 0, 0, 'null'),
(458, 93, 'admin/tickets', 1, 1, 1, 1, '[\"pending_tickets\",\"open_tickets\",\"should_reply_tickets\",\"fast_ticket_reply\",\"all_tickets\"]'),
(459, 93, 'admin/ticket_fast_reply', 1, 1, 1, 1, 'null'),
(460, 140, 'admin/offline-packages', 1, 1, 1, 1, '[\"pending\",\"accepted\",\"calculated\",\"calculated-to-agency\",\"confirm-from-client\",\"all\",\"in-progress\",\"finished\",\"confirmed-by-agency\"]'),
(461, 57, 'admin/offline-packages', 0, 0, 0, 0, 'null'),
(462, 127, 'admin/offline-packages', 1, 1, 1, 1, '[\"pending\",\"accepted\",\"calculated\",\"calculated-to-agency\",\"confirm-from-client\",\"cancelled\",\"rejected\",\"all\",\"in-progress\",\"finished\",\"confirmed-by-agency\"]'),
(518, 93, 'admin/offline-packages', 1, 1, 1, 1, '[\"pending\",\"accepted\",\"calculated\",\"calculated-to-agency\",\"confirm-from-client\",\"cancelled\",\"rejected\",\"all\",\"in-progress\",\"finished\",\"confirmed-by-agency\"]'),
(1429, 4, 'admin/support_messages', 1, 1, 1, 1, '[]'),
(1430, 4, 'admin/gift_cards', 1, 1, 1, 1, '[\"add_action\",\"show_action\"]'),
(1431, 4, 'admin/clients_orders', 1, 1, 1, 1, '[\"show_action\"]'),
(1432, 4, 'admin/clients', 1, 1, 1, 1, '[]'),
(2181, 7, 'admin/transactions_log', 1, 1, 1, 1, '[\"show_log\",\"deposit_money\",\"withdraw_money\"]'),
(2182, 7, 'admin/taxes_groups', 1, 1, 1, 1, '[]'),
(2183, 7, 'admin/support_messages', 1, 1, 1, 1, '[]'),
(2184, 7, 'admin/reports_total_quantities_at_inventory', 1, 1, 1, 1, '[\"show_report\"]'),
(2185, 7, 'admin/reports_sum_orders_items_categories', 1, 1, 1, 1, '[\"show_report\"]'),
(2186, 7, 'admin/reports_sum_orders_items_brands', 1, 1, 1, 1, '[\"show_report\"]'),
(2187, 7, 'admin/reports_sold_products_sku', 1, 1, 1, 1, '[\"show_report\"]'),
(2188, 7, 'admin/reports_sold_products', 1, 1, 1, 1, '[\"show_report\"]'),
(2189, 7, 'admin/reports_count_orders_categories', 1, 1, 1, 1, '[\"show_report\"]'),
(2190, 7, 'admin/reports_count_orders_brands', 1, 1, 1, 1, '[\"show_report\"]'),
(2191, 7, 'admin/registers_sessions_logs', 1, 1, 1, 1, '[\"show_action\"]'),
(2192, 7, 'admin/registers_sessions', 1, 1, 1, 1, '[\"start_session\",\"end_session\",\"show_action\",\"add_change\"]'),
(2193, 7, 'admin/registers', 1, 1, 1, 1, '[]'),
(2194, 7, 'admin/permissions', 1, 1, 1, 1, '[\"assign_all_permission\",\"manage_permissions\"]'),
(2195, 7, 'admin/paycheck', 1, 1, 1, 1, '[\"add_action\",\"change_is_received\"]'),
(2196, 7, 'admin/my_hr_national_holidays', 1, 1, 1, 1, '[]'),
(2197, 7, 'admin/my_hr_holiday_requests', 1, 1, 1, 1, '[]'),
(2198, 7, 'admin/my_hr_employees_warnings', 1, 1, 1, 1, '[\"show_action\"]'),
(2199, 7, 'admin/my_hr_employees_tasks_comments', 1, 1, 1, 1, '[\"add_action\",\"delete_action\"]'),
(2200, 7, 'admin/my_hr_employees_tasks', 1, 1, 1, 1, '[\"work_on_task\",\"show_task\"]'),
(2201, 7, 'admin/my_hr_employee_login_logout', 1, 1, 1, 1, '[\"show_action\",\"add_action\"]'),
(2202, 7, 'admin/my_hr_delay_early_requests', 1, 1, 1, 1, '[]'),
(2203, 7, 'admin/inventories_products', 1, 1, 1, 1, '[\"add_product\",\"add_broken_product\",\"move_product\",\"show_inventories_products\"]'),
(2204, 7, 'admin/inventories_log', 1, 1, 1, 1, '[\"show_logs\",\"add_invalid_entry\"]'),
(2205, 7, 'admin/holiday_requests', 1, 1, 1, 1, '[\"show_action\",\"accept_action\"]'),
(2206, 7, 'admin/gift_cards', 1, 1, 1, 1, '[\"add_action\",\"show_action\"]'),
(2207, 7, 'admin/expenses', 1, 1, 1, 1, '[]'),
(2208, 7, 'admin/employees_warnings', 1, 1, 1, 1, '[]'),
(2209, 7, 'admin/employees_tasks_comments', 1, 1, 1, 1, '[\"add_action\",\"delete_action\"]'),
(2210, 7, 'admin/employees_tasks', 1, 1, 1, 1, '[\"show_task\",\"accept_task\"]'),
(2211, 7, 'admin/employees', 1, 1, 1, 1, '[]'),
(2212, 7, 'admin/employee_login_logout', 1, 1, 1, 1, '[\"edit_action\"]'),
(2213, 7, 'admin/employee_action_log', 1, 1, 1, 1, '[\"show_action\"]'),
(2214, 7, 'admin/delay_early_requests', 1, 1, 1, 1, '[\"show_action\",\"accept_action\"]'),
(2215, 7, 'admin/coupons', 1, 1, 1, 1, '[]'),
(2216, 7, 'admin/clients_orders', 1, 1, 1, 1, '[\"show_action\"]'),
(2217, 7, 'admin/clients', 1, 1, 1, 1, '[]'),
(2218, 7, 'admin/branches_prices', 1, 1, 1, 1, '[\"show_branch_prices\",\"update_branch_prices\"]'),
(2219, 7, 'admin/branches', 1, 1, 1, 1, '[\"access_branch\"]'),
(2222, 3, 'admin/transactions_log', 1, 1, 1, 1, '[\"show_log\",\"deposit_money\",\"withdraw_money\"]'),
(2223, 3, 'admin/taxes_groups', 1, 1, 1, 1, '[]'),
(2224, 3, 'admin/support_messages', 1, 1, 1, 1, '[]'),
(2225, 3, 'admin/reports_total_quantities_at_inventory', 1, 1, 1, 1, '[\"show_report\"]'),
(2226, 3, 'admin/reports_sum_orders_items_categories', 1, 1, 1, 1, '[\"show_report\"]'),
(2227, 3, 'admin/reports_sum_orders_items_brands', 1, 1, 1, 1, '[\"show_report\"]'),
(2228, 3, 'admin/reports_sold_products_sku', 1, 1, 1, 1, '[\"show_report\"]'),
(2229, 3, 'admin/reports_sold_products', 1, 1, 1, 1, '[\"show_report\"]'),
(2230, 3, 'admin/reports_count_orders_categories', 1, 1, 1, 1, '[\"show_report\"]'),
(2231, 3, 'admin/reports_count_orders_brands', 1, 1, 1, 1, '[\"show_report\"]'),
(2232, 3, 'admin/registers_sessions_logs', 1, 1, 1, 1, '[\"show_action\"]'),
(2233, 3, 'admin/registers_sessions', 1, 1, 1, 1, '[\"start_session\",\"end_session\",\"show_action\",\"add_change\"]'),
(2234, 3, 'admin/registers', 1, 1, 1, 1, '[]'),
(2235, 3, 'admin/permissions', 1, 1, 1, 1, '[\"assign_all_permission\",\"manage_permissions\"]'),
(2236, 3, 'admin/paycheck', 1, 1, 1, 1, '[\"add_action\",\"change_is_received\"]'),
(2237, 3, 'admin/my_hr_national_holidays', 1, 1, 1, 1, '[]'),
(2238, 3, 'admin/my_hr_holiday_requests', 1, 1, 1, 1, '[]'),
(2239, 3, 'admin/my_hr_employees_warnings', 1, 1, 1, 1, '[\"show_action\"]'),
(2240, 3, 'admin/my_hr_employees_tasks_comments', 1, 1, 1, 1, '[\"add_action\",\"delete_action\"]'),
(2241, 3, 'admin/my_hr_employees_tasks', 1, 1, 1, 1, '[\"work_on_task\",\"show_task\"]'),
(2242, 3, 'admin/my_hr_employee_login_logout', 1, 1, 1, 1, '[\"show_action\",\"add_action\"]'),
(2243, 3, 'admin/my_hr_delay_early_requests', 1, 1, 1, 1, '[]'),
(2244, 3, 'admin/inventories_products', 1, 1, 1, 1, '[\"add_product\",\"add_broken_product\",\"move_product\",\"show_inventories_products\"]'),
(2245, 3, 'admin/inventories_log', 1, 1, 1, 1, '[\"show_logs\",\"add_invalid_entry\"]'),
(2246, 3, 'admin/holiday_requests', 1, 1, 1, 1, '[\"show_action\",\"accept_action\"]'),
(2247, 3, 'admin/gift_cards', 1, 1, 1, 1, '[\"add_action\",\"show_action\"]'),
(2248, 3, 'admin/expenses', 1, 1, 1, 1, '[]'),
(2249, 3, 'admin/employees_warnings', 1, 1, 1, 1, '[]'),
(2250, 3, 'admin/employees_tasks_comments', 1, 1, 1, 1, '[\"add_action\",\"delete_action\"]'),
(2251, 3, 'admin/employees_tasks', 1, 1, 1, 1, '[\"show_task\",\"accept_task\"]'),
(2252, 3, 'admin/employees', 1, 1, 1, 1, '[]'),
(2253, 3, 'admin/employee_login_logout', 1, 1, 1, 1, '[\"edit_action\"]'),
(2254, 3, 'admin/employee_action_log', 1, 1, 1, 1, '[\"show_action\"]'),
(2255, 3, 'admin/delay_early_requests', 1, 1, 1, 1, '[\"show_action\",\"accept_action\"]'),
(2256, 3, 'admin/coupons', 1, 1, 1, 1, '[]'),
(2257, 3, 'admin/clients_orders', 1, 1, 1, 1, '[\"show_action\"]'),
(2258, 3, 'admin/clients', 1, 1, 1, 1, '[]'),
(2259, 3, 'admin/branches_prices', 1, 1, 1, 1, '[\"show_branch_prices\",\"update_branch_prices\"]'),
(2260, 3, 'admin/branches', 1, 1, 1, 1, '[\"access_branch\"]'),
(2463, 1, 'admin/transactions_log', 1, 1, 1, 1, '[\"show_log\",\"deposit_money\",\"withdraw_money\"]'),
(2464, 1, 'admin/taxes_groups', 1, 1, 1, 1, '[]'),
(2465, 1, 'admin/support_messages', 1, 1, 1, 1, '[]'),
(2466, 1, 'admin/suppliers_orders', 1, 1, 1, 1, '[\"show_order\"]'),
(2467, 1, 'admin/suppliers', 1, 1, 1, 1, '[]'),
(2468, 1, 'admin/site_content', 1, 1, 1, 1, '[\"can_edit_content\"]'),
(2469, 1, 'admin/settings', 1, 1, 1, 1, '[\"can_edit_settings\"]'),
(2470, 1, 'admin/services', 1, 1, 1, 1, '[]'),
(2471, 1, 'admin/reports_total_quantities_at_inventory', 1, 1, 1, 1, '[\"show_report\"]'),
(2472, 1, 'admin/reports_sum_orders_items_categories', 1, 1, 1, 1, '[\"show_report\"]'),
(2473, 1, 'admin/reports_sum_orders_items_brands', 1, 1, 1, 1, '[\"show_report\"]'),
(2474, 1, 'admin/reports_sold_products_sku_sales_sum', 1, 1, 1, 1, '[\"show_report\"]'),
(2475, 1, 'admin/reports_sold_products_sku', 1, 1, 1, 1, '[\"show_report\"]'),
(2476, 1, 'admin/reports_sold_products_sales_sum', 1, 1, 1, 1, '[\"show_report\"]'),
(2477, 1, 'admin/reports_sold_products', 1, 1, 1, 1, '[\"show_report\"]'),
(2478, 1, 'admin/reports_orders_items_categories_sales_sum', 1, 1, 1, 1, '[\"show_report\"]'),
(2479, 1, 'admin/reports_orders_items_categories_sales_quantity', 1, 1, 1, 1, '[\"show_report\"]'),
(2480, 1, 'admin/reports_orders_items_brands_sales_sum', 1, 1, 1, 1, '[\"show_report\"]'),
(2481, 1, 'admin/reports_orders_items_brands_sales_quantity', 1, 1, 1, 1, '[\"show_report\"]'),
(2482, 1, 'admin/reports_count_orders_categories', 1, 1, 1, 1, '[\"show_report\"]'),
(2483, 1, 'admin/reports_count_orders_brands', 1, 1, 1, 1, '[\"show_report\"]'),
(2484, 1, 'admin/reports_count_client', 1, 1, 1, 1, '[\"show_report\"]'),
(2485, 1, 'admin/reports_client_orders_sum', 1, 1, 1, 1, '[\"show_report\"]'),
(2486, 1, 'admin/reports_client_orders_count', 1, 1, 1, 1, '[\"show_report\"]'),
(2487, 1, 'admin/registers_sessions_logs', 1, 1, 1, 1, '[\"show_action\"]'),
(2488, 1, 'admin/registers_sessions', 1, 1, 1, 1, '[\"start_session\",\"end_session\",\"show_action\",\"add_change\"]'),
(2489, 1, 'admin/registers', 1, 1, 1, 1, '[]'),
(2490, 1, 'admin/products', 1, 1, 1, 1, '[\"delete_variant\",\"show_product_skus\"]'),
(2491, 1, 'admin/product_promotions', 1, 1, 1, 1, '[]'),
(2492, 1, 'admin/permissions', 1, 1, 1, 1, '[\"assign_all_permission\",\"manage_permissions\"]'),
(2493, 1, 'admin/paycheck', 1, 1, 1, 1, '[\"add_action\",\"change_is_received\"]'),
(2494, 1, 'admin/pages', 1, 1, 1, 1, '[]'),
(2495, 1, 'admin/national_holidays', 1, 1, 1, 1, '[]'),
(2496, 1, 'admin/my_hr_national_holidays', 1, 1, 1, 1, '[]'),
(2497, 1, 'admin/my_hr_holiday_requests', 1, 1, 1, 1, '[]'),
(2498, 1, 'admin/my_hr_employees_warnings', 1, 1, 1, 1, '[\"show_action\"]'),
(2499, 1, 'admin/my_hr_employees_tasks_comments', 1, 1, 1, 1, '[\"add_action\",\"delete_action\"]'),
(2500, 1, 'admin/my_hr_employees_tasks', 1, 1, 1, 1, '[\"work_on_task\",\"show_task\"]'),
(2501, 1, 'admin/my_hr_employee_login_logout', 1, 1, 1, 1, '[\"show_action\",\"add_action\"]'),
(2502, 1, 'admin/my_hr_delay_early_requests', 1, 1, 1, 1, '[]'),
(2503, 1, 'admin/money_to_loyalty_points', 1, 1, 1, 1, '[]'),
(2504, 1, 'admin/money_installments', 1, 1, 1, 1, '[\"show_schedule_money\",\"schedule_all_debt_money\",\"add_schedule_debt_money\",\"schedule_all_owed_money\",\"add_schedule_owed_money\",\"delete_schedule_money\",\"edit_schedule_money\",\"receive_money_installment\"]'),
(2505, 1, 'admin/loyalty_points_to_money', 1, 1, 1, 1, '[]'),
(2506, 1, 'admin/languages', 1, 1, 1, 1, '[]'),
(2507, 1, 'admin/inventories_products', 1, 1, 1, 1, '[\"add_product\",\"add_broken_product\",\"move_product\",\"show_inventories_products\"]'),
(2508, 1, 'admin/inventories_log', 1, 1, 1, 1, '[\"show_logs\",\"add_invalid_entry\"]'),
(2509, 1, 'admin/inventories', 1, 1, 1, 1, '[]'),
(2510, 1, 'admin/holiday_requests', 1, 1, 1, 1, '[\"show_action\",\"accept_action\"]'),
(2511, 1, 'admin/gift_cards', 1, 1, 1, 1, '[\"add_action\",\"show_action\"]'),
(2512, 1, 'admin/gift_card_templates', 1, 1, 1, 1, '[]'),
(2513, 1, 'admin/expenses', 1, 1, 1, 1, '[]'),
(2514, 1, 'admin/employees_warnings', 1, 1, 1, 1, '[]'),
(2515, 1, 'admin/employees_tasks_comments', 1, 1, 1, 1, '[\"add_action\",\"delete_action\"]'),
(2516, 1, 'admin/employees_tasks', 1, 1, 1, 1, '[\"show_task\",\"accept_task\"]'),
(2517, 1, 'admin/employees', 1, 1, 1, 1, '[]'),
(2518, 1, 'admin/employee_login_logout', 1, 1, 1, 1, '[\"edit_action\"]'),
(2519, 1, 'admin/employee_action_log', 1, 1, 1, 1, '[\"show_action\"]'),
(2520, 1, 'admin/delay_early_requests', 1, 1, 1, 1, '[\"show_action\",\"accept_action\"]'),
(2521, 1, 'admin/currencies', 1, 1, 1, 1, '[]'),
(2522, 1, 'admin/coupons', 1, 1, 1, 1, '[]'),
(2523, 1, 'admin/clients_orders', 1, 1, 1, 1, '[\"show_action\"]'),
(2524, 1, 'admin/clients', 1, 1, 1, 1, '[]'),
(2525, 1, 'admin/categories', 1, 1, 1, 1, '[]'),
(2526, 1, 'admin/brands', 1, 1, 1, 1, '[]'),
(2527, 1, 'admin/branches_prices', 1, 1, 1, 1, '[\"show_branch_prices\",\"update_branch_prices\"]'),
(2528, 1, 'admin/branches_inventories', 1, 1, 1, 1, '[]'),
(2529, 1, 'admin/branches', 1, 1, 1, 1, '[\"access_branch\"]'),
(2530, 1, 'admin/admins', 1, 1, 1, 1, '[]');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `pro_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `pro_name` text NOT NULL,
  `pro_img_obj` varchar(300) NOT NULL,
  `pro_slider` text NOT NULL,
  `pro_desc` text NOT NULL,
  `standard_box_quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pro_id`, `cat_id`, `brand_id`, `pro_name`, `pro_img_obj`, `pro_slider`, `pro_desc`, `standard_box_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 2, 1, '{\"en\":\"vape type 1\"}', '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', '{\"slider_objs\":[],\"other_fields\":[]}', '{\"en\":\"vape type 1\"}', 12, '2022-11-29 10:03:42', '2022-11-29 10:03:42', NULL),
(3, 3, 2, '{\"en\":\"vape type 2\"}', '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', '{\"slider_objs\":[],\"other_fields\":[]}', '{\"en\":\"vape type 2\"}', 12, '2023-02-27 17:18:54', '2023-02-28 17:39:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_promotions`
--

CREATE TABLE `product_promotions` (
  `promo_id` int(11) NOT NULL,
  `promo_branch_id` int(11) DEFAULT NULL COMMENT 'if null then it will apply at all branches',
  `promo_title` varchar(300) NOT NULL,
  `promo_start_at` datetime NOT NULL,
  `promo_end_at` datetime NOT NULL,
  `promo_sku_ids` text COMMENT 'if null or empty, it will apply at all products',
  `promo_discount_percent` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_promotions`
--

INSERT INTO `product_promotions` (`promo_id`, `promo_branch_id`, `promo_title`, `promo_start_at`, `promo_end_at`, `promo_sku_ids`, `promo_discount_percent`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '{\"en\":\"new prom\"}', '2022-11-01 11:30:00', '2023-11-30 11:30:00', '[\"1\"]', 10, '2022-11-29 10:31:03', '2023-02-12 11:00:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `review_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `review_stars_val` int(11) NOT NULL,
  `review_approved` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_skus`
--

CREATE TABLE `product_skus` (
  `ps_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `ps_box_barcode` varchar(300) NOT NULL,
  `ps_item_barcode` varchar(300) NOT NULL,
  `ps_box_bought_price` decimal(10,2) NOT NULL,
  `ps_item_bought_price` decimal(10,2) NOT NULL,
  `ps_item_retailer_price` decimal(10,2) NOT NULL,
  `ps_item_wholesaler_price` decimal(10,2) NOT NULL,
  `ps_box_retailer_price` decimal(10,2) NOT NULL,
  `ps_box_wholesaler_price` decimal(10,2) NOT NULL,
  `ps_selected_variant_type_values` text NOT NULL,
  `ps_selected_variant_type_values_text` text NOT NULL COMMENT 'selected variant type names',
  `is_active` tinyint(1) NOT NULL,
  `use_default_images` tinyint(1) NOT NULL,
  `ps_img_obj` varchar(300) NOT NULL,
  `ps_slider` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_skus`
--

INSERT INTO `product_skus` (`ps_id`, `pro_id`, `ps_box_barcode`, `ps_item_barcode`, `ps_box_bought_price`, `ps_item_bought_price`, `ps_item_retailer_price`, `ps_item_wholesaler_price`, `ps_box_retailer_price`, `ps_box_wholesaler_price`, `ps_selected_variant_type_values`, `ps_selected_variant_type_values_text`, `is_active`, `use_default_images`, `ps_img_obj`, `ps_slider`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '103437368', '38944646', '0.00', '0.00', '100.00', '90.00', '1200.00', '1080.00', '1', '100 ml', 1, 1, '', '', '2022-11-29 10:03:42', '2022-11-29 15:58:41', NULL),
(2, 2, '82507379', '109876233', '0.00', '0.00', '200.00', '180.00', '2400.00', '2160.00', '2', '200 ml', 1, 1, '', '', '2022-11-29 10:03:42', '2022-11-29 10:05:37', NULL),
(3, 2, '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '1,3', '100 ml-banana', 1, 1, '', '', '2023-02-27 13:53:20', '2023-02-27 13:54:11', NULL),
(4, 2, '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '1,4', '100 ml-mango', 1, 1, '', '', '2023-02-27 13:53:20', '2023-02-27 13:54:13', NULL),
(5, 2, '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2,3', '200 ml-banana', 1, 1, '', '', '2023-02-27 13:53:20', '2023-02-27 13:54:14', NULL),
(6, 2, '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2,4', '200 ml-mango', 1, 1, '', '', '2023-02-27 13:53:20', '2023-02-27 13:54:15', NULL),
(7, 3, '13909672', '11740986', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '5', 'strwberry', 0, 1, '', '', '2023-02-27 17:18:54', '2023-03-01 22:14:38', NULL),
(8, 3, 'xd-123', '94935400', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6', 'mango', 0, 1, '', '', '2023-02-27 17:18:54', '2023-03-01 22:14:36', NULL),
(9, 3, '19132061', '73569172', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '7', 'vanilia', 0, 1, '', '', '2023-02-27 17:18:54', '2023-03-01 22:14:34', NULL),
(10, 3, '40119174', '24405235', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '5,8', 'strwberry-3', 1, 1, '', '', '2023-03-01 22:13:53', '2023-03-01 22:14:31', NULL),
(11, 3, '79334787', '56504809', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '5,9', 'strwberry-6', 1, 1, '', '', '2023-03-01 22:13:53', '2023-03-01 22:14:29', NULL),
(12, 3, '33898504', '35099284', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6,8', 'mango-3', 1, 1, '', '', '2023-03-01 22:13:53', '2023-03-01 22:14:28', NULL),
(13, 3, '24690372', '88354623', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6,9', 'mango-6', 1, 1, '', '', '2023-03-01 22:13:53', '2023-03-01 22:14:26', NULL),
(14, 3, '87008843', '17786553', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '7,8', 'vanilia-3', 1, 1, '', '', '2023-03-01 22:13:53', '2023-03-01 22:14:23', NULL),
(15, 3, '74768733', '104026208', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '7,9', 'vanilia-6', 1, 1, '', '', '2023-03-01 22:13:53', '2023-03-01 22:14:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_types`
--

CREATE TABLE `product_variant_types` (
  `variant_type_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `variant_type_name` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_variant_types`
--

INSERT INTO `product_variant_types` (`variant_type_id`, `pro_id`, `variant_type_name`) VALUES
(1, 2, 'size'),
(2, 2, 'flavor'),
(3, 3, 'flavor'),
(4, 3, 'mg');

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_type_values`
--

CREATE TABLE `product_variant_type_values` (
  `vt_value_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `variant_type_id` int(11) NOT NULL,
  `vt_value_name` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_variant_type_values`
--

INSERT INTO `product_variant_type_values` (`vt_value_id`, `pro_id`, `variant_type_id`, `vt_value_name`) VALUES
(1, 2, 1, '100 ml'),
(2, 2, 1, '200 ml'),
(3, 2, 2, 'banana'),
(4, 2, 2, 'mango'),
(5, 3, 3, 'strwberry'),
(6, 3, 3, 'mango'),
(7, 3, 3, 'vanilia'),
(8, 3, 4, '3'),
(9, 3, 4, '6');

-- --------------------------------------------------------

--
-- Table structure for table `registers`
--

CREATE TABLE `registers` (
  `register_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `register_name` varchar(300) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `registers`
--

INSERT INTO `registers` (`register_id`, `branch_id`, `register_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'first register', '2022-11-29 10:18:47', '2022-11-29 10:18:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `register_sessions`
--

CREATE TABLE `register_sessions` (
  `id` int(11) NOT NULL,
  `register_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `register_start_at` datetime NOT NULL,
  `register_start_cash_amount` decimal(10,2) NOT NULL,
  `register_closed_at` datetime DEFAULT NULL,
  `register_end_cash_amount` decimal(10,2) NOT NULL,
  `register_end_debit_count` int(11) NOT NULL,
  `register_end_debit_amount` decimal(10,2) NOT NULL,
  `register_end_credit_count` int(11) NOT NULL,
  `register_end_credit_amount` decimal(10,2) NOT NULL,
  `register_end_cheque_count` int(11) NOT NULL,
  `register_end_cheque_amount` decimal(10,2) NOT NULL,
  `approved_by_admin` tinyint(1) NOT NULL,
  `approved_by_user_id` int(11) NOT NULL COMMENT 'user => admin branch or admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `register_sessions`
--

INSERT INTO `register_sessions` (`id`, `register_id`, `employee_id`, `register_start_at`, `register_start_cash_amount`, `register_closed_at`, `register_end_cash_amount`, `register_end_debit_count`, `register_end_debit_amount`, `register_end_credit_count`, `register_end_credit_amount`, `register_end_cheque_count`, `register_end_cheque_amount`, `approved_by_admin`, `approved_by_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 3, '2022-12-29 06:55:44', '100.00', NULL, '0.00', 0, '0.00', 0, '0.00', 0, '0.00', 0, 0, '2022-12-29 04:55:44', '2022-12-29 04:55:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `register_session_logs`
--

CREATE TABLE `register_session_logs` (
  `id` int(11) NOT NULL,
  `register_session_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL COMMENT 'item_id => order_id, gift_card_id',
  `item_type` varchar(300) NOT NULL COMMENT 'order, gift_card',
  `operation_type` varchar(300) NOT NULL COMMENT 'buy, return',
  `cash_paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `debit_card_paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit_card_paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cheque_paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `register_session_logs`
--

INSERT INTO `register_session_logs` (`id`, `register_session_id`, `item_id`, `item_type`, `operation_type`, `cash_paid_amount`, `debit_card_paid_amount`, `credit_card_paid_amount`, `cheque_paid_amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 0, 'change', 'increase', '100.00', '0.00', '0.00', '0.00', '2022-12-29 05:05:02', '2022-12-29 05:05:02', NULL),
(2, 1, 5, 'order', 'increase', '180.00', '0.00', '0.00', '0.00', '2023-01-31 22:02:56', '2023-01-31 22:02:56', NULL),
(3, 1, 6, 'order', 'increase', '111.60', '0.00', '0.00', '0.00', '2023-02-12 12:25:34', '2023-02-12 12:25:34', NULL),
(4, 1, 7, 'order', 'increase', '111.60', '0.00', '0.00', '0.00', '2023-02-12 12:39:59', '2023-02-12 12:39:59', NULL),
(5, 1, 8, 'order', 'increase', '248.00', '0.00', '0.00', '0.00', '2023-02-12 12:41:03', '2023-02-12 12:41:03', NULL),
(6, 1, 9, 'order', 'increase', '223.20', '0.00', '0.00', '0.00', '2023-02-12 12:51:26', '2023-02-12 12:51:26', NULL),
(7, 1, 10, 'order', 'increase', '100.44', '0.00', '0.00', '0.00', '2023-02-12 12:52:23', '2023-02-12 12:52:23', NULL),
(8, 1, 11, 'order', 'increase', '238.00', '0.00', '0.00', '0.00', '2023-02-12 12:54:32', '2023-02-12 12:54:32', NULL),
(9, 1, 12, 'order', 'increase', '198.20', '0.00', '0.00', '0.00', '2023-02-12 13:05:17', '2023-02-12 13:05:17', NULL),
(10, 1, 13, 'order', 'increase', '213.00', '0.00', '0.00', '0.00', '2023-02-12 13:06:40', '2023-02-12 13:06:40', NULL),
(11, 1, 14, 'order', 'increase', '50.00', '61.60', '0.00', '0.00', '2023-02-12 13:11:36', '2023-02-12 13:11:36', NULL),
(12, 1, 15, 'order', 'increase', '300.00', '0.00', '0.00', '0.00', '2023-02-15 14:01:40', '2023-02-15 14:01:40', NULL),
(13, 1, 16, 'order', 'increase', '135.00', '0.00', '0.00', '0.00', '2023-02-21 15:52:29', '2023-02-21 15:52:29', NULL),
(14, 1, 17, 'order', 'increase', '300.00', '0.00', '0.00', '0.00', '2023-02-28 23:36:10', '2023-02-28 23:36:10', NULL),
(15, 1, 18, 'order', 'increase', '0.00', '0.00', '0.00', '0.00', '2023-03-01 16:45:26', '2023-03-01 16:45:26', NULL),
(16, 1, 19, 'order', 'increase', '0.00', '0.00', '0.00', '0.00', '2023-03-01 17:48:42', '2023-03-01 17:48:42', NULL),
(17, 1, 20, 'order', 'increase', '0.00', '0.00', '0.00', '0.00', '2023-03-01 18:07:09', '2023-03-01 18:07:09', NULL),
(18, 1, 21, 'order', 'increase', '0.00', '0.00', '0.00', '0.00', '2023-03-01 18:08:26', '2023-03-01 18:08:26', NULL),
(19, 1, 22, 'order', 'increase', '0.00', '0.00', '0.00', '0.00', '2023-03-01 18:08:59', '2023-03-01 18:08:59', NULL),
(20, 1, 23, 'order', 'increase', '0.00', '0.00', '0.00', '0.00', '2023-03-01 18:09:28', '2023-03-01 18:09:28', NULL),
(21, 1, 24, 'order', 'increase', '0.00', '0.00', '0.00', '0.00', '2023-03-01 18:10:37', '2023-03-01 18:10:37', NULL),
(22, 1, 25, 'order', 'increase', '0.00', '0.00', '0.00', '0.00', '2023-03-01 18:14:07', '2023-03-01 18:14:07', NULL),
(23, 1, 26, 'order', 'increase', '0.00', '0.00', '0.00', '0.00', '2023-03-02 04:35:42', '2023-03-02 04:35:42', NULL),
(24, 1, 26, 'order', 'decrease', '0.00', '0.00', '0.00', '0.00', '2023-03-02 04:21:10', '2023-03-02 04:21:10', NULL),
(25, 1, 26, 'order', 'decrease', '0.00', '0.00', '0.00', '0.00', '2023-03-02 04:22:46', '2023-03-02 04:22:46', NULL),
(26, 1, 27, 'order', 'increase', '0.00', '0.00', '0.00', '0.00', '2023-03-02 05:23:32', '2023-03-02 05:23:32', NULL),
(27, 1, 27, 'order', 'decrease', '0.00', '0.00', '0.00', '0.00', '2023-03-02 04:25:43', '2023-03-02 04:25:43', NULL),
(28, 1, 27, 'order', 'decrease', '0.00', '0.00', '0.00', '0.00', '2023-03-03 00:12:19', '2023-03-03 00:12:19', NULL),
(29, 1, 3, 'gift_card', 'increase', '500.00', '0.00', '0.00', '0.00', '2023-03-16 13:25:48', '2023-03-16 13:25:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8_unicode_ci,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7k2AMhd5Df4mA5E1TPXpxzu2EkQFswhu1CtkLD7d', 3, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'YToxMjp7czo2OiJfdG9rZW4iO3M6NDA6IjBmWTlTRmhkSUJDNFZnamdzZmVpd1Ywc2NjOTRxYlFHMUtheXduUmQiO3M6MTY6ImxhbmdfdXJsX3NlZ21lbnQiO3M6MToiLyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvbm90X2ZvdW5kX3BhZ2UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MTk6InBhc3N3b3JkX2NoYW5nZWRfYXQiO3M6MTk6IjIwMjItMTItMDEgMTg6MjU6MzAiO3M6MTI6InRoaXNfdXNlcl9pZCI7aTozO3M6MTQ6InRoaXNfdXNlcl90eXBlIjtzOjg6ImVtcGxveWVlIjtzOjEyOiJtZW51X2Rpc3BsYXkiO3M6NjoibmF2YmFyIjtzOjE3OiJjdXJyZW50X2JyYW5jaF9pZCI7aToxO3M6MTE6InJlZ2lzdGVyX2lkIjtzOjE6IjEiO3M6MTk6InJlZ2lzdGVyX3Nlc3Npb25faWQiO2k6MTt9', 1676480360),
('ar4A8RtPyHX8eBMwXbHG53fBAgxJcGw8VLHn90EP', 1, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36', 'YToxMDp7czo2OiJfdG9rZW4iO3M6NDA6InlaSllETlVXeFF2RnFESE45Y0tPdlNyNG0zNkVzMDhuNnhES0I0VnUiO3M6MTY6ImxhbmdfdXJsX3NlZ21lbnQiO3M6MToiLyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE5OiJwYXNzd29yZF9jaGFuZ2VkX2F0IjtzOjE5OiIyMDIyLTA5LTE1IDAwOjAwOjAwIjtzOjEyOiJ0aGlzX3VzZXJfaWQiO2k6MTtzOjE0OiJ0aGlzX3VzZXJfdHlwZSI7czozOiJkZXYiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjYzOiJodHRwOi8vbG9jYWxob3N0L3dvcmsvc2l0ZXMvY2FuZGFfZXJwL2FkbWluL3Byb2R1Y3RzLXNrdS9zaG93LzEiO31zOjEyOiJtZW51X2Rpc3BsYXkiO3M6NjoibmF2YmFyIjtzOjk6ImRhcmtfbW9kZSI7czozOiJvZmYiO30=', 1668138622),
('ba7ockXIpm07aTZ7KJ1hphtgBNsCFDoC6hxiuAD3', NULL, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRndyMmRxUXVFbU1TdFZPUWVUWGNNRW41cVJYZkNoTkhWYW5ic3BtYiI7czoxNzoiY3VycmVudF9icmFuY2hfaWQiO047czozOiJtc2ciO3M6NTg6IjxkaXYgY2xhc3M9J2FsZXJ0IGFsZXJ0LWluZm8nPllvdSBzaG91bGQgbG9naW4gZmlyc3Q8L2Rpdj4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czozOiJtc2ciO319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzA6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvYWRtaW4vZ2lmdC1jYXJkLXRlbXBsYXRlcy9zYXZlLzEiO319', 1678763509),
('bBi4TXKa6b0rQP9OWp3YG8RBWXceq9IDgAdAL5iZ', 1, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36', 'YToxMDp7czo2OiJfdG9rZW4iO3M6NDA6InRpT2RmcTlsWnA1VTBjWDFXeE41bGV6anVFbTd4MmRoQXA0OFk3VTQiO3M6MTY6ImxhbmdfdXJsX3NlZ21lbnQiO3M6MToiLyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvYWRtaW4vZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE5OiJwYXNzd29yZF9jaGFuZ2VkX2F0IjtzOjE5OiIyMDIyLTA5LTE1IDAwOjAwOjAwIjtzOjEyOiJ0aGlzX3VzZXJfaWQiO2k6MTtzOjE0OiJ0aGlzX3VzZXJfdHlwZSI7czozOiJkZXYiO3M6MTI6Im1lbnVfZGlzcGxheSI7czo2OiJuYXZiYXIiO3M6MTc6ImN1cnJlbnRfYnJhbmNoX2lkIjtOO30=', 1675194856),
('H4OTyo7THV7enuFJJAvUXRV31iovoy3ISDsyFGL3', 1, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiaTV5Rnl2WGxnc29zR2lYUVBGaG5RMWE5a0pjUHI1cXo4eTl5aERNMiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTk6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvbG9naW4/ZG9fbm90X2FqYXg9eWVzIjt9czoxNjoibGFuZ191cmxfc2VnbWVudCI7czoxOiIvIjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTk6InBhc3N3b3JkX2NoYW5nZWRfYXQiO3M6MTk6IjIwMjItMDktMTUgMDA6MDA6MDAiO3M6MTI6InRoaXNfdXNlcl9pZCI7aToxO3M6MTQ6InRoaXNfdXNlcl90eXBlIjtzOjM6ImRldiI7fQ==', 1663158979),
('Ivq1EciIJkmrKRBLVosfARB2sgFElrYtFW8f4R7m', 1, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36', 'YToxMjp7czo2OiJfdG9rZW4iO3M6NDA6InM4cEF0MTRDOWkwSEV0TWRZTVc5YWlPVlg1YjJWTmRwZFJSbTFHa1giO3M6MTY6ImxhbmdfdXJsX3NlZ21lbnQiO3M6MToiLyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvbm90X2ZvdW5kX3BhZ2UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTk6InBhc3N3b3JkX2NoYW5nZWRfYXQiO3M6MTk6IjIwMjItMDktMTUgMDA6MDA6MDAiO3M6MTI6InRoaXNfdXNlcl9pZCI7aToxO3M6MTQ6InRoaXNfdXNlcl90eXBlIjtzOjM6ImRldiI7czoxNzoiY3VycmVudF9icmFuY2hfaWQiO047czoxMjoibWVudV9kaXNwbGF5IjtzOjc6InNpZGViYXIiO3M6MTE6InJlZ2lzdGVyX2lkIjtpOjE7czoxOToicmVnaXN0ZXJfc2Vzc2lvbl9pZCI7aToxO30=', 1678982981),
('Nj0gx1yBqrpGFTfnBB1JDpD8mUY5s03oc3PZKD6A', 1, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiRkh3bW1lOWhTWGc0a2oxdWticjRPYTVGU2tMOUhxc3JzWTlhVVZQVyI7czoxNjoibGFuZ191cmxfc2VnbWVudCI7czoxOiIvIjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1MjoiaHR0cDovL2xvY2FsaG9zdC93b3JrL3NpdGVzL2NhbmRhX2VycC9ub3RfZm91bmRfcGFnZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxOToicGFzc3dvcmRfY2hhbmdlZF9hdCI7czoxOToiMjAyMi0wOS0xNSAwMDowMDowMCI7czoxMjoidGhpc191c2VyX2lkIjtpOjE7czoxNDoidGhpc191c2VyX3R5cGUiO3M6MzoiZGV2Ijt9', 1667755978),
('nM5ykrWFq73xJAq4ZbIIPTaetD49SIRryzZ4HtZ3', NULL, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOXdwUlMxYUxXV3lmMjhlbVVoOHNCZ3lYUmdZRlIwUGFPb0s3b2lVMyI7czoxNjoibGFuZ191cmxfc2VnbWVudCI7czoxOiIvIjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1OToiaHR0cDovL2xvY2FsaG9zdC93b3JrL3NpdGVzL2NhbmRhX2VycC9sb2dpbj9kb19ub3RfYWpheD15ZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1678763509),
('OG7GGXqAOWlbuiZ52YQz2IxUtsHgZFFf44FxhYUF', NULL, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWFRRd2VsbDlWTldPakF2WjFtYklLQVpoOHJqNWl4QzF6VFREZVBWYiI7czoxMjoidGhpc191c2VyX2lkIjtpOjE7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzA6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvYWRtaW4vZ2lmdC1jYXJkLXRlbXBsYXRlcy9zYXZlLzEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1678763518),
('SG2ejQ1DN1shduMDgKZ3bLa3pclfi3Oj8HU0sMiO', 1, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiU2FTZHZOR05mbUNqVHBDNjNlNUVlcU9rWWhlN0pFeFRNbzAwazNoTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTk6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvbG9naW4/ZG9fbm90X2FqYXg9eWVzIjt9czoxNjoibGFuZ191cmxfc2VnbWVudCI7czoxOiIvIjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTk6InBhc3N3b3JkX2NoYW5nZWRfYXQiO3M6MTk6IjIwMjItMDktMTUgMDA6MDA6MDAiO3M6MTI6InRoaXNfdXNlcl9pZCI7aToxO3M6MTQ6InRoaXNfdXNlcl90eXBlIjtzOjM6ImRldiI7fQ==', 1663158947),
('sNOASpx5qGROwHWOT2BKixgp0gbfGA3CZx9ukieI', 1, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTo4OntzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNzoiaHR0cDovL2xvY2FsaG9zdC93b3JrL3NpdGVzL2NhbmRhX2VycCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NjoiX3Rva2VuIjtzOjQwOiJ0bThLMm95TGd3OUlyQ05TUWY4Z2gyTHR3MTRMZTczSjM5dW1LSUZKIjtzOjE2OiJsYW5nX3VybF9zZWdtZW50IjtzOjE6Ii8iO3M6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxOToicGFzc3dvcmRfY2hhbmdlZF9hdCI7czoxOToiMjAyMi0wOS0xNSAwMDowMDowMCI7czoxMjoidGhpc191c2VyX2lkIjtpOjE7czoxNDoidGhpc191c2VyX3R5cGUiO3M6MzoiZGV2Ijt9', 1663159134),
('SOk0saKH0UoKeAcxR7Ic7wCnAR54SBerYqYob3R4', 1, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoibmk3REoyYmV3Y2MwVWZLWk5jWUpjSk5HanF5UWUwMVoydjdrbW4wTiI7czoxNjoibGFuZ191cmxfc2VnbWVudCI7czoxOiIvIjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1OToiaHR0cDovL2xvY2FsaG9zdC93b3JrL3NpdGVzL2NhbmRhX2VycC9sb2dpbj9kb19ub3RfYWpheD15ZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTk6InBhc3N3b3JkX2NoYW5nZWRfYXQiO3M6MTk6IjIwMjItMDktMTUgMDA6MDA6MDAiO3M6MTI6InRoaXNfdXNlcl9pZCI7aToxO3M6MTQ6InRoaXNfdXNlcl90eXBlIjtzOjM6ImRldiI7fQ==', 1663158935),
('tOYdoHIUhMbpbOWRpVw7EIxvUEYQ6zb1Gw18Y39m', NULL, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRlljOWxhcEZab3ZERmlDR2ptOXFNM1RzRkg0eG9jaEZHQWt4MUpjeiI7czozOiJtc2ciO3M6NTg6IjxkaXYgY2xhc3M9J2FsZXJ0IGFsZXJ0LWluZm8nPllvdSBzaG91bGQgbG9naW4gZmlyc3Q8L2Rpdj4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czozOiJtc2ciO319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjA6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvYWRtaW4vZW1wbG95ZWVzL3NhdmUvMiI7fX0=', 1669908058),
('WewnJ9RgJXVrtsFIUR8klLCznju16kgqwmv1FIsv', NULL, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic1lOcmJ5cFhER2lNaUFLQmw2WXdmdEFFMjRmTmpCVjBuc3RaeGF2SSI7czozOiJtc2ciO3M6NTg6IjxkaXYgY2xhc3M9J2FsZXJ0IGFsZXJ0LWluZm8nPllvdSBzaG91bGQgbG9naW4gZmlyc3Q8L2Rpdj4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czozOiJtc2ciO319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzA6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvYWRtaW4vc3VwcGxpZXJzLW9yZGVycy9hZGQtb3JkZXIiO319', 1668008307),
('wvMpiSF4foWEmkcO37EG0n73ar2RdArTEKBWWIe2', NULL, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibGNEV0FjUzBuSzMyY2pxdFZySnJhaGsxUzZWZ2tKaWtPU0lDQUk4OCI7czoxNzoiY3VycmVudF9icmFuY2hfaWQiO047czozOiJtc2ciO3M6NTg6IjxkaXYgY2xhc3M9J2FsZXJ0IGFsZXJ0LWluZm8nPllvdSBzaG91bGQgbG9naW4gZmlyc3Q8L2Rpdj4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czozOiJtc2ciO319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvYWRtaW4vZGFzaGJvYXJkIjt9fQ==', 1675194876),
('WyNZLU6I5ApS49pKazRhvzxmhuNdR3TF345xylaT', 1, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoidDFzb2dtNld5bEV5ZldTY0pUWHRvemw5OERuQXE4dmRGSDVFMlJxUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTk6Imh0dHA6Ly9sb2NhbGhvc3Qvd29yay9zaXRlcy9jYW5kYV9lcnAvbG9naW4/ZG9fbm90X2FqYXg9eWVzIjt9czoxNjoibGFuZ191cmxfc2VnbWVudCI7czoxOiIvIjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTk6InBhc3N3b3JkX2NoYW5nZWRfYXQiO3M6MTk6IjIwMjItMDktMTUgMDA6MDA6MDAiO3M6MTI6InRoaXNfdXNlcl9pZCI7aToxO3M6MTQ6InRoaXNfdXNlcl90eXBlIjtzOjM6ImRldiI7fQ==', 1663158976);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settings_id` int(10) UNSIGNED NOT NULL,
  `setting_group` varchar(255) NOT NULL COMMENT 'app or system or push_notification',
  `setting_key` varchar(255) NOT NULL COMMENT 'type or version or mail_type',
  `setting_type` varchar(255) NOT NULL COMMENT 'text or image',
  `setting_value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settings_id`, `setting_group`, `setting_key`, `setting_type`, `setting_value`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '', 'default_timezone', 'text', 'Africa/Cairo', '2022-12-29 04:36:17', '2022-12-28 20:37:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `sup_id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `sup_name` varchar(300) NOT NULL,
  `sup_phone` varchar(300) NOT NULL,
  `sup_company` varchar(300) NOT NULL,
  `sup_currency` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`sup_id`, `wallet_id`, `sup_name`, `sup_phone`, `sup_company`, `sup_currency`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 'bob the supplier', '123', '13', '', '2022-09-18 09:51:28', '2022-09-18 09:51:28', NULL),
(2, 25, 'Irma Carter', '+1 (393) 303-9284', 'Carver and Ingram Co', 'EUR', '2022-12-29 03:10:01', '2022-12-29 03:10:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_orders`
--

CREATE TABLE `supplier_orders` (
  `supplier_order_id` int(11) NOT NULL,
  `original_order_id` varchar(50) NOT NULL,
  `original_order_img_obj` varchar(300) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `total_items_cost` decimal(10,2) NOT NULL,
  `total_taxes` decimal(10,2) NOT NULL,
  `additional_fees_desc` varchar(300) NOT NULL,
  `additional_fees` decimal(10,2) NOT NULL,
  `total_return_amount` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `order_status` varchar(250) NOT NULL COMMENT 'pending, done',
  `ordered_at` datetime DEFAULT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `supplier_orders`
--

INSERT INTO `supplier_orders` (`supplier_order_id`, `original_order_id`, `original_order_img_obj`, `branch_id`, `supplier_id`, `employee_id`, `total_items_cost`, `total_taxes`, `additional_fees_desc`, `additional_fees`, `total_return_amount`, `total_cost`, `order_status`, `ordered_at`, `paid_amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, '', '', 0, 1, 1, '60.00', '0.00', '', '50.00', '0.00', '60.00', 'done', '2023-02-27 00:00:00', '60.00', '2023-02-27 01:11:17', '2023-02-27 01:11:17', NULL),
(11, '', '', 0, 1, 1, '56.00', '0.00', 'transportatin', '50.00', '0.00', '56.00', 'done', '2023-02-27 00:00:00', '6.00', '2023-02-27 18:19:44', '2023-02-27 18:19:44', NULL),
(12, '', '', 0, 2, 1, '1050.00', '0.00', '', '50.00', '0.00', '1050.00', 'done', '2023-02-27 00:00:00', '0.00', '2023-02-27 18:22:00', '2023-02-27 18:22:00', NULL),
(15, '', '', 0, 1, 1, '500.00', '0.00', '', '50.00', '0.00', '550.00', 'done', '2023-02-27 00:00:00', '500.00', '2023-02-27 18:38:21', '2023-02-27 18:38:21', NULL),
(16, '', '', 0, 1, 1, '500.00', '0.00', '', '50.00', '0.00', '550.00', 'done', '2023-02-27 00:00:00', '0.00', '2023-02-27 18:38:46', '2023-02-27 18:38:46', NULL),
(17, '', '', 0, 2, 1, '500.00', '0.00', '', '50.00', '0.00', '550.00', 'done', '2023-02-27 00:00:00', '0.00', '2023-02-27 18:40:10', '2023-02-27 18:40:10', NULL),
(18, '', '', 0, 1, 1, '50.00', '0.00', '', '10.00', '0.00', '60.00', 'done', '2023-03-01 00:00:00', '50.00', '2023-03-01 03:20:12', '2023-03-01 03:20:12', NULL),
(19, '', '', 0, 1, 1, '50.00', '0.00', '', '10.00', '0.00', '60.00', 'done', '2023-03-01 00:00:00', '0.00', '2023-03-01 03:20:35', '2023-03-01 03:22:14', NULL),
(20, '', '', 0, 1, 1, '100.00', '0.00', '', '10.00', '0.00', '110.00', 'done', '2023-03-01 00:00:00', '0.00', '2023-03-01 03:23:33', '2023-03-01 03:33:28', NULL),
(21, '', '', 0, 1, 1, '100.00', '0.00', '', '10.00', '0.00', '110.00', 'done', '2023-03-01 00:00:00', '0.00', '2023-03-01 03:33:52', '2023-03-01 03:35:57', NULL),
(22, '', '', 0, 1, 1, '100.00', '0.00', '', '10.00', '0.00', '110.00', 'cancel', '2023-03-01 00:00:00', '0.00', '2023-03-01 03:37:32', '2023-03-01 03:37:43', NULL),
(23, '', '', 0, 1, 1, '100.00', '0.00', '', '10.00', '0.00', '110.00', 'done', '2023-03-01 00:00:00', '0.00', '2023-03-01 03:38:06', '2023-03-01 03:38:36', NULL),
(24, '', '', 0, 1, 1, '100.00', '0.00', '', '0.00', '0.00', '100.00', 'pending', '2023-03-01 00:00:00', '0.00', '2023-03-01 03:45:35', '2023-03-01 03:45:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_order_items`
--

CREATE TABLE `supplier_order_items` (
  `id` int(11) NOT NULL,
  `operation_type` varchar(50) NOT NULL COMMENT 'buy or return',
  `supplier_order_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `pro_sku_id` int(11) NOT NULL,
  `item_type` varchar(50) NOT NULL COMMENT 'box or item',
  `order_quantity` int(11) NOT NULL,
  `item_cost` decimal(10,2) NOT NULL,
  `item_tax` decimal(10,2) NOT NULL,
  `item_total_cost` decimal(10,2) NOT NULL COMMENT 'item_cost + item_tax',
  `total_items_cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `supplier_order_items`
--

INSERT INTO `supplier_order_items` (`id`, `operation_type`, `supplier_order_id`, `inventory_id`, `pro_id`, `pro_sku_id`, `item_type`, `order_quantity`, `item_cost`, `item_tax`, `item_total_cost`, `total_items_cost`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'buy', 3, 1, 2, 1, 'item', 10, '0.00', '0.00', '1.00', '10.00', '2023-02-27 01:11:17', NULL, NULL),
(6, 'buy', 11, 1, 3, 8, 'item', 1, '0.00', '0.00', '6.00', '6.00', '2023-02-27 18:19:44', NULL, NULL),
(7, 'buy', 12, 1, 2, 1, 'item', 10, '0.00', '0.00', '100.00', '1000.00', '2023-02-27 18:22:00', NULL, NULL),
(8, 'buy', 15, 1, 3, 8, 'item', 10, '0.00', '0.00', '50.00', '500.00', '2023-02-27 18:38:21', NULL, NULL),
(9, 'buy', 16, 1, 2, 1, 'item', 10, '0.00', '0.00', '50.00', '500.00', '2023-02-27 18:38:46', NULL, NULL),
(10, 'buy', 17, 1, 2, 1, 'item', 10, '0.00', '0.00', '50.00', '500.00', '2023-02-27 18:40:10', NULL, NULL),
(11, 'buy', 18, 1, 3, 7, 'item', 1, '0.00', '0.00', '50.00', '50.00', '2023-03-01 03:20:12', NULL, NULL),
(12, 'buy', 19, 1, 3, 7, 'item', 1, '0.00', '0.00', '50.00', '50.00', '2023-03-01 03:20:35', NULL, NULL),
(13, 'return', 19, 1, 3, 7, 'item', 1, '0.00', '0.00', '50.00', '50.00', '2023-03-01 03:22:14', NULL, NULL),
(14, 'buy', 20, 1, 3, 7, 'item', 2, '0.00', '0.00', '50.00', '100.00', '2023-03-01 03:23:33', NULL, NULL),
(15, 'return', 20, 1, 3, 7, 'item', 1, '0.00', '0.00', '50.00', '50.00', '2023-03-01 03:29:58', NULL, NULL),
(16, 'return', 20, 1, 3, 7, 'item', 1, '0.00', '0.00', '50.00', '50.00', '2023-03-01 03:33:28', NULL, NULL),
(17, 'buy', 21, 1, 3, 7, 'item', 2, '0.00', '0.00', '50.00', '100.00', '2023-03-01 03:33:52', NULL, NULL),
(18, 'return', 21, 1, 3, 7, 'item', 2, '0.00', '0.00', '50.00', '100.00', '2023-03-01 03:35:57', NULL, NULL),
(19, 'buy', 22, 1, 3, 7, 'item', 2, '0.00', '0.00', '50.00', '100.00', '2023-03-01 03:37:32', NULL, NULL),
(20, 'buy', 23, 1, 3, 7, 'item', 2, '0.00', '0.00', '50.00', '100.00', '2023-03-01 03:38:06', NULL, NULL),
(21, 'buy', 24, 1, 3, 7, 'item', 2, '0.00', '0.00', '50.00', '100.00', '2023-03-01 03:45:35', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `support_messages`
--

CREATE TABLE `support_messages` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `full_name` varchar(300) NOT NULL,
  `phone` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL,
  `message` text NOT NULL,
  `is_seen` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `taxes_groups`
--

CREATE TABLE `taxes_groups` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(300) NOT NULL,
  `group_taxes` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `taxes_groups`
--

INSERT INTO `taxes_groups` (`group_id`, `group_name`, `group_taxes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Canda taxes', '[{\"id\":\"1\",\"tax_label\":\"tax1\",\"tax_percent\":\"14\"},{\"id\":\"2\",\"tax_label\":\"tax2\",\"tax_percent\":\"10\"}]', '2023-02-15 12:22:49', '2023-02-15 12:22:49', NULL),
(2, 'group germany', '[{\"id\":\"1\",\"tax_label\":\"50 percent\",\"tax_percent\":\"50\"}]', '2023-02-15 12:50:56', '2023-02-15 12:50:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions_log`
--

CREATE TABLE `transactions_log` (
  `t_log_id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `money_type` varchar(50) DEFAULT NULL COMMENT 'cash, visa',
  `transaction_type` varchar(50) NOT NULL,
  `transaction_operation` varchar(20) NOT NULL COMMENT '''increase'',''decrease''',
  `transaction_currency` varchar(20) NOT NULL,
  `transaction_amount` decimal(10,2) NOT NULL,
  `transaction_notes` varchar(1000) NOT NULL,
  `is_refunded` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transactions_log`
--

INSERT INTO `transactions_log` (`t_log_id`, `wallet_id`, `money_type`, `transaction_type`, `transaction_operation`, `transaction_currency`, `transaction_amount`, `transaction_notes`, `is_refunded`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 18, '', 'add_money_to_wallet', 'increase', '', '100.00', 'start amount (100) add gift card (1)', 0, '2022-11-29 10:14:55', '2022-11-29 10:14:55', NULL),
(2, 14, '', 'add_money_to_wallet', 'increase', '', '100.00', '(100) added to the wallet due to\n            the purchase of a gift card (1)', 0, '2022-11-29 10:14:56', '2022-11-29 10:14:56', NULL),
(3, 14, '', 'add_money_to_wallet', 'increase', '', '100.00', 'An amount of (100) was added to the wallet of the branch (canda branch) due to selling the\n                    order ( 1 ) to the client (general client)', 0, '2022-11-29 10:20:21', '2022-11-29 10:20:21', NULL),
(4, 19, '', 'add_money_to_wallet', 'increase', '', '100.00', 'start amount (100) add gift card (2)', 0, '2022-11-29 10:25:56', '2022-11-29 10:25:56', NULL),
(5, 16, '', 'add_money_to_wallet', 'increase', '', '0.00', '() added to the wallet due to\n            the purchase of a gift card (2)', 0, '2022-11-29 10:25:56', '2022-11-29 10:25:56', NULL),
(6, 19, '', 'get_money_from_wallet', 'decrease', '', '100.00', 'has been withdrawn money (100) from gift card (2)\n                                      by client (general client) for order (2)', 0, '2022-11-29 10:34:28', '2022-11-29 10:34:28', NULL),
(7, 14, '', 'add_money_to_wallet', 'increase', '', '200.00', 'An amount of (200) was added to the wallet of the branch (canda branch) due to selling the\n                    order ( 2 ) to the client (general client)', 0, '2022-11-29 10:34:29', '2022-11-29 10:34:29', NULL),
(8, 20, '', 'get_money_from_wallet', 'decrease', '', '90.00', 'has been withdrawn money (90) from\n                                            (abanoub as wholesaler) for order (3)', 0, '2022-11-29 10:35:29', '2022-11-29 10:35:29', NULL),
(9, 13, '', 'get_money_from_wallet', 'decrease', '', '0.00', 'has been withdrawn money (0) from\n                                            (general client) for order (4)', 0, '2022-11-29 10:37:31', '2022-11-29 10:37:31', NULL),
(10, 14, '', 'add_money_to_wallet', 'increase', '', '100.00', 'An amount of (100.00) was added to the wallet of the branch (canda branch) due to selling the\n                    order ( 4 ) to the client (general client)', 0, '2022-11-29 10:37:31', '2022-11-29 10:37:31', NULL),
(11, 14, '', 'get_money_from_wallet', 'decrease', '', '100.00', 'An amount of (100.00) was Withdrawn from the wallet of the branch (canda branch) due to return items\n        of order ( 4 ) to the client that belongs to the client (general client)', 0, '2022-11-29 10:40:41', '2022-11-29 10:40:41', NULL),
(12, 14, 'cash', 'expenses', 'decrease', 'USD', '1.00', '1', 0, '2022-12-29 05:35:21', '2022-12-29 05:35:21', NULL),
(13, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '180.00', '\n            An amount of (180.00) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 5 ) to the client (retailer client)\n        ', 0, '2023-01-31 22:02:56', '2023-01-31 22:02:56', NULL),
(14, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '111.60', '\n            An amount of (111.60) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 6 ) to the client (retailer client)\n        ', 0, '2023-02-12 12:25:34', '2023-02-12 12:25:34', NULL),
(15, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '111.60', '\n            An amount of (111.60) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 7 ) to the client (retailer client)\n        ', 0, '2023-02-12 12:39:59', '2023-02-12 12:39:59', NULL),
(16, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '248.00', '\n            An amount of (248.00) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 8 ) to the client (retailer client)\n        ', 0, '2023-02-12 12:41:03', '2023-02-12 12:41:03', NULL),
(17, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '223.20', '\n            An amount of (223.20) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 9 ) to the client (retailer client)\n        ', 0, '2023-02-12 12:51:26', '2023-02-12 12:51:26', NULL),
(18, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '100.44', '\n            An amount of (100.44) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 10 ) to the client (retailer client)\n        ', 0, '2023-02-12 12:52:23', '2023-02-12 12:52:23', NULL),
(19, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '238.00', '\n            An amount of (238.00) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 11 ) to the client (retailer client)\n        ', 0, '2023-02-12 12:54:32', '2023-02-12 12:54:32', NULL),
(20, 29, NULL, 'get_money_from_wallet', 'decrease', 'point', '500.00', 'has been withdrawn money (500) from your points\n                          for order (12)', 0, '2023-02-12 13:05:17', '2023-02-12 13:05:17', NULL),
(21, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '198.20', '\n            An amount of (198.20) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 12 ) to the client (retailer client)\n        ', 0, '2023-02-12 13:05:17', '2023-02-12 13:05:17', NULL),
(22, 29, NULL, 'get_money_from_wallet', 'decrease', 'point', '500.00', 'has been withdrawn money (500) from your points\n                          for order (13)', 0, '2023-02-12 13:06:40', '2023-02-12 13:06:40', NULL),
(23, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '213.00', '\n            An amount of (213.00) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 13 ) to the client (retailer client)\n        ', 0, '2023-02-12 13:06:40', '2023-02-12 13:06:40', NULL),
(24, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '50.00', '\n            An amount of (50.00) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 14 ) to the client (retailer client)\n        ', 0, '2023-02-12 13:11:36', '2023-02-12 13:11:36', NULL),
(25, 15, NULL, 'add_money_to_wallet', 'increase', 'USD', '61.60', '\n            An amount of (61.60) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 14 ) to the client (retailer client)\n        ', 0, '2023-02-12 13:11:36', '2023-02-12 13:11:36', NULL),
(26, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '300.00', '\n            An amount of (300.00) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 15 ) to the client (retailer client)\n        ', 0, '2023-02-15 14:01:40', '2023-02-15 14:01:40', NULL),
(27, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '135.00', '\n            An amount of (135.00) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 16 ) to the client (retailer client)\n        ', 0, '2023-02-21 15:52:29', '2023-02-21 15:52:29', NULL),
(28, 3, NULL, 'add_money_to_wallet', 'increase', '', '50.00', '\n                50 is added to supplier wallet,\n                related to order id - #11\n                we paid to him (6) of total cost (56) and remain (50)\n            ', 0, '2023-02-27 18:19:44', '2023-02-27 18:19:44', NULL),
(29, 25, NULL, 'add_money_to_wallet', 'increase', 'EUR', '1050.00', '\n                1050 is added to supplier wallet,\n                related to order id - #12\n                we paid to him (0) of total cost (1050) and remain (1050)\n            ', 0, '2023-02-27 18:22:00', '2023-02-27 18:22:00', NULL),
(30, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 111.6 at order #7', 0, '2023-02-27 19:37:12', '2023-02-27 19:37:12', NULL),
(31, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 248 at order #8', 0, '2023-02-27 19:37:12', '2023-02-27 19:37:12', NULL),
(32, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 223.2 at order #9', 0, '2023-02-27 19:37:13', '2023-02-27 19:37:13', NULL),
(33, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 100.44 at order #10', 0, '2023-02-27 19:37:13', '2023-02-27 19:37:13', NULL),
(34, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 238 at order #11', 0, '2023-02-27 19:37:13', '2023-02-27 19:37:13', NULL),
(35, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 198.2 at order #12', 0, '2023-02-27 19:37:13', '2023-02-27 19:37:13', NULL),
(36, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 213 at order #13', 0, '2023-02-27 19:37:13', '2023-02-27 19:37:13', NULL),
(37, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 111.6 at order #14', 0, '2023-02-27 19:37:13', '2023-02-27 19:37:13', NULL),
(38, 25, NULL, 'get_money_from_wallet', 'decrease', 'EUR', '1050.00', 'يشسيشي', 0, '2023-02-27 19:37:14', '2023-02-27 19:37:14', NULL),
(39, 3, NULL, 'add_money_to_wallet', 'increase', '', '500.00', '\n                500 is added to supplier wallet,\n                related to order id - #16\n                we paid to him (0) of total cost (500) and remain (500)\n            ', 0, '2023-02-27 18:38:46', '2023-02-27 18:38:46', NULL),
(40, 25, NULL, 'add_money_to_wallet', 'increase', 'EUR', '500.00', '\n                500 is added to supplier wallet,\n                related to order id - #17\n                we paid to him (0) of total cost (500) and remain (500)\n            ', 0, '2023-02-27 18:40:10', '2023-02-27 18:40:10', NULL),
(41, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '300.00', '\n            An amount of (300.00) was added to the wallet of the branch (canda branch)\n            due to selling the order ( 17 ) to the client (retailer client)\n        ', 0, '2023-02-28 23:36:10', '2023-02-28 23:36:10', NULL),
(42, 3, NULL, 'add_money_to_wallet', 'increase', '', '50.00', '\n                50 is added to supplier wallet,\n                related to order id - #19\n                we paid to him (0) of total cost (50.00) and remain (50)\n            ', 0, '2023-03-01 03:21:53', '2023-03-01 03:21:53', NULL),
(43, 3, NULL, 'add_money_to_wallet', 'increase', '', '100.00', '\n                100 is added to supplier wallet,\n                related to order id - #20\n                we paid to him (0) of total cost (100.00) and remain (100)\n            ', 0, '2023-03-01 03:29:40', '2023-03-01 03:29:40', NULL),
(44, 3, NULL, 'add_money_to_wallet', 'increase', '', '100.00', '\n                100 is added to supplier wallet,\n                related to order id - #21\n                we paid to him (0) of total cost (100.00) and remain (100)\n            ', 0, '2023-03-01 03:33:57', '2023-03-01 03:33:57', NULL),
(45, 3, NULL, 'add_money_to_wallet', 'increase', '', '100.00', '\n                100 is added to supplier wallet,\n                related to order id - #23\n                we paid to him (0) of total cost (100.00) and remain (100)\n            ', 0, '2023-03-01 03:38:36', '2023-03-01 03:38:36', NULL),
(46, 27, NULL, 'get_money_from_wallet', 'decrease', 'USD', '223.20', 'has been withdrawn money (223.2) from\n                                            (wholesaler client) for order (24)', 0, '2023-03-01 18:10:37', '2023-03-01 18:10:37', NULL),
(47, 27, NULL, 'get_money_from_wallet', 'decrease', 'USD', '223.20', 'has been withdrawn money (223.2) from\n                                            (wholesaler client) for order (25)', 0, '2023-03-01 18:14:07', '2023-03-01 18:14:07', NULL),
(48, 27, NULL, 'add_money_to_wallet', 'increase', 'USD', '446.40', 'dsa', 0, '2023-03-02 04:34:47', '2023-03-02 04:34:47', NULL),
(49, 27, NULL, 'get_money_from_wallet', 'decrease', 'USD', '446.40', 'has been withdrawn money (446.4) from\n                                            (wholesaler client) for order (26)', 0, '2023-03-02 04:35:42', '2023-03-02 04:35:42', NULL),
(50, 27, NULL, 'add_money_to_wallet', 'increase', 'USD', '223.20', 'An amount of (223.2) was added due to return items of order\n                (26) that belongs to the client (wholesaler client)', 0, '2023-03-02 04:21:10', '2023-03-02 04:21:10', NULL),
(51, 27, NULL, 'add_money_to_wallet', 'increase', 'USD', '223.20', 'An amount of (223.2) was added due to return items of order\n                (26) that belongs to the client (wholesaler client)', 0, '2023-03-02 04:22:46', '2023-03-02 04:22:46', NULL),
(52, 27, NULL, 'get_money_from_wallet', 'decrease', 'USD', '446.40', 'has been withdrawn money (446.4) from\n                                            (wholesaler client) for order (27)', 0, '2023-03-02 05:23:32', '2023-03-02 05:23:32', NULL),
(53, 27, NULL, 'add_money_to_wallet', 'increase', 'USD', '223.20', 'An amount of (223.2) was added due to return items of order\n                (27) that belongs to the client (wholesaler client)', 0, '2023-03-02 04:25:43', '2023-03-02 04:25:43', NULL),
(54, 27, NULL, 'add_money_to_wallet', 'increase', 'USD', '223.20', 'An amount of (223.2) was added due to return items of order\n                (27) that belongs to the client (wholesaler client)', 0, '2023-03-03 00:12:19', '2023-03-03 00:12:19', NULL),
(55, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 300 at order #15', 0, '2023-03-16 14:26:00', '2023-03-16 14:26:00', NULL),
(56, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 135 at order #16', 0, '2023-03-16 14:26:00', '2023-03-16 14:26:00', NULL),
(57, 29, NULL, 'add_money_to_wallet', 'increase', 'point', '10.00', 'add these points because you\'ve paid 300 at order #17', 0, '2023-03-16 14:26:00', '2023-03-16 14:26:00', NULL),
(58, 32, NULL, 'add_money_to_wallet', 'increase', 'USD', '500.00', 'amount (500) has been deposited to gift card (3)', 0, '2023-03-16 14:26:00', '2023-03-16 14:26:00', NULL),
(59, 14, NULL, 'add_money_to_wallet', 'increase', 'USD', '500.00', '(500) added to the wallet due to\n            the purchase of a gift card (3)', 0, '2023-03-16 14:26:00', '2023-03-16 14:26:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `used_coupons`
--

CREATE TABLE `used_coupons` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `used_coupons`
--

INSERT INTO `used_coupons` (`id`, `coupon_id`, `branch_id`, `client_id`, `order_id`, `discount_value`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 1, 1, 6, 12, '10.00', '2023-02-12 13:05:16', '2023-02-12 13:05:16', NULL),
(5, 2, 1, 6, 13, '10.00', '2023-02-12 13:06:40', '2023-02-12 13:06:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `user_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'admin, dev, employee, client',
  `user_role` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_enc_id` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `logo_img_obj` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `temp_email` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_changed_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `phone_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `verification_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `verification_code_expiration` datetime DEFAULT NULL,
  `password_reset_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_expire_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `branch_id`, `user_type`, `user_role`, `user_enc_id`, `logo_img_obj`, `email`, `temp_email`, `first_name`, `last_name`, `full_name`, `password`, `password_changed_at`, `remember_token`, `phone`, `phone_code`, `verification_code`, `verification_code_expiration`, `password_reset_code`, `password_reset_expire_at`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'dev', 'admin', 'c4ca4238a0b923820dcc509a6f75849b', '', 'admin@admin.com', '', 'admin', 'admin', 'admin', '$2y$10$GUeRWJPLe4P3B/uvWHXJ..n57IXl8Hr7IqMtcJpFT/urWrs/BWWTG', '2022-09-15 00:00:00', NULL, '', '', '', NULL, '', NULL, 1, NULL, NULL, NULL),
(2, 4, 'employee', '', 'c81e728d9d4c2f636f067f89cc14862c', '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', 'xegoxov@mailinator.com', '', 'Matthew', 'Vaughan', 'Matthew Vaughan', '$2y$10$mYJqWMJIYgUfey/9bggh1eyvkegO/zVQn8nfloK4.S7EgHIZMPPLq', '2022-10-31 16:18:40', NULL, '+1 (811) 866-4328', '+1 (105) 9', '', NULL, '', NULL, 1, '2022-10-31 14:18:40', '2022-10-31 14:18:40', NULL),
(3, 1, 'employee', 'branch_admin', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', 'branchadmin@sys.com', '', 'Hayes', 'Peterson', 'Hayes Peterson', '$2y$10$CBqHws916PC329BXeEMluOp.POY.gOyWwKP2C8Go54009S1ie5OcS', '2022-12-01 18:25:30', NULL, '+1 (102) 479-4293', '+1 (478) 1', '', NULL, '', NULL, 1, '2022-12-01 13:41:32', '2022-12-01 16:25:30', NULL),
(4, 1, 'employee', 'employee', 'a87ff679a2f3e71d9181a67b7542122c', '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', 'zumiqicyl@mailinator.com', '', 'Chastity', 'Lynch', 'Chastity Lynch', '$2y$10$GaXqRnXuyvAoWmcS3r3yROg2F14OFfy8coagqruP8TzE75yIkMv26', '2022-12-07 02:03:30', NULL, '+1 (287) 771-3859', '+1 (509) 3', '', NULL, '', NULL, 1, '2022-12-07 00:03:30', '2022-12-07 00:03:30', NULL),
(5, 2, 'employee', 'branch_admin', 'e4da3b7fbbce2345d7772b0674a318d5', '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', 'sozepyf@mailinator.com', '', 'Tyler', 'Maddox', 'Tyler Maddox', '$2y$10$z6ySGN2106fKC0z4kaSLreeDLB.dwwNlGLwacVgcoRRm2PRBFconK', '2023-02-21 17:44:49', NULL, '+1 (753) 486-9153', '+1 (836) 9', '', NULL, '', NULL, 0, '2023-02-21 15:44:49', '2023-02-21 15:44:49', NULL),
(6, 2, 'employee', 'branch_admin', '1679091c5a880faf6fb5e6087eb1b2dc', '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', 'sozepyf@mailinator.com', '', 'Tyler', 'Maddox', 'Tyler Maddox', '$2y$10$WJFQPHHfG5fjWD/I8CviEuK7mJKTdAtWHUSVP9qg8Or3ib1jLQFqu', '2023-02-21 17:44:54', NULL, '+1 (753) 486-9153', '+1 (836) 9', '', NULL, '', NULL, 0, '2023-02-21 15:44:54', '2023-02-21 15:44:54', NULL),
(7, 2, 'employee', 'branch_admin', '8f14e45fceea167a5a36dedd4bea2543', '{\"alt\":\"\",\"title\":\"\",\"path\":\"\"}', 'sozepyf@mailinator.com', '', 'Tyler', 'Maddox', 'Tyler Maddox', '$2y$10$tWzsXY9p0o5xMx0aNOVelOiXBBlPmKgBNQXM3oVT8WuYnat7v8XK6', '2023-02-21 17:45:21', NULL, '+1 (753) 486-9153', '+1 (836) 9', '', NULL, '', NULL, 0, '2023-02-21 15:45:21', '2023-02-21 15:45:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `wallet_id` int(11) NOT NULL,
  `wallet_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`wallet_id`, `wallet_amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '0.00', '2022-09-18 09:47:41', '2022-10-02 16:50:26', NULL),
(2, '0.00', '2022-09-18 09:48:23', '2022-09-18 09:48:23', NULL),
(3, '991.00', '2022-09-18 09:51:28', '2023-03-01 03:38:36', NULL),
(4, '0.00', '2022-09-21 19:00:34', '2022-09-25 18:22:00', NULL),
(5, '0.00', '2022-09-21 19:00:34', '2022-09-26 20:28:34', NULL),
(6, '0.00', '2022-10-11 19:41:44', '2022-10-11 19:41:44', NULL),
(7, '0.00', '2022-10-13 01:45:20', '2022-10-13 01:45:20', NULL),
(8, '0.00', '2022-10-13 01:45:20', '2022-10-13 01:45:20', NULL),
(9, '0.00', '2022-11-03 23:57:17', '2022-11-03 23:57:17', NULL),
(10, '0.00', '2022-11-03 23:57:17', '2022-11-03 23:57:17', NULL),
(11, '0.00', '2022-11-03 23:57:17', '2022-11-03 23:57:17', NULL),
(12, '0.00', '2022-11-03 23:57:17', '2022-11-03 23:57:17', NULL),
(13, '0.00', '2022-11-29 09:54:45', '2022-11-29 10:37:31', NULL),
(14, '3308.04', '2022-11-29 10:01:35', '2023-03-16 14:26:00', NULL),
(15, '61.60', '2022-11-29 10:01:35', '2023-02-12 13:11:36', NULL),
(16, '0.00', '2022-11-29 10:01:35', '2022-11-29 10:25:56', NULL),
(17, '0.00', '2022-11-29 10:01:35', '2022-11-29 10:01:35', NULL),
(18, '100.00', '2022-11-29 10:14:52', '2022-11-29 10:14:55', NULL),
(19, '0.00', '2022-11-29 10:25:55', '2022-11-29 10:34:28', NULL),
(20, '-90.00', '2022-11-29 10:34:53', '2022-11-29 10:35:29', NULL),
(21, '0.00', '2022-12-07 00:04:41', '2022-12-07 00:04:41', NULL),
(22, '0.00', '2022-12-07 00:04:41', '2022-12-07 00:04:41', NULL),
(23, '0.00', '2022-12-07 00:04:41', '2022-12-07 00:04:41', NULL),
(24, '0.00', '2022-12-07 00:04:41', '2022-12-07 00:04:41', NULL),
(25, '500.00', '2022-12-29 03:10:01', '2023-02-27 18:40:10', NULL),
(26, '-100.00', '2023-01-05 11:11:33', '2023-01-05 11:11:33', NULL),
(27, '0.00', '2023-02-12 09:50:17', '2023-03-03 00:12:19', NULL),
(28, '0.00', '2023-02-12 09:50:17', '2023-02-12 09:50:17', NULL),
(29, '110.00', '2023-02-12 09:51:03', '2023-03-16 14:26:00', NULL),
(30, '0.00', '2023-02-21 15:10:41', '2023-02-21 15:10:41', NULL),
(31, '0.00', '2023-02-21 15:10:41', '2023-02-21 15:10:41', NULL),
(32, '500.00', '2023-03-16 13:25:48', '2023-03-16 14:26:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `branch_inventory`
--
ALTER TABLE `branch_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_prices`
--
ALTER TABLE `branch_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD UNIQUE KEY `cache_key_unique` (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD KEY `wallet_id` (`wallet_id`);

--
-- Indexes for table `client_addresses`
--
ALTER TABLE `client_addresses`
  ADD PRIMARY KEY (`add_id`);

--
-- Indexes for table `client_orders`
--
ALTER TABLE `client_orders`
  ADD PRIMARY KEY (`client_order_id`);

--
-- Indexes for table `client_order_items`
--
ALTER TABLE `client_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_wishlist`
--
ALTER TABLE `client_wishlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indexes for table `employee_action_log`
--
ALTER TABLE `employee_action_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_details`
--
ALTER TABLE `employee_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_login_logout`
--
ALTER TABLE `employee_login_logout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_tasks`
--
ALTER TABLE `employee_tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `employee_task_comments`
--
ALTER TABLE `employee_task_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_warnings`
--
ALTER TABLE `employee_warnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_cards`
--
ALTER TABLE `gift_cards`
  ADD PRIMARY KEY (`card_id`);

--
-- Indexes for table `gift_card_templates`
--
ALTER TABLE `gift_card_templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `hr_delay_early_requests`
--
ALTER TABLE `hr_delay_early_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_holiday_requests`
--
ALTER TABLE `hr_holiday_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_national_holidays`
--
ALTER TABLE `hr_national_holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_paycheck`
--
ALTER TABLE `hr_paycheck`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_log`
--
ALTER TABLE `inventory_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_places`
--
ALTER TABLE `inventory_places`
  ADD PRIMARY KEY (`inv_id`);

--
-- Indexes for table `inventory_products`
--
ALTER TABLE `inventory_products`
  ADD PRIMARY KEY (`ip_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `langs`
--
ALTER TABLE `langs`
  ADD PRIMARY KEY (`lang_id`);

--
-- Indexes for table `loyalty_points_to_money`
--
ALTER TABLE `loyalty_points_to_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `money_installments`
--
ALTER TABLE `money_installments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `money_to_loyalty_points`
--
ALTER TABLE `money_to_loyalty_points`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`per_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`pro_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `product_promotions`
--
ALTER TABLE `product_promotions`
  ADD PRIMARY KEY (`promo_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `product_skus`
--
ALTER TABLE `product_skus`
  ADD PRIMARY KEY (`ps_id`),
  ADD KEY `product_skus_ibfk_1` (`pro_id`);

--
-- Indexes for table `product_variant_types`
--
ALTER TABLE `product_variant_types`
  ADD PRIMARY KEY (`variant_type_id`),
  ADD KEY `product_variant_types_ibfk_1` (`pro_id`);

--
-- Indexes for table `product_variant_type_values`
--
ALTER TABLE `product_variant_type_values`
  ADD PRIMARY KEY (`vt_value_id`),
  ADD KEY `pro_id` (`pro_id`),
  ADD KEY `variant_type_id` (`variant_type_id`);

--
-- Indexes for table `registers`
--
ALTER TABLE `registers`
  ADD PRIMARY KEY (`register_id`);

--
-- Indexes for table `register_sessions`
--
ALTER TABLE `register_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register_session_logs`
--
ALTER TABLE `register_session_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD UNIQUE KEY `sessions_id_unique` (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_id`),
  ADD KEY `settings_setting_group_index` (`setting_group`),
  ADD KEY `settings_setting_key_index` (`setting_key`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`sup_id`),
  ADD KEY `wallet_id` (`wallet_id`);

--
-- Indexes for table `supplier_orders`
--
ALTER TABLE `supplier_orders`
  ADD PRIMARY KEY (`supplier_order_id`);

--
-- Indexes for table `supplier_order_items`
--
ALTER TABLE `supplier_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes_groups`
--
ALTER TABLE `taxes_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `transactions_log`
--
ALTER TABLE `transactions_log`
  ADD PRIMARY KEY (`t_log_id`);

--
-- Indexes for table `used_coupons`
--
ALTER TABLE `used_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`wallet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branch_inventory`
--
ALTER TABLE `branch_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branch_prices`
--
ALTER TABLE `branch_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `client_addresses`
--
ALTER TABLE `client_addresses`
  MODIFY `add_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_orders`
--
ALTER TABLE `client_orders`
  MODIFY `client_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `client_order_items`
--
ALTER TABLE `client_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `client_wishlist`
--
ALTER TABLE `client_wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee_action_log`
--
ALTER TABLE `employee_action_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `employee_details`
--
ALTER TABLE `employee_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee_login_logout`
--
ALTER TABLE `employee_login_logout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `employee_tasks`
--
ALTER TABLE `employee_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_task_comments`
--
ALTER TABLE `employee_task_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee_warnings`
--
ALTER TABLE `employee_warnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `gift_cards`
--
ALTER TABLE `gift_cards`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gift_card_templates`
--
ALTER TABLE `gift_card_templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_delay_early_requests`
--
ALTER TABLE `hr_delay_early_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_holiday_requests`
--
ALTER TABLE `hr_holiday_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_national_holidays`
--
ALTER TABLE `hr_national_holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hr_paycheck`
--
ALTER TABLE `hr_paycheck`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_log`
--
ALTER TABLE `inventory_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `inventory_places`
--
ALTER TABLE `inventory_places`
  MODIFY `inv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory_products`
--
ALTER TABLE `inventory_products`
  MODIFY `ip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `langs`
--
ALTER TABLE `langs`
  MODIFY `lang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loyalty_points_to_money`
--
ALTER TABLE `loyalty_points_to_money`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `money_installments`
--
ALTER TABLE `money_installments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `money_to_loyalty_points`
--
ALTER TABLE `money_to_loyalty_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `per_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2531;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_promotions`
--
ALTER TABLE `product_promotions`
  MODIFY `promo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_skus`
--
ALTER TABLE `product_skus`
  MODIFY `ps_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_variant_types`
--
ALTER TABLE `product_variant_types`
  MODIFY `variant_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_variant_type_values`
--
ALTER TABLE `product_variant_type_values`
  MODIFY `vt_value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `registers`
--
ALTER TABLE `registers`
  MODIFY `register_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `register_sessions`
--
ALTER TABLE `register_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `register_session_logs`
--
ALTER TABLE `register_session_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `settings_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `sup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier_orders`
--
ALTER TABLE `supplier_orders`
  MODIFY `supplier_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `supplier_order_items`
--
ALTER TABLE `supplier_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxes_groups`
--
ALTER TABLE `taxes_groups`
  MODIFY `group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions_log`
--
ALTER TABLE `transactions_log`
  MODIFY `t_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `used_coupons`
--
ALTER TABLE `used_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `wallet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`wallet_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product_skus`
--
ALTER TABLE `product_skus`
  ADD CONSTRAINT `product_skus_ibfk_1` FOREIGN KEY (`pro_id`) REFERENCES `products` (`pro_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product_variant_types`
--
ALTER TABLE `product_variant_types`
  ADD CONSTRAINT `product_variant_types_ibfk_1` FOREIGN KEY (`pro_id`) REFERENCES `products` (`pro_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product_variant_type_values`
--
ALTER TABLE `product_variant_type_values`
  ADD CONSTRAINT `product_variant_type_values_ibfk_1` FOREIGN KEY (`pro_id`) REFERENCES `products` (`pro_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_ibfk_1` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`wallet_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
