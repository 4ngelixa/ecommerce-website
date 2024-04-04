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

// Check if email is provided in the URL parameters
if(isset($_GET['email'])) {
    $_SESSION["email"] = $_GET['email']; // Store the email in session
} else {
    $_SESSION["email"] = NULL; // Set to NULL if email is not provided
}

// Fetch products from database if cart is not empty
$productsInCart = [];
$subtotal = 0;
if (!empty($_SESSION['cart'])) {
    // Prepare statement to fetch products from database
    $productIds = implode(',', array_keys($_SESSION['cart']));
    $query = "SELECT * FROM product WHERE product_id IN ($productIds)";
    $result = $conn->query($query);
    // Store fetched products in an array
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $_SESSION['cart'][$product_id];
        $productsInCart[] = [
            'product_id' => $product_id,
            'pname' => $row['pname'],
            'price' => $row['price'],
            'quantity' => $quantity,
            'total' => $row['price'] * $quantity
        ];
        // Calculate subtotal
        $subtotal += $row['price'] * $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/checkout.css">
    <!-- Include any necessary PHP files -->
    <?php include "inc/head.inc.php"; ?>
</head>
<body>
    <!-- Include navigation -->
    <?php include "inc/nav.inc.php"; ?>
    
    <div class="checkout-container">
    <h1>Checkout</h1>

        <!-- Select Pickup Venue dropdown menu -->
        <div class="pickup-venue">
            <h2>Select Pickup Venue</h2>
            <select name="pickup-venue" id="pickup-venue">
                <option value="store">Select a venue</option>
                <option value="store1">Serangoon Chu Kang Stadium</option>
                <option value="store2">Yio Hougang Sports Hall</option>
                <option value="store3">Bouna Besar Sports Hall</option>
            </select>
        </div>
        <!-- Rest of your checkout form here -->
    </div>

        <!-- Display ordered products and total price -->
        <div class="ordered-products">
            <h2>Ordered Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productsInCart as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product["pname"]); ?></td>
                            <td>$<?= number_format((float)$product["price"], 2, '.', ''); ?></td>
                            <td><?= $product["quantity"]; ?></td>
                            <td>$<?= number_format((float)$product["total"], 2, '.', ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total-price">
                <h3>Total Price: $<?= number_format($subtotal, 2, '.', ''); ?></h3>
            </div>
        </div>

        <!-- Form to collect user's email for confirmation -->
        <div class="email-form">
            <h2>Enter Your Email</h2>
            <form action="place_order.php" method="post">
                <input type="email" name="email" id="email" placeholder="Your Email" required>
                <button type="submit" name="next" formaction="card.php">Next</button>
            </form>
        </div>

        <!-- Buttons for back and next -->
        <div class="buttons">
            <button onclick="window.location.href='shopping_cart.php'" class="back-button">Back</button>
        </div>
    </main>

    <!-- Include footer -->
    <?php include "inc/footer.inc.php"; ?>

    <!-- Include any necessary JavaScript files -->
    <script defer src="js/checkout.js"></script>
</body>
</html>
