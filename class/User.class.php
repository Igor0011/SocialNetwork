<?php
class User
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createUser($username, $password, $email, $gender, $birthDate)
    {
        $sql = "INSERT INTO User (Username, Password, Email, Gender, BirthDate) VALUES (:username, :password, :email, :gender, :birthDate)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_BCRYPT), // Use bcrypt for hashing passwords
            ':email'    => $email,
            ':gender'   => $gender,
            ':birthDate' => $birthDate
        ]);
    }

    public function getUser($id)
    {
        $sql = "SELECT * FROM User WHERE ID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $username, $password, $email, $gender, $birthDate)
    {
        $sql = "UPDATE User SET Username = :username, Password = :password, Email = :email, Gender = :gender, BirthDate = :birthDate WHERE ID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'       => $id,
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':email'    => $email,
            ':gender'   => $gender,
            ':birthDate' => $birthDate
        ]);
    }

    public function deleteUser($id)
    {
        $sql = "DELETE FROM User WHERE ID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM User";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getFriendList($userId)
    {
        $message = "";
        $listItems = "";

        try {
            // Fetch all friend requests where the logged-in user is the recipient
            $stmt = $this->pdo->prepare("SELECT IDAccount1, IDAccount2 FROM Friends WHERE IDAccount2 = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch all friend demands where the logged-in user is the sender
            $stmt = $this->pdo->prepare("SELECT IDAccount1, IDAccount2 FROM Friends WHERE IDAccount1 = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $demands = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Determine mutual friends
            $requestIds = array_column($requests, 'IDAccount1');
            $demandIds = array_column($demands, 'IDAccount2');
            $friends = array_intersect($requestIds, $demandIds);

            $allResults = [];
            foreach ($friends as $friendId) {
                $sql = "SELECT ID, Username FROM User WHERE ID = :friendId";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':friendId', $friendId, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $allResults[] = $result;
                }
            }
            // Prepare the HTML list items
            if (count($allResults) > 0) {
                $messageObj = new Message($this->pdo); // Assuming Message class exists and is correctly instantiated
                foreach ($allResults as $user) {
                    $id = htmlspecialchars($user['ID']);
                    $username = htmlspecialchars($user['Username']);
                    $msgCount = $messageObj->countFriendUnread($userId, $id);
                    if ($msgCount > 0) {
                        $listItems .= "<li><a href=\"#\" onclick='ChangeRecipient($id);' data-user=\"$username\">$username</a> (" .  $msgCount . ") </li>\n";
                    }
                    else{
                        $listItems.= "<li><a href=\"#\" onclick='ChangeRecipient($id);' data-user=\"$username\">$username</a></li>\n";
                    }
                }
            } else {
                $message = "Currently no friends";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
        return [
            'message' => $message,
            'listItems' => $listItems
        ];
    }
}
