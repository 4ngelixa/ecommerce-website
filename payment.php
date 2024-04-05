<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payment</title>
    <link rel="stylesheet" href="css/payment.css">
    <?php
    // Include the head content
    include "inc/head.inc.php";
    ?>
</head>

<body>
    <div role="main" class="container">
        <form action="orderconfirmation.php" method="post">
            <!-- Payment Information Section -->
            <div role="region" aria-label="Payment Information" class="row">
                <!-- Payment Column -->
                <div class="col">
                    <!-- Payment Title -->
                    <h1 class="title">Payment</h1>

                    <!-- Card Accepted Section -->
                    <div class="inputBox">
                        <!-- Card Accepted Label -->
                        <span>Card Accepted:</span>
                        <!-- Card Types Image -->
                        <img src="./images/cardtypes.png" alt="card types">
                    </div>

                    <!-- Name On Card Input -->
                    <div class="inputBox">
                        <label for="cardName">Name On Card:</label>
                        <input type="text" id="cardName" name="cardName" placeholder="Enter card name" required>
                    </div>

                    <!-- Credit Card Number Input -->
                    <div class="inputBox">
                        <label for="cardNum">Credit Card Number:</label>
                        <input type="text" id="cardNum" name="cardNum" placeholder="1111-2222-3333-4444" maxlength="19" required>
                    </div>

                    <!-- Expiry Month Selection -->
                    <div class="inputBox">
                        <label for="expMonth">Exp Month:</label>
                        <select name="expMonth" id="expMonth" required>
                            <option value="">Choose month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>

                    <!-- Expiry Year Selection -->
                    <div class="flex">
                        <div class="inputBox">
                            <label for="expYear">Exp Year:</label>
                            <select name="expYear" id="expYear" required>
                                <option value="">Choose Year</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                                <option value="2031">2031</option>
                                <option value="2032">2032</option>
                                <option value="2033">2033</option>
                                <option value="2034">2034</option>
                            </select>
                        </div>

                        <!-- CVV Input -->
                        <div class="inputBox">
                            <label for="cvv">CVV</label>
                            <input type="number" id="cvv" name="cvv" placeholder="123" required>
                        </div>
                    </div>
                </div>

                <!-- Billing Address Column -->
                <div class="col">
                    <!-- Billing Address Header -->
                    <div class="billing-header">
                        <h1 class="title">Billing Address</h1>
                        <button type="button" class="backButton" onclick="window.location.href='card.php'">Back</button>
                    </div>

                    <!-- Full Name Input -->
                    <div class="inputBox">
                        <label for="fullName">Full Name:</label>
                        <input type="text" id="fullName" name="fullName" placeholder="Enter your full name">
                    </div>

                    <!-- Email Input -->
                    <div class="inputBox">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Enter email address">
                    </div>

                    <!-- Address Input -->
                    <div class="inputBox">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" placeholder="Enter address">
                    </div>

                    <!-- City Input -->
                    <div class="inputBox">
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" placeholder="Enter city">
                    </div>

                    <div class="flex">
                        <!-- State Input -->
                        <div class="inputBox">
                            <label for="state">State:</label>
                            <input type="text" id="state" name="state" placeholder="Enter state">
                        </div>

                        <!-- Zip Code Input -->
                        <div class="inputBox">
                            <label for="zip">Zip Code:</label>
                            <input type="text" id="zip" name="zip" placeholder="123 456">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <input type="submit" value="Place Order" class="submit_btn">
        </form>
    </div>
</body>

</html>
