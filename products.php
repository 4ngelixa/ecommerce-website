<head>
    <?php
    include "inc/head.inc.php";
    ?>
</head>

<?php
// Database connection variables
$servername = "35.212.131.157";
$username = "inf1005-dev";
$password = "ADWXqezc1234";
$dbname = "inf1005_bling_bling";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product data from the database
$sql = "SELECT * FROM product WHERE product_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if product exists
if (!$product) {
    echo "Product not found";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Product Catalog</h1>
        <div class="products">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="product">
                        <img src="images/<?= strtolower($row["pname"]); ?>.png" alt="<?= htmlspecialchars($row["pname"]); ?>" />
                        <h2><a href="product_detail.php?id=<?= $row["product_id"]; ?>">
                                <?= htmlspecialchars($row["pname"]); ?>
                            </a></h2>
                        <p>$
                            <?= number_format((float) $row["price"], 2, '.', ''); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>