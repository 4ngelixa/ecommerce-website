<?php
// Database connection variables
$servername = "35.212.131.157";
$username = "inf1005-dev";
$password = "ADWXqezc1234";
$dbname = "inf1005_bling_bling";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Safely fetch the product ID from the URL
$productID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT pname, pdescription, sku, price, stock FROM Product WHERE product_id = ?");
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if(!$product) {
    echo "Product not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product["pname"]); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="product-detail">
        <img src="images/<?= strtolower($product["pname"]); ?>.png" alt="<?= htmlspecialchars($product["pname"]); ?>" />
        <h1><?= htmlspecialchars($product["pname"]); ?></h1>
        <p>Description: <?= nl2br(htmlspecialchars($product["pdescription"])); ?></p>
        <p>SKU: <?= htmlspecialchars($product["sku"]); ?></p>
        <p>Price: $<?= number_format((float)$product["price"], 2, '.', ''); ?></p>
        <p>Stock: <?= htmlspecialchars($product["stock"]); ?> available</p>
        <button onclick="location.href='payment.php?id=<?= $productID; ?>'">Add to Cart</button>
    </div>
</body>
</html>