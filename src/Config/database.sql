CREATE TABLE `dd_users`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(70) NOT NULL,
    `password` VARCHAR(70) NOT NULL,
    `first_name` VARCHAR(30) NULL,
    `last_name` VARCHAR(40) NULL,
    `phone_number` VARCHAR(15) NULL,
    `status` ENUM('pending', 'active', 'deactivated') NOT NULL,
    `role_id` INT UNSIGNED NOT NULL,
    `company_name` VARCHAR(50) NULL
);
ALTER TABLE
    `dd_users` ADD UNIQUE `dd_users_email_unique`(`email`);
CREATE TABLE `sd_roles`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` ENUM('admin', 'seller', 'client') NOT NULL
);
CREATE TABLE `categories`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(25) NOT NULL,
    `parent_category_id` INT UNSIGNED NOT NULL
);
CREATE TABLE `dd_products`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `price` SMALLINT UNSIGNED NOT NULL,
    `description` TEXT NULL,
    `image_path` VARCHAR(255) NULL,
    `date_added` DATETIME NOT NULL,
    `status` ENUM('draft', 'published', 'deleted') NOT NULL,
    `category_id` INT UNSIGNED NOT NULL
);
CREATE TABLE `sd_sizes`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(20) NOT NULL
);
ALTER TABLE
    `sd_sizes` ADD UNIQUE `sd_sizes_name_unique`(`name`);
CREATE TABLE `dd_product_sizes`(
    `product_id` INT UNSIGNED NOT NULL,
    `size_id` INT UNSIGNED NOT NULL,
    `amount` MEDIUMINT NOT NULL,
    PRIMARY KEY(`product_id`, `size_id`)
);
CREATE TABLE `sd_category_sizes`(
    `category_id` INT UNSIGNED NOT NULL,
    `size_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY(`category_id`, `size_id`)
);
CREATE TABLE `dd_orders`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `datetime` DATETIME NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `status` ENUM('processing', 'shipped') NOT NULL
);
CREATE TABLE `dd_sold_items`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `amount` SMALLINT NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `order_id` INT UNSIGNED NOT NULL
);
CREATE TABLE `email_confirmations`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `confirmation_hash` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `expires_at` DATETIME NOT NULL,
    `confirmed_at` DATETIME NOT NULL,
    `user_id` INT UNSIGNED NOT NULL
);
ALTER TABLE
    `dd_sold_items` ADD CONSTRAINT `dd_sold_items_product_id_foreign` FOREIGN KEY(`product_id`) REFERENCES `dd_products`(`id`);
ALTER TABLE
    `dd_users` ADD CONSTRAINT `dd_users_role_id_foreign` FOREIGN KEY(`role_id`) REFERENCES `sd_roles`(`id`);
ALTER TABLE
    `email_confirmations` ADD CONSTRAINT `email_confirmations_user_id_foreign` FOREIGN KEY(`user_id`) REFERENCES `dd_users`(`id`);
ALTER TABLE
    `dd_product_sizes` ADD CONSTRAINT `dd_product_sizes_product_id_foreign` FOREIGN KEY(`product_id`) REFERENCES `dd_products`(`id`);
ALTER TABLE
    `categories` ADD CONSTRAINT `categories_parent_category_id_foreign` FOREIGN KEY(`parent_category_id`) REFERENCES `categories`(`id`);
ALTER TABLE
    `dd_product_sizes` ADD CONSTRAINT `dd_product_sizes_size_id_foreign` FOREIGN KEY(`size_id`) REFERENCES `sd_sizes`(`id`);
ALTER TABLE
    `sd_category_sizes` ADD CONSTRAINT `sd_category_sizes_category_id_foreign` FOREIGN KEY(`category_id`) REFERENCES `categories`(`id`);
ALTER TABLE
    `dd_products` ADD CONSTRAINT `dd_products_category_id_foreign` FOREIGN KEY(`category_id`) REFERENCES `categories`(`id`);
ALTER TABLE
    `sd_category_sizes` ADD CONSTRAINT `sd_category_sizes_size_id_foreign` FOREIGN KEY(`size_id`) REFERENCES `sd_sizes`(`id`);
ALTER TABLE
    `dd_sold_items` ADD CONSTRAINT `dd_sold_items_order_id_foreign` FOREIGN KEY(`order_id`) REFERENCES `dd_orders`(`id`);

# 15.05.2025
ALTER TABLE `categories`
CHANGE `parent_category_id` `parent_category_id` int unsigned NULL AFTER `name`;