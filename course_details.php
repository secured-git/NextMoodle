<?php
// course_details.php

include 'db_connect.php';
session_start();


// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require 'auth.php';

checkAccess(['student']);

$user_id = $_SESSION['user_id'];

if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
    
    $query = $pdo->prepare("SELECT * FROM Enrollment WHERE student_id = ? AND course_id = ?");
    $query->execute([$user_id, $course_id]);
    $enrollment = $query->fetch(PDO::FETCH_ASSOC);
    
    if (!$enrollment) {
        die("You are not enrolled in this course.");
    }

    $query = $pdo->prepare("SELECT id, name, description, professor_id FROM Course WHERE id = ?");
    $query->execute([$course_id]);
    $course = $query->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        die("Course not found.");
    }

    $professor_id = $course['professor_id'];  

    $query = $pdo->prepare("SELECT id, professor_id, file_path, details FROM Slide WHERE course_id = ?");
    $query->execute([$course_id]);
    $slides = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $pdo->prepare("SELECT id, title, description, submission_deadline FROM Assignment WHERE course_id = ?");
    $query->execute([$course_id]);
    $assignments = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['assignment_file']) && isset($_POST['assignment_id'])) {
        $assignment_id = $_POST['assignment_id'];
        $assignment_file = $_FILES['assignment_file'];

        if ($assignment_file['error'] == 0) {
            $file_path = 'uploads/assignments/' . basename($assignment_file['name']);
            if (move_uploaded_file($assignment_file['tmp_name'], $file_path)) {
                $query = $pdo->prepare("INSERT INTO Submitted_assignments (student_id, professor_id, course_id, assignment_id, file_path, submission_date) 
                                        VALUES (?, ?, ?, ?, ?, NOW())");
                $query->execute([$user_id, $professor_id, $course_id, $assignment_id, $file_path]);

                header("Location: course_details.php?course_id=" . $course_id . "&message=submitted");
                exit();
            } else {
                header("Location: course_details.php?course_id=" . $course_id . "&message=uploadError");
                exit();
            }
        } else {
            header("Location: course_details.php?course_id=" . $course_id . "&message=noFile");
            exit();
        }
    }
} else {
    die("Invalid course ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <link rel="stylesheet" href="css/course_details.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<header class="header">
        <div class="container">
            <h1 class="header-title">Course Details</h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="logout.php"><img src="img/logout.png" alt=""></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main">
        <section class="dark-notebook-course-details">
            <h1 class="notebook-card_title">Course: <?php echo htmlspecialchars($course['name']); ?></h1>
            <h2 class="notebook-subtitle">Description</h2>
            <div class="notebook-card">
                <div class="notebook-description" id="description-box">
                    <span id="description-text">
                        <?php
                        $description = htmlspecialchars($course['description']);
                        $words = explode(' ', $description);
                        if (count($words) > 50) {
                            echo implode(' ', array_slice($words, 0, 50)) . '... ';
                            echo '<span id="more-text" style="display:none;">' . implode(' ', array_slice($words, 50)) . '</span>';
                            echo '<a href="javascript:void(0);" id="read-more" onclick="toggleDescription()">Read More</a>';
                        } else {
                            echo $description;
                        }
                        ?>
                    </span>
                </div>
            </div>
        </section>

        <section class="course-slides">
            <h2>Course Slides</h2>
            <?php
            if (!empty($slides)) {
                echo "<div class='gradient-cards'>";
                foreach ($slides as $slide) {
                    echo "<div class='card'>";
                    echo "<div class='container-card'>";
                    echo "<h3 class='card-title'>" . htmlspecialchars($slide['details']) . "</h3>";
                    echo "<div class='card-buttons'>";
                    echo "<a href='" . htmlspecialchars($slide['file_path']) . "' class='btn' target='_blank'>Download Slide</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>No slides available for this course.</p>";
            }
            ?>
        </section>

        <section class="course-assignments">
            <h2>Assignments</h2>
            <div class="assignments-table-wrapper">
                <table class="dark-table">
                    <tr>
                        <th>Assignment Title</th>
                        <th>Description</th>
                        <th>Submission Deadline</th>
                        <th>Submit</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    if (!empty($assignments)) {
                        foreach ($assignments as $assignment) {
                            $query = $pdo->prepare("SELECT * FROM Submitted_assignments WHERE student_id = ? AND assignment_id = ?");
                            $query->execute([$user_id, $assignment['id']]);
                            $submission = $query->fetch(PDO::FETCH_ASSOC);

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($assignment['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($assignment['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($assignment['submission_deadline']) . "</td>";

                            if (!$submission) {
                                echo "<td>
                                        <form method='POST' enctype='multipart/form-data' class='submit-form'>
                                            <input type='hidden' name='assignment_id' value='" . htmlspecialchars($assignment['id']) . "'>
                                            <input type='file' name='assignment_file' required>
                                            <button type='submit' class='btn submit-btn'>Submit</button>
                                        </form>
                                      </td>";
                                echo "<td class='status not-submitted'>Not Submitted</td>";
                            } else {
                                echo "<td>Already Submitted</td>";
                                echo "<td class='status submitted'>" . (isset($submission['grade']) ? "Grade: " . htmlspecialchars($submission['grade']) : "Pending Grade") . "</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No assignments for this course.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </section>
    </main>

    <div class="bottom-nav">
        <a href="student_dashboard.php" >
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
        function toggleDescription() {
            const moreText = document.getElementById("more-text");
            const readMoreLink = document.getElementById("read-more");

            if (moreText.style.display === "none") {
                moreText.style.display = "inline";
                readMoreLink.textContent = "Read Less";
            } else {
                moreText.style.display = "none";
                readMoreLink.textContent = "Read More";
            }
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');

            if (message === 'submitted') {
                alert("Assignment submitted successfully!");
            } else if (message === 'uploadError') {
                alert("Error uploading the assignment. Please try again.");
            } else if (message === 'noFile') {
                alert("Please choose a valid file to upload.");
            }
        };
    </script>
</body>
</html>
