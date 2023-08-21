<?php
if (isset($_SESSION["id"])) {
    include('../config.php'); 

    $query = "SELECT value FROM security_config WHERE type='admin_session_time'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $adminSessionLimitSecs = $row['value']*60;

    $adminId = $_SESSION["id"];
    $query = "SELECT TIMESTAMPDIFF(SECOND, login_time, NOW()) AS time_difference, logout_time FROM current_admin_sessions WHERE admin_id = ? AND logout_time IS NULL";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $adminId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $timeDifference, $logoutTime);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // $adminSessionLimitSecs = 18000;

    $minutesLeft = number_format((($adminSessionLimitSecs - $timeDifference) / 60), 2);

    
    if ($timeDifference > $adminSessionLimitSecs) {
        echo "<script>alert('Session Time is over. You have been logged out.'); window.location.replace('logout.php');</script>";
        exit;
    }

    $sessionId = session_id();

    $query = "SELECT logout_time FROM current_admin_sessions WHERE admin_id = ? AND session_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $adminId, $sessionId);
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
