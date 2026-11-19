-- Tạo cơ sở dữ liệu, bảng products và 12 sản phẩm mẫu.
-- Nhập file này một lần bằng chức năng Import trong phpMyAdmin.

CREATE DATABASE IF NOT EXISTS buoi9_products
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE buoi9_products;

CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price INT NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255) NULL,
    CONSTRAINT chk_products_name CHECK (CHAR_LENGTH(TRIM(name)) BETWEEN 1 AND 100),
    CONSTRAINT chk_products_price CHECK (price > 0),
    CONSTRAINT chk_products_stock CHECK (stock >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (name, price, stock) VALUES
    ('Bút bi', 5000, 20),
    ('Vở ô ly', 12000, 15),
    ('Thước kẻ', 8000, 0),
    ('Bút chì', 4000, 30), ('Tẩy', 3000, 25), ('Bút màu', 25000, 10),
    ('Sổ tay', 18000, 12), ('Giấy A4', 65000, 8), ('Kéo', 15000, 9),
    ('Hồ dán', 7000, 20), ('Bìa hồ sơ', 5000, 16), ('Compa', 22000, 7);

-- Gắn ảnh mẫu cho các sản phẩm vừa tạo.
UPDATE products SET image = CONCAT('images/product-', id, '.png') WHERE id BETWEEN 1 AND 12;
