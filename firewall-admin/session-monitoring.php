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
                    <li class="active"><a href="session-monitoring.php">Sessions Monitoring</a></li>
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
    function displaySessions($title, $sessions)
    {
        if (empty($sessions)) {
            echo "<div class='col-12'>";
            echo "<h2 style='text-align: center;'>$title</h2>";
            echo "<p style='text-align: center;'>NO ACTIVE SESSION</p>";
            echo "</div>";
            return;
        }
        echo "<div class='col-12'>";
        echo "<h2 style='text-align: center;'>$title</h2><br><br>";
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>Session ID</th><th>" . ($title === 'Current active Admin sessions' ? 'Admin ID' : 'Patient ID') . "</th><th>Login Time</th><th>Logout Time</th><th>IP</th><th>MAC Address</th><th>Action</th></tr></thead>";
        echo "<tbody>";
        foreach ($sessions as $session) {
            $session_id = $session['session_id'];
            if ($title == 'Current active Admin sessions')
                $user_id = $session['admin_id'];
            else
                $user_id = $session['patient_id'];
            $login_time = $session['login_time'];
            $logout_time = $session['logout_time'];
            $ip = $session['ip'];
            $mac = $session['mac'];

            echo "<tr>";
            echo "<td>$session_id</td>";
            echo "<td>$user_id</td>";
            echo "<td>$login_time</td>";
            echo "<td>$logout_time</td>";
            echo "<td>$ip</td>";
            echo "<td>$mac</td>";
            if ($logout_time === null) {
                echo "<td><a class='btn btn-danger' href='close-session.php?session_id=$session_id'>Close Session</a></td>";
            } else {
                echo "<td>-</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    }

    $query_admin = "SELECT * FROM current_admin_sessions WHERE logout_time IS NULL";
    $result_admin_sessions = mysqli_query($con, $query_admin);
    $admin_sessions = [];
    while ($row = mysqli_fetch_assoc($result_admin_sessions)) {
        $admin_sessions[] = $row;
    }

    $query_patient = "SELECT * FROM current_sessions WHERE logout_time IS NULL";
    $result_patient_sessions = mysqli_query($con, $query_patient);
    $patient_sessions = [];
    while ($row = mysqli_fetch_assoc($result_patient_sessions)) {
        $patient_sessions[] = $row;
    }
    ?>

    <div class="container-fluid" style="display: flex; margin: 20px 50px; justify-content: center;">
        <div class="row" style="width: 100%;">

            <?php
            displaySessions('Current active Patient sessions', $patient_sessions);
            ?>
            <br><br>
            <?php
            displaySessions('Current active Admin sessions', $admin_sessions);
            ?>
        </div>
    </div>

    <div class="container-fluid" style="margin: 50px auto; width: 80%">
        <div class="row">
            <div class="col-md-4">
                <h3 style="text-align: center">Patient Session Durations</h3><br>
                <canvas id="sessionDurationChart"></canvas>
            </div>
            <div class="col-md-4">
                <h3 style="text-align: center">Active Patient-login Hours</h3><br>
                <canvas id="mostActiveHourChart"></canvas>
            </div>
            <div class="col-md-4">
                <h3 style="text-align: center">Admin Session Durations</h3><br>
                <canvas id="adminSessionDurationChart"></canvas>
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
    $query_duration = "SELECT patient_id, login_time, logout_time FROM current_sessions";
    $result_duration = mysqli_query($con, $query_duration);
    $session_durations = array();
    while ($row_duration = mysqli_fetch_assoc($result_duration)) {
        $patient_id = $row_duration['patient_id'];
        $login_time = strtotime($row_duration['login_time']);
        $logout_time = strtotime($row_duration['logout_time']);
        $session_duration_minutes = 0;
        if ($logout_time !== null) {
            $session_duration_minutes = round(($logout_time - $login_time) / 60, 2);
        }
        if (!isset($session_durations[$patient_id])) {
            $session_durations[$patient_id] = $session_duration_minutes;
        } else {
            $session_durations[$patient_id] += $session_duration_minutes;
        }
    }
    ?>
    <?php
    $query_duration = "SELECT admin_id, login_time, logout_time FROM current_admin_sessions";
    $result_duration = mysqli_query($con, $query_duration);
    $adsession_durations = array();
    while ($row_duration = mysqli_fetch_assoc($result_duration)) {
        $admin_id = $row_duration['admin_id'];
        $login_time = strtotime($row_duration['login_time']);
        $logout_time = strtotime($row_duration['logout_time']);
        $session_duration_minutes = 0;
        if ($logout_time !== null && $logout_time > $login_time) {
            $session_duration_minutes = round(($logout_time - $login_time) / 60, 2);
        }
        if (!isset($adsession_durations[$admin_id])) {
            $adsession_durations[$admin_id] = $session_duration_minutes;
        } else {
            $adsession_durations[$admin_id] += $session_duration_minutes;
        }
    }
    ?>

    <?php
    $query_most_active_hour = "SELECT HOUR(login_time) AS hour, COUNT(*) AS session_count FROM current_sessions GROUP BY HOUR(login_time)";
    $result_most_active_hour = mysqli_query($con, $query_most_active_hour);
    $most_active_hour_data = array();
    while ($row_most_active_hour = mysqli_fetch_assoc($result_most_active_hour)) {
        $hour = $row_most_active_hour['hour'] . ':00';
        $session_count = $row_most_active_hour['session_count'];
        $most_active_hour_data[] = array('hour' => $hour, 'session_count' => $session_count);
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var patientIDs = <?php echo json_encode(array_keys($session_durations)); ?>;
        var sessionDurations = <?php echo json_encode(array_values($session_durations)); ?>;

        var sessionDurationChartConfig = {
            type: 'bar',
            data: {
                labels: patientIDs,
                datasets: [{
                    label: 'Session Duration (Minutes)',
                    data: sessionDurations,
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
                            text: 'Session Duration (Minutes)'
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
        var sessionDurationChart = new Chart(document.getElementById('sessionDurationChart'), sessionDurationChartConfig);


        var adminIDs = <?php echo json_encode(array_keys($adsession_durations)); ?>;
        var adsessionDurations = <?php echo json_encode(array_values($adsession_durations)); ?>;

        var adminSessionDurationChartConfig = {
            type: 'bar',
            data: {
                labels: adminIDs,
                datasets: [{
                    label: 'Session Duration (Minutes)',
                    data: adsessionDurations,
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
                            text: 'Session Duration (Minutes)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Admin ID'
                        }
                    }
                }
            }
        };
        var adminSessionDurationChart = new Chart(document.getElementById('adminSessionDurationChart'), adminSessionDurationChartConfig);


        var mostActiveHourData = <?php echo json_encode($most_active_hour_data); ?>;

        var hourSpans = mostActiveHourData.map(item => item.hour);
        var sessionCounts = mostActiveHourData.map(item => item.session_count);

        var mostActiveHourChartConfig = {
            type: 'pie',
            data: {
                labels: hourSpans,
                datasets: [{
                    data: sessionCounts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                    ],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            }
        };

        var mostActiveHourChart = new Chart(document.getElementById('mostActiveHourChart'), mostActiveHourChartConfig);
    </script>

</body>

</html>