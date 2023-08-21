<?php
    include('../config.php');
    session_start();
    if (!isset($_SESSION["id"]))
        header('location: admin-login.php');
    else if ($_SESSION["type"] != 'firewall-admin')
        header('location: ../index.php');
    
    $uid = $_SESSION["id"];
    if (isset($_GET['id']) && isset($_GET['status'])) {
        $patientId = $_GET['id'];
        $status = $_GET['status'];

        $newStatus = ($status == "Banned") ? "Active" : "Banned";
        $query = "UPDATE Patient SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $newStatus, $patientId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $message = ($newStatus == "Active") ? "Patient has been un-banned. Email notification has been sent!" : "Patient has been banned. Email notification has been sent!";
        echo "<script>alert('$message');</script>";

        $query = "SELECT email FROM Patient WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $patientId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $patient_email);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);



        $to = $patient_email;
        $subject = "Health Arena - Patient Profile Status Update";
        $message = "Dear Patient (<em>$patientId</em>),<br>Your account status has been changed to ' $newStatus ' for security reasons.<br>Feel free to reach out to us for further details.<br>You can <a href='http://localhost/HealthArena%20Firewall/#contact'>Contact Us</a> 24/7<br><br>Thank you.<br>Best regards,<br>Health Arena";

        $headers = "From: From: healtharenaservices@gmail.com\r\n";
        $headers .= "Content-type: text/html\r\n";

        mail($to, $subject, $message, $headers);

        echo "<script>alert('$message');</script>";
        echo "<script>window.location.href = 'view-profiles.php';</script>";
        exit();
    } else {
        header("Location: admin-dashboard.php");
        exit();
    }
?>
