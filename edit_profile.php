<?php require "inc/session.php"; ?>

<?php
$email = "user@example.com"; // Example data
// Update user data logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Normally, you would update these values in the database
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    // Update session data
    $_SESSION['username'] = $new_username;

    // Redirect to profile page after updating
    header("Location: profile.php");
    exit();
}
$Name = 'first name';
$LastName = 'last name';
$BirthDate = '1990-01-01'; // Example data
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>
    <?php require 'inc/navbar.php' ?>
    <main class="container">
        <section class="edit-profile">
            <h2>Edit Profile</h2>
            <form action="edit_profile.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

                <label for="Name">Name:</label>
                <input type="Name" id="email" name="Name" value="<?php echo $Name; ?>" required>

                <label for="LastName">LastName:</label>
                <input type="LastName" id="LastName" name="LastName" value="<?php echo $LastName; ?>" required>

                <label for="BirthDate">BirthDate:</label>
                <input type="BirthDate" id="BirthDate" name="BirthDate" value="<?php echo $BirthDate; ?>" required>

                <button type="submit">Save Changes</button>
            </form>
        </section>
    </main>
    <?php require 'inc/footer.php' ?>
</body>

</html>