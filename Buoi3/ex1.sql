-- Chuẩn bị Database
CREATE DATABASE shopping_cart;
USE shopping_cart;

-- 1. Tạo bảng cart_items
CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

-- 2.1. Thêm ít nhất 5 sản phẩm 
INSERT INTO cart_items (name, price, quantity) 
VALUES 
    ('Chuột Logitech', 150000, 2),
    ('Bàn phím cơ', 850000, 1),
    ('Lót chuột', 50000, 10),
    ('Tai nghe Sony', 1200000, 3),
    ('Cáp sạc Type-C', 80000, 6);

-- 2.2. Hiển thị toàn bộ sản phẩm
SELECT * FROM cart_items;

-- 2.3. Hiển thị sản phẩm có giá lớn hơn 100000
SELECT * FROM cart_items WHERE price > 100000;

-- 2.4. Hiển thị sản phẩm có số lượng lớn hơn 5
SELECT * FROM cart_items WHERE quantity > 5;

-- 2.5. Sắp xếp sản phẩm theo giá giảm dần (DESC)
SELECT * FROM cart_items ORDER BY price DESC;

DELIMITER $$

-- Hàm 1: Lấy toàn bộ sản phẩm trong giỏ hàng
CREATE PROCEDURE GetAllCartItems()
BEGIN
    SELECT 
        id, 
        name, 
        price, 
        quantity, 
        (price * quantity) AS thanh_tien 
    FROM cart_items;
END $$

-- Hàm 2: Thêm một sản phẩm mới vào giỏ hàng
CREATE PROCEDURE AddToCart(
    IN p_name VARCHAR(100), 
    IN p_price DECIMAL(10,2), 
    IN p_quantity INT
)
BEGIN
    INSERT INTO cart_items (name, price, quantity) 
    VALUES (p_name, p_price, p_quantity);
END $$
-- Hàm 4: Cập nhật giá sản phẩm
CREATE PROCEDURE UpdateCartPrice(
    IN p_id INT, 
    IN p_new_price DECIMAL(10,2)
)
BEGIN
    UPDATE cart_items 
    SET price = p_new_price 
    WHERE id = p_id;
END $$

-- Hàm 3: Cập nhật số lượng của một sản phẩm 
CREATE PROCEDURE UpdateCartQuantity(
    IN p_id INT, 
    IN p_new_quantity INT
)
BEGIN
    UPDATE cart_items 
    SET quantity = p_new_quantity 
    WHERE id = p_id;
END $$

-- Hàm 5: Xóa sản phẩm khỏi giỏ hàng
CREATE PROCEDURE RemoveFromCart(
    IN p_id INT
)
BEGIN
    DELETE FROM cart_items WHERE id = p_id;
END $$

-- Hàm 6: Tính tổng tiền của toàn bộ giỏ hàng
CREATE PROCEDURE GetCartTotal()
BEGIN
    SELECT SUM(price * quantity) AS tong_tien FROM cart_items;
END $$

DELIMITER ;


-- Lấy toàn bộ sản phẩm trong giỏ hàng
CALL GetAllCartItems();

-- Thêm một sản phẩm mới 
CALL AddToCart('Loa Bluetooth', 450000, 1);

-- Cập nhật số lượng của sản phẩm có id = 2 thành 5
CALL UpdateCartQuantity(2, 5);

-- Cập nhật giá của sản phẩm có id = 1 thành 140000
CALL UpdateCartPrice(1, 140000);

-- Xóa sản phẩm có id = 3
CALL RemoveFromCart(3);

-- Tính tổng tiền của toàn bộ giỏ hàng
CALL GetCartTotal();


