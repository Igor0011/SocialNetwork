<?php require "inc/session.php"; ?>
<?php
$notifications = [
    ['id' => 1, 'message' => 'Wellcome to the SocialNet', 'timestamp' => '2024-08-12 14:00:00', 'read' => 'unread', 'type' => 2]
    // ['id' => 1, 'message' => 'Your post has been liked!', 'timestamp' => '2024-08-12 14:00:00', 'read' => false],
    // ['id' => 2, 'message' => 'You have a new follower.', 'timestamp' => '2024-08-11 09:30:00', 'read' => true],
    // Add more notifications as needed
];

// Mark notification as read (dummy handling)
if (isset($_GET['mark_read'])) {
    $id = intval($_GET['mark_read']);
    // Normally, update this in the database
    foreach ($notifications as &$notification) {
        if ($notification['id'] === $id) {
            $notification['read'] = true;
        }
    }
}

// Delete notification (dummy handling)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Normally, delete this from the database
    $notifications = array_filter($notifications, function ($notification) use ($id) {
        return $notification['id'] !== $id;
    });
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="css/notifications.css">
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>
    <div class="wrapper">
        <?php require 'inc/navbar.php' ?>
        <main class="container">
            <section class="notifications">
                <h2>Notifications</h2>

                <?php
                require('engine/sql.php');
                try {
                    // Fetch all potential friend requests where the logged-in user is the recipient
                    $stmt = $pdo->prepare("SELECT IDAccount1, IDAccount2 FROM Friends WHERE IDAccount2 = :userId");
                    $stmt->bindParam(':userId', $userid, PDO::PARAM_INT);
                    $stmt->execute();
                    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $stmt = $pdo->prepare("SELECT IDAccount1, IDAccount2 FROM Friends WHERE IDAccount1 = :userId");
                    $stmt->bindParam(':userId', $userid, PDO::PARAM_INT);
                    $stmt->execute();
                    $demands = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $r = [];
                    foreach ($requests as $request) {
                        $r[] = $request['IDAccount1'];
                    }
                    $d = [];
                    foreach ($demands as $demand) {
                        $d[] = $demand['IDAccount2'];
                    }
                    $FriendRequest = array_diff($r, $d);

                    // $notifications = [];
                    foreach ($FriendRequest as $request) {
                        $stmt = $pdo->prepare("SELECT Username FROM User WHERE ID = :id");
                        $stmt->bindParam(':id', $request, PDO::PARAM_INT);
                        $stmt->execute();
                        $username = $stmt->fetchColumn();

                        if ($username === false) {
                            $username = 'Unknown User';
                        }

                        $notifications[] = [
                            'id' => $request,
                            'message' => htmlspecialchars($username . ' wants to be friends.'),
                            'timestamp' => date('Y-m-d H:i:s'),
                            'read' => 'unread',
                            'type' => 1
                        ];
                    }
                } catch (PDOException $e) {
                    echo 'Database error: ' . $e->getMessage();
                }
                ?>
                <ul>
                    <?php foreach ($notifications as $notification): ?>
                        <li class="<?php echo $notification['read'] ? 'read' : 'unread'; ?>">
                            <p><?php echo htmlspecialchars($notification['message']); ?></p>
                            <span class="timestamp"><?php echo htmlspecialchars($notification['timestamp']); ?></span>

                            <?php if ($notification['type'] == 1): ?>
                                <a class="button" href="#" onclick="ManageRequest(1, <?= $userid ?>, <?= $notification['id'] ?>)">Accept</a>
                                <a class="button" href='#' onclick="ManageRequest(2, <?= $userid ?>, <?= $notification['id'] ?>)">Decline</a>
                            <?php elseif ($notification['type'] == 2): ?>
                                <a href="?mark_read=<?php echo $notification['id']; ?>" class="button">Mark as read</a>
                                <a href="?delete=<?php echo $notification['id']; ?>" class="button">Delete</a>
                            <?php endif; ?>
                        </li>

                    <?php endforeach; ?>
                </ul>
            </section>
        </main>
        <?php require 'inc/footer.php' ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function ManageRequest(action, acc1, acc2) {
            // Retrieve values using getElementById
            var IDAccount1 = acc1;
            var IDAccount2 = acc2;

            // Prepare data to be sent via AJAX
            var formData = {
                IDAccount1: IDAccount1,
                IDAccount2: IDAccount2,
                Action: action
            };

            $.ajax({
                url: 'ajx/ajxManageFriendRequest.php',
                type: 'POST',
                data: formData, // Send data to the server
                success: function(response) {
                    // Handle the response from the server
                    alert(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    alert('An error occurred: ' + error);
                }
            });
        }
    </script>
</body>

</html>