<?php
    include('config.php');
    session_start();
    if (!isset($_SESSION["id"])) {
        header('location: patient-login.php');
        exit();
    } elseif ($_SESSION["type"] != 'patient') {
        header('location: index.php');
        exit();
    }


    include('firewall-check-threats.php');

    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    $inputValues = array(
        $_POST['date'],
        $_POST['slot'],
        $_POST['department'],
        $_POST['doctor'],
        $_POST['message'],
        $_POST['prescription'],
        $_POST['password'],
        $ipAddress
    );

    $uid = $_SESSION["id"];

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $domain = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    $pageUrl = $protocol . "://" . $domain . $uri;

    checkAndLogThreats($inputValues, $uid, $pageUrl, $ipAddress, $con);

    $date = mysqli_real_escape_string($con, $_POST['date']);
    $slot = mysqli_real_escape_string($con, $_POST['slot']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $doctor = mysqli_real_escape_string($con, $_POST['doctor']);
    $message = mysqli_real_escape_string($con, $_POST['message']);
    $prescription = mysqli_real_escape_string($con, $_POST['prescription']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $tmp_name    = $_FILES['report']['tmp_name'];
    $name        = $_FILES['report']['name'];
    $size        = $_FILES['report']['size'];
    $type        = $_FILES['report']['type']; 
    $error       = $_FILES['report']['error'];

    $allowedFileTypes = array('application/pdf', 'image/jpeg', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

    if (!in_array($_FILES['report']['type'], $allowedFileTypes)) {
        $errorMsg = "Invalid file type! Only PDF, JPG, DOC, and DOCX files are allowed.";
        LogUploadThreats($uid, $pageUrl, $ipAddress, $con);
        echo "<script>alert('$errorMsg');window.location.replace('new-health-record.php')</script>";
        exit();
    }

    if ($_FILES['report']['error'] === UPLOAD_ERR_OK) {
        $appo_id = 'apt' . date("mdYhis");
        
        $s = ucfirst($name);
        $data = preg_replace('/\s+/', '', $s);
        $directory1 = "patient-report-uploads/report-" . $uid . "-" . $appo_id . ".tmp"; 
        move_uploaded_file($tmp_name, $directory1);

        if (!file_exists($directory1)) {
            echo "<script>alert('Error: The uploaded file could not be found.'); window.location.replace('new-health-record.php');</script>";
            exit();
        }

        $zip = new ZipArchive();
        $zipFilePath = "patient-report-uploads/report-" . $uid . "-" . $appo_id . ".zip";
        
        $fileName = $_FILES["report"]["name"];

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::EM_AES_256) === true) {
            $content = file_get_contents($directory1);
            $zip->addFromString($fileName, $content);
            $zip->setEncryptionName($fileName, ZipArchive::EM_AES_256);

            $zip->setPassword($password);

            $zip->close();

            unlink($directory1);

            $directory1 = $zipFilePath;
        } else {
            echo "<script>alert('Error creating the ZIP archive.'); window.location.replace('new-health-record.php');</script>";
            exit();
        }
        $query = "SELECT * FROM appointments WHERE patient_id = ? AND date = ? AND slot = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "sss", $uid, $date, $slot);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) != 0) {
            echo "<script>alert('You already have this slot filled on " . $date . "! Please try again with a different date or slot!'); window.location.replace('manage-health-records.php');</script>";
        } else {
            $query = "INSERT INTO appointments (appo_id, patient_id, doc_id, date, slot, patient_desc, prescription, latest_report) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "ssssssss", $appo_id, $uid, $doctor, $date, $slot, $message, $prescription, $directory1);
            mysqli_stmt_execute($stmt);

            echo "<script>alert('Health Record Successfully Updated on date: " . $date . "'); window.location.replace('manage-health-records.php');</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('File upload error.'); window.location.replace('new-health-record.php');</script>";
        exit();
    }

    mysqli_close($con);
?>
