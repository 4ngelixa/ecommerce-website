

<?php
    session_start();
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
        $otp = $_SESSION["otp"];
        reset_password($otp, $pwd);
    
        function reset_password($otp, $pwd){
            $error_msg = NULL;
            $config = parse_ini_file('/var/www/private/db-config.ini');
            $conn = new mysqli(
                $config['servername'],
                $config['username'], 
                $config['password'], 
                $config['dbname']);
            
            if (is_null($otp)){
                $error_msg .= $error_msg."Something went wrong";
                echo "<script type='text/javascript'>alert('$error_msg');</script>";
            }
            else{
                if ($conn->connect_error) {
                    $error_msg .= "Connection failed: " . $conn->connect_error;
                } 
                else {
                    $query = $conn->prepare("UPDATE member SET password=? WHERE otp=?");
                    $query->bind_param("ss", $pwd, $otp);
                    if($query->execute()) {
                        $query->close();
                        echo "<script type='text/javascript'>alert('Password has been changed.');</script>";
                        $query_2 = $conn->prepare("UPDATE member SET otp=NULL WHERE otp=?");
                        $query_2->bind_param("s", $otp);
                        if($query_2->execute()) {
                            $query_2->close();
                        }
                    }
                    else{
                        $query->close();
                        $error_msg .= "Failed to change password.";
                        echo "<script type='text/javascript'>alert('$error_msg');</script>";
                    }
                    $conn->close();
                    header("Location: authentication.php");
                }
            }  
        }


 ?>
    