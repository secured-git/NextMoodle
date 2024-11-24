<?php
require 'auth.php';
checkAccess(['secretary', 'admin']);





?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Secretary Dashboard</title>
</head>
<body class="bg-gray-900 text-white font-sans">

<header class="bg-gray-800 p-4 shadow-lg flex justify-between items-center">
    <h1 class="text-xl font-bold">Secretary Dashboard</h1>
    <a href="logout.php">
        <img src="img/logout.png" alt="Logout" class="w-6 h-6">
    </a>
</header>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6">
    <a href="manage_courses.php" class="block p-6 bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold text-lg rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
        Manage Courses
    </a>
    <a href="manage_users.php" class="block p-6 bg-gradient-to-r from-teal-500 to-green-500 text-white font-semibold text-lg rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
        Manage Users
    </a>
    <!-- <a href="audit_logs.php" class="block p-6 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold text-lg rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
        View Audit Logs
    </a>
    <a href="view_professors.php" class="block p-6 bg-gradient-to-r from-pink-500 to-red-500 text-white font-semibold text-lg rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
        View Professors and Students
    </a> -->
</div>

<div class="fixed bottom-0 left-0 w-full bg-gray-800 p-3 flex justify-around shadow-inner">
    <a href="secretary_dashboard.php" class="flex flex-col items-center text-indigo-400">
        <img src="img/dashboard.png" alt="Dashboard" class="w-6 h-6">
        <span class="text-sm mt-1">Dashboard</span>
    </a>
    <a href="course_catalogue.php" class="flex flex-col items-center text-gray-400 hover:text-indigo-400">
        <img src="img/book.png" alt="Courses" class="w-6 h-6">
        <span class="text-sm mt-1">Courses</span>
    </a>
    <a href="myprofile.php" class="flex flex-col items-center text-gray-400 hover:text-indigo-400">
        <img src="img/user.png" alt="Profile" class="w-6 h-6">
        <span class="text-sm mt-1">Profile</span>
    </a>
</div>

</body>
</html>
