<?php
// professor_dashboard.php

require 'auth.php';
checkAccess(['professor']);
include 'db_connect.php';

session_start();
$user_id = $_SESSION['user_id'];

// Fetch professor's username
$query = $pdo->prepare("SELECT username FROM User WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die("Error: User not found.");
}

// Handle course creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_course'])) {
    $course_name = $_POST['course_name'];
    $course_description = $_POST['course_description'];

    // Default course status to "active"
    $query = $pdo->prepare("INSERT INTO Course (name, professor_id, status, description) VALUES (?, ?, 'active', ?)");
    $query->execute([$course_name, $user_id, $course_description]);

    // Redirect with success message
    header("Location: professor_dashboard.php?message=created");
    exit();
}

// Handle course deletion
if (isset($_GET['delete_course']) && is_numeric($_GET['delete_course'])) {
    $course_id = intval($_GET['delete_course']);
    $query = $pdo->prepare("DELETE FROM Course WHERE id = ? AND professor_id = ?");
    $query->execute([$course_id, $user_id]);

    // Redirect with success message
    header("Location: professor_dashboard.php?message=deleted");
    exit();
}

// Handle course description update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_description'])) {
    $course_id = intval($_POST['course_id']);
    $course_description = $_POST['course_description'];

    $query = $pdo->prepare("UPDATE Course SET description = ? WHERE id = ? AND professor_id = ?");
    $query->execute([$course_description, $course_id, $user_id]);

    // Redirect with success message
    header("Location: professor_dashboard.php?message=description_updated");
    exit();
}

// Handle course status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $course_id = intval($_POST['course_id']);
    $course_status = $_POST['course_status'];

    $query = $pdo->prepare("UPDATE Course SET status = ? WHERE id = ? AND professor_id = ?");
    $query->execute([$course_status, $course_id, $user_id]);

    // Redirect with success message
    header("Location: professor_dashboard.php?message=status_updated");
    exit();
}

// Fetch courses created by the professor
$query = $pdo->prepare("SELECT * FROM Course WHERE professor_id = ?");
$query->execute([$user_id]);
$professor_courses = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/professor_dashboard.css">
</head>
<body>
<header class="header">
    <div class="container">
        <h1 class="header-title">Welcome, <span><?php echo htmlspecialchars($user['username']); ?></span></h1>
        <nav>
            <ul class="nav-links">
                <li><a href="logout.php"><img src="img/logout.png" alt=""></a></li>
            </ul>
        </nav>
    </div>
</header>

<h2>Create a New Course</h2>
<form method="POST">
    <label for="course_name">Course Name:</label><br>
    <input type="text" name="course_name" id="course_name" required><br>

    <label for="course_description">Course Description:</label><br>
    <textarea name="course_description" id="course_description" rows="4" cols="50" required></textarea><br>

    <button type="submit" name="create_course">Create Course</button>
</form>

<h2>Your Courses</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($professor_courses as $course): ?>
        <tr>
            <td><?php echo htmlspecialchars($course['name']); ?></td>
            <td>
                <form method="POST" class="description-form">
                    <textarea name="course_description" required><?php echo htmlspecialchars($course['description']); ?></textarea>
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <button type="submit" name="update_description">Update</button>
                </form>
            </td>
            <td>
                <form method="POST" class="status-form">
                    <select name="course_status" required>
                        <option value="active" <?php echo $course['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="archived" <?php echo $course['status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                    </select>
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <button type="submit" name="update_status">Update</button>
                </form>
            </td>
            <td>
                <button><a href="professor_course.php?course_id=<?php echo $course['id']; ?>">Manage</a></button> | 
                <a href="?delete_course=<?php echo $course['id']; ?>" onclick="return confirm('Delete this course?')"><img src="img/delete.png" alt=""></a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<div class="bottom-nav">
    <a href="professor_dashboard.php" class="active">
        <img src="img/dashboard.png" alt="Dashboard" class="nav-icon">
        
    </a>
    <a href="course_catalogue.php">
        <img src="img/book.png" alt="Courses" class="nav-icon">
    </a>
    <a href="myprofile.php">
        <img src="img/user.png" alt="Profile" class="nav-icon">
    </a>
</div>

<script>
const urlParams = new URLSearchParams(window.location.search);
const message = urlParams.get('message');

if (message) {
    const messages = {
        created: 'Course created successfully!',
        deleted: 'Course deleted successfully!',
        description_updated: 'Course description updated successfully!',
        status_updated: 'Course status updated successfully!'
    };

    if (messages[message]) {
        const popup = document.createElement('div');
        popup.textContent = messages[message];
        popup.style.position = 'fixed';
        popup.style.bottom = '20px';
        popup.style.right = '20px';
        popup.style.backgroundColor = '#28a745'; 
        popup.style.padding = '10px 20px';
        popup.style.borderRadius = '5px';
        popup.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.2)';
        popup.style.fontSize = '16px';
        popup.style.zIndex = '1000';
        document.body.appendChild(popup);

        setTimeout(() => {
            popup.remove();
        }, 3000);

        history.replaceState({}, document.title, window.location.pathname);
    }
}
</script>

</body>
</html>
