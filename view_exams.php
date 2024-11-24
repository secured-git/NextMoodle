<?php
//view_exams.php
session_start();
require 'auth.php';

checkAccess(['student']);

require 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch exams for enrolled courses
$query = $pdo->prepare("
    SELECT Exam.file_path, Course.name AS course_name, Exam.release_date
    FROM Exam
    JOIN Course ON Exam.course_id = Course.id
    JOIN Enrollment ON Course.id = Enrollment.course_id
    WHERE Enrollment.student_id = ? AND Exam.release_date <= NOW()
");
$query->execute([$user_id]);
$exams = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Exams</title>
</head>
<body>
    <h1>Your Exams</h1>
    <ul>
        <?php foreach ($exams as $exam): ?>
            <li>
                <?= htmlspecialchars($exam['course_name']); ?> - 
                <a href="<?= htmlspecialchars($exam['file_path']); ?>" download>Download Exam</a>
                (Release Date: <?= htmlspecialchars($exam['release_date']); ?>)
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="student_dashboard.php">Back to Dashboard</a>
</body>
</html>
