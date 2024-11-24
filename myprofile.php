<?php
// myprofile.php

session_start();
include 'db_connect.php';
require 'auth.php'; 

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

checkAccess(['admin', 'student', 'professor', 'secretary']);

$user_id = $_SESSION['user_id']; 

if (!isset($user_id)) {
    die("User is not logged in.");
}

$query = $pdo->prepare("SELECT id, username, email, password_hash FROM User WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_password'])) {
        $original_password = $_POST['original_password'];
        $new_password = $_POST['new_password'];

        if (password_verify($original_password, $user['password_hash'])) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = $pdo->prepare("UPDATE User SET password_hash = ? WHERE id = ?");
            $update_query->execute([$new_password_hash, $user_id]);

            $message = "Password updated successfully!";
        } else {
            $message = "The original password is incorrect!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/myprofile.css">
</head>
<body>

<header class="header">
        <div class="container">
            <h1 class="header-title">Welcome, <span ><?php echo htmlspecialchars($user['username']); ?></span></h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="logout.php"><img src="img/logout.png" alt=""></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="main">
        <h2>Welcome,<span> <?php echo htmlspecialchars($user['username']); ?>!</span></h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

        <h3>Change Password</h3>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
    <div>
        <label for="original_password">Original Password:</label>
        <input type="password" id="original_password" name="original_password" required>
    </div>
    <div>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required 
               pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" 
               title="Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.">
        <small style="color: red; display: none;" id="password-error">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
    </div>
    <div>
        <button type="submit" name="change_password">Change Password</button>
    </div>
</form>

    </div>

    <div class="bottom-nav">
    <a href="<?php echo ($_SESSION['role'] === 'student') ? 'student_dashboard.php' : 
                   (($_SESSION['role'] === 'professor') ? 'professor_dashboard.php' : 
                   (($_SESSION['role'] === 'secretary') ? 'secretary_dashboard.php' : '#')); ?>">
        <img src="img/dashboard.png" alt="Dashboard" class="nav-icon">
    </a>
    <a href="course_catalogue.php" >
        <img src="img/book.png" alt="Courses" class="nav-icon">
    </a>
    <a href="myprofile.php" class="active">
        <img src="img/user.png" alt="Profile" class="nav-icon">
    </a>
</div>




</body>
</html>
