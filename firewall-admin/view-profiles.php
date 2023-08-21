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
                    <li class=""><a href="security-assistance.php">Security Assistance</a></li>
                    <li class="active"><a href="view-profiles.php">Patient Profiles Management</a></li>
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
    $query = "SELECT * FROM Patient";
    $result = mysqli_query($con, $query);
    ?>

    <div class="container-fluid" style="display: flex; margin: 20px 50px; justify-content: center;">
        <div class="row" style="width: 100%;">
            <div class="col-12">
                <h1 style="text-align: center;">Manage Patient Profiles</h1><br><br>
            </div>
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>Medical History</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['phone'] . "</td>";
                            echo "<td>" . $row['dob'] . "</td>";
                            echo "<td>" . $row['gender'] . "</td>";
                            echo "<td>" . $row['height'] . "</td>";
                            echo "<td>" . $row['weight'] . "</td>";
                            echo "<td>" . $row['medical_history'] . "</td>";
                            echo "<td>" . $row['created_at'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td>";
                            echo "<a class='btn btn-warning' href='ban-unban.php?id=" . $row['id'] . "&status=" . $row['status'] . "' onclick='return confirm(\'Are you sure you want to Perform this action?\')'>" . ($row['status'] == "Banned" ? 'Un-Ban' : 'Ban') . "</a>";
                            echo " <a class='btn btn-info' href='patient-health-records.php?id=" . $row['id'] . "'>Check Health Records</a>";
                            echo " <a class='btn btn-primary' href='patient-login-sessions.php?id=" . $row['id'] . "'>Check Login Sessions</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="container-fluid" style="margin: 50px auto; width: 80%">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h3 style="text-align: center">Most Active Patients</h3><br>
                <canvas id="loginCountChart"></canvas>
            </div>

            <div class="col-md-3">
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


    <?php
        $query_logins = "SELECT patient_id, COUNT(*) AS login_count FROM current_sessions GROUP BY patient_id";
        $result_logins = mysqli_query($con, $query_logins);
        $login_counts = array();
        while ($row_logins = mysqli_fetch_assoc($result_logins)) {
            $patient_id = $row_logins['patient_id'];
            $login_count = $row_logins['login_count'];
            $login_counts[$patient_id] = $login_count;
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var patientIDs = <?php echo json_encode(array_keys($login_counts)); ?>;
        var loginCounts = <?php echo json_encode(array_values($login_counts)); ?>;

        var loginCountChartConfig = {
            type: 'bar',
            data: {
                labels: patientIDs,
                datasets: [{
                    label: 'Number of Logins',
                    data: loginCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Logins'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Patient ID'
                        }
                    }
                }
            }
        };
        var loginCountChart = new Chart(document.getElementById('loginCountChart'), loginCountChartConfig);
    </script>

</body>

</html>