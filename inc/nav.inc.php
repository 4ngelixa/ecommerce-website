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
        <!-- Nav links -->
        <?php 
        if ($_SESSION['admin'] == "admin") {
            echo '
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/profile.php"><i class="fa-solid fa-lock"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout.php"><i class="fa-solid fa-sign-hanging"></i> Sign Out</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/products.php"><i class="fa-solid fa-box"></i> Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about_us.php"><i class="fa-regular fa-address-card"></i> About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/venue.php"><i class="fa-solid fa-sign-hanging"></i> Venue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin_panel.php"><i class="fa-solid fa-cog"></i> Admin Panel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shopping_cart.php"><i class="fas fa-shopping-cart"></i> Cart <span><?php echo $num_items_in_cart; ?></span></a>
                </li>
            </ul>
            ';
        } else if(isset($_SESSION['fname'])) {
            echo '
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/profile.php"><i class="fa-solid fa-lock"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout.php"><i class="fa-solid fa-sign-hanging"></i> Sign Out</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/products.php"><i class="fa-solid fa-box"></i> Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about_us.php"><i class="fa-regular fa-address-card"></i> About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/venue.php"><i class="fa-solid fa-sign-hanging"></i> Venue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shopping_cart.php"><i class="fas fa-shopping-cart"></i> Cart <span><?php echo $num_items_in_cart; ?></span></a>
                </li>
            </ul>
            ';
        } else {
            echo '
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/authentication.php"><i class="fa-solid fa-lock"></i>Login/SignUp</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/products.php"><i class="fa-solid fa-box"></i> Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about_us.php"><i class="fa-regular fa-address-card"></i> About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/venue.php"><i class="fa-solid fa-sign-hanging"></i> Venue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shopping_cart.php"><i class="fas fa-shopping-cart"></i> Cart <span><?php echo $num_items_in_cart; ?></span></a>
                </li>
            </ul>
            ';
        }
        ?>
    </div>

    <!-- Burger icon for smaller screens -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
