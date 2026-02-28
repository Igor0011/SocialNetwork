<?php
require '../engine/sql.php';

// Create a new PDO instance
try {
    // Get the POST data
    $idAccount1 = isset($_POST['idAccount1']) ? (int) $_POST['idAccount1'] : 0;
    $idAccount2 = isset($_POST['idAccount2']) ? (int) $_POST['idAccount2'] : 0;

    // Validate inputs
    if ($idAccount1 <= 0 || $idAccount2 <= 0) {
        throw new Exception('Invalid account ID.');
    }

    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO Friends (IDAccount1, IDAccount2) VALUES (:idAccount1, :idAccount2)");
    $stmt->bindParam(':idAccount1', $idAccount1, PDO::PARAM_INT);
    $stmt->bindParam(':idAccount2', $idAccount2, PDO::PARAM_INT);

    // Execute the statement
    if ($stmt->execute()) {
        echo 'Success';
    } else {
        echo 'Error';
    }
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
