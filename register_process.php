<?php
include 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        echo '<script>alert("All fields are required!"); window.location.href="index.php";</script>';
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email address!"); window.location.href="index.php";</script>';
        exit();
    }

    if (strpos($email, '@student.uni.lu') !== false) {
        $role = 'student';
    } elseif (strpos($email, '@uni.lu') !== false) {
        $role = 'professor';
    } else {
        echo '<script>alert("Invalid email domain! Only students and faculty members are allowed."); window.location.href="index.php";</script>';
        exit();
    }

    if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}/', $password)) {
        echo '<script>alert("Password must be at least 6 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character."); window.location.href="index.php";</script>';
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare('INSERT INTO User (username, password_hash, email, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $password_hash, $email, $role]);
        echo '<script>alert("Registration successful! Please login."); window.location.href="index.php";</script>';
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo '<script>alert("Username or email already exists."); window.location.href="index.php";</script>';
        } else {
            echo '<script>alert("Registration failed: ' . $e->getMessage() . '"); window.location.href="index.php";</script>';
        }
    }
} else {
    echo '<script>alert("Invalid request."); window.location.href="index.php";</script>';
    exit();
}
?>
