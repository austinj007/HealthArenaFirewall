<?php
    include('config.php');


    include('firewall-check-threats.php');

    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    $inputValues = array(
        $_GET['name'],
        $_GET['email'],
        $_GET['msg'],
        $ipAddress
    );

    $uid='';
    session_start();
    if (isset($_SESSION["id"])) {
        $uid=$_SESSION["id"];
    }
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $domain = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    $pageUrl = $protocol . "://" . $domain . $uri;

    checkAndLogThreats($inputValues, $uid, $pageUrl, $ipAddress, $con);

    $name = $_GET['name'];
    $email = $_GET['email'];
    $message = $_GET['msg'];

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $sql = "INSERT INTO contact (name, email, message) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            // Send email
            $to = 'healtharenaservices@gmail.com';
            $subject = 'New Contact Form Submission';
            $emailMessage = "Name: $name\nEmail: $email\nMessage: $message";
            $headers = 'From: '.$email;

            if (mail($to, $subject, $emailMessage, $headers)) {
                echo "<script>alert('Thank you for getting in touch!! We will get back to you shortly');window.location.replace('index.php')</script>";
            } else {
                echo "<script>alert('Something went wrong with sending the email. Please try again later');window.location.replace('index.php')</script>";
            }
        } else {
            echo "<script>alert('Something went wrong. Try again later');window.location.replace('index.php')</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Something went wrong. Try again later');window.location.replace('index.php')</script>";
    }

    $con->close();
?>
