<?php

function sendEmail($to, $subject, $message) {
    $headers = "From: healtharenaservices@gmail.com";
    mail($to, $subject, $message, $headers);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["contactId"]) && isset($_POST["subject"]) && isset($_POST["message"])) {
    $contactId = $_POST["contactId"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    include("../config.php");
    $query = "SELECT email FROM Contact WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $contactId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $to);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $updateQuery = "UPDATE Contact SET reply_status = 1 WHERE id = ?";
    $updateStmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, "i", $contactId);
    mysqli_stmt_execute($updateStmt);
    mysqli_stmt_close($updateStmt);

    mysqli_close($con);

    sendEmail($to, $subject, $message);

    echo "<script>window.location.href = 'patient-contacts.php';</script>";
    exit();
}
?>
