<?php

session_start();
include('check-session.php');
include('config.php');
if (!isset($_SESSION["id"]))
    header('location: patient-login.php');
else if ($_SESSION["type"] != 'patient')
    header('location: index.php');

$uid = $_SESSION["id"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Health Arena</title>
    <meta content="" name="best health service">
    <meta content="" name="healthcare, clinic, best health service">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!--    <link href="assets/css/style.css" rel="stylesheet">-->
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <header id="header">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><img src="assets/img/logo.png" style="width: 100px; margin: 0 auto" alt=""></a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#about">About</a></li>
                    <li><a href="index.php#about">Contact</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="" style="color: #000"><a href="new-health-record.php">New Health Record</a></li>
                    <li class="active" style="color: #000"><a href="manage-health-records.php">Manage Health Records</a></li>
                    <li class="" style="color: #000"><a href="patient-profile.php">Profile</a></li>
                    <li class="" style="color: #000"><a href="logout.php" class="c3">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>


    <div class="container-fluid" style="display: flex; min-height: 90vh;justify-content: center; ">
        <div class='wrapper' style="" id="">
            <section id="appointments" class="appointment section-bg" style="padding: 40px 30px">
                <h1>Your Health Appointments History</h1><br>
                <?php
                    $query = "SELECT * FROM appointments WHERE patient_id = ? AND status = '1' ORDER BY date DESC";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, "s", $uid);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) == 0) {
                        echo "<h2>No appointments found!</h2>";
                    } else {
                        echo '<table class="table-bordered">';
                        echo '<tr><th>Appointment ID</th><th>Date</th><th>Slot</th><th>Doctor</th><th>Your issue</th><th>Lab Report</th><th>Prescription/Treatment Plan</th><th>Operations</th></tr>';
                        while ($row = mysqli_fetch_array($result)) {
                            $opt = '';
                            $opt = '<a href="delete-appointment.php?appoid=' . $row["appo_id"] . '" onclick="return confirm(\'Are you sure you want to delete this appointment?\')" style="color: red">Delete Record</a>';

                            $disp = '';
                            if ($row['latest_report'] == null) {
                                $disp = 'style="display: none"';
                            }

                            echo '<tr><td>' . htmlspecialchars($row["appo_id"]) . '</td><td>' . htmlspecialchars($row["date"]) . '</td><td>' . htmlspecialchars($row["slot"]) . '</td><td>' . htmlspecialchars($row["doc_id"]) . '</td><td>' . 
                            htmlspecialchars($row["patient_desc"]) . '</td><td><a ' . $disp . ' target="_blank" href="' . htmlspecialchars($row["latest_report"]) . 
                            '">Download Report</a></td><td>' . htmlspecialchars($row["prescription"]) . '</td><td>' . $opt . '</td></tr>';
                        }
                        echo '</table>';
                    }
                ?>

                <div class="ad"></div>
            </section>


        </div>
    </div>

    <footer id="footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>Health Arena</span></strong>. All Rights Reserved
            </div>
        </div>
    </footer>


</body>

</html>