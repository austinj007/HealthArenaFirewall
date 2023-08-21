<?php
    include('config.php');
    session_start();

    $appoid = $_GET["appoid"];

    $query = "UPDATE appointments SET status=0 WHERE appo_id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $appoid);
    $qry_result = mysqli_stmt_execute($stmt);

    if ($qry_result) {
        if ($_SESSION["type"] == 'patient') {
            echo "<script>alert('Health Record has been Removed'); window.location.replace('manage-health-records.php');</script>";
        } else {
            echo "<script>alert('Health Record has been Disabled from Patient'); window.location.replace('admin-dashboard.php');</script>";
        }
    } else {
        if ($_SESSION["type"] == 'patient') {
            echo "<script>alert('Could not remove'); window.location.replace('manage-health-records.php');</script>";
        } else {
            echo "<script>alert('Could not Disable'); window.location.replace('admin-dashboard.php');</script>";
        }
    }
?>
