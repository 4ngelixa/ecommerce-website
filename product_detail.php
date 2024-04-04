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

// Fetch product details
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

    // Fetch product reviews
    $reviews = [];
    if ($success && $productID) {
        $reviewQuery = "SELECT m.fname, m.lname, pr.review, pr.review_date 
                    FROM product_review pr 
                    JOIN member m ON pr.member_id = m.member_id 
                    WHERE pr.product_id = ? 
                    ORDER BY pr.review_date DESC";
        if ($stmt = $conn->prepare($reviewQuery)) {
            $stmt->bind_param("i", $productID);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
            $stmt->close();
        }
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
            margin-right: 160px;
        }

        .product-image img {
            max-width: 100%;
            max-height: 300px;
        }

        .product-name {
            flex: 2;
            margin-left: 20px;
            font-size: 40px;
        }

        .product-price {
            font-size: 30px;
            font-weight: bold;
            color: #e44d26;
            margin-bottom: 10px;
        }

        .product-description {
            margin-bottom: 10px;
            font-weight: normal;
            font-size: 20px;
            line-height: 1.2;
            margin-bottom: 10px;
            text-align: justify;
            text-justify: inter-word;
        }

        .product-sku,
        .product-stock {
            margin-bottom: 5px;
            color: darkgrey;
            line-height: 1.2;
            font-size: 20px;
        }

        /* 'Quantity' label */
        form#add-to-cart-form label {
            font-size: 20px;
        }

        /* 'Quantity' input field */
        form#add-to-cart-form input[type="number"] {
            font-size: 20px;
        }

        /* Add to Cart' button */
        form#add-to-cart-form button[type="submit"] {
            background-color: #413ea1;
            color: white;
            border: 1px solid grey;
            padding: 12px 18px;
            font-size: 20px;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        form#add-to-cart-form button[type="submit"]:hover {
            background-color: grey;
            color: white;
            border: 1px solid #413ea1;
        }

        .reviews-section {
            margin-top: 20px;
        }

        .reviewer-name {
            font-weight: bold;
        }

        .review-date {
            margin-left: 10px;
            font-size: 0.9em;
        }

        .review-text {
            margin-top: 5px;
            line-height: 1.4;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <?php include "inc/head.inc.php"; ?>
    <?php include "inc/nav.inc.php"; ?>

    <div style="padding-left: 30px; margin-top: 40px;">
        <a href="products.php" style="text-decoration: none; color: #413ea1; font-weight: bold;">&larr; Back to
            Products</a>
    </div>

    <?php if ($success && $product): ?>
        <div class="product-container">
            <div class="product-image">
                <img src="images/<?= strtolower($product["pname"]); ?>.png"
                    alt="<?= htmlspecialchars($product["pname"]); ?>" />
            </div>
            <div class="product-name">
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

    <hr>

    <!-- User Reviews !-->
    <?php if ($success && $product): ?>
        <!-- If there are product details to show -->
        <?php if (!empty($reviews)): ?>
            <div class="reviews-section">
                <h2>User Reviews</h2>
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <span class="reviewer-name">
                            <?= htmlspecialchars($review['fname'] . ' ' . $review['lname']); ?>
                        </span>
                        <span class="review-date" style="color: grey;">
                            <?= date("F j, Y, g:i a", strtotime($review['review_date'])); ?>
                        </span>
                        <p class="review-text">
                            <?= nl2br(htmlspecialchars($review['review'])); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <center>
                <p>No reviews yet.</p>
            </center>
        <?php endif; ?>
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