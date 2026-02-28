<?php

class Post {
    private $pdo;
    private $table = 'Post'; // Replace with your actual table name

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a new post
    public function create($IDUser, $Title, $Description, $Image, $Likes) {
        $sql = "INSERT INTO {$this->table} (IDUser, Title, Description, Image, Likes) VALUES (:IDUser, :Title, :Description, :Image, :Likes)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':IDUser', $IDUser, PDO::PARAM_INT);
        $stmt->bindParam(':Title', $Title, PDO::PARAM_STR);
        $stmt->bindParam(':Description', $Description, PDO::PARAM_STR);
        $stmt->bindParam(':Image', $Image, PDO::PARAM_LOB);
        $stmt->bindParam(':Likes', $Likes, PDO::PARAM_INT);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    // Read a post by ID
    public function read($id) {
        $sql = "SELECT * FROM {$this->table} WHERE ID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a post
    public function update($id, $IDUser, $Title, $Description, $Image, $Likes) {
        $sql = "UPDATE {$this->table} SET IDUser = :IDUser, Title = :Title, Description = :Description, Image = :Image, Likes = :Likes WHERE ID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':IDUser', $IDUser, PDO::PARAM_INT);
        $stmt->bindParam(':Title', $Title, PDO::PARAM_STR);
        $stmt->bindParam(':Description', $Description, PDO::PARAM_STR);
        $stmt->bindParam(':Image', $Image, PDO::PARAM_LOB);
        $stmt->bindParam(':Likes', $Likes, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Delete a post
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE ID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    // Optionally, you can add a method to get all posts
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Usage example
// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     $post = new Post($pdo);

//     // Create a new post
//     $id = $post->create(1, 'Sample Title', 'Sample Description', file_get_contents('path/to/image.jpg'), 10);
//     echo "New post created with ID: $id\n";

//     // Read a post
//     $record = $post->read($id);
//     print_r($record);

//     // Update a post
//     $affectedRows = $post->update($id, 2, 'Updated Title', 'Updated Description', file_get_contents('path/to/new_image.jpg'), 20);
//     echo "$affectedRows post(s) updated\n";

//     // Delete a post
//     $affectedRows = $post->delete($id);
//     echo "$affectedRows post(s) deleted\n";

//     // Get all posts
//     $posts = $post->getAll();
//     print_r($posts);

// } catch (PDOException $e) {
//     echo "Error: " . $e->getMessage();
// }

?>
