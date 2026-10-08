<?php
require_once __DIR__ . '/../common/dbConnect.php';
// Function to get all products from the database
function getAllProducts()
{
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error fetching products: " . $e->getMessage());
    }
}
// Function to get a product by its ID
function getProductById($id)
{
    global $conn;
    try {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("Invalid product ID: " . $id);
        }
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch();
        if (!$product) {
            return null; // Return null if no product is found
        }
        return $product;

    } catch (PDOException $e) {
        throw new Exception("Error fetching product " . $id . ": " . $e->getMessage());
    }
}

// Function to add a new product to the database
function addProduct($name, $price, $quantity)
{
    global $conn;
    try {
        if (empty($name)) {
            throw new InvalidArgumentException("Product name cannot be empty.");
        }
        if (!is_numeric($price) || $price <= 0) {
            throw new InvalidArgumentException("Product price must be a non-negative number.");
        }
        if (!is_numeric($quantity) || $quantity <= 0) {
            throw new InvalidArgumentException("Product quantity must be a non-negative number.");
        }

        $stmt = $conn->prepare("INSERT INTO products (name, price, quantity) VALUES (:name, :price, :quantity)");

        $trimmedName = trim($name);
        $floatPrice = (float) $price;
        $intQuantity = (int) $quantity;

        $stmt->bindParam(':name', $trimmedName);
        $stmt->bindParam(':price', $floatPrice);
        $stmt->bindParam(':quantity', $intQuantity);

        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error adding product: " . $e->getMessage());
    }
}

// Function to update an existing product in the database
function updateProduct($id, $name, $price, $quantity)
{
    global $conn;
    try {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("Invalid product ID: " . $id);
        }
        if (empty($name)) {
            throw new InvalidArgumentException("Product name cannot be empty.");
        }
        if (!is_numeric($price) || $price <= 0) {
            throw new InvalidArgumentException("Product price must be a non-negative number.");
        }
        if (!is_numeric($quantity) || $quantity <= 0) {
            throw new InvalidArgumentException("Product quantity must be a non-negative number.");
        }

        $stmt = $conn->prepare("UPDATE products SET name = :name, price = :price, quantity = :quantity WHERE id = :id");

        $trimmedName = trim($name);
        $floatPrice = (float) $price;
        $intQuantity = (int) $quantity;

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $trimmedName);
        $stmt->bindParam(':price', $floatPrice);
        $stmt->bindParam(':quantity', $intQuantity);

        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error updating product " . $id . ": " . $e->getMessage());
    }
}

// Function to delete a product from the database
function deleteProduct($id)
{
    global $conn;
    try {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("Invalid product ID: " . $id);
        }
        $product = getProductById($id);
        if (!$product) {
            throw new Exception("Product with ID " . $id . " does not exist.");
        }
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error deleting product " . $id . ": " . $e->getMessage());
    } catch (Exception $e) {
        throw new Exception("Error deleting product " . $id . ": " . $e->getMessage());
    }
}

?>