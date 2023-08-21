<?php
    session_start();

    if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiry'])) {
        header('location: patient-register.php');
        exit();
    }

    $currentTimestamp = time();

    if ($currentTimestamp > $_SESSION['otp_expiry']) {
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
        header('location: patient-register.php');
        exit();
    }

    if (isset($_POST['submit'])) {
        $enteredOTP = $_POST['otp'];

        if ($enteredOTP == $_SESSION['otp']) {
            unset($_SESSION['otp']);
            unset($_SESSION['otp_expiry']);
        
            // Insert the new user details into the database
            include('config.php');
        
            // Retrieve user data from session
            $userData = $_SESSION['user_data'];
            $uid = mysqli_real_escape_string($con, $userData['uid']);
            $name = mysqli_real_escape_string($con, $userData['name']);
            $email = mysqli_real_escape_string($con, $userData['email']);
            $phone = mysqli_real_escape_string($con, $userData['phone']);
            $hashedPwd = mysqli_real_escape_string($con, $userData['hashedPwd']);
            $med = mysqli_real_escape_string($con, $userData['med']);
            $dob = mysqli_real_escape_string($con, $userData['dob']);
            $gender = mysqli_real_escape_string($con, $userData['gender']);
            $height = mysqli_real_escape_string($con, $userData['height']);
            $weight = mysqli_real_escape_string($con, $userData['weight']);
        
            $query = "INSERT INTO patient (id, name, password, phone, email, dob, gender, height, weight, medical_history) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "ssssssssss", $uid, $name, $hashedPwd, $phone, $email, $dob, $gender, $height, $weight, $med);
            $qry_result = mysqli_stmt_execute($stmt);
        
            if ($qry_result) {
                $success_message = "New Patient Account Created! Account Username: " . $uid;
                $sanitized_success_message = htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8');
                echo "<script>alert('$sanitized_success_message'); window.location.replace('patient-login.php');</script>";
                exit();
            } else {
                $error_message = "Error in creating the patient account. Please try again.";
                $sanitized_error_message = htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8');
                echo "<script>alert('$sanitized_error_message'); window.location.replace('patient-register.php');</script>";
                exit();
            }
        }
         else {
            $error_message = "Invalid OTP! Please try again.";
        }
    }

    if (isset($_POST['logout'])) {
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
        header('location: patient-register.php');
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
                window.location.href = 'patient-register.php';
            }
        }

        setInterval(startTimer, 1000);
    </script>
</body>

</html>