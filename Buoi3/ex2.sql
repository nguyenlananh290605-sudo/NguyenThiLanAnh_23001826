-- 1. TẠO BẢNG MOVIES
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);

-- 2.1. Thêm ít nhất 5 bộ phim 
INSERT INTO movies (title, price, total_seats, available_seats) 
VALUES 
    ('Mai', 120000, 200, 10),
    ('Đào, Phở và Piano', 90000, 150, 150),
    ('Avengers: Endgame', 150000, 300, 0),
    ('Dune: Part Two', 110000, 250, 100),
    ('Kung Fu Panda 4', 95000, 200, 80);

-- 2.2. Hiển thị toàn bộ danh sách phim
SELECT * FROM movies;

-- 2.3. Hiển thị phim có giá vé lớn hơn 100000
SELECT * FROM movies WHERE price > 100000;

-- 2.4. Hiển thị phim còn nhiều hơn 50 ghế
SELECT * FROM movies WHERE available_seats > 50;

-- 2.5. Sắp xếp phim theo giá vé giảm dần
SELECT * FROM movies ORDER BY price DESC;

-- 2.6. Cập nhật số ghế còn lại của một phim 
UPDATE movies SET available_seats = 8 WHERE id = 1;

-- 2.7. Xóa một phim
DELETE FROM movies WHERE id = 2;

-- 2.8. Hiển thị số vé đã bán của từng phim
SELECT 
    title, 
    (total_seats - available_seats) AS ve_da_ban 
FROM movies;

-- 2.9. Tính doanh thu của từng phim
SELECT 
    title, 
    ((total_seats - available_seats) * price) AS doanh_thu 
FROM movies;

-- 2.10. Tính tổng doanh thu của tất cả các phim 
SELECT 
    SUM((total_seats - available_seats) * price) AS tong_doanh_thu 
FROM movies;

-- 2.11. Tìm phim có số vé bán ra nhiều nhất
SELECT 
    title, 
    (total_seats - available_seats) AS ve_da_ban 
FROM movies 
WHERE (total_seats - available_seats) = (
    SELECT MAX(total_seats - available_seats) FROM movies
);