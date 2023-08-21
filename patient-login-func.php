<?php
    include('config.php');
    // include('function-modules.php');
    session_start();

    if (isset($_SESSION["id"])) {
        header('location: patient-profile.php');
        exit();
    }


    include('firewall-check-threats.php');

    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $domain = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    $pageUrl = $protocol . "://" . $domain . $uri;

    $inputValues = array(
        $_POST['uid'],
        $_POST['pwd'],
        $ipAddress
    );
    

    $id = mysqli_real_escape_string($con, $_POST['uid']);
    $pwd = mysqli_real_escape_string($con, $_POST['pwd']);

    $query = "SELECT * FROM patient WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $qry_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($qry_result) == 0) {
        checkAndLogThreats($inputValues, '', $pageUrl, $ipAddress, $con);
        $error_message = "Invalid Login Credentials!";
        $sanitized_error_message = htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8');
        echo "<script>alert('$sanitized_error_message'); window.location.replace('patient-login.php');</script>";
        exit();
    } else {
        checkAndLogThreats($inputValues, $id, $pageUrl, $ipAddress, $con);
        $row = mysqli_fetch_assoc($qry_result);
        $hashedPwd = $row['password'];
        $loginAttempts = $row['login_attempts'];

        if ($row['status']=='Suspended') {
            $error_message = "Your Account had been previously Suspended for suspicious attempts! Please contact us for further assistance!";
            $sanitized_error_message = htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$sanitized_error_message'); window.location.replace('patient-login.php');</script>";
            exit();
        }
        else if ($row['status']=='Banned') {
            $error_message = "Your Account has been banned! Please contact us for further assistance!";
            $sanitized_error_message = htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$sanitized_error_message'); window.location.replace('patient-login.php');</script>";
            exit();
        }
        else if (password_verify($pwd, $hashedPwd)) {
            $otp = generateOTP();
            $expiryTime = time() + (5 * 60);

            // Store OTP and expiry time in session for verification
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = $expiryTime;
            $_SESSION['id_temp'] = $row['id'];
            $_SESSION['type'] = 'patient';

            
            $resetAttemptsQuery = "UPDATE patient SET login_attempts = 0 WHERE id = ?";
            $resetStmt = mysqli_prepare($con, $resetAttemptsQuery);
            mysqli_stmt_bind_param($resetStmt, "s", $id);
            mysqli_stmt_execute($resetStmt);

            session_regenerate_id(true);

            // Send OTP to registered email
            $to = $row['email'];
            $subject = 'One-Time Password (OTP) for Health Arena Login';
            $message = 'Your Login OTP is: ' . $otp . ' | Please enter this OTP within 5 minutes to log in.';
            $headers = 'From: healtharenaservices@gmail.com';
            mail($to, $subject, $message, $headers);

            header('location: enter-login-otp.php');
            exit();
        } else {
            $loginAttempts++;

            if ($loginAttempts > 3) {
                $error_message = "Account Suspended for several suspicious attempts! Please contact us for further assistance!";
                $sanitized_error_message = htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8');
                echo "<script>alert('$sanitized_error_message'); window.location.replace('patient-login.php');</script>";

                $updateStatusQuery = "UPDATE patient SET status = 'Suspended' WHERE id = ?";
                $updateStmt = mysqli_prepare($con, $updateStatusQuery);
                mysqli_stmt_bind_param($updateStmt, "s", $id);
                mysqli_stmt_execute($updateStmt);

                exit();
            } else {
                $updateAttemptsQuery = "UPDATE patient SET login_attempts = ? WHERE id = ?";
                $updateAttemptsStmt = mysqli_prepare($con, $updateAttemptsQuery);
                mysqli_stmt_bind_param($updateAttemptsStmt, "is", $loginAttempts, $id);
                mysqli_stmt_execute($updateAttemptsStmt);

                $error_message = "Invalid Login Credentials! You have ".(3-$loginAttempts)." attempt(s) remaining!";
                $sanitized_error_message = htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8');
                echo "<script>alert('$sanitized_error_message'); window.location.replace('patient-login.php');</script>";
                exit();
            }
        }
    }

    
?>
