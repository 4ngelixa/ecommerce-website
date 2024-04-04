<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Calculate total quantity of items in the cart
$num_items_in_cart = 0;
if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $quantity) {
        $num_items_in_cart += $quantity;
    }
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
    if ($_SESSION['admin'] == "admin") {
        echo '
        <!-- Side Nav Menu -->
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" id="close-btn">&times;</a>
            <a href="/profile.php"> <i class="fa-solid fa-lock"></i> Profile</a>
            <a href="/logout.php"><i class="fa-solid fa-sign-hanging"> </i> Sign Out</a>
            <a href="/products.php"><i class="fa-solid fa-box"></i> Products</a>
            <a href="/about_us.php"><i class="fa-regular fa-address-card"></i> About Us</a>
            <a href="/venue.php"><i class="fa-regular fa-address-card"></i> Venue</a>
            <a href="/admin_panel.php"><i class="fa-solid fa-cog"></i> Admin Panel</a>
            <a href="/shopping_cart.php"><i class="fas fa-shopping-cart"></i> Cart <span>'.$num_items_in_cart.'</span></a>

        </div>
        ';
    } else if(isset($_SESSION['fname'])) {
        echo '
        <!-- Side Nav Menu -->
        <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" id="close-btn">&times;</a>
        <a href="/profile.php"> <i class="fa-solid fa-lock"></i> Profile</a>
        <a href="/logout.php"><i class="fa-solid fa-sign-hanging"> </i> Sign Out</a>
        <a href="/products.php"><i class="fa-solid fa-box"></i> Products</a>
        <a href="/about_us.php"><i class="fa-regular fa-address-card"></i> About Us</a>
        <a href="/venue.php"><i class="fa-regular fa-address-card"></i> Venue</a>
        <a href="/shopping_cart.php"><i class="fas fa-shopping-cart"></i> Cart <span>'.$num_items_in_cart.'</span></a>
        </div>
        ';
    } else {
        echo '
        <!-- Side Nav Menu -->
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" id="close-btn">&times;</a>
            <a href="/authentication.php"> <i class="fa-solid fa-lock"></i> Login</a>
            <a href="/authentication.php"><i class="fa-solid fa-sign-hanging"> </i> Sign Up</a>
            <a href="/products.php"><i class="fa-solid fa-box"></i> Products</a>
            <a href="/about_us.php"><i class="fa-regular fa-address-card"></i> About Us</a>
            <a href="/venue.php"><i class="fa-regular fa-address-card"></i> Venue</a>
            <a href="/shopping_cart.php"><i class="fas fa-shopping-cart"></i> Cart <span>'.$num_items_in_cart.'</span></a>
        </div>
        ';
    }
    ?>
</nav>
