<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/cart.css">
    <?php include "inc/head.inc.php"; ?>
</head>

<?php
// Include navigation
include "inc/nav.inc.php";
?>

<main class="container">
    <?php
    // Check if the cart is empty
    if (empty($products_in_cart)) {
        echo "<div class='empty-cart-container'>";
        echo "<div class='empty-cart-message'>Your Cart is Empty</div>";
        echo "<div class='empty-cart-image'><img src='images/emptycart.jpg' alt='Empty Cart Image'></div>";
        echo "</div>";
        echo "<button class='view-products-button' onclick='location.href=\"products.php\"'>View Products</button>";
    } else {
        // Cart is not empty, display cart items
    ?>
        <h1>Your Cart</h1>
        <div class="cart-items">
            <form method="post">
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="img">
                                    <!-- Add image here if needed -->
                                </td>
                                <td>
                                    <h2><?= htmlspecialchars($product["pname"]); ?></h2>
                                </td>
                                <td class="price">$<?= number_format((float)$product["price"], 2, '.', ''); ?></td>
                                <td class="quantity">
                                    <input type="number" name="quantity-<?= $product["id"] ?>" value="<?= $products_in_cart[$product['id']] ?>" min="1" max="10">
                                </td>
                                <td class="price">$<?= number_format((float)$product['price'] * (int)$products_in_cart[$product['id']], 2, '.', ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="subtotal">
                    <span class="text">Subtotal</span>
                    <span class="price">$<?= number_format($subtotal, 2, '.', ''); ?></span>
                </div>
                <div class="buttons">
                    <input type="submit" value="Update" name="update">
                    <input type="submit" value="Place Order" name="placeorder">
                    <button onclick="location.href='card.php'">Proceed to Checkout</button>
                </div>
            </form>
        </div>
    <?php } ?>
</main>

<?php
// Include footer
include "inc/footer.inc.php";
?>

<script defer src="js/cart.js"></script>
</body>
