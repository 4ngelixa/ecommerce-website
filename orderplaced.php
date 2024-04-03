<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Confirmation</title>
    <link rel="stylesheet" href="css/orderplaced.css">
    <?php
        include "inc/head.inc.php";
    ?>
</head>
<body>
    <?php 
        include "inc/nav.inc.php";
    ?>
    <?php
    // Assuming you have already started the session
    session_start();

    if (isset($_SESSION['user_email'])) {
        // Send email notification
        $to = $_SESSION['user_email']; // Retrieve user's email from session
        $subject = 'Your Order Confirmation';
        $message = 'Thank you for your order! Your order has been successfully placed.';
        $headers = 'From: your@example.com' . "\r\n" .
            'Reply-To: your@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        // Send email
        if (mail($to, $subject, $message, $headers)) {
            echo '<div class="placeorder content-wrapper">';
            echo '<div class="tick-container">';
            echo '<img src="./images/check.png" alt="Tick Image">';
            echo '</div>';
            echo '<h1>Your Order Has Been Placed</h1>';
            echo '<p>Thank you for ordering with us! We\'ll contact you by email with your order details.</p>';
            echo '</div>';
        } else {
            echo 'Failed to send email notification.';
        }
    } else {
        echo 'User email not found in session.';
    }
    ?>

    <?php
    include "inc/footer.inc.php";
    ?>
</body>
</html>
