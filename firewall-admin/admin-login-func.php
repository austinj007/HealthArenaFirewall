<?php
    include('../config.php');
    // include('../function-modules.php');
    session_start();

    if (isset($_SESSION["id"]) && $_SESSION["type"]=="firewall-admin") {
        header('location: admin-dashboard.php');
        exit();
    }

    include('../firewall-check-threats.php');

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
    $pwd = sha1(mysqli_real_escape_string($con, $_POST['pwd']));

    $query = "SELECT * FROM admin WHERE admin_id = ? AND designation='Firewall Administrator'";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $qry_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($qry_result) == 0) {
        checkAndLogThreats($inputValues, '', $pageUrl, $ipAddress, $con);
        $error_message = "Invalid Login Credentials !!";
        $sanitized_error_message = htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8');
        echo "<script>alert('$sanitized_error_message'); window.location.replace('admin-login.php');</script>";
        exit();
    } else {
        checkAndLogThreats($inputValues, $id, $pageUrl, $ipAddress, $con);
        $row = mysqli_fetch_assoc($qry_result);
        $hashedPwd = $row['password'];
        if ($pwd==$hashedPwd) {
            $otp = generateOTP();
            $expiryTime = time() + (5 * 60);

            // Store OTP and expiry time in session for verification
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = $expiryTime;
            $_SESSION['id_temp'] = $row['admin_id'];
            $_SESSION['type'] = 'firewall-admin';

            session_regenerate_id(true);

            // Send OTP to registered email
            $to = $row['email'];
            $subject = 'One-Time Password (OTP) for Health Arena | ADMIN LOGIN';
            $message = 'Your Admin-login OTP is: ' . $otp . ' | Please enter this OTP within 5 minutes to log in.';
            $headers = 'From: healtharenaservices@gmail.com';
            mail($to, $subject, $message, $headers);

            header('location: enter-login-otp.php');
            exit();
        } else {
            $error_message = "Invalid Login Credentials !";
            $sanitized_error_message = htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$sanitized_error_message'); window.location.replace('admin-login.php');</script>";
            exit();
        }
    }

    
?>
