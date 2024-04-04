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
    <style>
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

        /* Apply animation to button */
        button[type="submit"] {
            animation: scaleUp 0.3s ease-in-out;
        }

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #closeBtn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 40px;
            color: white;
            cursor: pointer;
        }

        #zoomedImg {
            display: block;
            max-width: 80%;
            max-height: 80%;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
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
            margin-right: 50px;
        }

        .product-image img {
            max-width: 100%;
            max-height: 300px;
        }

        .product-details {
            flex: 2;
            margin-left: 20px;
        }

        .product-details h1 {
            font-size: 35px;
            font-weight: bold;
        }

        .product-price {
            font-size: 28px;
            font-weight: bold;
            color: #e44d26;
            margin-bottom: 10px;
        }

        .product-description {
            margin-bottom: 10px;
            margin-right: 10px;
            font-weight: normal;
            font-size: 18px;
            line-height: 1.3;
            text-align: justify;
            text-justify: inter-word;
        }

        .product-sku,
        .product-stock {
            padding-top: 5px;
            margin-bottom: 5px;
            color: darkgrey;
            line-height: 1.2;
            font-size: 15px;
            font-weight: normal;
        }

        /* 'Quantity' label */
        form#add-to-cart-form label {
            font-size: 18px;
        }

        /* 'Quantity' input field */
        form#add-to-cart-form input[type="number"] {
            font-size: 18px;
        }

        /* Add to Cart' button */
        form#add-to-cart-form button[type="submit"] {
            background-color: #413ea1;
            color: white;
            border: 1px solid grey;
            padding: 8px 14px;
            font-size: 15px;
            text-transform: uppercase;
            cursor: pointer;
            margin-left: 10px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        form#add-to-cart-form button[type="submit"]:hover {
            background-color: grey;
            color: white;
            border: 1px solid #413ea1;
        }

        .reviews-section {
            margin: 30px 50px 40px;
        }

        .reviews-section h2 {
            font-size: 25px;
            font-weight: bold;
            color: #413ea1;
        }

        .reviewer-name {
            font-weight: bold;
        }

        .review-date {
            margin-left: 10px;
            font-size: 0.9em;
            font-weight: normal;
        }

        .review-text {
            margin-top: 5px;
            line-height: 1.4;
            font-weight: normal;
        }

        /* Media query for smaller screens */
        @media only screen and (max-width: 768px) {
            .product-container {
                flex-direction: column;
                align-items: stretch;
            }

            .product-image {
                margin-right: 0;
                margin-bottom: 20px;
            }

            .product-details {
                margin-left: 0;
            }
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
            <div class="product-image" onclick="zoomImage(this)">
                <img id="productImg" src="images/<?= strtolower($product["pname"]); ?>.png"
                    alt="<?= htmlspecialchars($product["pname"]); ?>" />
                <div id="overlay" onclick="closeZoom()" style="display:none;">
                    <span id="closeBtn" title="Close">&times;</span>
                    <img src="" id="zoomedImg" alt="Zoomed" />
                </div>
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

    <hr>

    <!-- User Reviews !-->
    <?php if ($success && $product): ?>
        <!-- If there are product details to show -->
        <?php if (!empty($reviews)): ?>
            <div class="reviews-section">
                <h2>✧ User Reviews ✧</h2>
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
            <h2 style="font-size: 25px; padding-left: 40px; font-weight: bold; color: #413ea1;">✧ User Reviews ✧</h2>
            <p style="padding-left: 40px; font-weight = normal;">No reviews yet.</p>
        <?php endif; ?>
    <?php endif; ?>


    <script>
        // function to zoom product image
        function zoomImage(imageContainer) {
            var imgSrc = imageContainer.querySelector('img').src; // Get the source of the image to be zoomed
            var overlay = document.getElementById('overlay');
            var zoomedImg = document.getElementById('zoomedImg');

            zoomedImg.src = imgSrc; // Set the source for the zoomed image
            overlay.style.display = 'flex'; // Display the overlay with flex to center the image
        }

        function closeZoom() {
            var overlay = document.getElementById('overlay');
            overlay.style.display = 'none'; // Hide the overlay
        }

        // Ensure that the overlay is closed when the close button is clicked.
        document.getElementById('closeBtn').onclick = function (event) {
            closeZoom();
            event.stopPropagation(); // Prevent the event from bubbling up to the image container
        }

        // Prevent the overlay from closing when the zoomed image itself is clicked
        document.getElementById('zoomedImg').onclick = function (event) {
            event.stopPropagation();
        }

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