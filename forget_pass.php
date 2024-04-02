
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
    ?>
    <main class="container d-flex justify-content-center">
    <div class="login-wrap">
	<div class="login-html">
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign In</label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Sign Up</label>
		<div class="login-form">
        <form action="process_reset.php" method="post">
			<div class="sign-in-htm">
				<div class="group">
                <label for="email" class="form-label">Email:</label>
                <input  maxlength="45" type="email" id="email" name="email" class="form-control" placeholder="Enter email">
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
