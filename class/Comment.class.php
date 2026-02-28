<?php

class Comment
{
    private $pdo;
    private $id;
    private $postID;
    private $idAccount;
    private $text;
    private $timeStamp;
    private $username;

    public function __construct($pdo, $id = null, $postID = null, $idAccount = null, $text = null, $timeStamp = null, $username=null)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->postID = $postID;
        $this->idAccount = $idAccount;
        $this->text = $text;
        $this->timeStamp = date('Y-m-d H:i:s');
        $this->username = $username;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getPostID()
    {
        return $this->postID;
    }

    public function getIdAccount()
    {
        return $this->idAccount;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getTimeStamp()
    {
        return $this->timeStamp;
    }
    public function getUsername()
    {
        return $this->username;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setPostID($postID)
    {
        $this->postID = $postID;
    }

    public function setIdAccount($idAccount)
    {
        $this->idAccount = $idAccount;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setTimeStamp($timeStamp = null)
    {
        if ($timeStamp === null) {
            // Set to current datetime in MySQL format
            $timeStamp = date('Y-m-d H:i:s');
        }
        $this->timeStamp = $timeStamp;
    }

    // Methods to interact with the database
    public function save()
    {
        if ($this->id === null) {
            // Insert
            $stmt = $this->pdo->prepare("INSERT INTO Comment (PostID, IDAccount, Text) VALUES (:postID, :idAccount, :text)");
            $stmt->execute([
                ':postID' => $this->postID,
                ':idAccount' => $this->idAccount,
                ':text' => $this->text,
            ]);
            $this->id = $this->pdo->lastInsertId();
        } else {
            // Update
            $stmt = $this->pdo->prepare("UPDATE Comment SET PostID = :postID, IDAccount = :idAccount, Text = :text WHERE ID = :id");
            $stmt->execute([
                ':postID' => $this->postID,
                ':idAccount' => $this->idAccount,
                ':text' => $this->text,
                ':id' => $this->id,
            ]);
        }
    }

    public function delete()
    {
        if ($this->id !== null) {
            $stmt = $this->pdo->prepare("DELETE FROM Comment WHERE ID = :id");
            $stmt->execute([':id' => $this->id]);
        }
    }

    public static function findById($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM Comment WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new self($pdo, $row['ID'], $row['PostID'], $row['IDAccount'], $row['Text'], $row['TimeStamp'],'');
        } else {
            return null;
        }
    }
    public static function filterByPost($pdo, $postid)
    {
        $stmt = $pdo->prepare("SELECT * FROM Comment JOIN User ON Comment.IDAccount = User.ID WHERE PostID = :id");
        $stmt->execute([':id' => $postid]);

        // Use fetchAll to get an array of all rows
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $comments = [];
        foreach ($rows as $row) {
            $comments[] = new self(
                $pdo,
                $row['ID'],
                $row['PostID'],
                $row['IDAccount'],
                $row['Text'],
                $row['TimeStamp'],
                $row['Username']
            );
        }

        return $comments;
    }
}
