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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="admin-dashboard-body">
    <header id="header">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><img src="../assets/img/logo.png" style="width: 100px; margin: 0 auto" alt=""></a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="view-profiles.php"><i class="fas fa-arrow-left"></i> Go Back</a></li>
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
            echo "<p style='text-align: center;'>NO LOGIN SESSIONS FOUND FOR THIS USER</p>";
            echo "</div>";
            return;
        }
        echo "<div class='col-12'>";
        echo "<h2 style='text-align: center;'>$title</h2><br><br>";
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>Session ID</th><th>Patient ID</th><th>Login Time</th><th>Session Duration</th><th>Logout Time</th><th>IP</th><th>MAC Address</th><th>Action</th></tr></thead>";
        echo "<tbody>";
        foreach ($sessions as $session) {
            $session_id = $session['session_id'];
            $patient_id = $session['patient_id'];
            $login_time = $session['login_time'];
            $logout_time = $session['logout_time'];
            $ip = $session['ip'];
            $mac = $session['mac'];
        
            echo "<tr>";
            echo "<td>$session_id</td>";
            echo "<td>$patient_id</td>";
            echo "<td>$login_time</td>";
            echo "<td>";
        
            if ($logout_time === null) {
                echo "Session Ongoing";
            } else {
                $login_timestamp = strtotime($login_time);
                $logout_timestamp = strtotime($logout_time);
                $session_duration_minutes = ($logout_timestamp - $login_timestamp) / 60;
                echo number_format($session_duration_minutes, 2) . " minutes";

            }
        
            echo "</td>";
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

    $patient_id = $_GET['id'];
    $query_patient = "SELECT * FROM current_sessions WHERE patient_id = ? ORDER BY login_time DESC";
    $stmt = mysqli_prepare($con, $query_patient);
    mysqli_stmt_bind_param($stmt, "s", $patient_id);
    mysqli_stmt_execute($stmt);
    $result_patient_sessions = mysqli_stmt_get_result($stmt);

    if ($result_patient_sessions) {
        $patient_sessions = mysqli_fetch_all($result_patient_sessions, MYSQLI_ASSOC);
    } else {
        $patient_sessions = []; 
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
    ?>


    <div class="container-fluid" style="display: flex; margin: 20px 50px; justify-content: center;">
        <div class="row" style="width: 100%;">
            <?php
            displaySessions('Login sessions', $patient_sessions);
            ?>
            <br>
        </div>
    </div>


    <div class="container-fluid" style="margin: 20px auto; width: 800px">
        <div class="row">
            <div class="col-12">
                <h2 style="text-align: center;">Login Sessions Chart</h2><br>
                <canvas id="sessionsChart" style="width: 50%;"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('sessionsChart').getContext('2d');
        var sessionsData = <?php echo json_encode($patient_sessions); ?>;

        var sessionIds = [];
        var sessionDurations = [];
        var timeColor = [];

        sessionsData.forEach(function(session) {
            var sessionId = session.session_id;
            var loginTime = new Date(session.login_time);
            var logoutTime = session.logout_time ? new Date(session.logout_time) : new Date();
            var durationInSeconds = Math.round((logoutTime - loginTime) / 1000);
            var durationInMinutes = durationInSeconds / 60;

            sessionIds.push(sessionId);
            sessionDurations.push(durationInMinutes);

            var color = 'rgba(' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',0.7)';
            timeColor.push(color);
        });

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sessionIds,
                datasets: [{
                    data: sessionDurations,
                    backgroundColor: timeColor,
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Session ID'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Session Duration (minutes)'
                        },
                        beginAtZero: true,
                        maxBarThickness: 30
                    }
                }
            }
        });
    </script>

</body>

</html>