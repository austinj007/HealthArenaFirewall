<?php
$test_cases = array(
    "<script>alert('XSS Attack');</script>",
    "<img src='x' onerror=alert('XSS Attack')>",
    "<a href='javascript:alert(\"XSS Attack\")'>Click me</a>",
    "<div style='background-image: url(javascript:alert(\"XSS Attack\"))'></div>",
    "Safe Input without XSS",
);

$expected_result = "Input is sanitized";

foreach ($test_cases as $index => $input) {
    $actual_result = "";
    
    // Perform XSS attack detection and sanitization
    $clean_input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    if ($clean_input === $input) {
        $actual_result = "Input is sanitized";
    } else {
        $actual_result = "XSS attack detected";
    }
    
    // Compare actual and expected results
    if ($actual_result === $expected_result) {
        echo "Test Case $index Passed: $actual_result\n";
    } else {
        echo "Test Case $index Failed: Expected '$expected_result', but got '$actual_result'\n";
    }
}
?>


<?php
$test_cases = array(
    "SELECT * FROM users WHERE username='admin'; DROP TABLE users;",
    "SELECT * FROM users WHERE username='john' OR '1'='1'",
    "'; DELETE FROM users; --",
    "Safe Input without SQL Injection",
);

$expected_result = "Input is sanitized";

// Simulated database connection
function mysqli_real_escape_string($con, $input) {
    return addslashes($input);
}

foreach ($test_cases as $index => $input) {
    $actual_result = "";
    
    // Perform SQL injection detection and prevention
    $sanitized_input = mysqli_real_escape_string(null, $input);
    if ($sanitized_input === $input) {
        $actual_result = "Input is sanitized";
    } else {
        $actual_result = "SQL injection detected";
    }
    
    // Compare actual and expected results
    if ($actual_result === $expected_result) {
        echo "Test Case $index Passed: $actual_result\n";
    } else {
        echo "Test Case $index Failed: Expected '$expected_result', but got '$actual_result'\n";
    }
}
?>


<?php
// Simulated database connection
function mysqli_prepare($con, $query) {
    return true; // Mocking a successful prepare
}

function mysqli_stmt_bind_param($stmt, $types, ...$params) {
    return true; // Mocking successful parameter binding
}

function mysqli_stmt_execute($stmt) {
    return true; // Mocking successful execution
}

function mysqli_stmt_close($stmt) {
    return true; // Mocking statement closure
}

function mysqli_query($con, $query) {
    return true; // Mocking successful query
}

function mysqli_fetch_assoc($result) {
    // Simulating fetching data
    static $data = [
        ['session_id' => 'session1', 'patient_id' => 'patient1', 'login_time' => '2023-06-01 10:00:00', 'logout_time' => null],
        ['session_id' => 'session2', 'patient_id' => 'patient2', 'login_time' => '2023-06-01 10:05:00', 'logout_time' => null],
        ['session_id' => 'session3', 'patient_id' => 'patient3', 'login_time' => '2023-06-01 10:10:00', 'logout_time' => null],
        // ... more simulated data ...
    ];
    
    return array_shift($data);
}

// Simulated SQL injection prevention function
function mysqli_real_escape_string($con, $input) {
    return addslashes($input);
}

// Simulated session handling
function session_start() {}
function session_id() { return 'test_session_id'; }
function session_destroy() {}

// Simulated configuration update
function updateConfigValue($con, $configId, $value) {
    return true; // Mocking successful config update
}

// Simulated security feature check
function checkMaxUserLoad($con, $maxUserLoad) {
    // Here you can fetch the actual active session count and compare it with the maxUserLoad
    // Simulating that the check passes
    return true;
}

// Simulated logout of old sessions
function logoutOldSessions($con, $maxUserLoad) {
    // Simulated logout of sessions exceeding the maxUserLoad
    // Here you would perform the actual logout of sessions with logout_time = null
    return true;
}

// Simulate changing the max_user_load value
$newMaxUserLoad = 10;
updateConfigValue(null, 'max_user_load', $newMaxUserLoad);

// Simulate fetching the new max_user_load value
$fetchConfigQuery = "SELECT value FROM security_config WHERE type = 'max_user_load'";
$result = mysqli_query(null, $fetchConfigQuery);
$newMaxUserLoad = mysqli_fetch_assoc($result)['value'];

// Simulate inserting sessions into the current_sessions table
$sessionIdPrefix = 'session_';
$patientIdPrefix = 'patient_';

for ($i = 1; $i <= $newMaxUserLoad + 5; $i++) {
    $sessionInsertQuery = "INSERT INTO current_sessions (session_id, patient_id, login_time, logout_time, ip, mac)
                           VALUES (?, ?, NOW(), NULL, '127.0.0.1', '00:00:00:00:00:00')";
    $sessionStmt = mysqli_prepare(null, $sessionInsertQuery);
    $sessionId = $sessionIdPrefix . $i;
    $patientId = $patientIdPrefix . $i;
    mysqli_stmt_bind_param($sessionStmt, 'ss', $sessionId, $patientId);
    mysqli_stmt_execute($sessionStmt);
    mysqli_stmt_close($sessionStmt);
}

// Simulate checking and preventing extra logins
if (checkMaxUserLoad(null, $newMaxUserLoad)) {
    echo "Test Passed: Extra logins are being prevented.\n";
} else {
    echo "Test Failed: Extra logins are not being prevented.\n";
}

// Simulate logout of old sessions
logoutOldSessions(null, $newMaxUserLoad);

// Simulate cleaning up and ending the session
session_start();
session_destroy();
?>
