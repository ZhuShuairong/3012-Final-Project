<?php
session_start();
print $_SESSION['cart'];

    if (isset($cart_items)): ?>
        <div class="cart-item">
            <h2>Cart Items</h2>
            <?php foreach ($cart_items as $item): ?>
                <p>Product ID: <?php echo $item['product_id']; ?> | Quantity: <?php echo $item['quantity']; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
