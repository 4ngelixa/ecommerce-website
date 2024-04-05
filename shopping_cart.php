<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/carts.css">
    <?php 
        include "inc/head.inc.php"; 
    ?>
</head>

<?php
// Include database connection or any necessary initialization
// Initialize error message and success flag
$errorMsg = '';
$success = true;

// Start the session
session_start();

// Create database connection
$config = parse_ini_file('/var/www/private/db-config.ini');
if (!$config) {
    $errorMsg = "Failed to read database config file.";
    $success = false;
} else {
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    }
}

// Check if the user has submitted a form to add a product to the cart
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    // Set the post variables so we easily identify them, also make sure they are integer
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    // Prepare the SQL statement, we basically are checking if the product exists in our database
    $stmt = $conn->prepare('SELECT * FROM product WHERE product_id = ?');
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    // Check if the product exists (array is not empty)
    if ($product && $quantity > 0) {
        // Product exists in database, now we can create/update the session variable for the cart
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (array_key_exists($product_id, $_SESSION['cart'])) {
                // Product exists in cart so just update the quantity
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                // Product is not in cart so add it
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            // There are no products in cart, this will add the first product to cart
            $_SESSION['cart'] = array($product_id => $quantity);
        }
    }
}

// Fetch products from database if cart is not empty
$productsInCart = [];
if (!empty($_SESSION['cart'])) {
    // Prepare statement to fetch products from database
    $productIds = implode(',', array_keys($_SESSION['cart']));
    $query = "SELECT * FROM product WHERE product_id IN ($productIds)";
    $result = $conn->query($query);
    // Store fetched products in an array
    while ($row = $result->fetch_assoc()) {
        $productsInCart[$row['product_id']] = $row;
    }
}

// Handle Remove Action
if(isset($_POST['remove'])) {
    $removeProductId = $_POST['remove'];
    if(isset($_SESSION['cart'][$removeProductId])) {
        unset($_SESSION['cart'][$removeProductId]);
    }
    // Optionally, redirect back to the same page to avoid resubmission on refresh
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle Update Action
if(isset($_POST['update']) && isset($_SESSION['cart'])) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'quantity-') !== false && is_numeric($value)) {
            $productId = str_replace('quantity-', '', $key);
            $quantity = (int)$value;

            if (isset($_SESSION['cart'][$productId]) && $quantity > 0) {
                $_SESSION['cart'][$productId] = $quantity;
            }
        }
    }
    // Optionally, redirect back to the same page to avoid resubmission on refresh
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Close the database connection
$conn->close();
?>

<body>

<?php
// Include navigation
include "inc/nav.inc.php";
?>

<main class="container">
    <?php
    // Check if the cart is empty
    if (empty($productsInCart)) {
        echo "<div class='empty-cart-container'>";
        echo "<div class='empty-cart-message'>Your Cart is Empty</div>";
        echo "<div class='empty-cart-image'><img src='images/emptycart.jpg' alt='Empty Cart Image'></div>";
        echo '<form action="products.php" method="get"><button type="submit" class="view-products-button">View Products</button></form>';
        echo "</div>";
    } else {
        // Cart is not empty, display cart items
    ?>
    <div class="cart-container">
        <h1>Your Cart</h1>
        <div class="cart-items">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th> <!-- Added a column for action -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productsInCart as $product): ?> <!-- Changed $products to $productsInCart -->
                            <tr>
                                <td>
                                    <h2><?= htmlspecialchars($product["pname"]); ?></h2>
                                </td>
                                <td class="price">$<?= number_format((float)$product["price"], 2, '.', ''); ?></td>
                                <td class="quantity">
                                    <input type="number" name="quantity-<?= $product["product_id"] ?>" value="<?= $_SESSION['cart'][$product['product_id']] ?>" min="0" max="100" aria-label="Quantity">
                                </td>
                                <td class="price">$<?= number_format((float)$product['price'] * (int)$_SESSION['cart'][$product['product_id']], 2, '.', ''); ?></td>
                                <td class="actions">
                                <button type="submit" class="remove-button" name="remove" value="<?= $product['product_id'] ?>" aria-label="Remove Product <?= $product['product_name'] ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="subtotal">
                    <span class="text">Subtotal</span>
                    <?php
                        // Calculate subtotal
                        $subtotal = 0;
                        foreach ($productsInCart as $product) {
                            $subtotal += $product['price'] * $_SESSION['cart'][$product['product_id']];
                        }
                    ?>
                    <span class="price">$<?= number_format($subtotal, 2, '.', ''); ?></span>
                </div>
                <div class="buttons">
                    <input type="submit" value="Continue Browsing" name="continue_browsing" formaction="products.php">
                    <input type="submit" value="Update" name="update">
                    <input type="submit" value="Proceed to checkout" name="proceed_to_checkout" formaction="checkout.php">
                </div>
            </form>
        </div>
    <?php
    }
    ?>
</main>

<?php
// Include footer
include "inc/footer.inc.php";
?>

<script defer src="js/cart.js"></script>
</body>
</html>
