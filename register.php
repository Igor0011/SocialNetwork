<?php
session_start();

// Include database connection (replace with actual file and credentials)
// include 'db_connect.php';

// Initialize variables
$username = $email = $password = "";
$username_err = $email_err = $password_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Check if username is already taken (replace with actual query)
        // $sql = "SELECT id FROM users WHERE username = ?";
        // $stmt = $pdo->prepare($sql);
        // $stmt->execute([$_POST["username"]]);
        // if ($stmt->rowCount() > 0) {
        //     $username_err = "This username is already taken.";
        // } else {
        //     $username = trim($_POST["username"]);
        // }
        $username = trim($_POST["username"]); // Placeholder for real validation
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        // Check if email is already taken (replace with actual query)
        // $sql = "SELECT id FROM users WHERE email = ?";
        // $stmt = $pdo->prepare($sql);
        // $stmt->execute([$_POST["email"]]);
        // if ($stmt->rowCount() > 0) {
        //     $email_err = "This email is already taken.";
        // } else {
        //     $email = trim($_POST["email"]);
        // }
        $email = trim($_POST["email"]); // Placeholder for real validation
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        // Prepare insert query (replace with actual query)
        // $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        // $stmt = $pdo->prepare($sql);
        // $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);

        // Redirect to login page after successful registration
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Social Network</title>
    <link rel="stylesheet" href="css/register.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-container h2 {
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
        }

        .form-container input {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Register</h2>
        <form action="/register" method="POST">
            <div class="error" id="error-message"></div>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" required>

            <label for="birthdate">Birthdate</label>
            <input type="date" id="birthdate" name="birthdate" required>

            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="" disabled selected>Select your gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="non-binary">Non-binary</option>
                <option value="other">Other</option>
            </select><br><br>

            <label for="terms">
                <input type="checkbox" style="width: auto;" id="terms" name="terms" required>
                I agree to the <a href="/terms-of-service">Terms of Service</a>
            </label>

            <button type="submit">Register</button>
        </form>
        <p class="signup-link">Allready have an account? <a href="index.php">Log in here</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                var form = $(this);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: 'ajx/ajxRegister.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Handle successful registration
                            alert('Registration successful!');
                            form[0].reset(); // Reset the form
                        } else {
                            // Display errors
                            $('#error-message').text(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        $('#error-message').text('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>

</body>

</html>