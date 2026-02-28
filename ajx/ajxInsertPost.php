<?php
require "../engine/sql.php"; // Ensure this file contains the database connection setup

// Check if POST data is received
if (isset($_POST['userid'], $_POST['title'], $_POST['description'])) {
    $idUser = $_POST['userid'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $likes = 0;

    // Initialize imageBlob as null
    $imageBlob = null;

    // Check if an image file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        // Read the image file into a binary string
        $imageBlob = file_get_contents($image['tmp_name']);
    }

    try {
        // Define the SQL INSERT statement
        // Handle both cases: with and without an image
        $sql = "INSERT INTO Post (IDUser, Title, Description, Image, Likes) 
                VALUES (:idUser, :title, :description, :image, :likes)";

        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);

        // If there is an image, bind it as a BLOB
        if ($imageBlob !== null) {
            $stmt->bindParam(':image', $imageBlob, PDO::PARAM_LOB);
        } else {
            // If no image, bind NULL
            $stmt->bindValue(':image', '', PDO::PARAM_LOB);
        }

        $stmt->bindParam(':likes', $likes, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Record inserted successfully.']);
    } catch (PDOException $e) {
        // Handle any errors
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
}
?>
