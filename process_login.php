<html>


<head>
    <?php
    include "inc/head.inc.php";
    ?>
</head>

<body>

    <!--Nav PHP Header-->
    <?php
    include "inc/nav.inc.php";
    ?>

    <main class="container">

        <hr>
        <?php

        //SANTISATION
        $email = "";
        $pwd = "";
        $success = true;

        //Email
        if (empty($_POST["email"])) {
            $errorMsg .= "Email is required.<br>";
            $success = false;
        } else {
            $email = sanitize_input($_POST["email"]);

            // Additional check to make sure e-mail address is well-formed.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMsg .= "Invalid email format.<br> ";
                $success = false;
            }
        }

        //Password
        if (empty($_POST["pwd"])) {
            $errorMsg .= "Passwords are empty. They should not be empty<br>";
            $success = false;
        } else {
            $pwd = sanitize_input($_POST["pwd"]);
            $pwd = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
        }

        authenticateUser();

        //ERROR MESSAGE LOGIC
        if ($success) {
            echo "<h2>Login successful!</h2>";
            echo "<h4>Welcome back, " . $fname . " " . $lname . ".</h4>";
            echo "<button class='btn btn-success' type='redirect'onclick=\"location.href='\\index.php'\">Return to Homepage</button>";
            
            // check if is saved.
            if ($success) {
                echo "<p>" . $errorMsg . "</p>";
            } else {
                echo "<p>" . $errorMsg . "</p>";
            }

        } else {
            echo "<h2>Oops!</h2>";
            echo "<h4>The following input errors were detected:</h4>";
            echo "<p> $errorMsg </p>";
            echo "<button class='btn btn-warning' type='redirect'onclick=\"location.href='\\authentication.php'\">Return to Login</button>";
        }



        /*
        * Helper function that checks input for malicious or unwanted content.
        */
        function sanitize_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        /*
        * Helper function to authenticate the login.
        */
        function authenticateUser()
        {
            global $fname, $lname, $email, $pwd_hashed, $errorMsg, $success;
            $otp = NULL;

            // Create database connection.
            $config = parse_ini_file('/var/www/private/db-config.ini');
            if (!$config) {
                $errorMsg = "Failed to read database config file.";
                $success = false;
            } else {
                $conn = new mysqli(
                    $config['servername'],
                    $config['username'],
                    $config['password'],
                    $config['dbname']
                );
                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    // Prepare the statement:
                    $stmt = $conn->prepare("SELECT * FROM inf1005_bling_bling.member WHERE email=?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        // Note that email field is unique, so should only have
                        // one row in the result set.
                        $row = $result->fetch_assoc();
                        $fname = $row["fname"];
                        $lname = $row["lname"];
                        $phone = $row["phone"];
                        $country = $row["country"];
                        $pwd_hashed = $row["password"];
                        $admin = $row["admin"];

                        // Check if the password matches:
                        if (!password_verify($pwd, $pwd_hashed)) {
                            // Don't be too specific with the error message - hackers don't
                            // need to know which one they got right or wrong. :)
                            $errorMsg = "Email not found or password doesn't match...";
                            $success = false;
                        }
                        else if(password_verify($pwd, $pwd_hashed)) {
                            session_start();
                            session_regenerate_id(true);
                            $_SESSION["fname"] = $fname;
                            $_SESSION["lname"] = $lname;
                            $_SESSION["email"] = $email;
                            $_SESSION["phone"] = $phone;
                            $_SESSION["country"] = $country;
                            $_SESSION["otp"] = $otp;
                            $_SESSION["admin"] = $admin;
                        }
                    } else {
                        $errorMsg = "Email not found or password doesn't match...";
                        $success = false;
                    }
                    $stmt->close();
                }
                $conn->close();
            }
        }


       
        ?>
        <hr>
    </main>
</body>


<?php
include "inc/footer.inc.php";
?>

</html>