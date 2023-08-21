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

</head>

<body class="admin-dashboard-body">
    <header id="header">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><img src="../assets/img/logo.png" style="width: 100px; margin: 0 auto" alt=""></a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class=""><a href="session-monitoring.php">Sessions Monitoring</a></li>
                    <li class=""><a href="security-assistance.php">Security Assistance</a></li>
                    <li class=""><a href="view-profiles.php">Patient Profiles Management</a></li>
                    <li class=""><a href="patient-contacts.php">Patient Enquiries</a></li>
                    <li class=""><a href="manage-configurations.php">Security Configurations</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="active" style="color: #000"><a href="admin-profile.php">Profile</a></li>
                    <li class="" style="color: #000"><a href="logout.php" class="c3">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container-fluid" style="display: flex; min-height: 90vh; justify-content: center;">
        <div class='wrapper' id="">
            <section id="profile-details" class="appointment section-bg" style="padding: 40px 30px">
                <h1 style="text-align: center;">Admin Profile Details</h1><br>
                <?php
                $query = "SELECT * FROM Admin WHERE admin_id='$uid'";
                $qry_result = mysqli_query($con, $query) or die(mysqli_error($con));

                if (mysqli_num_rows($qry_result) == 0) {
                    echo "<h2>No data found for this Admin!</h2>";
                } else {
                    echo '<table class="table-bordered profile">';

                    while ($row = mysqli_fetch_array($qry_result)) {
                        echo '<tr><th>Admin ID</th><td>' . $row["admin_id"] . '</td></tr>';
                        echo '<tr><th>Name</th><td>' . $row["name"] . '</td></tr>';
                        echo '<tr><th>Email</th><td>' . $row["email"] . '</td></tr>';
                        echo '<tr><th>Designation</th><td>' . $row["designation"] . '</td></tr>';
                    }
                    $query = "SELECT COUNT(*) AS login_count FROM current_admin_sessions WHERE admin_id='$uid'";
                    $qry_result = mysqli_query($con, $query) or die(mysqli_error($con));

                    if (mysqli_num_rows($qry_result) == 0) {
                        echo "<h2>No login data found for this Admin!</h2>";
                    } else {
                        while ($row = mysqli_fetch_array($qry_result)) {
                            echo '<tr><th>Total Login Count</th><td>' . $row["login_count"] . '</td></tr>';
                        }
                    }
                    echo '<tr><td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changePasswordModal">Change Password</button></td><td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#editModal">Edit Profile</button></td></tr>';


                    echo '</table>';
                }
                ?>
            </section>
        </div>
    </div>

    <!-- Modal for editing profile details -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Admin Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <?php
                        $query = "SELECT * FROM Admin WHERE admin_id='$uid'";
                        $qry_result = mysqli_query($con, $query) or die(mysqli_error($con));

                        if (mysqli_num_rows($qry_result) > 0) {
                            $row = mysqli_fetch_array($qry_result);
                            echo '<input type="hidden" name="admin_id" value="' . $row["admin_id"] . '">';

                            echo '<div class="form-group">';
                            echo '<label for="name">Name:</label>';
                            echo '<input type="text" class="form-control" id="name" name="name" value="' . $row["name"] . '" required>';
                            echo '</div>';

                            echo '<div class="form-group">';
                            echo '<label for="email">Email:</label>';
                            echo '<input type="email" class="form-control" id="email" name="email" value="' . $row["email"] . '" required>';
                            echo '</div>';

                            echo '<div class="form-group">';
                            echo '<label for="designation">Designation:</label>';
                            echo '<input type="text" class="form-control" id="designation" name="designation" value="' . $row["designation"] . '" required>';
                            echo '</div>';
                        }
                        ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for changing password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" method="post">
                        <div class="form-group">
                            <label for="oldPassword">Old Password</label>
                            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
        if (isset($_POST['admin_id'])) {
            include('../config.php');
        
            $name = $_POST['name'];
            $email = $_POST['email'];
            $designation = $_POST['designation'];
        
            $update_query = "UPDATE Admin SET name=?, email=?, designation=? WHERE admin_id=?";
            $stmt = mysqli_prepare($con, $update_query);
        
            mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $designation, $uid);
        
            if (mysqli_stmt_execute($stmt)) {
                echo '<script>alert("Admin profile updated successfully!"); window.location.href = "admin-profile.php";</script>';
            } else {
                echo '<script>alert("Error updating admin profile.");</script>';
            }
        
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        
            exit;
        }
        
    }

    else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['oldPassword'])) {
        $old_password = mysqli_real_escape_string($con, $_POST['oldPassword']);
        $new_password = mysqli_real_escape_string($con, $_POST['newPassword']);
        $confirm_password = mysqli_real_escape_string($con, $_POST['confirmPassword']);
    
        $query = "SELECT password FROM Admin WHERE admin_id='$uid'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $old_password_from_db = $row['password'];
    
        if (sha1($old_password) !== $old_password_from_db) {
            echo "<script>alert('Old password does not match. Please try again.');</script>";
        } else {
            $passwordRegex = "/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()])[A-Za-z\d!@#$%^&*()]{8,}$/";
            if (!preg_match($passwordRegex, $new_password)) {
                echo "<script>alert('Password must be at least 8 characters and include at least one uppercase letter, one digit, and one special character.');</script>";
            } elseif ($new_password !== $confirm_password) {
                echo "<script>alert('Passwords do not match. Please try again.');</script>";
            } else {
                $hashed_new_password = sha1($new_password);
                $update_query = "UPDATE Admin SET password='$hashed_new_password' WHERE admin_id='$uid'";
                mysqli_query($con, $update_query);
    
                echo '<script>alert("Admin profile updated successfully!"); window.location.href = "admin-profile.php";</script>';
            }
        }
    }
    
    ?>


    <footer id="footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>Health Arena</span></strong>. All Rights Reserved
            </div>
        </div>
    </footer>
    <script src="../assets/js/main.js"></script>

</body>

</html>