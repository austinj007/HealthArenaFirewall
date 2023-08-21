<?php 
    include('config.php');
    session_start();
    if(!isset($_SESSION["id"]))
        header('location: index.php');
    else{

        if($_SESSION["type"]=='patient'){
            // Update the relevant session with the logout_time
            $sessionId = session_id();
            $logoutTime = 'CURRENT_TIMESTAMP';

            $updateQuery = "UPDATE current_sessions SET logout_time = $logoutTime WHERE session_id = ?";
            $updateStmt = mysqli_prepare($con, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "s", $sessionId);
            mysqli_stmt_execute($updateStmt);
        }

        unset($_SESSION["id"]);
        unset($_SESSION["type"]);
        header('location: index.php');
    }
?>