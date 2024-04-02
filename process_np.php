<?php


    if (empty($_POST["pwd"])) {
            $errorMsg .= "Passwords are empty. They should not be empty<br>";
            $success = false;
        } else {
            if (strlen($_POST["pwd"]) < 8) {
                $errorMsg .= "Password must be at least 8 characters long.<br>";
                $success = false;
            } elseif ($_POST["pwd"] != $_POST["pwd_confirm"]) {
                $errorMsg .= "Passwords are not the same.<br>";
                $success = false;
            } else {
                $pwd = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
            }
        }
        $otp = $_GET['OTP'];
        reset_password($otp, $pwd);
    
    function reset_password($otp, $pwd){

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

                $otp = bin2hex(openssl_random_pseudo_bytes(16));
                $query = $conn->prepare("UPDATE member SET password=? WHERE otp=?");
                $query->bind_param("ss", $pwd, $otp);
                if($query->execute()) {
                    $query->close();
                    echo "<h2>Password reset successful!</h2>";
                    echo "<button class='btn btn-success' type='redirect'onclick=\"location.href='\\index.php'\">Return to Homepage</button>";
                }

    }


 ?>
    