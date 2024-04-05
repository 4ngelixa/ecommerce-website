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
<!--DOCTYPE html>
<html lang="en">
<head>
    <title>Your Order Confirmation</title>
    <link rel="stylesheet" href="css/orderplaced.css">
    <?php
        //include "inc/head.inc.php";
    ?>
</head>
<body>
    <?php 
        //include "inc/nav.inc.php";
    ?>
    <?php
    // Assuming you have already started the session
    //session_start();

    //if (isset($_SESSION['user_email'])) {
        // Send email notification
    //     $to = $_SESSION['user_email']; // Retrieve user's email from session
    //     $subject = 'Your Order Confirmation';
    //     $message = 'Thank you for your order! Your order has been successfully placed.';
    //     $headers = 'From: your@example.com' . "\r\n" .
    //         'Reply-To: your@example.com' . "\r\n" .
    //         'X-Mailer: PHP/' . phpversion();

    //     // Send email
    //     if (mail($to, $subject, $message, $headers)) {
    //         echo '<div class="placeorder content-wrapper">';
    //         echo '<div class="tick-container">';
    //         echo '<img src="./images/check.png" alt="Tick Image">';
    //         echo '</div>';
    //         echo '<h1>Your Order Has Been Placed</h1>';
    //         echo '<p>Thank you for ordering with us! We\'ll contact you by email with your order details.</p>';
    //         echo '</div>';
    //     } else {
    //         echo 'Failed to send email notification.';
    //     }
    // } else {
    //     echo 'User email not found in session.';
    // }
    ?>

    <?php
    //include "inc/footer.inc.php";
    ?>
</body>
</html-->
