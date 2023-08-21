<?php 
    include('../config.php');
    session_start();
    if(!isset($_SESSION["id"]))
        header('location: admin-login.php');
    else{

        if($_SESSION["type"]=='firewall-admin'){
            $sessionId = session_id();
            $logoutTime = 'CURRENT_TIMESTAMP';

            $updateQuery = "UPDATE current_admin_sessions SET logout_time = $logoutTime WHERE session_id = ?";
            $updateStmt = mysqli_prepare($con, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "s", $sessionId);
            mysqli_stmt_execute($updateStmt);
        }

        unset($_SESSION["id"]);
        unset($_SESSION["type"]);
        header('location: admin-login.php');
    }
?>