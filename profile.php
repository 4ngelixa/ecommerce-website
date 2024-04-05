<!DOCTYPE html>
<html lang="en">

<?php session_start(); ?>

<head>
    <title>Bling Bling</title>
    <style>
        .text {
            font-size : 16px;
            color : rgba(7, 7, 7);
            --bs-text-opacity: 1;
        }
    </style>
    <?php
        include "inc/head.inc.php";
    ?>
    <link rel="stylesheet" href="css/Profile.css">
</head>

<body>
    <?php 
        include "inc/nav.inc.php";
    ?>
    <main>
    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5"
                        width="150" alt="profile"
                        src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
                    <span class="font-weight-bold"><?php echo $_SESSION['fname']; ?></span>
                    <span class="text" id="em"><?php echo $_SESSION['email']; ?></span>
                </div>
            </div>
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="text-right">Profile Settings</h1>
                    </div>
                    <form action="process_profile.php" method="post">
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">fname</label><input type="text"
                                    class="form-control" id="fname" name="fname" placeholder= "fname"
                                    value="<?php echo $_SESSION['fname']; ?>">
                            </div>
                            <div class="col-md-6"><label class="labels">lname</label>
                                <input type="text" class="form-control" id="lname" name="lname" placeholder= "lname"
                                    value="<?php echo $_SESSION['lname']; ?>">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text"
                                    class="form-control" id="phone" name="phone" placeholder= "enter phone number"
                                    value="<?php echo $_SESSION['phone']; ?>">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"><label class="labels">Country</label><input type="text"
                                    class="form-control" id="country" name="country" placeholder= "country"
                                    value="<?php echo $_SESSION['country']; ?>">
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <input type="submit" class="button" value="Save Profile">
                        </div>
                    </form>
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