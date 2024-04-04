<?php
session_start(); // Start a new session or resume the existing one
error_log('Session: ' . print_r($_SESSION, true));
$errorMsg = ''; // Initialize error message and success flag
$success = true;
$reviewPosted = false; // Flag to check if the review was posted

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

function sanitizeInput($data) {
    $data = trim($data); // Strip unnecessary characters (extra space, tab, newline)
    $data = stripslashes($data); // Remove backslashes (\)
    $data = htmlspecialchars($data); // Convert special characters to HTML entities
    return $data;
}

// Function to check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['id']); // 'id' is set in $_SESSION when a user logs in
}

// Include dependencies
include "inc/head.inc.php";
include "inc/nav.inc.php";

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

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review'])) {
    if (!isLoggedIn()) {
        // If the user is not logged in, redirect to the login page
        header('Location: authentication.php');
        exit();
    } else {
        $memberID = $_SESSION['id']; // Assuming 'id' is the session variable for the logged-in user
        $review = sanitizeInput($_POST['review']); // Sanitize the review
        $productID = $_POST['product_id']; // Assuming this comes from a hidden input in the form

        if (empty($review)) {
            $errorMsg = "Review cannot be empty.";
            $success = false;
        } else {
            $insertReviewSQL = "INSERT INTO product_review (product_id, member_id, review, review_date) VALUES (?, ?, ?, NOW())";
            if ($stmt = $conn->prepare($insertReviewSQL)) {
                $stmt->bind_param("iis", $productID, $memberID, $review);
                if ($stmt->execute()) {
                    $reviewPosted = true; // Indicate that the review was posted
                } else {
                    $errorMsg = "Error submitting the review: " . $stmt->error;
                    $success = false;
                }
                $stmt->close();
            } else {
                $errorMsg = "Failed to prepare the statement.";
                $success = false;
            }
        }
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

    // If review was posted successfully, show a pop-up message
    if ($reviewPosted) {
        echo "<script>alert('Your review has been posted successfully.');</script>";
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
    <link rel="stylesheet" href="css/product_detail.css">
    <title>
        <?= $success && $product ? htmlspecialchars($product["pname"]) : "Product Not Found"; ?>
    </title>
</head>

<body>
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

    <!-- User Reviews Section -->
    <?php if ($success && $product): ?>
        <div class="reviews-section">
            <h2 class="user-reviews-header">✧ User Reviews ✧</h2>
            <form method="post" action="product_detail.php?id=<?= $productID; ?>" class="review-form">
                <textarea name="review" required placeholder="Write your review here..." class="review-textarea"></textarea>
                <input type="hidden" name="product_id" value="<?= $productID; ?>">
                <div class="submit-button-container">
                    <button type="submit" class="submit-button">Submit Review</button>
                </div>
            </form>
            <!-- Existing Reviews -->
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <span class="reviewer-name">
                            <?= htmlspecialchars($review['fname'] . ' ' . $review['lname']); ?>
                        </span>
                        <span class="review-date">
                            <?= date("F j, Y, g:i a", strtotime($review['review_date'])); ?>
                        </span>
                        <p class="review-text-content">
                            <?= nl2br(htmlspecialchars($review['review'])); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-reviews-message">No reviews yet.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <script>
        var isLoggedIn = <?php echo json_encode(isLoggedIn()); ?>;

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

        // Function to handle review form submission
        document.querySelector('.review-form').addEventListener('submit', function (event) {
            // Prevent form submission
            event.preventDefault();

            // Check if the user is logged in
            if (!isLoggedIn) {
                // If the user is not logged in, redirect to the login page
                window.location.href = 'authentication.php';
                return;
            }

            // If logged in, proceed with form submission
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', this.action);
            xhr.onload = function () {
                // Handle response if needed
                if (xhr.status === 200) {
                    alert('Your review has been posted successfully.');
                    // Optionally reset the form or update the UI
                    document.querySelector('.review-form').reset();
                    // Code to refresh the reviews section can be added here
                } else {
                    // Handle error
                    alert('There was an error submitting your review.');
                }
            };
            xhr.onerror = function () {
                // Handle network errors
                alert('Network error. Please try again.');
            };
            xhr.send(formData);
        });
    </script>

    <?php include "inc/footer.inc.php"; ?>
</body>

</html>