<?php
require 'auth.php';
checkAccess(['professor']);
include 'db_connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$user_id = $_SESSION['user_id'];


if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
    
    $query = $pdo->prepare("SELECT * FROM Course WHERE id = ? AND professor_id = ?");
    $query->execute([$course_id, $user_id]);
    $course = $query->fetch(PDO::FETCH_ASSOC);
    
    if (!$course) {
        die("Invalid course ID. You do not have access to this course.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_assignment'])) {
        $assignment_title = $_POST['assignment_title'];
        $assignment_description = $_POST['assignment_description'];
        $submission_deadline = $_POST['submission_deadline'];

        $insert = $pdo->prepare("INSERT INTO Assignment (title, description, course_id, professor_id, submission_deadline) 
                                 VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$assignment_title, $assignment_description, $course_id, $user_id, $submission_deadline]);

        header("Location: professor_course.php?course_id=" . $course_id . "&message=assignment_created");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_slide'])) {
        $slide_details = $_POST['slide_details'];
        $slide_file = $_FILES['slide_file'];

        if ($slide_file['error'] === UPLOAD_ERR_OK) {
            $file_path = 'uploads/' . uniqid() . '_' . basename($slide_file['name']);
            
            if (move_uploaded_file($slide_file['tmp_name'], $file_path)) {
                $insert = $pdo->prepare("INSERT INTO Slide (course_id, professor_id, file_path, details) 
                                         VALUES (?, ?, ?, ?)");
                $insert->execute([$course_id, $user_id, $file_path, $slide_details]);

                header("Location: professor_course.php?course_id=" . $course_id . "&message=slide_uploaded");
                exit();
            } else {
                die("Error uploading file.");
            }
        } else {
            die("Error: File upload failed.");
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_slide_id'])) {
        $slide_id = intval($_POST['edit_slide_id']);
        $slide_details = $_POST['slide_details_edit'];

        $update = $pdo->prepare("UPDATE Slide SET details = ? WHERE id = ? AND course_id = ?");
        $update->execute([$slide_details, $slide_id, $course_id]);

        header("Location: professor_course.php?course_id=" . $course_id . "&message=slide_updated");
        exit();
    }

    if (isset($_GET['delete_slide']) && is_numeric($_GET['delete_slide'])) {
        $slide_id = intval($_GET['delete_slide']);
        $delete = $pdo->prepare("DELETE FROM Slide WHERE id = ? AND course_id = ?");
        $delete->execute([$slide_id, $course_id]);

        header("Location: professor_course.php?course_id=" . $course_id . "&message=slide_deleted");
        exit();
    }

    if (isset($_GET['delete_assignment']) && is_numeric($_GET['delete_assignment'])) {
        $assignment_id = intval($_GET['delete_assignment']);
        $delete = $pdo->prepare("DELETE FROM Assignment WHERE id = ? AND course_id = ?");
        $delete->execute([$assignment_id, $course_id]);

        header("Location: professor_course.php?course_id=" . $course_id . "&message=assignment_deleted");
        exit();
    }

    $query = $pdo->prepare("SELECT * FROM Assignment WHERE course_id = ?");
    $query->execute([$course_id]);
    $assignments = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $pdo->prepare("SELECT * FROM Slide WHERE course_id = ?");
    $query->execute([$course_id]);
    $slides = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("Invalid course ID.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/professor_course.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Manage Course: <?php echo htmlspecialchars($course['name']); ?></title>
</head>
<body>
<header class="header">
    <div class="container">
        <h1 class="header-title">Manage Course: <?php echo htmlspecialchars($course['name']); ?></h1>
        <nav>
            <ul class="nav-links">
                <li><a href="logout.php"><img src="img/logout.png" alt=""></a></li>
            </ul>
        </nav>
    </div>
</header>

<h2>Create Assignment</h2>
<form method="POST" action="">
    <input type="hidden" name="create_assignment" value="1">
    <label for="assignment_title">Title:</label><br>
    <input type="text" id="assignment_title" name="assignment_title" required><br>
    <label for="assignment_description">Description:</label><br>
    <textarea id="assignment_description" name="assignment_description" required></textarea><br>
    <label for="submission_deadline">Deadline:</label><br>
    <input type="datetime-local" id="submission_deadline" name="submission_deadline" required><br>
    <button type="submit">Create Assignment</button>
</form>

<h2>Assignments</h2>
<ul class="assignment-cards">
    <?php foreach ($assignments as $assignment): ?>
        <li style="--cardColor:#393D47;">
            <div class="content">
                <div class="title"><?php echo htmlspecialchars($assignment['title']); ?></div>
                <div class="text">
                    <?php echo htmlspecialchars($assignment['description']); ?><br><br>
                    <strong>Deadline:</strong> <?php echo htmlspecialchars($assignment['submission_deadline']); ?>
                </div>
                <a href="?course_id=<?php echo $course_id; ?>&delete_assignment=<?php echo $assignment['id']; ?>"><img src="img/delete.png" alt=""></a>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

 <div class="submitted-assignments">
<h3>Submitted Assignments</h3>
<?php foreach ($assignments as $assignment): ?>
    <h4><?php echo htmlspecialchars($assignment['title']); ?></h4>
    <table border="1">
        <tr>
            <th>Student Name</th>
            <th>Submission Date</th>
            <th>Status</th>
            <th>Grade</th>
            <th>Actions</th>
        </tr>
        <?php
        if (isset($submitted_assignments[$assignment['id']])) {
            foreach ($submitted_assignments[$assignment['id']] as $submission) {
                $status = (strtotime($submission['submission_date']) <= strtotime($assignment['submission_deadline'])) ? 'On Time' : 'Late';
                echo "<tr>";
                echo "<td>" . htmlspecialchars($submission['username']) . "</td>";
                echo "<td>" . htmlspecialchars($submission['submission_date']) . "</td>";
                echo "<td>" . $status . "</td>";
                echo "<td>";
                echo "<form method='POST'>
                        <input type='hidden' name='submitted_assignment_id' value='" . htmlspecialchars($submission['student_id']) . "'>
                        <input type='hidden' name='assignment_id' value='" . htmlspecialchars($assignment['id']) . "'>
                        <input type='number' name='grade' value='" . htmlspecialchars($submission['grade']) . "' min='0' max='100' required>
                        <button type='submit' name='grade_submission'> > </button>
                      </form>";
                echo "</td>";
                echo "<td>";
                if (!empty($submission['file_path'])) {
                    echo "<a href='" . htmlspecialchars($submission['file_path']) . "' target='_blank'>View File</a>";
                } else {
                    echo "No file submitted";
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No submissions for this assignment yet.</td></tr>";
        }
        ?>
    </table>
<?php endforeach; ?>
</div>
<h2>Upload Slide</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="upload_slide" value="1">
    <label for="slide_details">Slide Details:</label><br>
    <textarea id="slide_details" name="slide_details" required></textarea><br>
    <label for="slide_file">Upload File:</label><br>
    <input type="file" id="slide_file" name="slide_file" accept="application/pdf,image/*" required><br>
    <button type="submit">Upload Slide</button>
</form>

 <div class="manage-slides">
<h2>Slides</h2>
<ul>
    <?php foreach ($slides as $slide): ?>
        <li>
            <strong>Details:</strong> <?php echo htmlspecialchars($slide['details']); ?><br>
            <strong>File:</strong> <a href="<?php echo htmlspecialchars($slide['file_path']); ?>" target="_blank">View</a><br>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="edit_slide_id" value="<?php echo $slide['id']; ?>"><br>
                <label for="slide_details_edit">Edit Details:</label>
                <input type="text" name="slide_details_edit" value="<?php echo htmlspecialchars($slide['details']); ?>" required>
                <button type="submit">Update</button>
            </form>
            <a href="?course_id=<?php echo $course_id; ?>&delete_slide=<?php echo $slide['id']; ?>">Delete</a>
        </li>
    <?php endforeach; ?>
</ul>
</div>
<div class="bottom-nav">
    <a href="<?php echo ($_SESSION['role'] === 'student') ? 'student_dashboard.php' : 
                   (($_SESSION['role'] === 'professor') ? 'professor_dashboard.php' : 
                   (($_SESSION['role'] === 'secretary') ? 'secretary_dashboard.php' : '#')); ?>">
        <img src="img/dashboard.png" alt="Dashboard" class="nav-icon">
    </a>
    <a href="course_catalogue.php" >
        <img src="img/book.png" alt="Courses" class="nav-icon">
    </a>
    <a href="myprofile.php" >
        <img src="img/user.png" alt="Profile" class="nav-icon">
    </a>
</div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');

        if (message) {
            const messages = {
                assignment_created: 'Assignment created successfully!',
                slide_uploaded: 'Slide uploaded successfully!',
                slide_updated: 'Slide updated successfully!',
                slide_deleted: 'Slide deleted successfully!',
                assignment_deleted: 'Assignment deleted successfully!',
                default: 'Action completed successfully!'
            };

            const alertMessage = messages[message] || messages.default;

            const popup = document.createElement('div');
            popup.textContent = alertMessage;
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
    });
</script>

</html>
