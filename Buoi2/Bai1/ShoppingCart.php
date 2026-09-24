<?php
require_once 'CartItem.php';
class ProductNotFoundException extends Exception
{
}
class EmptyCartException extends Exception
{
}
class ShoppingCart
{
    private $items;

    public function addItem($item)
    {
        if (!$item instanceof CartItem) {
            throw new Exception("Item must be an instance of CartItem");
        }
        $this->items[] = $item;
        echo "Item added: " . $item->getName() . ", Price: " . $item->getPrice() . ", Quantity: " . $item->getQuantity() . "<br>";

    }

    public function removeItem($name)
    {
        if (empty($this->items)) {
            throw new EmptyCartException("Cart is empty");
        }

        $foundKey = null;
        foreach ($this->items as $key => $item) {
            if (strcasecmp($item->getName(), $name) === 0) {
                $foundKey = $key;
                break;
            }
        }

        if ($foundKey === null) {
            throw new ProductNotFoundException("Product not found in cart");
        }
        unset($this->items[$foundKey]);
        $this->items = array_values($this->items);
        echo "Item removed: " . $name . "<br>";

    }
    public function calculateTotal()
    {

        if (empty($this->items)) {
            throw new EmptyCartException("Cart is empty");
        }
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }
        return $total;
    }
    public function displayCart()
    {
        if (empty($this->items)) {
            throw new EmptyCartException("Cart is empty");
        }
        echo "<pre>";
        echo "Shopping Cart:<br>";
        echo "+------+----------------------+--------------+----------+----------------+<br>";
        echo "| STT  | Tên sản phẩm         | Đơn giá      | Số lượng | Thành tiền     |<br>";
        echo "+------+----------------------+--------------+----------+----------------+<br>";

        foreach ($this->items as $index => $item) {
            printf(
                "| %-4d | %-20s | %-12.2f | %-8d | %-14.2f |<br>",
                $index + 1,
                $item->getName(),
                $item->getPrice(),
                $item->getQuantity(),
                $item->getTotal()
            );
        }

        echo "+------+----------------------+--------------+----------+----------------+<br>";
        printf("| %-50s %19.2f VNĐ |<br>", "Total:", $this->calculateTotal());
        echo "+------------------------------------------------------------------------+<br>";
        echo "</pre>";
    }
}
?>