<?php
if (isset($_SESSION["id"])) {
    include('config.php'); 

    $query = "SELECT value FROM security_config WHERE type='patient_session_time'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $patientSessionLimitSecs = $row['value']*60;

    $patientId = $_SESSION["id"];
    $query = "SELECT TIMESTAMPDIFF(SECOND, login_time, NOW()) AS time_difference FROM current_sessions WHERE patient_id = ? AND logout_time IS NULL";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $patientId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $timeDifference);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);


    $minutesLeft = number_format((($patientSessionLimitSecs - $timeDifference) / 60), 2);
    if($minutesLeft>0)
        echo "<script>alert('You have " . $minutesLeft . " minutes left in this session');</script>";   

    
    if ($timeDifference > $patientSessionLimitSecs) {
        echo "<script>alert('You have been logged out due to session expiry.'); window.location.replace('logout.php');</script>";
        exit;
    }

    $sessionId = session_id();

    $query = "SELECT logout_time FROM current_sessions WHERE patient_id = ? AND session_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $patientId, $sessionId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $currentLogoutTime);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($currentLogoutTime !== null) {
        echo "<script>alert('You have been logged out due to security reasons !!'); window.location.replace('logout.php');</script>";
        exit();
    }
}
?>
