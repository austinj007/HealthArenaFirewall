<?php
    session_start();
    include('check-session.php');
    include('../config.php');
    if (!isset($_SESSION["id"]))
        header('location: admin-login.php');
    else if ($_SESSION["type"] != 'firewall-admin')
        header('location: ../index.php');

    $uid = $_SESSION["id"];

    $query_config = "SELECT * FROM security_config";
    $result_config = mysqli_query($con, $query_config);
    $config_data = array();
    while ($row_config = mysqli_fetch_assoc($result_config)) {
        $config_data[$row_config['id']] = $row_config;
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include('../config.php');

        $update_query = "UPDATE security_config SET value=? WHERE id=?";
        $stmt = mysqli_prepare($con, $update_query);

        foreach ($_POST['config'] as $config_id => $config_values) {
            $value = $config_values['value'];

            mysqli_stmt_bind_param($stmt, "si", $value, $config_id);

            mysqli_stmt_execute($stmt);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }


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
                    <li class="active"><a href="manage-configurations.php">Security Configurations</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="" style="color: #000"><a href="admin-profile.php">Profile</a></li>
                    <li class="" style="color: #000"><a href="logout.php" class="c3">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>


    <div class="container-fluid" style="display: flex; margin: 100px 50px; justify-content: center; min-height: 90vh; ">
        <div class="row" style="width: 100%;">
            <div class="col-12">
                <h1 style="text-align: center;">Confiugure Settings</h1><br><br>
            </div>
            <div class="col-md-8 col-md-offset-2">
                <form method="post">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Minimum Value</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($config_data as $config_id => $config) { ?>
                                <tr>
                                    <td>
                                        <label><?php echo $config['type']; ?> </label>
                                    </td>
                                    <td>
                                        <label><?php echo $config['min_value']; ?> </label>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="config[<?php echo $config_id; ?>][value]" value="<?php echo $config['value']; ?>" min="<?php echo $config['min_value'];?>" required>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" style="width: 200px; margin: 10px auto;">Save</button>
                        <button type="button" class="btn btn-warning" style="width: 200px; margin: 10px auto;">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <footer id="footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>Health Arena</span></strong>. All Rights Reserved
            </div>
        </div>
    </footer>


</body>

</html>