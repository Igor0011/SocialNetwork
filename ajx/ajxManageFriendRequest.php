<?php
require '../engine/sql.php';

try {
    // Get action parameter
    $action = isset($_POST['Action']) ? $_POST['Action'] : '';

    if ($action == '1') {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO Friends (IDAccount1, IDAccount2) VALUES (:IDAccount1, :IDAccount2)");
        $stmt->bindParam(':IDAccount1', $IDAccount1, PDO::PARAM_INT);
        $stmt->bindParam(':IDAccount2', $IDAccount2, PDO::PARAM_INT);
        $IDAccount1 = $_POST['IDAccount1'];
        $IDAccount2 = $_POST['IDAccount2'];

        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Failed to insert record";
        }
    } elseif ($action == '2') {
        // Delete
        $stmt = $pdo->prepare("DELETE FROM Friends WHERE IDAccount1 = :IDAccount2 AND IDAccount2 = :IDAccount1");
        $stmt->bindParam(':IDAccount1', $IDAccount1, PDO::PARAM_INT);
        $stmt->bindParam(':IDAccount2', $IDAccount2, PDO::PARAM_INT);
        $IDAccount1 = $_POST['IDAccount1'];
        $IDAccount2 = $_POST['IDAccount2'];

        if ($stmt->execute()) {
            echo "Record deleted successfully";
        } else {
            echo "Failed to delete record";
        }
    } else {
        echo "Invalid action";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
