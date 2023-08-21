<?php
session_start();
include('config.php');

// Function to generate a random OTP
function generateOTP()
{
    $otp = rand(100000, 999999);
    return $otp;
}

// Function to send OTP to the user's email
function sendOTP($email, $otp)
{
    $to = $email;
    $subject = 'One-Time Password (OTP) for Health Arena Password Change request';
    $message = 'Dear UserYour Password modification OTP is: ' . $otp . ' | Please enter this OTP within 5 minutes to change your password.';
    $headers = 'From: healtharenaservices@gmail.com';
    mail($to, $subject, $message, $headers);
}

if (isset($_SESSION["id"])) {
    if ($_SESSION["type"] != 'patient') {
        header('location: patient-profile.php');
        exit;
    }
    header('location: firewall-admin/admin-dashboard.php');
    exit;
}

if (isset($_POST['username'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);

    // Check if the username exists in the patient table
    $query = "SELECT email FROM patient WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $email);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (empty($email)) {
        $error_message = "Username not found!";
    } else {
        // Generate and send OTP to the user's email
        $otp = generateOTP();
        sendOTP($email, $otp);

        // Store the OTP and its expiry time in the session
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expiry'] = time() + 300; // OTP valid for 5 minutes

        // Redirect to the OTP verification page
        header('location: verify-otp.php');
        exit;
    }
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
                <strong>Change your password</strong><br>
                <h1 class="card-title">Enter Your Username</h1><br>
                <?php if (isset($error_message)) { ?>
                    <p class="text-danger"><?php echo $error_message; ?></p>
                <?php } ?>
                <form method="post" action="" class="mb-4">
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" id="username" required style="text-align: center; width: 200px; margin: 0 auto">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
