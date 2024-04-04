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

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>
