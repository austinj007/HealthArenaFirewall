<?php
    session_start();
    include('check-session.php');
    include('config.php');
    if(!isset($_SESSION["id"]))
        header('location: patient-login.php');
    else if($_SESSION["type"]!='patient')
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
                    <li class="active" style="color: #000"><a href="new-health-record.php">New Health Record</a></li>
                    <li class="" style="color: #000"><a href="manage-health-records.php">Manage Health Records</a></li>
                    <li class="" style="color: #000"><a href="patient-profile.php">Profile</a></li>
                    <li class="" style="color: #000"><a href="logout.php" class="c3">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>


    <div class="container-fluid" style="display: flex;min-height: 90vh;align-items: center; background-image: url(assets/img/departments-3.jpg); background-size: cover">
        <div class='form-wrapper' style="" id="book">
            <h1>Add a new Health Appointment Record</h1><br>

            <form action="save-appointmnet-record.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <input type="date" name="date" required class="form-control datepicker" id="date" placeholder="Appointment Date">
                    </div>
                    <div class="col-md-6 form-group">
                        <select name="department" id="department" class="form-control" required>
                            <option value="select">Select Department</option>
                            <option value="Heart Specialist">Heart Specialist</option>
                            <option value="Lungs Specialist">Lungs Specialist</option>
                            <option value="Skin Specialist">Skin Specialist</option>
                            <option value="Eyes Specialist">Eyes Specialist</option>
                            <option value="Bones Specialist">Bones Specialist</option>
                            <option value="Surgery Specialist">Surgery Specialist</option>
                            <option value="General">General</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="">Select a Slot</label>
                        <select name="slot" id="slot" class="form-control" required>
                            <option value="" disabled>Select Slot</option>
                            <option value="1">10 AM</option>
                            <option value="2">12 PM</option>
                            <option value="3">02 PM</option>
                            <option value="4">04 PM</option>
                            <option value="5">06 PM</option>
                            <option value="6">08 PM</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group" id="doctor-list">
                        <label for="">Select the Doctor</label>
                        <select name="doctor" id="doctor" class="form-control" required>
                            <option value="" disabled>Select Doctor</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Write your medical concern</label>
                    <textarea class="form-control" name="message" rows="5" placeholder="Write Concern" required></textarea>
                </div>
                <div class="form-group">
                    <label for="">Enter Doctor's Prescription</label>
                    <textarea class="form-control" name="prescription" rows="5" placeholder="Write the details from Doctor's Prescription" required></textarea>
                </div>
                <div class="form-group">
                    <label for="">Upload supporting document, if any</label>
                    <input type="file" class="form-control" name="report" required></textarea>
                    <input type="password" class="form-control" name="password" placeholder="Create a password to protect your document" minlength="6" required></textarea>

                </div>
                <div class="text-center"><button type="submit" class="btn btn-success" id="bookbt">Save Appointment Details</button></div>
            </form>

        </div>
    </div>

    <footer id="footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>Health Arena</span></strong>. All Rights Reserved
            </div>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            $('#department').change(function() {
                if ($('#date').val() != '') {
                    var ajaxRequest;
                    try {
                        ajaxRequest = new XMLHttpRequest();
                    } catch (e) {
                        try {
                            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                        } catch (e) {
                            try {
                                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                            } catch (e) {
                                alert("Your browser broke!");
                                return false;
                            }
                        }
                    }
                    ajaxRequest.onreadystatechange = function() {
                        if (ajaxRequest.readyState == 4) {
                            $('#doctor-list').html(ajaxRequest.responseText);
                        }
                    }
                    var queryString = "?searchdt=" + $('#date').val() + "&dept=" + $('#department').val() + "&slot=" + $('#slot').val();
                    ajaxRequest.open("GET", "get-doctors.php" + queryString, true);
                    ajaxRequest.send(null);
                } else {
                    alert("Please select a date first");
                }
            });
            $('#date').change(function() {

                console.log($('#date').val()+" || "+new Date());

                if(new Date($('#date').val())>=new Date()){
                    alert("Please select a date from the past");
                    $('#date').val("");
                    return;
                }

                var ajaxRequest;
                try {
                    ajaxRequest = new XMLHttpRequest();
                } catch (e) {
                    try {
                        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                            alert("Your browser broke!");
                            return false;
                        }
                    }
                }
                ajaxRequest.onreadystatechange = function() {
                    if (ajaxRequest.readyState == 4) {
                        $('#doctor-list').html(ajaxRequest.responseText);
                    }
                }
                var queryString = "?searchdt=" + $('#date').val() + "&dept=" + $('#department').val() + "&slot=" + $('#slot').val();
                ajaxRequest.open("GET", "get-doctors.php" + queryString, true);
                ajaxRequest.send(null);
            });
            $('#slot').change(function() {
                var ajaxRequest;
                try {
                    ajaxRequest = new XMLHttpRequest();
                } catch (e) {
                    try {
                        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                            alert("Your browser broke!");
                            return false;
                        }
                    }
                }
                ajaxRequest.onreadystatechange = function() {
                    if (ajaxRequest.readyState == 4) {
                        $('#doctor-list').html(ajaxRequest.responseText);
                    }
                }
                var queryString = "?searchdt=" + $('#date').val() + "&dept=" + $('#department').val() + "&slot=" + $('#slot').val();
                ajaxRequest.open("GET", "get-doctors.php" + queryString, true);
                ajaxRequest.send(null);
            });
        });

    </script>

</body>

</html>
