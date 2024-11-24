<?php
require 'auth.php';
require 'db_connect.php';

checkAccess(['admin', 'secretary']);

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_course_id'])) {
        $delete_course_id = intval($_POST['delete_course_id']); // Ensure ID is an integer
        try {
            $pdo->beginTransaction();

            $tables = ['Slide', 'Exam', 'Grade', 'Enrollment'];
            foreach ($tables as $table) {
                $stmt = $pdo->prepare("DELETE FROM $table WHERE course_id = ?");
                $stmt->execute([$delete_course_id]);
            }

            $stmt = $pdo->prepare("DELETE FROM Course WHERE id = ?");
            $stmt->execute([$delete_course_id]);

            $pdo->commit();
            echo json_encode(['success' => true]);
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() === '23000') { // Foreign key constraint violation
                echo json_encode(['success' => false, 'error' => 'This course has pending assignments. Please ensure all related assignments are completed or removed before deleting the course.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'An error occurred: ' . $e->getMessage()]);
            }
            exit;
        }
    }

    if (isset($_POST['update_course_status'])) {
        $course_id = intval($_POST['course_id']);
        $new_status = $_POST['new_status'];

        try {
            $stmt = $pdo->prepare("UPDATE Course SET status = ? WHERE id = ?");
            $stmt->execute([$new_status, $course_id]);

            echo json_encode(['success' => true]);
            exit;
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
}

$courses = [];
try {
    $stmt = $pdo->query("
        SELECT 
            c.id, c.name, c.professor_id, c.status,
            u.username AS professor_name, 
            (SELECT COUNT(*) FROM Enrollment WHERE course_id = c.id) AS student_count
        FROM Course c
        LEFT JOIN User u ON c.professor_id = u.id
        ORDER BY c.id DESC
    ");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = 'Database error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="css/main.css">
    <link href="Tailwind/output.css" rel="stylesheet">


</head>
<body class="bg-gray-900 text-gray-200">

<header class="bg-gray-800 p-4 shadow-lg flex justify-between items-center">
    <h1 class="text-xl font-bold"> Manage Course </h1>
    <a href="logout.php">
        <img src="img/logout.png" alt="Logout" class="w-6 h-6">
    </a>
</header>
<div class="container mx-auto p-6 bg-gray-900 text-white rounded-lg shadow-md">

    <?php if (!empty($errors)): ?>
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <ul class="list-disc pl-6">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>


    <?php if (empty($courses)): ?>
        <p class="text-gray-400">No courses found.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="table-auto w-full text-left text-sm">
                <thead class="bg-gray-800 text-gray-300">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Course Name</th>
                        <th class="px-4 py-2">Professor</th>
                        <th class="px-4 py-2">Students Enrolled</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr class="hover:bg-gray-700 transition">
                            <td class="px-4 py-2"><?= htmlspecialchars($course['id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($course['name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($course['professor_name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($course['student_count']) ?></td>
                            <td class="px-4 py-2">
                                <select
                                    class="bg-gray-800 text-gray-300 p-2 rounded w-full"
                                    onchange="updateStatus(<?= $course['id'] ?>, this.value)">
                                    <option value="active" <?= $course['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="archived" <?= $course['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                                </select>
                            </td>
                            <td class="px-4 py-2">
                                <button
                                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                                    onclick="deleteCourse(<?= $course['id'] ?>)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
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
<script>
    function deleteCourse(courseId) {
    if (confirm("Are you sure you want to delete this course and its associated details?")) {
        const formData = new FormData();
        formData.append('delete_course_id', courseId);

        fetch('manage_courses.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`tr[data-id="${courseId}"]`).remove();
                alert('Course deleted successfully!');
            } else {
                alert(data.error || 'Unknown error occurred while deleting the course.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

    function updateStatus(courseId, newStatus) {
        const formData = new FormData();
        formData.append('update_course_status', true);
        formData.append('course_id', courseId);
        formData.append('new_status', newStatus);

        fetch('manage_courses.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Course status updated successfully!');
            } else {
                alert('Error updating course status: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>

</html>
