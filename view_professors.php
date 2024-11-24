<?php
require 'auth.php';

// Only secretary and admin can view this page
checkAccess(['secretary', 'admin']);


$conn = new mysqli("localhost", "root", "", "cms_project");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT u.username AS professor, c.name AS course, e.student_id 
        FROM User u
        JOIN Course c ON u.id = c.professor_id
        JOIN Enrollment e ON c.id = e.course_id
        WHERE u.role = 'professor'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professors and Students</title>
</head>
<body>
    <h1>Professors and Students</h1>
    <table border="1">
        <tr>
            <th>Professor</th>
            <th>Course</th>
            <th>Student ID</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['professor']) ?></td>
                <td><?= htmlspecialchars($row['course']) ?></td>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>
