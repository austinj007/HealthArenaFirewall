<?php
    include('config.php');
    session_start();

    if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiry'])) {
        header('location: patient-login.php');
        exit();
    }

    $currentTimestamp = time();

    if ($currentTimestamp > $_SESSION['otp_expiry']) {
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
        header('location: patient-login.php');
        exit();
    }

    if (isset($_POST['submit'])) {
        $enteredOTP = $_POST['otp'];

        if ($enteredOTP == $_SESSION['otp']) {
            unset($_SESSION['otp']);
            unset($_SESSION['otp_expiry']);
            $_SESSION['id'] = $_SESSION['id_temp'];
            $_SESSION['type'] = 'patient';

            $patientId = $_SESSION['id'];

            $ipAddress = $_SERVER['REMOTE_ADDR'];
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
                $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }


            include('function-modules.php');
            $macAddress=getMacAddress();
            $insertQuery = "INSERT INTO current_sessions (session_id, patient_id, ip, mac) VALUES (?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($con, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "ssss", session_id(), $patientId, $ipAddress, $macAddress);
            mysqli_stmt_execute($insertStmt);

            header('location: patient-profile.php');
            exit();
        } else {
            $error_message = "Invalid OTP! Please try again.";
        }
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location: patient-login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Health Arena</title>
    <meta content="" name="Best secured health service">
    <meta content="" name="healthcare, clinic, best health service, firewall, data security">
    <link href="assets/img/favicon.png" rel="icon">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card">
            <div class="card-body text-center" style="padding: 50px;">
                <h1 class="card-title">An OTP has been sent to your registered E-mail ID</h1><br>
                <h5 class="card-subtitle mb-4"><em>Please Enter the OTP below</em></h5>
                <?php if (isset($error_message)) { ?>
                    <p class="text-danger"><?php echo $error_message; ?></p>
                <?php } ?>
                <form method="post" action="" class="mb-4">
                    <div class="form-group">
                        <input type="text" class="form-control" name="otp" id="otp" required style="text-align: center; width: 200px; margin: 0 auto">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                </form>
                <form method="post" action="">
                    <button type="submit" name="logout" class="btn btn-link">Go Back to Login</button>
                </form>
                <p class="mt-4">Time remaining to enter OTP: <span id="timer"></span></p>
            </div>
        </div>
    </div>

    <script>
        var expiryTime = <?php echo $_SESSION['otp_expiry']; ?>;
        var timerElement = document.getElementById('timer');

        function startTimer() {
            var now = Math.floor(Date.now() / 1000);
            var remainingSeconds = expiryTime - now;

            if (remainingSeconds >= 0) {
                var minutes = Math.floor(remainingSeconds / 60);
                var seconds = remainingSeconds % 60;

                timerElement.textContent = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
            } else {
                timerElement.textContent = '00:00';
                window.location.href = 'patient-login.php';
            }
        }

        setInterval(startTimer, 1000);
    </script>
</body>

</html>