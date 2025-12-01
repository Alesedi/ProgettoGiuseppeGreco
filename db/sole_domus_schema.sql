-- SoleDomus schema SQL (MySQL)
-- Eseguire in MySQL 8 / DB Designer 4 (Reverse Engineer dall'SQL)

SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(255) DEFAULT NULL,
  `username` VARCHAR(100) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- assicurati che username sia unico se usato
CREATE UNIQUE INDEX IF NOT EXISTS idx_users_username ON `users`(`username`);

CREATE TABLE `addresses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `label` VARCHAR(100) DEFAULT NULL,
  `recipient_name` VARCHAR(255) NOT NULL,
  `street` VARCHAR(255) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `postal_code` VARCHAR(20) NOT NULL,
  `province` VARCHAR(100) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT 'Italy',
  `phone` VARCHAR(50) DEFAULT NULL,
  `is_default` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sku` VARCHAR(100) UNIQUE,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `power_watt` INT DEFAULT NULL,
  `efficiency` DECIMAL(5,2) DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT DEFAULT 0,
  `image` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `product_options` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price_delta` DECIMAL(10,2) DEFAULT 0.00,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `carts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cart_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `cart_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `product_option_id` INT DEFAULT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`product_option_id`) REFERENCES `product_options`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `address_id` INT DEFAULT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `status` VARCHAR(50) DEFAULT 'pending',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `product_option_id` INT DEFAULT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`product_option_id`) REFERENCES `product_options`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pagamenti: per la simulazione non salviamo CVV. Si salva solo info non sensibili (ultimo4, brand) o un token fittizio.
CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `method` VARCHAR(50) DEFAULT 'card',
  `status` VARCHAR(50) DEFAULT 'initiated',
  `transaction_ref` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `payment_cards` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `cardholder_name` VARCHAR(255) NOT NULL,
  `card_brand` VARCHAR(50) DEFAULT NULL,
  `card_last4` CHAR(4) DEFAULT NULL,
  `exp_month` TINYINT DEFAULT NULL,
  `exp_year` SMALLINT DEFAULT NULL,
  `token` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- Indici utili
CREATE INDEX idx_products_price ON `products`(`price`);
CREATE INDEX idx_orders_user ON `orders`(`user_id`);

-- Nota: per DB Designer 4 incollare questo SQL o importarlo tramite Reverse Engineer.
