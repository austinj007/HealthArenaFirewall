<?php
session_start();
include('config.php');

if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiry'])) {
    header('location: patient-forgot-password.php');
    exit;
}

// Function to validate OTP
function validateOTP($otp)
{
    if ($_SESSION['otp'] == $otp && time() <= $_SESSION['otp_expiry']) {
        return true;
    }
    return false;
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
    <link rel="stylesheet" href="styles/style.css">

</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card">
            <div class="card-body text-center" style="padding: 50px;">
                <h1 class="card-title">Enter OTP</h1><br>
                <p>An OTP has been sent to your registered E-mail ID</p>
                <?php if (isset($error_message)) { ?>
                    <p class="text-danger"><?php echo $error_message; ?></p>
                <?php } ?>
                <form method="post" action="" class="mb-4">
                    <div class="form-group">
                        <input type="text" class="form-control" name="otp" id="otp" required style="text-align: center; width: 200px; margin: 0 auto">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                </form>
                <p class="mt-4">Time remaining to enter OTP: <span id="timer"></span></p>
            </div>
        </div>
    </div>

    <?php
    
    if (isset($_POST['otp'])) {
        $enteredOTP = mysqli_real_escape_string($con, $_POST['otp']);
    
        if (validateOTP($enteredOTP)) {
            // OTP is validated, show non-closable modal to set a new password
            echo '<div class="modal-dialog modal-dialog-centered" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title">Set New Password</h5>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<form method="post" action="">';
            echo '<div class="form-group">';
            echo '<input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter New Password" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm New Password" required>';
            echo '</div>';
            echo '<button type="submit" name="submit_new_password" class="btn btn-success">Submit</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
    
            exit;
        } else {
            $error_message = "Invalid OTP. Please try again.";
        }
    }

    ?>

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
                window.location.href = 'patient-forgot-password.php';
            }
        }

        setInterval(startTimer, 1000);
    </script>
</body>

</html>
