<?php
    include('config.php');
    session_start();

    if(!isset($_SESSION["id"])) {
        header('location: patient-login.php');
        exit();
    } elseif ($_SESSION["type"] != 'patient') {
        header('location: index.php');
        exit();
    }

    $dt = mysqli_real_escape_string($con,$_GET['searchdt']);
    $dept = mysqli_real_escape_string($con,$_GET['dept']);
    $slot = mysqli_real_escape_string($con,$_GET['slot']);


    $query = "SELECT * FROM doctor WHERE field = ? AND id NOT IN (SELECT doc_id FROM appointments WHERE date = ? AND slot = ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sss", $dept, $dt, $slot);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 0) {
        echo "<label for=''>Select a Doctor</label><br><p>Sorry! No doctors were available on this date-slot!</p>";
    } else {
        echo '<label for="">Select a Doctor</label><br><select name="doctor" id="doctor" class="form-control" required>';
        while($row = mysqli_fetch_array($result)) {
            echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
        }
        echo '</select>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
?>
