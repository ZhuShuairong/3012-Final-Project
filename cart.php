<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if cart session variable exists, if not, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product already exists in the cart
    $foundInCart = false;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['product_id'] === $product_id) {
            $foundInCart = true;
            break;
        }
    }

    if ($foundInCart) {
        echo "This item is already in your cart.";
    } else {
        // Add the product to the cart
        $cart_item = array(
            'product_id' => $product_id,
            'quantity' => $quantity
        );

        $_SESSION['cart'][] = $cart_item;

        echo "Item added to cart successfully.";
    }
}

if (isset($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];

    if (!empty($cart_items)) {
        echo "<div class='cart-item'>";
        echo "<h2>Cart Items</h2>";
        foreach ($cart_items as $item) {
            echo "<p>Product ID: " . $item['product_id'] . " | Quantity: " . $item['quantity'] . "</p>";
        }
        echo "</div>";
    } else {
        echo "<p>Cart is empty.</p>";
    }
} else {
    echo "<p>Cart is empty.</p>";
}
?>
<a href="shop.php" class="back-button">Back to Shop</a>