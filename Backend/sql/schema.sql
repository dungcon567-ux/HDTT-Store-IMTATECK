-- =====================================================================
-- HDTT Store - Schema lõi (dựng lại từ các câu SQL trong models/controllers)
-- Chạy: mysql -u root < Backend/sql/schema.sql
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `php-oop-basic`
    DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `php-oop-basic`;

-- ===================== DANH MỤC =====================
CREATE TABLE IF NOT EXISTS categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== SẢN PHẨM =====================
CREATE TABLE IF NOT EXISTS products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    category_id INT NULL,
    description TEXT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== MÀU / SIZE =====================
CREATE TABLE IF NOT EXISTS color (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS size (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== BIẾN THỂ SẢN PHẨM =====================
-- status = 1 (hiển thị) / 0 (đã ẩn - xoá mềm)
CREATE TABLE IF NOT EXISTS product_variants (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    color_id   INT NOT NULL,
    size_id    INT NOT NULL,
    image      VARCHAR(255) NULL,
    price      INT NOT NULL DEFAULT 0,
    stock      INT NOT NULL DEFAULT 0,
    status     TINYINT(1) NOT NULL DEFAULT 1,
    INDEX (product_id), INDEX (color_id), INDEX (size_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== NGƯỜI DÙNG =====================
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(255) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    std        VARCHAR(20)  NULL,
    diachi     VARCHAR(500) NULL,
    avatar     VARCHAR(255) NULL,
    role       VARCHAR(20)  NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_username (username),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== GIỎ HÀNG =====================
CREATE TABLE IF NOT EXISTS cart (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    variant_id INT NOT NULL,
    quantity   INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX (user_id), INDEX (variant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== ĐƠN HÀNG =====================
-- status: cho_xac_nhan | da_dat_hang | hoan_thanh | da_huy
-- payment_status: unpaid | paid
-- online: 'no' (COD) | 'yes' (thanh toán online)
CREATE TABLE IF NOT EXISTS orders (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NULL,
    total            DECIMAL(12,2) NOT NULL DEFAULT 0,
    shipping_fee     DECIMAL(12,2) NOT NULL DEFAULT 0,
    payment_method   VARCHAR(30)  NOT NULL DEFAULT 'cod',
    status           VARCHAR(30)  NOT NULL DEFAULT 'cho_xac_nhan',
    payment_status   VARCHAR(20)  NOT NULL DEFAULT 'unpaid',
    receiver_name    VARCHAR(255) NULL,
    receiver_phone   VARCHAR(20)  NULL,
    receiver_address VARCHAR(500) NULL,
    online           VARCHAR(10)  NOT NULL DEFAULT 'no',
    discount         INT NOT NULL DEFAULT 0,
    voucher_code     VARCHAR(50)  NULL,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX (user_id), INDEX (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_details (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    order_id   INT NOT NULL,
    variant_id INT NOT NULL,
    quantity   INT NOT NULL DEFAULT 1,
    price      INT NOT NULL DEFAULT 0,
    INDEX (order_id), INDEX (variant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== ĐÁNH GIÁ =====================
CREATE TABLE IF NOT EXISTS reviews (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    product_id INT NOT NULL,
    rating     TINYINT NOT NULL DEFAULT 5,
    content    TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX (product_id), INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== YÊU THÍCH =====================
CREATE TABLE IF NOT EXISTS wishlist (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_wishlist (user_id, product_id),
    INDEX (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
