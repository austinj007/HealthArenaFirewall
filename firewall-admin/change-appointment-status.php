<?php
include('../config.php');
session_start();

if (!isset($_SESSION["id"]) || $_SESSION["type"] !== 'firewall-admin') {
    header('location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status_change']) && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];
    $patient_id = $_POST['patient_id'];

    $query = "SELECT status FROM Appointments WHERE appo_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $appointment_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $current_status);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $new_status = ($current_status == 1) ? 0 : 1;

    $query = "UPDATE Appointments SET status = ? WHERE appo_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "is", $new_status, $appointment_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);


    $query = "SELECT email FROM Patient WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $patient_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $patient_email);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    $to = $patient_email;
    $subject = "Health Arena - Appointment Status Update";
    $message = "Dear Patient (<em>$patient_id</em>),<br>Your appointment health records with ID: $appointment_id has been " . ($new_status == 1 ? "Re-activated" : "Removed due to security concerns") . ".<br>Thank you.<br>Best regards,<br>Health Arena";

    $headers = "From: From: healtharenaservices@gmail.com\r\n";
    $headers .= "Content-type: text/html\r\n";

    mail($to, $subject, $message, $headers);


    echo "<script>alert('Appointment Health Records status updated successfully! Patient has been notified via email');</script>";
    echo "<script>window.location.href = 'patient-health-records.php?id=$patient_id';</script>";
    exit();
} else {
    header("Location: admin-dashboard.php");
    exit();
}
?>
