<?php
session_start();
require 'auth.php';
checkAccess(['secretary', 'admin']);
require 'db_connect.php';

$users = [];
try {
    $stmt = $pdo->query("SELECT id, username, email, role FROM User ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['new_role'];

    try {
        $stmt = $pdo->prepare("UPDATE User SET role = ? WHERE id = ?");
        $stmt->execute([$new_role, $user_id]);
        header("Location: manage_users.php");
        exit;
    } catch (PDOException $e) {
        die("Error updating role: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $user_id = intval($_POST['delete_user_id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM User WHERE id = ?");
        $stmt->execute([$user_id]);
        header("Location: manage_users.php");
        exit;
    } catch (PDOException $e) {
        die("Error deleting user: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="Tailwind/output.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-200">

    <header class="bg-gray-800 p-4 shadow-lg flex justify-between items-center">
        <h1 class="text-xl font-bold">Manage Users</h1>
        <a href="logout.php">
            <img src="img/logout.png" alt="Logout" class="w-6 h-6">
        </a>
    </header>

    <div class="container mx-auto my-8 p-6 bg-gray-800 rounded-lg shadow-lg">
        <?php if (empty($users)): ?>
            <p class="text-gray-400">No users found.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-left text-sm">
                    <thead class="bg-gray-700 text-gray-300">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Username</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-700 transition">
                                <td class="px-4 py-2"><?= htmlspecialchars($user['id']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="px-4 py-2">
                                    <form method="POST">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <select
                                            name="new_role"
                                            class="bg-gray-800 text-gray-300 p-2 rounded w-full"
                                            onchange="this.form.submit()">
                                            <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                                            <option value="professor" <?= $user['role'] === 'professor' ? 'selected' : '' ?>>Professor</option>
                                            
                                            <option value="secretary" <?= $user['role'] === 'secretary' ? 'selected' : '' ?>>secretary</option>
                                            <option value="blocked" <?= $user['role'] === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-2">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                                        <button
                                            type="submit"
                                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                            Delete
                                        </button>
                                    </form>
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

</body>
</html>
