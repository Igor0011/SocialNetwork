<?php
session_start();

// Include the database connection file
include '../engine/sql.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare the SQL statement to fetch the user data
        $sql = "SELECT ID, Username, Password FROM User WHERE Username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
            // Password is correct
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $user['ID'];
            
            // Redirect to a protected page
            header("Location: ../main.php");
            exit();
        } else {
            // Invalid credentials
            echo "Invalid username or password.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Not a POST request
    header("Location: ../index.php");
    exit();
}
?>
