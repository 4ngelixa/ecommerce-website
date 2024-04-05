<?php
// Initialize error message and success flag
$errorMsg = '';
$success = true;
Session_start();

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
            width: 100%;
            overflow: hidden;
            padding-bottom: 80px;
            margin: 0;
        }

        p {
            font-size: 18px;
            font-weight: normal;
            line-height: 1.3;
        }

        .product-catalog-title {
            font-size: 35px;
            text-align: center;
            padding-top: 40px;
            padding-bottom: 20px;
            font-weight: bold;
        }

        .products {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        a.product {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-basis: 20%;
            margin: 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
            background: #fff;
            text-decoration: none;
            color: inherit;
            transition: background-color 0.3s;
        }

        a.product:hover {
            background-color: #f8f8f8;
            cursor: pointer;
        }

        .product img {
            max-width: 100%;
            height: auto;
            margin-bottom: auto;
        }

        .product h2 {
            font-size: 20px;
            margin: 0.5em 0;
        }

        .product p {
            color: #333;
            font-size: 18px;
            margin: 1px 0;
            font-weight: bold;
        }

        @media (max-width: 1200px) {
            a.product {
                flex-basis: 25%;
            }
        }

        @media (max-width: 600px) {
            a.product {
                flex-basis: 50%;
                padding: 10px;
            }
        }

        @media (max-width: 400px) {
            a.product {
                flex-basis: 100%;
                padding: 5px;
            }
        }
    </style>
</head>

<body>
    <?php include "inc/head.inc.php"; ?>
    <?php include "inc/nav.inc.php"; ?>

    <div class="container">
        <h1 class="product-catalog-title">✧ Bling Bling Badminton Products ✧</h1>
        <p> At Bling Bling Badminton, we are proud to offer a premier selection of essential badminton equipment for enthusiasts and
            professionals alike. </p>
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
    <?php include "inc/footer.inc.php"; ?>
</body>

</html>