<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: redirect.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="form signin">
            <h2>Login</h2>
            <form action="login_process.php" method="POST">
                <div class="inputBox">
                    <input type="text" name="username" required 
                           pattern="^[a-zA-Z0-9]{3,20}$" title="Username must be 3-20 characters, letters and numbers only.">
                    <span>Username</span>
                    <i class="icon">👤</i>
                </div>
                <div class="inputBox">
                    <input type="password" name="password" required 
                           pattern=".{6,}" title="Password must be at least 6 characters long.">
                    <span>Password</span>
                    <i class="icon">🔒</i>
                </div>
                <input type="submit" value="Login">
            </form>
            <p>Don't have an account? <a href="#" onclick="toggleForm()">Register</a></p>
        </div>

        <div class="form signup">
            <h2>Register</h2>
            <form action="register_process.php" method="POST">
                <div class="inputBox">
                    <input type="text" name="username" required 
                           pattern="^[a-zA-Z0-9]{3,20}$" title="Username must be 3-20 characters, letters and numbers only.">
                    <span>Username</span>
                    <i class="icon">👤</i>
                </div>
                <div class="inputBox">
                    <input type="email" name="email" required>
                    <span>Email</span>
                    <i class="icon">📧</i>
                </div>
                <div class="inputBox">
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}" 
                        title="Password must be at least 6 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.">
                    <span>Password</span>
                    <i class="icon">🔒</i>
                </div>
                <input type="submit" value="Register">
            </form>
            <p>Already have an account? <a href="#" onclick="toggleForm()">Login</a></p>
        </div>
    </div>

    <script>
        function toggleForm() {
            const container = document.querySelector('.container');
            container.classList.toggle('signinForm');
        }
    </script>
</body>
</html>
