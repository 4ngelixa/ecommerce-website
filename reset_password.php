
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "inc/head.inc.php";

    ?>
</head>
<body>
    <?php 
        include "inc/nav.inc.php";
        if(isset($_GET['OTP'])){
            $_SESSION["otp"] = $_GET['OTP'];
            
        }
        else{
            $_SESSION["otp"] = NULL;
        }
    ?>
    <main class="container d-flex justify-content-center">
    <div class="login-wrap">
	<div class="login-html">
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab"></label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab"></label>
		<div class="login-form">
        <form action="process_np.php" method="post">
			<div class="sign-in-htm">
            <div class="mb-3">
                    <label for="pwd" class="form-label">Password:</label>
                    <input required type="password" id="pwd" name="pwd" class="form-control" placeholder="Enter password">
                </div>

                <div class="mb-3">
                    <label for="pwd_confirm" class="form-label">Confirm Password:</label>
                    <input required type="password" id="pwd_confirm" name="pwd_confirm" class="form-control" placeholder="Confirm password">
                </div>
                <br>
				<div class="group">
					<input type="submit" class="button" value="Submit">
				</div>
				<div class="hr"></div>
				<div class="foot-lnk">
					<a href="authentication.php">Login</a>
				</div>
			</div>
	</div>
    </div>
        
    </main>
        
    <?php
    include "inc/footer.inc.php";
    ?>
</body>
</html>
