<?php
class InvalidProductDataException extends Exception
{
}
class CartItem
{
    private $name;
    private $price;
    private $quantity;

    public function __construct($name, $price, $quantity)
    {
        if (!is_string($name) || !is_numeric($price) || !is_numeric($quantity)) {
            throw new InvalidProductDataException("Invalid input types for CartItem");
        }
        if (empty($name)) {
            throw new InvalidProductDataException("Name cannot be empty");
        }
        if ($price <= 0) {
            throw new InvalidProductDataException("Price cannot be zero or negative");
        }
        if ($quantity <= 0) {
            throw new InvalidProductDataException("Quantity cannot be zero or negative");
        }
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function getTotal()
    {
        return $this->price * $this->quantity;
    }
}
?>