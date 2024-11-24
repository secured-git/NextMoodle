<?php
// submitted_assignments.php

session_start();
include 'db_connect.php';

require 'auth.php';

checkAccess(['professor']);

if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
} else {
    die("Invalid course ID.");
}

$user_id = $_SESSION['user_id'];
$query = $pdo->prepare("SELECT username FROM User WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Error: User not found.");
}

$query = $pdo->prepare("SELECT professor_id, name FROM Course WHERE id = ?");
$query->execute([$course_id]);
$course = $query->fetch(PDO::FETCH_ASSOC);

if (!$course || $course['professor_id'] !== $user_id) {
    die("You do not have permission to view this course's submissions.");
}

$query = $pdo->prepare("SELECT id, title, description, submission_deadline FROM Assignment WHERE course_id = ?");
$query->execute([$course_id]);
$assignments = $query->fetchAll(PDO::FETCH_ASSOC);

$submitted_assignments = [];
foreach ($assignments as $assignment) {
    $query = $pdo->prepare("
        SELECT sa.student_id, sa.submission_date, sa.grade, u.username 
        FROM Submitted_assignments sa
        JOIN User u ON sa.student_id = u.id
        WHERE sa.assignment_id = ?");
    $query->execute([$assignment['id']]);
    $submitted_assignments[$assignment['id']] = $query->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['grade_submission']) && isset($_POST['submitted_assignment_id']) && isset($_POST['grade']) && isset($_POST['assignment_id'])) {
    $submitted_assignment_id = $_POST['submitted_assignment_id'];
    $grade = $_POST['grade'];
    $assignment_id = $_POST['assignment_id'];

    $query = $pdo->prepare("UPDATE Submitted_assignments SET grade = ? WHERE student_id = ? AND assignment_id = ?");
    $query->execute([$grade, $submitted_assignment_id, $assignment_id]);

    $_SESSION['recent_grade'][$assignment_id][$submitted_assignment_id] = $grade;

    header("Location: submitted_assignments.php?course_id=" . $course_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submitted Assignments - <?php echo htmlspecialchars($course['name']); ?></title>
</head>
<body>
    <h1>Course: <?php echo htmlspecialchars($course['name']); ?></h1>
    <h2>Professor: <?php echo htmlspecialchars($user['username']); ?></h2>

    <h3>Submitted Assignments</h3>

    <?php
    if (!empty($assignments)) {
        foreach ($assignments as $assignment) {
            echo "<h4>" . htmlspecialchars($assignment['title']) . "</h4>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($assignment['description']) . "</p>";
            echo "<p><strong>Submission Deadline:</strong> " . htmlspecialchars($assignment['submission_deadline']) . "</p>";

            echo "<table border='1'>";
            echo "<tr>
                    <th>Student Name</th>
                    <th>Submission Date</th>
                    <th>Status (Before Deadline?)</th>
                    <th>Grade</th>
                    <th>Actions</th>
                  </tr>";

            if (isset($submitted_assignments[$assignment['id']])) {
                foreach ($submitted_assignments[$assignment['id']] as $submission) {
                    $status = (strtotime($submission['submission_date']) <= strtotime($assignment['submission_deadline'])) ? 'On Time' : 'Late';
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($submission['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($submission['submission_date']) . "</td>";
                    echo "<td>" . $status . "</td>";

                    echo "<td>";
                    echo "<form method='POST' action=''>
                            <input type='hidden' name='submitted_assignment_id' value='" . htmlspecialchars($submission['student_id']) . "'>
                            <input type='hidden' name='assignment_id' value='" . htmlspecialchars($assignment['id']) . "'>
                            <input type='number' name='grade' value='" . htmlspecialchars($submission['grade']) . "' min='0' max='100' required>
                            <button type='submit' name='grade_submission'>Submit Grade</button>
                          </form>";
                    echo "</td>";

                    echo "<td>";
                    if (isset($_SESSION['recent_grade'][$assignment['id']][$submission['student_id']])) {
                        echo "Grade: " . htmlspecialchars($_SESSION['recent_grade'][$assignment['id']][$submission['student_id']]);
                    } else {
                        echo "No action yet";
                    }
                    echo "</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No submissions for this assignment yet.</td></tr>";
            }

            echo "</table><br>";
        }
    } else {
        echo "<p>No assignments found for this course.</p>";
    }
    ?>

    <br><a href="professor_dashboard.php">Back to Dashboard</a>
    <br><a href="logout.php">Logout</a>
</body>
</html>
