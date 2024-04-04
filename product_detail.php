<?php
// Initialize error message and success flag
$errorMsg = '';
$success = true;

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

if ($success) {
    // Safely fetch the product ID from the URL
    $productID = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    // Prepare the SQL statement to prevent SQL injection
    if ($stmt = $conn->prepare("SELECT pname, pdescription, sku, price, stock FROM product WHERE product_id = ?")) {
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if (!$product) {
            $errorMsg = "Product not found.";
            $success = false;
        }
    } else {
        $errorMsg = "Failed to prepare the statement.";
        $success = false;
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $success && $product ? htmlspecialchars($product["pname"]) : "Product Not Found"; ?>
    </title>
    <style scoped>
        /* Define keyframes for animation */
        @keyframes scaleUp {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Apply animation to the button */
        button[type="submit"] {
            /* Your existing styles */
            animation: scaleUp 0.3s ease-in-out;
            /* Apply animation */
        }

        body {
            font-family: 'Inter', sans-serif;
            padding: 0;
            margin: 0;
            position: relative;
        }

        .product-container {
            display: flex;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            align-items: center;
        }

        .product-image {
            flex: 1;
            text-align: center;
            padding: 30px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image img {
            max-width: 100%;
            max-height: 300px;
        }

        .product-details {
            flex: 2;
            margin-left: 20px;
            font-size: 2em;
            font-weight: 700;
        }

        .product-price {
            font-size: 24px;
            font-weight: bold;
            color: #e44d26;
            margin-bottom: 10px;
        }

        .product-description {
            margin-bottom: 10px;
            font-size: 1em;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .product-sku,
        .product-stock {
            margin-bottom: 5px;
            color: darkgrey;
        }

        button {
            border: 1px;
            color: white;
            padding: 10px 20px;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            color: black;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include "inc/head.inc.php"; ?>
    <?php include "inc/nav.inc.php"; ?>

    <div style="padding-left: 30px; margin-top: 40px;">
        <a href="products.php" style="text-decoration: none; color: #413ea1; font-weight: bold;">&larr; Back to Products</a>
    </div>

    <?php if ($success && $product): ?>
        <div class="product-container">
            <div class="product-image">
                <img src="images/<?= strtolower($product["pname"]); ?>.png"
                    alt="<?= htmlspecialchars($product["pname"]); ?>" />
            </div>
            <div class="product-details">
                <h1>
                    <?= htmlspecialchars($product["pname"]); ?>
                </h1>
                <div class="product-price">$
                    <?= number_format((float) $product["price"], 2, '.', ''); ?>
                </div>
                <br>
                <div class="product-description">
                    <?= nl2br(htmlspecialchars($product["pdescription"])); ?>
                </div>
                <div class="product-sku">SKU:
                    <?= htmlspecialchars($product["sku"]); ?>
                </div>
                <div class="product-stock">Stock:
                    <?= htmlspecialchars($product["stock"]); ?> available
                </div>
                <br>
                <form method="post" action="shopping_cart.php" id="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?= $productID ?>">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="0" max="100">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <p>
            <?= $errorMsg ?: "Product not found." ?>
        </p>
    <?php endif; ?>

    <script>
        // Function to calculate total quantity in the cart
        function updateCartIcon() {
            var totalQuantity = 0;
            <?php if (isset($_SESSION['cart'])): ?>
                <?php foreach ($_SESSION['cart'] as $productId => $quantity): ?>
                    totalQuantity += <?= $quantity ?>;
                <?php endforeach; ?>
            <?php endif; ?>

            // Update the cart icon in the navbar
            var cartIcon = document.getElementById('cart-icon');
            if (cartIcon) {
                cartIcon.innerText = totalQuantity;
            }
        }

        // Function to handle form submission using AJAX
        document.getElementById('add-to-cart-form').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent form submission
            var quantity = parseInt(document.getElementById('quantity').value);

            // Now submit the form using AJAX
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', this.action);
            xhr.onload = function () {
                // Handle response if needed
                updateCartIcon(); // Update cart icon after successful submission
            };
            xhr.send(formData);
        });

        // Call the function initially to update the cart icon when the page loads
        updateCartIcon();
    </script>

    <?php include "inc/footer.inc.php"; ?>
</body>

</html>