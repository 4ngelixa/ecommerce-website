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
        $email = $errorMsg = $fname = $lname = $pwd = "";
        $success = true;

        //Email
        if (empty($_POST["email1"])) {
            $errorMsg .= "Email is required.<br>";
            $success = false;
        } else {
            $email = sanitize_input($_POST["email1"]);

            // Additional check to make sure e-mail address is well-formed.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMsg .= "Invalid email format.<br> ";
                $success = false;
            }
        }

        if (empty($_POST["fname"])) {
            $errorMsg .= "First Name is required.<br>";
        } else {
            $fname = sanitize_input($_POST["fname"]);
        }

        //Last Name
        if (empty($_POST["lname"])) {
            $errorMsg .= "Last Name is required.<br>";
        } else {
            $lname = sanitize_input($_POST["lname"]);
        }

        //Password
        if (empty($_POST["pwd1"])) {
            $errorMsg .= "Passwords are empty. They should not be empty<br>";
            $success = false;
        } else {
            if (strlen($_POST["pwd1"]) < 8) {
                $errorMsg .= "Password must be at least 8 characters long.<br>";
                $success = false;
            } elseif ($_POST["pwd1"] != $_POST["pwd_confirm"]) {
                $errorMsg .= "Passwords are not the same.<br>";
                $success = false;
            } else {
                $pwd = sanitize_input($_POST["pwd1"]);
                $pwd = password_hash($_POST["pwd1"], PASSWORD_DEFAULT);
            }
        }
        

        saveMemberToDB();

        //ERROR MESSAGE LOGIC
        if ($success) {
            echo "<h2>Registration successful!</h2>";
            echo "<h4>Thank you for signing up, " . $fname . " " . $lname . ".</h4>";
            echo "<button class='btn btn-success' type='redirect'onclick=\"location.href='authentication.php'\">Log-in</button>";
            // check if is saved.
            if ($success) {
                echo "<p>" . $errorMsg . "</p>"; 
            } else {
                echo "<p>" . $errorMsg . "</p>";
            }

        } else {
            echo "<h4>The following input errors were detected:</h4>";
            echo "<p>" . $errorMsg . "</p>";
            echo "<button class='btn btn-danger' type='redirect'onclick=\"location.href='authentication.php'\">Return to Sign Up</button>";
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
        * Helper function to write the member data to the database.
        */
        function saveMemberToDB()
        {
            try {
                global $fname, $lname, $email, $pwd, $errorMsg, $success;

                // Enable exception handling for mysqli
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                // Create database connection.
                $config = parse_ini_file('/var/www/private/db-config.ini');
                if (!$config) {
                    throw new Exception("Failed to read database config file.");
                }

                $conn = new mysqli(
                    $config['servername'],
                    $config['username'],
                    $config['password'],
                    $config['dbname']
                );

                // Prepare the statement
                $stmt = $conn->prepare("INSERT INTO member (fname, lname, email, user_password) VALUES (?, ?, ?, ?)");

                // Bind & execute the query statement
                $stmt->bind_param("ssss", $fname, $lname, $email, $pwd);

                if (!$stmt->execute()) {
                    throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                }

                $stmt->close();
                $conn->close();

            } catch (Exception $e) {
                $errorMsg = "Error: " . $e->getMessage();
                $success = false;
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