<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// db_connect.php

// Database credentials
$dsn = "mysql:host=localhost;dbname=SocialNet";
$dbusername = "igorj";
$dbpassword = "igorj";

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $dbusername, $dbpassword);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optionally set charset
    $pdo->exec("set names utf8");

    // Use this $pdo variable in other scripts
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
