<!--DOCTYPE html-->
<!--html lang="en"-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Payment Method</title>
    <link rel="stylesheet" type="text/css" href="css/card.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <h4>Select a <span style="color:cornflowerblue">Payment</span> method</h4>
        </div>

        <form action="#">
            <input type="radio" name="payment" id="creditcard">
            <input type="radio" name="payment" id="grabpay">
            <input type="radio" name="payment" id="paypal">
            <input type="radio" name="payment" id="googlepay">

            <div class="category">
                <label for="creditcard" class="creditMethod">
                    <div class="imgName">
                        <div class="imgContainer credit">
                            <img src="./images/visa_mastercard.png" alt="credit card">
                        </div>
                        <span class="name">Credit Card</span>
                    </div>
                    <span><i class="fa-solid fa-circle-check" style="color:cornflowerblue;"></i></span>
                </label>
                <label for="grabpay" class="grabpayMethod">
                    <div class="imgName">
                    <div class="imgContainer">
                            <img src="./images/grabpay.png" alt="grab pay">
                        </div>
                        <span class="name">Grab Pay</span>
                    </div>
                    <span><i class="fa-solid fa-circle-check" style="color:cornflowerblue;"></i></span>
                </label>
                <label for="paypal" class="paypalMethod">
                    <div class="imgName">
                    <div class="imgContainer">
                            <img src="./images/paypal.png" alt="paypal">
                        </div>
                        <span class="name">PayPal</span>
                    </div>
                    <span><i class="fa-solid fa-circle-check" style="color:cornflowerblue;"></i></span>
                </label>
                <label for="googlepay" class="googlepayMethod">
                    <div class="imgName">
                    <div class="imgContainer">
                            <img src="./images/googlepay.png" alt="google pay">
                        </div>
                        <span class="name">Google Pay</span>
                    </div>
                    <span><i class="fa-solid fa-circle-check" style="color:cornflowerblue;"></i></span>
                </label>
            </div>
            <div class="button-container">
                <button type="button" class="nextButton" onclick="window.location.href='payment.php'">Next</button>
                <button type="button" class="backButton" onclick="window.location.href='shopping_cart.php'">Back</button>
            </div>
            </form>
    </div>