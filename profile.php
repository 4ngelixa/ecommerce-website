<?php session_start(); ?>
<head>
    <?php
        include "inc/head.inc.php";
    ?>
    <link rel="stylesheet" href="css/Profile.css">
</head>
<body>
    <?php 
        include "inc/nav.inc.php";
    ?>
    
    <main class="container">
        <h1>Profile Page</h1>
        <?php echo "<h4>Welcome, " . $_SESSION["fname"] . "!</h4>"; ?>

        <form action="process_profile.php" method="update">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input required maxlength="45" type="text" id="username" name="username" class="form-control" placeholder="Enter username" value="<?php echo $_SESSION['fname']; ?>" required>
        </div>

        <div>
            <label for="email" class="form-label">Email:</label>
            <input required maxlength="45" type="email" id="email" name="email" class="form-control" placeholder="Enter email" value="<?php echo $_SESSION['email']; ?>" required>
        </div>
        <div>
            <label for="pwd" class="form-label">Password:</label>
            <input required type="password" id="pwd" name="pwd" class="form-control" placeholder="Enter password" required>
        </div>

        <div>
            <label for="pwd_confirm" class="form-label">Confirm Password:</label>
            <input required type="password" id="pwd_confirm" name="pwd_confirm" class="form-control" placeholder="Confirm password" required>
        </div>

        <div class="mb-3">
            <button type="submit" class = "btn_submit">Update Profile</button>
        </div>
    </main>

    <?php
    include "inc/footer.inc.php";
    ?>
</body>