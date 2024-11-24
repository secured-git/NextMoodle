<?php
// student_dashboard.php

include 'db_connect.php';
session_start();


require 'auth.php';

checkAccess(['student']);

$user_id = $_SESSION['user_id'];

$query = $pdo->prepare("SELECT username FROM User WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['unenroll_id']) && is_numeric($_GET['unenroll_id'])) {
    $unenroll_id = intval($_GET['unenroll_id']);
    $delete = $pdo->prepare("DELETE FROM Enrollment WHERE student_id = ? AND course_id = ?");
    $delete->execute([$user_id, $unenroll_id]);
    
    header("Location: student_dashboard.php?message=unenrolled");
    exit();
}



$query = $pdo->prepare("
    SELECT Course.id, Course.name 
    FROM Course
    JOIN Enrollment ON Course.id = Enrollment.course_id
    WHERE Enrollment.student_id = ?
");
$query->execute([$user_id]);
$enrolled_courses = $query->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/student_dashboard.css">
    <link rel="stylesheet" href="css/main.css">
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

    <main class="container">
        <h2 class="section-title">Your Enrolled Courses</h2>
        <div class="gradient-cards">
            <?php
            if (!empty($enrolled_courses)) {
                foreach ($enrolled_courses as $course) {
                    echo "<div class='card'>";
                    echo "<div class='container-card'>";
                    echo "<h3 class='card-title'>" . htmlspecialchars($course['name']) . "</h3>";
                    echo "<div class='card-buttons'>";
                    echo "<a class='btn' href='course_details.php?course_id=" . urlencode($course['id']) . "'>View Details</a>";
                    echo "<a class='btn danger' href='?unenroll_id=" . urlencode($course['id']) . "'>Unenroll</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p class='no-courses'>You are not enrolled in any courses.</p>";
            }
            ?>
        </div>
    </main>

    <div class="bottom-nav">
    <a href="student_dashboard.php" class="active">
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

    if (message === 'unenrolled') {
        const popup = document.createElement('div');
        popup.textContent = 'Successfully unenrolled from the course!';
        popup.style.position = 'fixed';
        popup.style.bottom = '20px';
        popup.style.right = '20px';
        popup.style.backgroundColor = '#28a745'; 
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
