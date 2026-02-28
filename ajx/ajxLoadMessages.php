<?php
// Assume $pdo is your PDO connection instance and $userid is the current user's ID
require '../engine/sql.php';


try {
    // Prepare the SQL statement to fetch all messages for the current user
    $userid = $_GET['userId'];
    $recipientid = $_GET['recipientId'];


    $sql = "UPDATE Message 
    SET MsgRead = :msgRead 
    WHERE IDAccount1 = :idAccount1 AND IDAccount2 = :idAccount2";

    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':idAccount1', $idAccount1, PDO::PARAM_INT);
    $stmt->bindParam(':idAccount2', $idAccount2, PDO::PARAM_INT);
    $stmt->bindParam(':msgRead', $msgRead, PDO::PARAM_INT);

    // Sample values for demonstration
    $idAccount1 = $recipientid; // The value for IDAccount1
    $idAccount2 = $userid; // The value for IDAccount2
    $msgRead = 1;      // New value for MsgRead (e.g., 1 for read)

    // Execute the statement
    $stmt->execute();



    $sql = "SELECT * FROM Message WHERE (IDAccount1 = :userid OR IDAccount2 = :userid) AND (IDAccount1 = :recepientid OR IDAccount2 = :recepientid) ORDER BY Time ASC";
    // $sql = "SELECT * FROM Message WHERE IDAccount1 = $userid OR IDAccount2 = $userid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindParam(':recepientid', $recipientid, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare the messages array for JSON encoding
    $messages = [];
    foreach ($results as $row) {
        // Format the time
        $formattedTime = date("h:i A", strtotime($row['Time'])); // 12-hour format with AM/PM

        // Determine message class
        $messageClass = ($row['IDAccount1'] == $userid) ? 'sent' : 'received';

        // Add to messages array
        $messages[] = [
            'text' => htmlspecialchars($row['Text']),
            'time' => htmlspecialchars($formattedTime),
            'class' => htmlspecialchars($messageClass)
        ];
    }

    // Return the messages as JSON
    echo json_encode($messages);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
