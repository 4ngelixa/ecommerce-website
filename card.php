<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Method</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" type="text/css" href="css/card.css">
</head>
<body>
    <main>
        <div class="container">
            <div class="title">
                <h1>Select a <span style="color: rgb(33, 77, 159)">Payment</span> method</h1>
            </div>

            <form action="#">
                <div class="category">
                    <input type="radio" name="payment" id="creditcard" value="creditcard">
                    <label for="creditcard" class="creditMethod">
                        <img src="./images/visa_mastercard.png" alt="credit card">
                        <span class="name">Credit Card</span>
                        <span><i class="fa-solid fa-circle-check" style="color:cornflowerblue;"></i></span>
                    </label>

                    <input type="radio" name="payment" id="grabpay" value="grabpay">
                    <label for="grabpay" class="grabpayMethod">
                        <img src="./images/grabpay.png" alt="grab pay">
                        <span class="name">Grab Pay</span>
                        <span><i class="fa-solid fa-circle-check" style="color:cornflowerblue;"></i></span>
                    </label>

                    <input type="radio" name="payment" id="paypal" value="paypal">
                    <label for="paypal" class="paypalMethod">
                        <img src="./images/paypal.png" alt="paypal">
                        <span class="name">PayPal</span>
                        <span><i class="fa-solid fa-circle-check" style="color:cornflowerblue;"></i></span>
                    </label>

                    <input type="radio" name="payment" id="googlepay" value="googlepay">
                    <label for="googlepay" class="googlepayMethod">
                        <img src="./images/googlepay.png" alt="google pay">
                        <span class="name">Google Pay</span>
                        <span><i class="fa-solid fa-circle-check" style="color:cornflowerblue;"></i></span>
                    </label>
                </div>

                <div class="button-container">
                    <button type="button" class="nextButton" onclick="window.location.href='payment.php'">Next</button>
                    <button type="button" class="backButton" onclick="window.location.href='checkout.php'">Back</button>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <!-- Footer content goes here if applicable -->
    </footer>
</body>
</html>
