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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT product_id, pname, price FROM Product";
$result = mysqli_query($conn, $sql);
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
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="product">
                        <img src="images/<?= strtolower($row["pname"]); ?>.png" alt="<?= htmlspecialchars($row["pname"]); ?>" />
                        <h2><a href="product_detail.php?id=<?= $row["product_id"]; ?>"><?= htmlspecialchars($row["pname"]); ?></a></h2>
                        <p>$<?= number_format((float)$row["price"], 2, '.', ''); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>