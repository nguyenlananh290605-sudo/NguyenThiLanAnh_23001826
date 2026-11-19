-- Gắn ảnh mẫu theo tên, chỉ khi sản phẩm chưa có ảnh.
USE buoi9_products;
UPDATE products SET image = CASE name
    WHEN 'Bút bi' THEN 'images/product-1.png'
    WHEN 'Vở ô ly' THEN 'images/product-2.png'
    WHEN 'Thước kẻ' THEN 'images/product-3.png'
    WHEN 'Bút chì' THEN 'images/product-4.png'
    WHEN 'Tẩy' THEN 'images/product-5.png'
    WHEN 'Bút màu' THEN 'images/product-6.png'
    WHEN 'Sổ tay' THEN 'images/product-7.png'
    WHEN 'Giấy A4' THEN 'images/product-8.png'
    WHEN 'Kéo' THEN 'images/product-9.png'
    WHEN 'Hồ dán' THEN 'images/product-10.png'
    WHEN 'Bìa hồ sơ' THEN 'images/product-11.png'
    WHEN 'Compa' THEN 'images/product-12.png'
    ELSE image END
WHERE image IS NULL;
