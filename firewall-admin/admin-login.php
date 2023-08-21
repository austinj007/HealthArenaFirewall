<?php

session_start();
include('../config.php');
if (isset($_SESSION["id"]))
    if ($_SESSION["type"] == 'firewall-admin')
        header('location: admin-dashboard.php');
    else if ($_SESSION["type"] == 'patient'){
        include('../firewall-check-threats.php');

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        $inputValues = array(
            $ipAddress
        );

        $uid=$_SESSION["id"];
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $domain = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $pageUrl = $protocol . "://" . $domain . $uri;

        LogAccessThreats($uid, $pageUrl, $ipAddress, $con);
        header('location: ../index.php');
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Health Arena - Firewall Administrator</title>
    <meta content="" name="Best secured health service">
    <meta content="" name="healthcare, clinic, best health service, firewall, data security">
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

<body>
    <header id="header">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><img src="../assets/img/logo.png" style="width: 100px; margin: 0 auto" alt=""></a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Website</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container-fluid" style="display: flex;min-height: 90vh;align-items: center; background-image: url(../assets/img/security-bg.jpg); background-size: cover">
        <div class='form-wrapper'>
            <h1>Admin Login</h1>
            <form action="admin-login-func.php" method="post" onsubmit="return validateForm();">
                <div class="form-group">
                    <label for="uid">Enter Admin-ID</label>
                    <input type="text" name="uid" required class="form-control" id="uid" placeholder="Enter Your Admin-ID">
                </div>
                <div class="form-group">
                    <label for="pwd">Enter Password</label>
                    <input type="password" name="pwd" required class="form-control" id="pwd" placeholder="Enter Your Password">
                </div>
                <div class="form-group d-flex align-items-center">
                    <canvas id="captchaCanvas"></canvas>
                    <i class="fas fa-sync-alt refresh-icon ml-2" onclick="generateCaptcha()"></i>
                </div>

                <div class="form-group">
                    <label for="captcha">Enter Captcha</label>
                    <input type="text" name="incaptcha" required class="form-control" id="incaptcha" placeholder="Enter Captcha">
                </div>
                <button type="submit" class="btn btn-info" style="width: 30%">Login</button><br><br>
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

    <script src="../assets/js/main.js"></script>
    <script>
        generateCaptcha();
    </script>
</body>

</html>