<?php
include('config.php');
session_start();
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
                    <li><a href="#about">About</a></li>
                    <li><a href="#services">Security</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>



                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if (!isset($_SESSION["id"])) {
                        echo '<li class="c1"><a href="patient-login.php"> Patient Login</a></li>';
                        echo '<li class="c2"><a href="patient-register.php"> Patient Registration</a></li>';
                    } else {
                        if ($_SESSION["type"] == 'patient') {
                            echo '<li class="c1"><a href="patient-profile.php">Patient Dashboard</a></li>';
                        } else {
                            echo '<li class="c2"><a href="firewall-admin/admin-dashboard.php">Admin Dashboard</a></li>';
                        }
                        echo '<li class="c3"><a href="logout.php">Logout</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </header>

    <div class="hero-image">
        <div class="hero-content">
            <h2 style="text-transform: uppercase; letter-spacing: 3px; font-size: 25px">Welcome to <span>Health Arena</span></h2>
            <p>Health Arena is the trusted and familiar home for millions of individuals seeking comprehensive healthcare services while ensuring the utmost security and privacy of their health data. We prioritize the protection of sensitive information by implementing robust security measures at every step of our services. From the moment users access our platform, their data is safeguarded through state-of-the-art encryption protocols, stringent access controls, and regular security audits.</p>
            <?php
            if (!isset($_SESSION["id"])) {
                echo '<a href="patient-register.php">Create Patient Profile</a>';
            } else if ($_SESSION["type"] == 'patient') {
                echo '<a href="patient-profile.php">Visit Profile</a>';
            } else {
                echo '<a href="firewall-admin/admin-dashboard.php" >Admin Dashboard</a>';
            }
            ?>
        </div>
    </div>
    <div class="container-fluid gap" id="about">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-5 login-area">
                <div class="container-fluid about" id="login-area" style="margin: 0 100px 0 0;">
                    <h2>About Health Arena</h2>
                    <p>Health Arena is the trusted and familiar home for millions of individuals seeking comprehensive healthcare services while ensuring the utmost security and privacy of their health data. We prioritize the protection of sensitive information by implementing robust security measures at every step of our services. From the moment users access our platform, their data is safeguarded through state-of-the-art encryption protocols, stringent access controls, and regular security audits.</p>
                    <p>At Health Arena, we offer a wide range of services designed to meet the diverse healthcare needs of our users. Our platform enables individuals to assess health issues and connect with the right doctors, ensuring personalized and quality care. </p>
                    <h2>Medical Services</h2>
                    <p>Healthcare providers can also harness the power of Health Arena as the definitive platform that helps them build their presence, grow establishments and engage patients more deeply than ever.</p>
                    <ul>
                        <li>General Checkups</li>
                        <li>Dental Care</li>
                        <li>Skin Care</li>
                        <li>Heart Check-ups</li>
                        <li>Lungs Checkups</li>
                        <li>Bones and Muscle Health</li>
                        <li>Optics</li>
                        <li>Critical Surgery</li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-5 d-flex justify-content-center">
                <div class="container-fluid about" id="login-area" style="margin: auto auto; display: flex">
                    <img src="assets/img/logosq.png" style="width: 50%; margin: 0 auto" alt="logo">
                </div>
            </div>
            <div class="col-sm-1"></div>
        </div>
    </div>
    <div class="container-fluid gap" id="services">
        <div class="row" style="width: 86%; margin: 0 auto">
            <div class="col-sm-12">
                <h2>Why Trust Us</h2>
            </div>
        </div>
        <div class="row" style="width: 86%; margin: 30px auto">
            <div class="col-sm-4">
                <div class="service">
                    <img src="https://www.analyticsinsight.net/wp-content/uploads/2019/08/Data-Security-in-Healthcare.jpg" alt="Service 2">
                    <h3>Data Security</h3>
                    <p>Ensuring the highest level of security measures to protect patient data, including encryption, access controls, and regular audits.</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="service">
                    <img src="https://imageio.forbes.com/specials-images/imageserve/6209634f27505b3fb0c646cc/0x0.jpg?format=jpg&width=1200" alt="Service 2">
                    <h3>Data Privacy</h3>
                    <p>Respecting patient privacy by strictly adhering to privacy regulations and policies, and implementing robust data privacy practices.</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="service">
                    <img src="https://www.tsplegal.com/wp-content/uploads/2017/11/Data_Security_Health_Care_Medical_Data_Protection_Web.jpg" alt="Service 2">
                    <h3>Data Backup and Recovery</h3>
                    <p>Regularly backing up patient data and implementing robust recovery mechanisms to ensure data availability and continuity of care.</p>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid gap" id="contact">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-5">
                <h2>Contact Us</h2>
                <form action="submit-contact.php" class="">
                    <div class="form-group">
                        <br>
                        <label for="pwd">Full Name:</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address:</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Please write your message:</label>
                        <textarea class="form-control" name="msg" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-info" style="width: 30%">Submit</button>
                </form>
            </div>
            <div class="col-sm-5">
                <div class="container-fluid about" id="login-area" style="margin: auto auto; display: flex">
                    <img src="assets/img/contact.png" style="width: 50%; margin: 0 auto" alt="logo">
                </div>
            </div>
            <div class="col-sm-1"></div>
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