<?php
include('config.php');
session_start();

    try{
        session_destroy();
    }
    catch(Exception $e){
    }
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
                    <li class="c1"><a href="patient-login.php"> Patient Login</a></li>
                    <li class="c2"><a href="patient-register.php"> Patient Registration</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container-fluid" style="display: flex;min-height: 90vh;align-items: center; background-image: url(assets/img/slide/slide-2.jpg); background-size: cover">
        <div class='form-wrapper'>
            <h1>Patient Registration</h1>
            <hr>
            <form action="patient-register-func.php" method="post" onsubmit="return validateRegistration();">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="uid">Enter Patient Username</label>
                        <input type="text" name="uid" required class="form-control" id="uid" placeholder="Enter Patient Username">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="pwd">Enter Password</label>
                        <input type="password" name="pwd" required class="form-control" id="pwd" placeholder="Enter Password">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="confirmPwd">Confirm Password</label>
                        <input type="password" name="confirmPwd" required class="form-control" id="confirmPwd" placeholder="Confirm Password">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="name">Enter Name</label>
                        <input type="text" name="name" required class="form-control" id="name" placeholder="Enter Name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="email">Enter E-mail ID</label>
                        <input type="email" name="email" required class="form-control" id="email" placeholder="Enter E-mail ID">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="phone">Enter Phone No. (10 digits)</label>
                        <input type="number" name="phone" required class="form-control" id="phone" placeholder="Enter Phone No. (10 digits)" maxlength="10">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="dob">Enter Date of Birth</label>
                        <input type="date" name="dob" required class="form-control" id="dob" placeholder="">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="gender">Select Gender</label>
                        <select name="gender" required class="form-control" id="gender">
                            <option selected>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="height">Enter Height (cm)</label>
                        <input type="number" step="any" name="height" required class="form-control" id="height" placeholder="Enter Height (cm)" min="1" />
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="weight">Enter Weight (Kg)</label>
                        <input type="number" step="any" name="weight" required class="form-control" id="weight" placeholder="Enter Weight (Kg)" min="1" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="med">Enter Medical History</label>
                        <textarea name="med" required class="form-control" id="med" placeholder="Enter Medical History"></textarea>
                    </div>
                </div>
                <div class="form-group d-flex align-items-center">
                    <canvas id="captchaCanvas"></canvas>
                    <i class="fas fa-sync-alt refresh-icon ml-2" onclick="generateCaptcha()"></i>
                </div>
                <div class="form-group">
                    <label for="captcha">Enter Captcha</label>
                    <input type="text" name="incaptcha" required class="form-control" id="incaptcha" placeholder="Enter Captcha">
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <div class="text-center"><button type="submit" class="btn btn-success">Register</button></div><br>
                    </div>
                </div>
                <p style="text-align: left">Already have a patient account? <a href="patient-login.php">Login here</a></p>
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

    <script src="assets/js/main.js"></script>
    <script>
        generateCaptcha();
    </script>

</body>

</html>