<?php

class Message
{
    private $pdo;
    private $table = 'Message'; // Replace with your actual table name

    // Private attributes
    private $id;
    private $IDAccount1;
    private $IDAccount2;
    private $Text;
    private $MsgRead;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Create a new record
    public function create($IDAccount1, $IDAccount2, $Text, $MsgRead)
    {
        $sql = "INSERT INTO {$this->table} (IDAccount1, IDAccount2, Text, MsgRead) VALUES (:IDAccount1, :IDAccount2, :Text, :MsgRead)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':IDAccount1' => $IDAccount1,
            ':IDAccount2' => $IDAccount2,
            ':Text' => $Text,
            ':MsgRead' => $MsgRead
        ]);
        $this->id = $this->pdo->lastInsertId(); // Set the id attribute after insert
        return $this->id;
    }

    // Read a record by ID
    public function read($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set attributes from fetched data
        if ($data) {
            $this->id = $data['ID'];
            $this->IDAccount1 = $data['IDAccount1'];
            $this->IDAccount2 = $data['IDAccount2'];
            $this->Text = $data['Text'];
            $this->MsgRead = $data['MsgRead'];
        }

        return $data;
    }

    // Update a record
    public function update($id, $IDAccount1, $IDAccount2, $Text, $MsgRead)
    {
        $sql = "UPDATE {$this->table} SET IDAccount1 = :IDAccount1, IDAccount2 = :IDAccount2, Text = :Text, MsgRead = :MsgRead WHERE ID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':IDAccount1' => $IDAccount1,
            ':IDAccount2' => $IDAccount2,
            ':Text' => $Text,
            ':MsgRead' => $MsgRead,
            ':id' => $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a record
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE ID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    // Optionally, you can add a method to get all records
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAllUnread($accountID)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE IDAccount2 = :accountID AND MsgRead = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':accountID' => $accountID]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];  // Cast to integer
    }

    public function countFriendUnread($account1ID, $account2ID)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE IDAccount2 = :account1ID AND IDAccount1 = :account2ID AND MsgRead = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':account1ID' => $account1ID, ':account2ID' => $account2ID]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];  // Cast to integer
    }

    // Getter and Setter methods for attributes

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIDAccount1()
    {
        return $this->IDAccount1;
    }

    public function setIDAccount1($IDAccount1)
    {
        $this->IDAccount1 = $IDAccount1;
    }

    public function getIDAccount2()
    {
        return $this->IDAccount2;
    }

    public function setIDAccount2($IDAccount2)
    {
        $this->IDAccount2 = $IDAccount2;
    }

    public function getText()
    {
        return $this->Text;
    }

    public function setText($Text)
    {
        $this->Text = $Text;
    }

    public function getMsgRead()
    {
        return $this->MsgRead;
    }

    public function setMsgRead($MsgRead)
    {
        $this->MsgRead = $MsgRead;
    }
}



// Usage example
// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     $myTable = new Message($pdo);

//     // Create a new record
//     $id = $myTable->create(1, 2, 'Sample text', 0);
//     echo "New record created with ID: $id\n";

//     // Read a record
//     $record = $myTable->read($id);
//     print_r($record);

//     // Update a record
//     $affectedRows = $myTable->update($id, 3, 4, 'Updated text', 1);
//     echo "$affectedRows record(s) updated\n";

//     // Delete a record
//     $affectedRows = $myTable->delete($id);
//     echo "$affectedRows record(s) deleted\n";

//     // Get all records
//     $records = $myTable->getAll();
//     print_r($records);

// } catch (PDOException $e) {
//     echo "Error: " . $e->getMessage();
// }
