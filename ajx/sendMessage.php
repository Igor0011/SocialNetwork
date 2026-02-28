<?php
// Include the database connection
require '../engine/sql.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST request
    $idAccount1 = $_POST['idAccount1'];
    $idAccount2 = $_POST['idAccount2'];
    $text = $_POST['text'];
    $time = date('Y-m-d H:i:s'); // Current time
    if ($idAccount2 != 0) {
        try {
            // Prepare the SQL statement
            // $stmt = $pdo->prepare("INSERT INTO Message (IDAccount1, IDAccount2, Text, Time) VALUES (:idAccount1, :idAccount2, :text, :time)");
            $stmt = $pdo->prepare("INSERT INTO Message (IDAccount1, IDAccount2, Text) VALUES (:idAccount1, :idAccount2, :text)");


            // Bind parameters to the SQL statement
            $stmt->bindParam(':idAccount1', $idAccount1, PDO::PARAM_INT);
            $stmt->bindParam(':idAccount2', $idAccount2, PDO::PARAM_INT);
            $stmt->bindParam(':text', $text, PDO::PARAM_STR);
            // $stmt->bindParam(':time', $time, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();

            // Send a success response
            echo "New record created successfully";
        } catch (PDOException $e) {
            // Send an error response
            echo "Error: " . $e->getMessage();
        }
    }
    else{
        echo 'Recipient not selected.';
    }
} else {
    // If not a POST request
    echo "Invalid request method";
}
