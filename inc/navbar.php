<?php
require "class/Message.class.php";
$message = new Message($pdo);
?>
<header>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script> -->
    <div class="container">
        <div class="logo">
            <h1>SocialNet</h1>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="main.php">Home</a></li>
                <?php if ($message->countAllUnread($userid) > 0) { ?>
                    <li><a href="messages.php">Messages</a> <?= $message->countAllUnread($userid); ?></li>
                <?php } else { ?>
                    <li><a href="messages.php">Messages</a> </li>
                <?php } ?>
                <li><a href="notifications.php">Notifications</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="games.php">Games</a></li>
                <li><a href="ajx/logout.php">Log Out</a></li>
            </ul>
            <!-- <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div> -->
        </nav>
    </div>
</header>