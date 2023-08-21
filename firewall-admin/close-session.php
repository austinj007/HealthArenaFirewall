<?php
include('../config.php');
session_start();

if (!isset($_SESSION["id"])) {
    header('location: admin-login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    // Fetch the session details to check if it's an admin session or patient session
    $query = "SELECT * FROM current_sessions WHERE session_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $session = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($session) {
        $query = "UPDATE current_sessions SET logout_time = NOW() WHERE session_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $session_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: session-monitoring.php#patient-sessions");
        exit();
    } else {
        $query = "UPDATE current_admin_sessions SET logout_time = NOW() WHERE session_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $session_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: session-monitoring.php#admin-sessions");
        exit();
    }
} else {
    header("Location: session-monitoring.php");
    exit();
}
?>
