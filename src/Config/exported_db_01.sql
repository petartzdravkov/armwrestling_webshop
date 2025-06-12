-- Adminer 4.17.1 MySQL 8.0.41-0ubuntu0.22.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `parent_category_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_category_id_foreign` (`parent_category_id`),
  CONSTRAINT `categories_parent_category_id_foreign` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `categories` (`id`, `name`, `parent_category_id`) VALUES
(1,	'clothing',	NULL),
(2,	'equipment',	NULL),
(3,	'shirts',	1),
(4,	'hoodies',	1),
(5,	'straps',	2),
(6,	'belts',	2);

DROP TABLE IF EXISTS `dd_orders`;
CREATE TABLE `dd_orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `status` enum('processing','shipped') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `dd_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dd_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `dd_orders` (`id`, `datetime`, `user_id`, `status`) VALUES
(4,	'2025-05-22 23:31:03',	NULL,	'shipped'),
(5,	'2025-05-22 23:32:20',	NULL,	'shipped'),
(6,	'2025-05-22 23:38:42',	NULL,	'shipped'),
(7,	'2025-05-22 23:41:53',	2,	'shipped'),
(8,	'2025-05-23 15:53:06',	2,	'processing'),
(9,	'2025-05-23 16:54:13',	2,	'processing'),
(10,	'2025-06-09 16:09:44',	NULL,	'processing'),
(11,	'2025-06-09 16:10:16',	NULL,	'processing'),
(12,	'2025-06-09 16:14:22',	NULL,	'shipped'),
(13,	'2025-06-09 16:18:07',	5,	'shipped'),
(14,	'2025-06-11 10:34:02',	NULL,	'processing'),
(22,	'2025-06-12 12:36:58',	2,	'processing'),
(23,	'2025-06-12 12:38:01',	7,	'processing');

DROP TABLE IF EXISTS `dd_product_sizes`;
CREATE TABLE `dd_product_sizes` (
  `product_id` int unsigned NOT NULL,
  `size_id` int unsigned NOT NULL,
  `amount` mediumint NOT NULL,
  PRIMARY KEY (`product_id`,`size_id`),
  KEY `dd_product_sizes_size_id_foreign` (`size_id`),
  CONSTRAINT `dd_product_sizes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `dd_products` (`id`),
  CONSTRAINT `dd_product_sizes_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sd_sizes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `dd_product_sizes` (`product_id`, `size_id`, `amount`) VALUES
(1,	1,	10),
(1,	2,	9),
(1,	3,	17),
(1,	4,	0),
(1,	5,	0),
(1,	6,	0),
(2,	1,	4),
(2,	2,	0),
(2,	3,	14),
(2,	4,	0),
(2,	5,	6),
(2,	6,	0),
(7,	7,	12),
(7,	8,	23),
(11,	1,	29),
(11,	2,	33),
(11,	3,	30),
(11,	4,	12),
(11,	5,	0),
(11,	6,	20),
(13,	7,	0),
(13,	8,	0);

DROP TABLE IF EXISTS `dd_products`;
CREATE TABLE `dd_products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` smallint unsigned NOT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `status` enum('draft','published','deleted') NOT NULL,
  `category_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `dd_products_category_id_foreign` (`category_id`),
  CONSTRAINT `dd_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `dd_products` (`id`, `name`, `price`, `description`, `image_path`, `date_added`, `status`, `category_id`) VALUES
(1,	'Table Kingdom Shirts',	27,	'Our premium table kingodm shirt.',	'assets/images/products/img_6848505e8c51a1.38848442.png',	'2025-05-15 13:08:05',	'published',	1),
(2,	'Dominate Hoodie',	50,	'This is our amazing strength hoodie.',	'assets/images/products/img_68484fd7656c05.68033628.png',	'2025-05-15 13:09:50',	'published',	1),
(3,	'Dominate Shirt',	25,	'Dominate your enemies with the premium quality shirt.',	'assets/images/products/img_68485019ac5f54.35628431.png',	'2025-05-15 17:14:47',	'draft',	1),
(4,	'Flash Pin Shirt',	25,	'Flash pin your opponents with this blzingly fast shirt.',	'assets/images/products/img_684852b6c0e137.64758061.png',	'2025-05-15 17:22:41',	'published',	1),
(5,	'Ready Go Hoodie',	50,	'Prepare for battle and become the victor with this premium quality hoodie.',	'assets/images/products/img_684850238d6510.56997838.png',	'2025-05-15 17:23:16',	'deleted',	1),
(6,	'IP Strap',	15,	'Sturdy but prestine armwrestling strap.',	'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fae01.alicdn.com%2Fkf%2FS7e42e0c16edc484ca2b0bed60ae969dbd%2F1-Pair-Arm-Wrestling-Strap-with-Metal-Buckle-for-Competition-Match-100cm-Long-Non-Slip-Exercise.jpg',	'2025-05-15 23:52:13',	'published',	2),
(7,	'Hui',	69,	'Dickus Maximus',	'assets/images/products/img_684a5e4649b660.99820660.jpeg',	'2025-06-11 15:47:00',	'published',	2),
(11,	'Guz',	42,	'Mirishesht dirnik',	'assets/images/products/img_68498a9a4b5cd6.98869694.jpg',	'2025-06-11 16:54:34',	'published',	1),
(13,	'Gosho',	12,	'az sum gosho',	'assets/images/products/img_684a8faaa9a049.66518716.jpg',	'2025-06-12 11:10:47',	'deleted',	2);

DROP TABLE IF EXISTS `dd_sold_items`;
CREATE TABLE `dd_sold_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `amount` smallint NOT NULL,
  `product_id` int unsigned NOT NULL,
  `order_id` int unsigned NOT NULL,
  `size_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dd_sold_items_product_id_foreign` (`product_id`),
  KEY `dd_sold_items_order_id_foreign` (`order_id`),
  KEY `size_id` (`size_id`),
  CONSTRAINT `dd_sold_items_ibfk_1` FOREIGN KEY (`size_id`) REFERENCES `sd_sizes` (`id`),
  CONSTRAINT `dd_sold_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `dd_orders` (`id`),
  CONSTRAINT `dd_sold_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `dd_products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `dd_sold_items` (`id`, `amount`, `product_id`, `order_id`, `size_id`) VALUES
(6,	1,	1,	4,	1),
(7,	1,	1,	4,	2),
(8,	2,	2,	4,	5),
(9,	2,	1,	5,	3),
(10,	1,	2,	5,	3),
(11,	1,	2,	6,	5),
(12,	1,	2,	7,	5),
(13,	1,	2,	8,	4),
(14,	1,	2,	8,	3),
(15,	1,	1,	8,	1),
(16,	3,	2,	9,	4),
(17,	1,	2,	10,	1),
(18,	1,	1,	11,	2),
(19,	1,	1,	12,	2),
(20,	1,	1,	13,	1),
(21,	1,	1,	14,	3),
(22,	1,	11,	22,	2),
(23,	1,	11,	22,	1),
(24,	1,	11,	23,	2),
(25,	2,	11,	23,	4);

DROP TABLE IF EXISTS `dd_users`;
CREATE TABLE `dd_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(70) NOT NULL,
  `password` varchar(70) NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `status` enum('pending','active','deactivated') NOT NULL,
  `role_id` int unsigned NOT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dd_users_email_unique` (`email`),
  KEY `dd_users_role_id_foreign` (`role_id`),
  CONSTRAINT `dd_users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `sd_roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `dd_users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone_number`, `status`, `role_id`, `company_name`) VALUES
(2,	'pesho@abv.bg',	'$2y$10$YUgK14bxAnkYtaxiA/K/gej82b8VKV6wprcP9FbZJ/kgkAvPDMiaa',	NULL,	NULL,	NULL,	'active',	1,	NULL),
(5,	'vladi@abv.bg',	'$2y$10$eqFSrvKk62CbOnEo3S7z2eDT95RQzJi7I1vX4SpD.EZoazW12EYvG',	NULL,	NULL,	NULL,	'active',	2,	NULL),
(7,	'rumi@abv.bg',	'$2y$10$FgdPp.T9K4fsHuHQKHXmleV8khjkfakOjn6ZRbrBIVzE6nadC9JLq',	NULL,	NULL,	NULL,	'active',	3,	NULL);

DROP TABLE IF EXISTS `email_confirmations`;
CREATE TABLE `email_confirmations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `confirmation_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  `confirmed_at` datetime NOT NULL,
  `user_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email_confirmations_user_id_foreign` (`user_id`),
  CONSTRAINT `email_confirmations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `dd_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `sd_category_sizes`;
CREATE TABLE `sd_category_sizes` (
  `category_id` int unsigned NOT NULL,
  `size_id` int unsigned NOT NULL,
  PRIMARY KEY (`category_id`,`size_id`),
  KEY `sd_category_sizes_size_id_foreign` (`size_id`),
  CONSTRAINT `sd_category_sizes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `sd_category_sizes_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sd_sizes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `sd_category_sizes` (`category_id`, `size_id`) VALUES
(1,	1),
(1,	2),
(1,	3),
(1,	4),
(1,	5),
(1,	6),
(2,	7),
(2,	8);

DROP TABLE IF EXISTS `sd_roles`;
CREATE TABLE `sd_roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` enum('admin','seller','client') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `sd_roles` (`id`, `name`) VALUES
(1,	'admin'),
(2,	'seller'),
(3,	'client');

DROP TABLE IF EXISTS `sd_sizes`;
CREATE TABLE `sd_sizes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sd_sizes_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `sd_sizes` (`id`, `name`) VALUES
(7,	'120'),
(8,	'280'),
(3,	'l'),
(2,	'm'),
(1,	's'),
(4,	'xl'),
(6,	'xs'),
(5,	'xxl');

-- 2025-06-12 11:27:26
