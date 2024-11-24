<?php
session_start();
require 'db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = '';
try {
    $stmt = $pdo->prepare("SELECT username FROM User WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $username = htmlspecialchars($result['username']);
    } else {
        $username = 'Guest';
    }
} catch (PDOException $e) {
    die("Error fetching username: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <link href="Tailwind/output.css" rel="stylesheet">

</head>
<body class="bg-red-700 text-white min-h-screen flex items-center justify-center">

    <div class="text-center p-6 bg-red-800 rounded-lg shadow-lg max-w-lg mx-auto">
        <h1 class="text-3xl font-bold mb-4">Hello <?= $username; ?></h1>
        <p class="text-lg mb-6">
            Your access is temporarily blocked by the administrator.
        </p>
        <a href="logout.php" class="bg-red-600 hover:bg-red-500 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
            Go Back to Home
        </a>
    </div>

</body>
</html>
