<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}
$username = $_SESSION['username'];
$userid = $_SESSION['id'];

// Include the necessary files with error handling
$helperFile = 'class/Helper.class.php';
$sqlFile = 'engine/sql.php';

if (!file_exists($helperFile)) {
    echo '<script>console.log("Helper.class.php not accessible")</script>';
    // exit(); // Stop further execution
} else {
    require_once $helperFile;
}

if (!file_exists($sqlFile)) {
    echo '<script>console.log("sql.php not accessible")</script>';
    // exit(); // Stop further execution
} else {
    require_once $sqlFile;
}

?>
