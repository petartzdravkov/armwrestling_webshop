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

-- 2025-06-17 14:46:32
