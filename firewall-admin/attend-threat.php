<?php
include('../config.php');

if (isset($_GET['id'])) {
    $threatId = $_GET['id'];
    $newStatus = 1;

    $updateQuery = "UPDATE threats SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ii", $newStatus, $threatId);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        header('Location: security-assistance.php');
        exit();
    } else {
        echo "Error updating threat status: " . mysqli_error($con);
    }
} else {
    echo "Invalid parameters";
}
?>
