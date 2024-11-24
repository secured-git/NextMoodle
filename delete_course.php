<?php
require 'auth.php';
checkAccess(['professor']);
include 'db_connect.php';

if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
    $user_id = $_SESSION['user_id'];
    //check if course really belongs to professor
    $query = $pdo->prepare("SELECT * FROM Course WHERE id = ? AND professor_id = ?");
    $query->execute([$course_id, $user_id]);
    $course = $query->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        die("Error: You do not have permission to delete this course.");
    }

    $pdo->beginTransaction();

    try {
        $delete_assignments = $pdo->prepare("DELETE FROM Assignment WHERE course_id = ?");
        $delete_assignments->execute([$course_id]);

        $delete_enrollments = $pdo->prepare("DELETE FROM Enrollment WHERE course_id = ?");
        $delete_enrollments->execute([$course_id]);

        $delete_course = $pdo->prepare("DELETE FROM Course WHERE id = ?");
        $delete_course->execute([$course_id]);

        $pdo->commit();
        echo "<p>Course and associated data deleted successfully.</p>";
        echo "<a href='professor_dashboard.php'>Back to Dashboard</a>";
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: Unable to delete course. " . $e->getMessage());
    }
} else {
    die("Invalid course ID.");
}
?>
