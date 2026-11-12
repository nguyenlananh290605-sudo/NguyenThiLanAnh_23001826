-- Câu 1: Tạo cơ sở dữ liệu, bảng products và ba sản phẩm mẫu.
-- Nhập file này một lần bằng chức năng Import trong phpMyAdmin.

CREATE DATABASE IF NOT EXISTS buoi8_products
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE buoi8_products;

CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price INT NOT NULL,
    stock INT NOT NULL,
    CONSTRAINT chk_products_name CHECK (CHAR_LENGTH(TRIM(name)) BETWEEN 1 AND 100),
    CONSTRAINT chk_products_price CHECK (price > 0),
    CONSTRAINT chk_products_stock CHECK (stock >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (name, price, stock) VALUES
    ('Bút bi', 5000, 20),
    ('Vở ô ly', 12000, 15),
    ('Thước kẻ', 8000, 0);
