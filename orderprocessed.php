<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Confirmation</title>
    <link rel="stylesheet" href="css/orderplaced.css">
    <?php include "inc/head.inc.php"; ?>
</head>
<body>
    <?php include "inc/nav.inc.php"; ?>
    
    <?php
    // Include database connection or any necessary initialization
// Initialize error message and success flag
    $errorMsg = '';
    $success = true;

    // Start the session
    session_start();

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

    // Retrieve the member ID from the session or wherever it's stored after login
    $member_id = $_SESSION['member_id']; // Adjust this according to your actual session implementation

    // Query to retrieve the purchase details for the member
    $sql = "SELECT p.*, pi.quantity, pr.pname, pr.price
            FROM purchase p
            JOIN purchase_item pi ON p.purchase_id = pi.purchase_id
            JOIN product pr ON pi.product_id = pr.product_id
            WHERE p.member_id = $member_id";

    $result = mysqli_query($conn, $sql); // Change $connection to $conn

    // Check if there are any purchases
    if (mysqli_num_rows($result) > 0) {
        // Display purchase details
        echo "<div class='purchase-history'>";
        echo "<h2>Your Purchase History</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Product Name: " . $row['pname'] . "</p>";
            echo "<p>Quantity: " . $row['quantity'] . "</p>";
            echo "<p>Price: $" . $row['price'] . "</p>";
            // You can display more details as needed
            echo "<hr>";
        }
        echo "</div>";
    } else {
        // No purchases found for the member
        echo "<p>No purchases found.</p>";
    }

    // Close the database connection
    mysqli_close($conn); // Change $connection to $conn
    ?>
    
    <?php include "inc/footer.inc.php"; ?>
</body>
</html>
