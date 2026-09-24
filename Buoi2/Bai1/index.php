<?php

require_once 'ShoppingCart.php';
$cart = new ShoppingCart();
function addItemToCart(ShoppingCart $cart, $item)
{
    try {
        $cart->addItem($item);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}
function removeItemFromCart(ShoppingCart $cart, $name)
{
    try {
        $cart->removeItem($name);
    } catch (ProductNotFoundException $e) {
        echo "Error Product Not Found: " . $e->getMessage() . "<br>";
    } catch (EmptyCartException $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}
/*
echo "checking invalid product data:<br>";
try {
    $invalidItem1 = new CartItem("", 10, 1);
} catch (InvalidProductDataException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

try {
    $invalidItem2 = new CartItem("Product 1", -5, 1);
} catch (InvalidProductDataException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

try {
    $invalidItem3 = new CartItem("Product 2", 10, 0);
} catch (InvalidProductDataException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
echo "----------------------------<br>";
*/

echo "Adding valid items to cart:<br>";
try {
    $item1 = new CartItem("laptop", 10, 2);

    $item2 = new CartItem("Mouse", 20, 1);

    $item3 = new CartItem("Keyboard", 15, 3);

    $item4 = new CartItem("Monitor", 25, 1);

    addItemToCart($cart, $item1);
    addItemToCart($cart, $item2);
    addItemToCart($cart, $item3);
    addItemToCart($cart, $item4);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

$cart->displayCart();

echo "Removing an item from cart:<br>";
removeItemFromCart($cart, "Mouse");
$cart->displayCart();


?>