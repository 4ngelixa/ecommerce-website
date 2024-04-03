<?php
        session_start();
        ini_set('display_errors',  1); 
        ini_set('display_startup_errors',  1);
        error_reporting(E_ALL);

        if (empty($_POST["lname"])) {
            $errorMsg = "Last Name is required.<br>";
        } else {
            $lname = sanitize_input($_POST["lname"]);
        }
        $fname = sanitize_input($_POST["fname"]);
        $phone = sanitize_input($_POST["phone"]);
        $country = sanitize_input($_POST["country"]);
        $email = sanitize_input($_SESSION["email"]);

        update_data($email, $fname, $lname, $phone, $country);

        function sanitize_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

        function update_data($email, $fname, $lname, $phone, $country){
            $error_msg = NULL;
            $otp = "";
            $config = parse_ini_file('/var/www/private/db-config.ini');
            $conn = new mysqli(
                $config['servername'],
                $config['username'], 
                $config['password'], 
                $config['dbname']);
            
            if ($conn->connect_error) {
                $errorMsg .= "Connection failed: " . $conn->connect_error;
            } else {
                $query = $conn->prepare("UPDATE member SET fname = ?, lname = ?, phone = ?, country = ? WHERE email = ?");
                // Bind & execute the query statement
                $query->bind_param("sssss", $fname, $lname,$phone,$country,$email);
                if (!$query->execute()) {
                    throw new Exception("Execute failed: (" . $query->errno . ") " . $query->error);
                }else{
                    echo "<h2>Profile Updated!</h2>";
                    echo "<h4>Thank you for updating your profile, " . $fname . " " . $lname . ".</h4>";
                    $_SESSION["fname"] = $fname;
                    $_SESSION["lname"] = $lname;
                    $_SESSION["phone"] = $phone;
                    $_SESSION["country"] = $country;
                    echo "<button class='btn btn-success' type='redirect'onclick=\"location.href='profile.php'\">Return to Profile</button>";
                }
                $query->close();
                $conn->close();
            }
            
        }

        ?>