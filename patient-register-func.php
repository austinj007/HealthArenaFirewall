<?php
    include('config.php');
    include('function-modules.php');
    session_start();

    if (isset($_SESSION["id"])) {
        header('location: index.php');
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
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['med'],
        $_POST['dob'],
        $_POST['gender'],
        $_POST['height'],
        $_POST['weight'],
        $ipAddress
    );

    $uid = mysqli_real_escape_string($con, $_POST['uid']);

    checkAndLogThreats($inputValues, $uid, $pageUrl, $ipAddress, $con);

    $query = "SELECT * FROM patient WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $error_message = "Username already exists! Please choose a different username.";
        $sanitized_error_message = htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8');
        echo "<script>alert('$sanitized_error_message'); window.location.replace('patient-register.php');</script>";
        exit();
    }


    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $pwd = mysqli_real_escape_string($con, $_POST['pwd']);
    $med = mysqli_real_escape_string($con, $_POST['med']);
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $height = mysqli_real_escape_string($con, $_POST['height']);
    $weight = mysqli_real_escape_string($con, $_POST['weight']);

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    $otp = generateOTP();
    $expiryTime = time() + (5 * 60);

    $_SESSION['user_data'] = array(
        'uid' => $uid,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'hashedPwd' => $hashedPwd,
        'med' => $med,
        'dob' => $dob,
        'gender' => $gender,
        'height' => $height,
        'weight' => $weight
    );
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = $expiryTime;

    $to = $email;
    $subject = 'One-Time Password (OTP) for Health Arena Account Creation';
    $message = 'Your Registration OTP is: ' . $otp . ' | Please enter this OTP within 5 minutes to create a new account.';
    $headers = 'From: healtharenaservices@gmail.com';
    mail($to, $subject, $message, $headers);

    header('location: enter-registration-otp.php');
    exit();

?>
