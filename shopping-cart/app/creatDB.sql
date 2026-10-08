DROP DATABASE IF EXISTS shopping_cart;
CREATE DATABASE IF NOT EXISTS shopping_cart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shopping_cart;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO products (name, price, quantity) VALUES
('Laptop Dell Inspiron', 15500000.00, 10),
('Chuột Logitech Wireless', 350000.00, 50),
('Bàn phím cơ AKKO', 1250000.00, 20),
('Màn hình LG 24 inch', 3200000.00, 15),
('Tai nghe Kingston HyperX', 990000.00, 30);