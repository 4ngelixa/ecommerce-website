<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Order Confirmation</title>
    <link rel="stylesheet" href="css/orderplaced.css">
    <?php include "inc/head.inc.php"; ?>
</head>
<body>
    <?php include "inc/nav.inc.php"; ?>

    <main>
        <div class="placeorder content-wrapper">
            <div class="tick-container">
                <img src="./images/check.png" alt="Tick Image">
            </div>
            <h1>Your Order Has Been Placed</h1>
            <h2>Your Purchase History</h2>
            <div class="purchase-history">
                <table>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                    <?php
                    $orders = array(
                        array("name" => "Racket", "quantity" => 2, "price" => 40.00),
                        array("name" => "Shuttlecocks", "quantity" => 2, "price" => 30.00),
                        array("name" => "Towel", "quantity" => 2, "price" => 14.00)
                    );

                    // Display order details in table rows
                    foreach ($orders as $order) {
                        echo "<tr>";
                        echo "<td>" . $order['name'] . "</td>";
                        echo "<td>" . $order['quantity'] . "</td>";
                        echo "<td>$" . $order['price'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <p>Thank you for ordering with us!</p>
        </div>
    </main>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>

<!--intended implementation codes-->
<?php
// // Include necessary files
// require 'vendor/autoload.php'; // Include PHPMailer library

// // Initialize error message and success flag
// $errorMsg = '';
// $success = true;

// // Start the session
// session_start();

// // Create database connection
// $config = parse_ini_file('/var/www/private/db-config.ini');
// $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

// // Check connection
// if ($conn->connect_error) {
//     $errorMsg = "Connection failed: " . $conn->connect_error;
//     $success = false;
// }

// // Check if the connection was successful
// if ($success) {
//     // Database connection successful
//     // Now you can fetch the order details from the database and display them
//     // For example:
//     $query = "SELECT p.pname, pi.quantity, p.price FROM purchase_item pi INNER JOIN product p ON pi.product_id = p.product_id WHERE pi.purchase_id = ?";

//     // Assuming you have the purchase ID stored in the session
//     $purchaseId = $_SESSION['purchase_id'];

//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("i", $purchaseId);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         // Output the order details in a table
//         echo "<!DOCTYPE html>";
//         echo "<html lang='en'>";
//         echo "<head>";
//         echo "<title>Your Order Confirmation</title>";
//         echo "<link rel='stylesheet' href='css/orderplaced.css'>";
//         echo "</head>";
//         echo "<body>";
//         echo "<main>";
//         echo "<div class='placeorder content-wrapper'>";
//         echo "<div class='tick-container'>";
//         echo "<img src='./images/check.png' alt='Tick Image'>";
//         echo "</div>";
//         echo "<h1>Your Order Has Been Placed</h1>";
//         echo "<h2>Your Purchase History</h2>";
//         echo "<div class='purchase-history'>";
//         echo "<table>";
//         echo "<tr><th>Product Name</th><th>Quantity</th><th>Price</th></tr>";
//         while ($row = $result->fetch_assoc()) {
//             echo "<tr>";
//             echo "<td>" . $row['pname'] . "</td>";
//             echo "<td>" . $row['quantity'] . "</td>";
//             echo "<td>$" . $row['price'] . "</td>";
//             echo "</tr>";
//         }
//         echo "</table>";
//         echo "</div>";
//         echo "<p>Thank you for ordering with us!</p>";
//         echo "</div>";
//         echo "</main>";
//         echo "</body>";
//         echo "</html>";

//         // Send order confirmation email
//         $to = $_SESSION['user_email']; // Retrieve user's email from session
//         $subject = 'Your Order Confirmation';
//         $message = 'Thank you for your order! Your order has been successfully placed.';
//         $headers = 'From: your@example.com' . "\r\n" .
//             'Reply-To: your@example.com' . "\r\n" .
//             'X-Mailer: PHP/' . phpversion();

//         // Send email
//         if (mail($to, $subject, $message, $headers)) {
//             // Email sent successfully
//             echo "<script type='text/javascript'>alert('Order confirmation email sent.');</script>";
//         } else {
//             // Failed to send email
//             echo "<script type='text/javascript'>alert('Failed to send order confirmation email.');</script>";
//         }
//     } else {
//         echo "No orders found.";
//     }

//     $stmt->close();
// } else {
//     // Database connection failed
//     echo "Database connection failed. Error: " . $errorMsg;
// }
?>
