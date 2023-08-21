<?php

session_start();
include('check-session.php');
include('../config.php');
if (!isset($_SESSION["id"]))
    header('location: admin-login.php');
else if ($_SESSION["type"] != 'firewall-admin')
    header('location: ../index.php');

$uid = $_SESSION["id"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Health Arena - Firewall Administrator</title>
    <meta content="" name="best health service">
    <meta content="" name="healthcare, clinic, best health service">
    <link href="../assets/img/admin-logo.png" rel="icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!--    <link href="assets/css/style.css" rel="stylesheet">-->
    <link rel="stylesheet" href="../styles/style.css">

</head>

<body class="admin-dashboard-body">
    <header id="header">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><img src="../assets/img/logo.png" style="width: 100px; margin: 0 auto" alt=""></a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class=""><a href="session-monitoring.php">Sessions Monitoring</a></li>
                    <li class=""><a href="security-assistance.php">Security Assistance</a></li>
                    <li class=""><a href="view-profiles.php">Patient Profiles Management</a></li>
                    <li class=""><a href="patient-contacts.php">Patient Enquiries</a></li>
                    <li class=""><a href="manage-configurations.php">Security Configurations</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="" style="color: #000"><a href="admin-profile.php">Profile</a></li>
                    <li class="" style="color: #000"><a href="logout.php" class="c3">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>


    <?php

    // Function to fetch count from database
    function getCountFromTable($table, $column, $whereCondition = "") {
        global $con;
        $query = "SELECT COUNT($column) AS count FROM $table $whereCondition";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }

    // Fetch counts from respective tables
    $patientCount = getCountFromTable('Patient', 'id');
    $activeSessionsCount = getCountFromTable('current_sessions', 'session_id', 'WHERE logout_time IS NULL')+getCountFromTable('current_admin_sessions', 'session_id', 'WHERE logout_time IS NULL');
    $threatsCount = getCountFromTable('threats', 'id', 'WHERE status = 0');
    $enquiriesCount = getCountFromTable('contact', 'id');

?>

    <div class="container-fluid" style="display: flex; align-items: center; justify-content: center; min-height: 90vh; ">
        <div class="row" style="width: 100%;">
            <div class="col-12">
                <h1 style="text-align: center;">Firewall Admin Dashboard</h1><br><br>
            </div>
            <div class="col-md-4">
                <div class="dashboard-box bg-primary">
                    <h3 class="dashboard-heading">Patient Profiles</h3>
                    <p class="dashboard-number"><?php echo $patientCount; ?></p>
                    <a href="view-profiles.php" class="btn btn-danger">View All Profiles</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-box bg-success">
                    <h3 class="dashboard-heading">Current Login Sessions</h3>
                    <p class="dashboard-number"><?php echo $activeSessionsCount; ?></p>
                    <a href="session-monitoring.php" class="btn btn-danger">View Active Sessions</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-box bg-danger">
                    <h3 class="dashboard-heading">Security Threats</h3>
                    <p class="dashboard-number"><?php echo $threatsCount; ?></p>
                    <a href="security-assistance.php" class="btn btn-danger">View Threat Details</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-box bg-info">
                    <h3 class="dashboard-heading">Enquiries</h3>
                    <p class="dashboard-number"><?php echo $enquiriesCount; ?></p>
                    <a href="patient-contacts.php" class="btn btn-danger">View Patient Enquiries</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-box bg-warning">
                    <h3 class="dashboard-heading">Profile</h3>
                    <p class="dashboard-number">-</p>
                    <a href="admin-profile.php" class="btn btn-danger">Manage Your Profile</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-box bg-secondary">
                    <h3 class="dashboard-heading">Configuration Settings</h3>
                    <p class="dashboard-number">-</p>
                    <a href="manage-configurations.php" class="btn btn-danger">Change Config Settings</a>
                </div>
            </div>
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