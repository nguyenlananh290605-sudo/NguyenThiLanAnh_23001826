-- Tùy chọn: nâng cấp dữ liệu buổi 8 hiện có. Chỉ chạy một lần.
-- Sau đó đổi tên cơ sở dữ liệu trong config/database.php thành buoi8_products.
USE buoi8_products;
ALTER TABLE products ADD COLUMN image VARCHAR(255) NULL;
INSERT INTO products (name, price, stock) VALUES
    ('Bút chì', 4000, 30), ('Tẩy', 3000, 25), ('Bút màu', 25000, 10),
    ('Sổ tay', 18000, 12), ('Giấy A4', 65000, 8), ('Kéo', 15000, 9),
    ('Hồ dán', 7000, 20), ('Bìa hồ sơ', 5000, 16), ('Compa', 22000, 7);
