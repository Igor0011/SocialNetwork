<?php
// register.php

// Include the database connection file
include '../engine/sql.php';

// Initialize response array
$response = array('success' => false, 'message' => '');

// Get form data
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm-password'] ?? '';
$birthDate = $_POST['birthdate'] ?? '';
$gender = $_POST['gender'] ?? '';
$terms = isset($_POST['terms']);

// Basic validation
if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($birthDate) || empty($gender)) {
    $response['message'] = 'All fields are required.';
} elseif ($password !== $confirmPassword) {
    $response['message'] = 'Passwords do not match.';
} elseif (!$terms) {
    $response['message'] = 'You must agree to the terms of service.';
} else {
    try {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL insert statement
        $sql = "INSERT INTO User (Username, Password, Email, Gender, BirthDate) VALUES (:username, :password, :email, :gender, :birthDate)";

        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':birthDate', $birthDate);

        // Execute the statement
        $stmt->execute();

        $response['success'] = true;
        $response['message'] = 'Registration successful!';
    } catch (PDOException $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }
}

// Send response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
