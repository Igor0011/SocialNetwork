<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network Login</title>
    <link rel="stylesheet" href="css/login-page.css">
</head>
<body>
    <div class="container">
        <h1>Welcome Back!</h1>
        <p>Please log in to continue.</p>
        
        <form class="login-form" method="post" action="ajx/login.php">
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Log In</button>
        </form>
        
        <div class="social-login">
            <p>Or log in with:</p>
            <button class="social-btn google">Sign in with Google</button>
            <button class="social-btn facebook">Sign in with Facebook</button>
            <button class="social-btn twitter">Sign in with Twitter</button>
        </div>
        
        <p class="signup-link">Don't have an account? <a href="register.php">Sign up here</a></p>
    </div>
    <script>
        
    </script>
</body>
</html>
