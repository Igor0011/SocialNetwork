<?php
// Include the database connection script
require '../inc/session.php';
require "../engine/sql.php";

// Check if the data was sent via POST
if (isset($_POST['gameScores'])) {
    $gameScores = json_decode($_POST['gameScores'], true); // Decode the JSON data into an associative array

    // Prepare the SQL query for inserting multiple records
    $sql = "INSERT INTO GameScore (Score, Game, GameName, UserID) VALUES (:score, :game, :gameName, :userID)";
    
    try {
        // Prepare the PDO statement
        $stmt = $pdo->prepare($sql);

        // Begin a transaction for batch insert
        $pdo->beginTransaction();

        // Loop through the array to bind the values and execute the insert
        foreach ($gameScores as $entry) {
            // Bind the values to the prepared statement
            $stmt->bindParam(':score', $entry['score'], PDO::PARAM_INT);
            $stmt->bindParam(':game', $entry['game'], PDO::PARAM_INT);
            $stmt->bindParam(':gameName', $entry['gameName'], PDO::PARAM_STR);
            $stmt->bindParam(':userID', $_SESSION['id'], PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();
        }

        // Commit the transaction
        $pdo->commit();

        // Success message
        echo "Success: Data inserted successfully.";
    } catch (PDOException $e) {
        // Rollback the transaction if there's an error
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No data received.";
}
?>
