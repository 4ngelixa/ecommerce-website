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

// Proceed with database operations if successful
if ($success) {
    $sql = "SELECT product_id, pname, price FROM Product";
    $result = $conn->query($sql);
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
            <?php if ($success && $result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
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
                <p>
                    <?= $errorMsg ?: "No products found." ?>
                </p>
            <?php endif; ?>
            <?php
            // Close the database connection if it was successful
            if ($success) {
                $conn->close();
            }
            ?>
        </div>
    </div>
</body>

</html>