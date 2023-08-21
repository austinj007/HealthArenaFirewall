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
                    <li><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class=""><a href="session-monitoring.php">Sessions Monitoring</a></li>
                    <li class="active"><a href="security-assistance.php">Security Assistance</a></li>
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
    $query = "SELECT * FROM threats";
    $result = mysqli_query($con, $query);
    ?>

    <div class="container-fluid" style="display: flex; margin: 20px 50px; justify-content: center;">
        <div class="row" style="width: 100%;">
            <div class="col-12">
                <h1 style="text-align: center;">Security Threats</h1><br><br>
            </div>
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Threat ID</th>
                            <th>Date/Time</th>
                            <th>User Id (If Any)</th>
                            <th>IP Address</th>
                            <th>MAC Address</th>
                            <th>URL</th>
                            <th>Threat Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['datetime'] . "</td>";
                            echo "<td>" . $row['user_id'] . "</td>";
                            echo "<td>" . $row['ip'] . "</td>";
                            echo "<td>" . $row['mac'] . "</td>";
                            echo "<td>" . $row['url'] . "</td>";
                            echo "<td>" . $row['description'] . "</td>";
                            echo "<td>" . (($row['status'] == "1") ? "Attended" : "Pending"). "</td>";
                            echo "<td>";
                            if ($row['status'] == "0") {
                                echo "<a class='btn btn-warning' href='attend-threat.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to mark this threat as attended?\")'>Mark Attended</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</body>

</html>