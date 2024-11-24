<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>
    <link href="Tailwind/output.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-200">
    <!-- Header -->
    <header class="bg-gray-800 p-4 shadow-lg flex justify-between items-center">
    <h1 class="text-xl font-bold"> Audit Logs // Not functional yet! </h1>
    <a href="logout.php">
        <img src="img/logout.png" alt="Logout" class="w-6 h-6">
    </a>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto my-8">

        <!-- Filter Form -->
        <form method="GET" class="mb-4 flex gap-4">
            <select name="action_type" class="p-2 border border-gray-600 bg-gray-700 rounded text-gray-300">
                <option value="">All Actions</option>
                <option value="registration">Registration</option>
                <option value="course_creation">Course Creation</option>
                <option value="enrollment">Enrollment</option>
                <option value="unenrollment">Unenrollment</option>
                <option value="logout">Logout</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
        </form>

        <!-- Audit Log Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-700">
                <thead>
                    <tr class="bg-gray-800 text-gray-300">
                        <th class="border border-gray-700 px-4 py-2">Timestamp</th>
                        <th class="border border-gray-700 px-4 py-2">Action Type</th>
                        <th class="border border-gray-700 px-4 py-2">Details</th>
                        <th class="border border-gray-700 px-4 py-2">Performed By</th>
                        <th class="border border-gray-700 px-4 py-2">Entity</th>
                        <th class="border border-gray-700 px-4 py-2">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($auditLogs as $log): ?>
                        <tr class="hover:bg-gray-700">
                            <td class="border border-gray-700 px-4 py-2"><?= htmlspecialchars($log['timestamp']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?= htmlspecialchars($log['action_type']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?= htmlspecialchars($log['action_details']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?= htmlspecialchars($log['performed_by']); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?= htmlspecialchars($log['related_entity'] ?? 'N/A'); ?></td>
                            <td class="border border-gray-700 px-4 py-2"><?= htmlspecialchars($log['ip_address']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Bottom Navigation -->
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
