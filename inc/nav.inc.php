<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 ?>

<nav id="Navbar" class="navbar navbar-expand-sm navbar-dark sticky-top">
    <!-- Logo -->
    <a class="navbar-brand" href="/index.php">
        <img class="rounded-logo" src="images/logo-no-background.png" alt="bling bling">
    </a>

    <!-- Navbar items -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        
    </div>

    <!-- Menu icon moved to the right -->
    <span id="menu-icon">
        <i class="fa-solid fa-bars"></i>
    </span>

    <?php 
            if (isset($_SESSION['lname'])){
                echo'
                <!-- Side Nav Menu -->
                <div id="mySidenav" class="sidenav">
                    <a href="javascript:void(0)" class="closebtn" id="close-btn">&times;</a>
                    <a href="/profile.php"> <i class="fa-solid fa-lock"></i> Profile</a>
                    <a href="/logout.php"><i class="fa-solid fa-sign-hanging"> </i>Sign Out</a>
                    <a href="/about_us.php"><i class="fa-regular fa-address-card"></i> About Us</a>
                </div>
                ';} 
                else{
                    echo'
                    <!-- Side Nav Menu -->
                    <div id="mySidenav" class="sidenav">
                        <a href="javascript:void(0)" class="closebtn" id="close-btn">&times;</a>
                        <a href="/authentication.php"> <i class="fa-solid fa-lock"></i> Login</a>
                        <a href="/authentication.php"><i class="fa-solid fa-sign-hanging"> </i>Sign Up</a>
                        <a href="/about_us.php"><i class="fa-regular fa-address-card"></i> About Us</a>
                    </div>
                    ';
                }
                ?>

</nav>
