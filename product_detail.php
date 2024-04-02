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
    if ($stmt = $conn->prepare("SELECT pname, pdescription, sku, price, stock FROM Product WHERE product_id = ?")) {
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
        <?= $success ? htmlspecialchars($product["pname"]) : "Product Not Found"; ?>
    </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php if ($success): ?>
        <div class="product-detail">
            <img src="images/<?= strtolower($product["pname"]); ?>.png" alt="<?= htmlspecialchars($product["pname"]); ?>" />
            <h1>
                <?= htmlspecialchars($product["pname"]); ?>
            </h1>
            <p>Description:
                <?= nl2br(htmlspecialchars($product["pdescription"])); ?>
            </p>
            <p>SKU:
                <?= htmlspecialchars($product["sku"]); ?>
            </p>
            <p>Price: $
                <?= number_format((float) $product["price"], 2, '.', ''); ?>
            </p>
            <p>Stock:
                <?= htmlspecialchars($product["stock"]); ?> available
            </p>
            <button onclick="location.href='payment.php?id=<?= $productID; ?>'">Add to Cart</button>
        </div>
    <?php else: ?>
        <p>
            <?= $errorMsg; ?>
        </p>
    <?php endif; ?>
</body>

</html>