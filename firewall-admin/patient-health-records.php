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
    if (isset($_GET['id'])) {
        $patientId = $_GET['id'];
        $query = "SELECT * FROM Patient WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $patientId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $patient = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        $query = "SELECT * FROM Appointments WHERE patient_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $patientId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
    ?>

    <div class="container-fluid" style="display: flex; margin: 100px 50px; justify-content: center; min-height: 90vh; ">
        <div class="row" style="width: 100%;">
            <div class="col-12">
                <h1 style="text-align: center;">Health Records - Appointments</h1>
                <h3 style="text-align: center;">Patient ID: <?php echo $patientId; ?></h3><br><br>
            </div>
            <div class="col-12">
                <table class="table table-bordered">
                    <tr>
                        <th>Appointment ID</th>
                        <th>Date</th>
                        <th>Doctor ID</th>
                        <th>Patient Description</th>
                        <th>Prescription</th>
                        <th>Latest Report</th>
                        <th>Status</th>
                        <th>Date/Time</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $query = "SELECT * FROM Doctor WHERE id = ?";
                        $stmt = mysqli_prepare($con, $query);
                        mysqli_stmt_bind_param($stmt, "s", $row['doc_id']);
                        mysqli_stmt_execute($stmt);
                        $doctorResult = mysqli_stmt_get_result($stmt);
                        $doctor = mysqli_fetch_assoc($doctorResult);
                        mysqli_stmt_close($stmt);
                    ?>
                        <tr>
                            <td><?php echo $row['appo_id']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="#doctorModal<?php echo $row['doc_id']; ?>"><?php echo $row['doc_id']; ?></a>
                                <div class="modal fade" id="doctorModal<?php echo $row['doc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="doctorModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="doctorModalLabel">Doctor Details</h3>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Doctor ID:</strong> <?php echo $doctor['id']; ?><br><br>
                                                        <strong>Email:</strong> <?php echo $doctor['email']; ?><br><br>
                                                        <strong>Field:</strong> <?php echo $doctor['field']; ?><br><br>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Name:</strong> <?php echo $doctor['name']; ?><br><br>
                                                        <strong>Phone:</strong> <?php echo $doctor['phone']; ?><br><br>
                                                        <strong>Fees:</strong> <?php echo "£" . $doctor['fees']; ?><br><br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </td>
                            <td><?php echo $row['patient_desc']; ?></td>
                            <td><?php echo $row['prescription']; ?></td>
                            <td><a href="../<?php echo $row['latest_report']; ?>" download>Download Report</a></td>
                            <td><?php echo ($row['status'] == 1) ? 'Active' : 'Removed'; ?></td>
                            <td><?php echo $row['datetime']; ?></td>
                            <?php
                            echo "<td>";
                            echo "<form action='change-appointment-status.php' method='post'>";
                            echo "<input type='hidden' name='appointment_id' value='{$row['appo_id']}'>";
                            echo "<input type='hidden' name='patient_id' value='{$patientId}'>";
                
                            if ($row['status'] == 1) {
                                echo "<button type='submit' name='status_change' class='btn btn-danger'>";
                                echo "<i class='fas fa-trash-alt'></i> Remove";
                            } else {
                                echo "<button type='submit' name='status_change' class='btn btn-info'>";
                                echo "<i class='fas fa-check'></i> Activate";
                            }
                            echo "</button>";
                            echo "</form>";
                            echo "</td>";
                            ?>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
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