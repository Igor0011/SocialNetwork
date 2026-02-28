<?php require "inc/session.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title</title>
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>
    <div class="wrapper">
        <?php require 'inc/navbar.php' ?>
        <?php $email = "user@example.com" ?>
        <main class="container">
            <section class="profile">
                <h2>Profile Information</h2>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <a href="edit_profile.php" class="button">Edit Profile</a>
                <a href="ajx/logout.php" class="button">Logout</a>
            </section>
        </main>
        <?php require 'inc/footer.php' ?>
    </div>
</body>

</html>