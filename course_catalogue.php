<?php
// course_catalogue.php

include 'db_connect.php';
session_start();

require 'auth.php';

checkAccess(['professor', 'student', 'secretary']);

$user_id = $_SESSION['user_id'];
$query = $pdo->prepare("SELECT role FROM User WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$user_role = $user['role'];

if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
    if ($user_role === 'student') { 
        $query = $pdo->prepare("SELECT * FROM Enrollment WHERE student_id = ? AND course_id = ?");
        $query->execute([$user_id, $course_id]);
        if ($query->rowCount() === 0) {
            $insert = $pdo->prepare("INSERT INTO Enrollment (student_id, course_id) VALUES (?, ?)");
            $insert->execute([$user_id, $course_id]);

            header("Location: course_catalogue.php?message=enrolled");
            exit();
        } else {
            header("Location: course_catalogue.php?message=alreadyEnrolled");
            exit();
        }
    } else {
        header("Location: course_catalogue.php?message=notAllowed");
        exit();
    }
}

$query = $pdo->prepare("
    SELECT Course.id, Course.name, User.username AS professor_name 
    FROM Course 
    JOIN User ON Course.professor_id = User.id 
    WHERE Course.status = 'active'
");
$query->execute();
$courses = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Catalogue</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/course_catalogue.css">

</head>
<body>
<header class="header">
    <div class="container">
        <h1 class="header-title">Course Catalogue</h1>
        <nav class="nav-links">
            <a href="logout.php"><img src="img/logout.png" alt="Logout"></a>
        </nav>
    </div>
</header>

<div class="gradient-cards">
    <?php
    if (!empty($courses)) {
        foreach ($courses as $course) {
            echo "<div class='card'>";
            echo "<div class='card-title'>" . htmlspecialchars($course['name']) . "</div>";
            echo "<div class='card-professor'>Professor: " . htmlspecialchars($course['professor_name']) . "</div>";
            echo "<div class='card-buttons'>";
            // Only show the Enroll button if the user role is student
            if ($user_role === 'student') {
                echo "<a href='?course_id=" . urlencode($course['id']) . "'>Enroll</a>";
            }
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No active courses available.</p>";
    }
    ?>
</div>

<div class="bottom-nav">
    <a href="<?php echo ($_SESSION['role'] === 'student') ? 'student_dashboard.php' : 
                   (($_SESSION['role'] === 'professor') ? 'professor_dashboard.php' : 
                   (($_SESSION['role'] === 'secretary') ? 'secretary_dashboard.php' : '#')); ?>">
        <img src="img/dashboard.png" alt="Dashboard" class="nav-icon">
    </a>
    <a href="course_catalogue.php" class="active" >
        <img src="img/book.png" alt="Courses" class="nav-icon">
    </a>
    <a href="myprofile.php" >
        <img src="img/user.png" alt="Profile" class="nav-icon">
    </a>
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');

    if (message === 'enrolled') {
        showMessage('enrolled');
    } else if (message === 'alreadyEnrolled') {
        showMessage('alreadyEnrolled');
    }

    function showMessage(message) {
        console.log('showMessage function triggered: ' + message); // Debugging line
        const popup = document.createElement('div');
        let messageText = '';
        let backgroundColor = '#28a745'; 

        switch (message) {
            case 'enrolled':
                messageText = 'Successfully enrolled in the course!';
                break;
            case 'alreadyEnrolled':
                messageText = 'You are already enrolled in this course.';
                backgroundColor = '#ffc107'; 
                break;
        }

        popup.textContent = messageText;
        popup.style.position = 'fixed';
        popup.style.bottom = '20px';
        popup.style.right = '20px';
        popup.style.backgroundColor = backgroundColor;
        popup.style.color = '#fff';
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
</script>

</body>
</html>
