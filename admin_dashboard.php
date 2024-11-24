<?php
//admin_dashboard.php
session_start();
require 'auth.php';

checkAccess(['admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, Admin</h1>
    <a href="manage_users.php">Manage Users</a><br>
    <a href="audit_logs.php">View Audit Logs</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>
