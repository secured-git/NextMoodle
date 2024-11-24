<?php
//redirect.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: index.php');
    exit();
}

switch ($_SESSION['role']) {
    case 'student':
        header('Location: student_dashboard.php');
        break;
    case 'professor':
        header('Location: professor_dashboard.php');
        break;
    case 'secretary':
        header('Location: secretary_dashboard.php');
        break;
    case 'admin':
        header('Location: admin_dashboard.php');
        break;
    case 'blocked':
        header('Location: blocked.php');
        break;
    default:
        header('Location: index.php');
}
?>

