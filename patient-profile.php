<?php
    session_start();
    include('check-session.php');
    if(!isset($_SESSION["id"]))
        header('location: patient-login.php');
    else if($_SESSION["type"]!='patient')
        header('location: index.php');

    $uid = $_SESSION["id"];
    include('config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Health Arena</title>
    <meta content="" name="Best secured health service">
    <meta content="" name="healthcare, clinic, best health service, firewall, data security">
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
                    <li class="" style="color: #000"><a href="manage-health-records.php">Manage Health Records</a></li>
                    <li class="active" style="color: #000"><a href="patient-profile.php">Profile</a></li>
                    <li class="" style="color: #000"><a href="logout.php" class="c3">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container-fluid" style="display: flex; min-height: 90vh;justify-content: center; ">
        <div class='wrapper' id="">
            <section id="appointments" class="appointment section-bg" style="padding: 40px 30px">
               <h1 style="text-align: center;">Profile Details</h1><br>
                <?php
                $query="select * from patient where id='$uid'";
                $qry_result = mysqli_query($con,$query) or die(mysqli_error($con));
                if(mysqli_num_rows ( $qry_result )==0){
                    echo "<h2>No history recorded!</h2>";
                }
                else{
                    echo'<table class="table-bordered profile">';
                    
                        while($row = mysqli_fetch_array($qry_result)) {
                            echo '<tr><th>Patient ID</th><td>' . $row["id"] . '</td></tr>';
                        echo '<tr><th>Name</th><td>' . $row["name"] . '</td></tr>';
                        echo '<tr><th>Phone</th><td>' . $row["phone"] . '</td></tr>';
                        echo '<tr><th>Email</th><td>' . $row["email"] . '</td></tr>';
                        echo '<tr><th>Date of Birth</th><td>' . $row["dob"] . '</td></tr>';
                        echo '<tr><th>Gender</th><td>' . $row["gender"] . '</td></tr>';
                        echo '<tr><th>Height</th><td>' . $row["height"] . ' Cm</td></tr>';
                        echo '<tr><th>Weight</th><td>' . $row["weight"] . ' Kg</td></tr>';
                        echo '<tr><th>Medical History</th><td>' . $row["medical_history"] . '</td></tr>';
                        }
                        echo '</table>';
                }
            ?>
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

    <script src="assets/js/main.js"></script>


</body>

</html>