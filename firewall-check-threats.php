<?php
    include('function-modules.php');


    function checkAndLogThreats($values, $patientId, $url, $ip, $con) {
        $invalidCharacters = array("'", "\"", ";", "--", "/*", "*/");
        $threatDescription = "SQL Injection Attempt";

        $macAddress=getMacAddress();

        foreach ($values as $value) {
            foreach ($invalidCharacters as $char) {
                if (strpos($value, $char) !== false) {
                    $insertQuery = "INSERT INTO threats (user_id, url, ip, mac, description) VALUES (?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $insertQuery);
                    $macAddress = getMacAddress();
                    mysqli_stmt_bind_param($stmt, "sssss", $patientId, $url, $ip, $macAddress, $threatDescription);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        echo "<script>alert('Malacious characters detected');</script>";
                        return;
                    } else {
                        $error = "Error: " . mysqli_error($con);
                        // echo "<script>alert('$error');</script>";
                    }
                }
            }
        }
    }
    function LogUploadThreats($patientId, $url, $ip, $con) {
        $threatDescription = "Unsupported or malicious file upload Attempt";
        $macAddress=getMacAddress();
        $insertQuery = "INSERT INTO threats (user_id, url, ip, mac, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sssss", $patientId, $url, $ip, $macAddress, $threatDescription);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return;
        } else {
            $error = "Error: " . mysqli_error($con);
            // echo "<script>alert('$error');</script>";
        }
    }
    function LogAccessThreats($patientId, $url, $ip, $con) {
        $threatDescription = "Access Overbound";
        $macAddress=getMacAddress();
        $insertQuery = "INSERT INTO threats (user_id, url, ip, mac, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sssss", $patientId, $url, $ip, $macAddress, $threatDescription);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return;
        } else {
            $error = "Error: " . mysqli_error($con);
            // echo "<script>alert('$error');</script>";
        }
    }
?>
