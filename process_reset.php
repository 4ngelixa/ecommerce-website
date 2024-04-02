        <?php
            ini_set('display_errors',  1); 
            ini_set('display_startup_errors',  1);
            error_reporting(E_ALL);
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\SMTP;
            use PHPMailer\PHPMailer\Exception;
            require 'vendor/autoload.php';

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
            forgot_password($email);

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

        function sanitize_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

        function forgot_password($email){
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
            } 
            else {
                //create OTP
                $otp = bin2hex(openssl_random_pseudo_bytes(16));
                $query = $conn->prepare("UPDATE member SET otp=? WHERE email=?");
                $query->bind_param("ss", $otp, $email);
                if($query->execute()) {
                    $query->close();
                    $mail = new PHPMailer;
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->isSMTP();
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Port = '587';
                    $mail->isHTML(True);
                    $mail->Username = 'blingblinginf1005@gmail.com';
                    $mail->Password = 'mvoj oran yzay eotw';
                    $mail->setFrom('blingblinginf1005@gmail.com');
                    $mail->Subject = 'Reset password instructions';
                    $mail->Body = "
                                    <html>
                                        <body>
                                            <p>Forgot your password?</p>
                                            <p>Please <a href='35.212.131.157/reset_password.php?OTP=$otp'>click here</a> to Reset your account.</p>
                                            <p>Best regards,<br>BlingBling Team</p>
                                        </body>
                                    </html>";

                    $mail->addAddress($email);
                    if(!$mail -> send()){
                        $errorMsg .= "Failed to send reset password email.";
                        echo "<script type='text/javascript'>alert('$errorMsg');</script>";
                    }else{
                        echo "<script type='text/javascript'>alert('Reset Password email sent.');</script>";
                    }
                }
                else{
                    $query->close();
                    $error_msg .= "Email has not been registered.";
                    echo "<script type='text/javascript'>alert('$errorMsg');</script>";
                }
                $conn->close();
                header("Location: authentication.php");
            }
            
        }

        ?>