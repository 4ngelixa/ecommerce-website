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
    $sql = "SELECT product_id, pname, price FROM product";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .container {
            width: 90%;
            margin: auto;
            overflow: hidden;
        }

        .product-catalog-title {
            text-align: center;
            margin: 2em 0;
            font-weight: bold;
        }

        .products {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        a.product {
            flex-basis: 20%;
            width: 20%;
            margin: 2%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 1em;
            background: #fff;
            text-decoration: none;
            color: inherit;
        }

        a.product:hover {
            background-color: #f8f8f8;
            cursor: pointer;
        }

        .product img {
            max-width: 100%;
            height: auto;
        }

        .product h2 {
            font-size: 1.5em;
            margin: 0.5em 0;
        }

        .product p {
            color: #333;
            font-size: 1.3em;
            margin: 0.5em 0;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            a.product {
                flex-basis: 45%;
                width: 45%;
            }
        }

    </style>
</head>

<body>
    <?php include "inc/head.inc.php"; ?>
    <?php include "inc/nav.inc.php"; ?>

    <div class="container">
        <h1 class="product-catalog-title">✧ Bling Bling Badminton Products ✧</h1>
        <div class="products">
            <?php if ($success && $result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <a href="product_detail.php?id=<?= $row["product_id"]; ?>" class="product">
                        <img src="images/<?= strtolower($row["pname"]); ?>.png" alt="<?= htmlspecialchars($row["pname"]); ?>" />
                        <h2>
                            <?= htmlspecialchars($row["pname"]); ?>
                        </h2>
                        <p>$
                            <?= number_format((float) $row["price"], 2, '.', ''); ?>
                        </p>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>
                    <?= $errorMsg ?: "No products found." ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    // Close the database connection if it was successful
    if ($success) {
        $conn->close();
    }
    ?>
</body>

</html>