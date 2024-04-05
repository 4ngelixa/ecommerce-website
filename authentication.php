<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/logins.css">
    <title>Bling Bling</title>
    <?php
    include "inc/head.inc.php";
    ?>
</head>

<body>
    <?php 
        include "inc/nav.inc.php";
    ?>
    <main class="container d-flex justify-content-center">
        <div class="login-wrap">
            <div class="login-html">
                
                <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign
                    In</label>
                <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Sign Up</label>
                <div class="login-form">
                    <form action="process_login.php" method="post">
                        <div class="sign-in-htm">
                        <h1 class="text-center">Welcome to Bling Bling</h1>
                            <div class="group">
                                <label for="email" class="form-label">Email:</label>
                                <input maxlength="45" type="email" id="email" name="email" class="form-control"
                                    placeholder="Enter email">
                            </div>
                            <div class="group">
                                <label for="pwd" class="form-label">Password:</label>
                                <input type="password" id="pwd" name="pwd" class="form-control"
                                    placeholder="Enter password">
                            </div>
                            <br>
                            <div class="group">
                                <input type="submit" class="button" value="Sign In">
                            </div>
                            <div class="hr"></div>
                            <div class="foot-lnk">
                                <a href="forget_pass.php" style="color : #F2F2F2;">Forgot Password?</a>
                            </div>
                        </div>
                    </form>
                    <form action="process_register.php" method="post">
                        <div class="sign-up-htm">
                            <div class="mb-3">
                                <label for="fname" class="form-label">First Name:</label>
                                <input required type="text" id="fname" name="fname" class="form-control"
                                    placeholder="Enter first name">
                            </div>

                            <div class="mb-3">
                                <label for="lname" class="form-label">Last Name:</label>
                                <input required type="text" id="lname" name="lname" class="form-control"
                                    placeholder="Enter last name">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email1" name="email1" class="form-control"
                                    placeholder="Enter email">
                            </div>

                            <div class="mb-3">
                                <label for="pwd1" class="form-label">Password:</label>
                                <input required type="password" id="pwd1" name="pwd1" class="form-control"
                                    placeholder="Enter password">
                            </div>

                            <div class="mb-3">
                                <label for="pwd_confirm" class="form-label">Confirm Password:</label>
                                <input required type="password" id="pwd_confirm" name="pwd_confirm" class="form-control"
                                    placeholder="Confirm password">
                            </div>

                            <div class="mb-3 form-check">
                                <input required type="checkbox" name="agree" id="agree" class="form-check-input">
                                <label class="form-check-label" for="agree">
                                    Agree to terms and conditions.
                                </label>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
    <?php
    include "inc/footer.inc.php";
    ?>
</body>

</html>