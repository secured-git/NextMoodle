

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE Assignment (
  id INT NOT NULL,
  title VARCHAR(100),
  description TEXT,
  course_id INT,
  professor_id INT,
  submission_deadline DATETIME NOT NULL
);

--
-- Dumping data for table `Assignment`
--

INSERT INTO `Assignment` (`id`, `title`, `description`, `course_id`, `professor_id`, `submission_deadline`) VALUES
(3, 'database RBAC', 'DEcrp xyz', 1, 2, '2024-11-13 12:22:00'),
(4, 'assignment1', 'descr1', 33, 9, '2024-11-20 12:01:00'),
(5, 'ass2', 'ass2', 33, 9, '2024-11-14 12:22:00'),
(6, 'assign3', 'assign3', 33, 9, '2024-11-07 23:33:00'),
(8, 'new', 'do thi thi this', 1, 2, '2024-11-01 16:22:00'),
(9, 'assd4', 'no desc', 33, 9, '2024-11-08 17:40:00'),
(29, 'assignment3', 'assign3', 1, 2, '2024-11-08 15:36:00'),
(30, 'asssgn1', 'adsrfz', 45, 2, '2024-10-31 21:21:00'),
(31, 'asssgn1', 'adsrfz', 45, 2, '2024-10-31 21:21:00'),
(32, 'asssgn2', 'adsrfz', 45, 2, '2024-10-31 21:25:00'),
(33, 'assgn2', ' 80-', 1, 2, '2024-11-15 21:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `AuditLog`
--

CREATE TABLE `AuditLog` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `details` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Course`
--

CREATE TABLE `Course` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `professor_id` int DEFAULT NULL,
  `status` enum('active','archived') DEFAULT 'active',
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Course`
--

INSERT INTO `Course` (`id`, `name`, `professor_id`, `status`, `description`) VALUES
(1, 'Database Security', 2, 'active', 'yo hello sir how are you doing I hope you are \r\n\r\nImage of a cluster of light bulbs.My husband is hoarding our lightbulbs. When this place was going to be our home, he replaced the greenish incandescent bulbs with ones like clean sunlight. Now I come home and he’s put them back in their boxes, swaddled in paper towels. They’re going to California with him.\r\n\r\nHe put the incandescent ones back, of course. He isn’t leaving me behind with nothing—just returning our home to a house, where I’ll live alone. Our white walls become lunar in the bulbs’ green glow. I lay in bed and listen to him pack, our ceiling above cratered with strange shadows.jnksdbqeddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr\r\n\r\nImage of a cluster of light bulbs.My husband is hoarding our lightbulbs. When this place was going to be our home, he replaced the greenish incandescent bulbs with ones like clean sunlight. Now I come home and he’s put them back in their boxes, swaddled in paper towels. They’re going to California with him.\r\n\r\nHe put the incandescent ones back, of course. He isn’t leaving me behind with nothing—just returning our home to a house, where I’ll live alone. Our white walls become lunar in the bulbs’ green glow. I lay in bed and listen to him pack, our ceiling above cratered with strange shadows.hjnkl'),
(33, 'Database security', 9, 'archived', 'edited'),
(45, 'course3', 2, 'active', 'xaseedaw4gfdgbf'),
(47, 'cybercrime', 2, 'active', 'dsjbkkfaes3qew');

-- --------------------------------------------------------

--
-- Table structure for table `Enrollment`
--

CREATE TABLE `Enrollment` (
  `id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Enrollment`
--

INSERT INTO `Enrollment` (`id`, `student_id`, `course_id`, `status`) VALUES
(14, 1, 1, 'active'),
(31, 5, 1, 'active'),
(40, 1, 45, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `Exam`
--

CREATE TABLE `Exam` (
  `id` int NOT NULL,
  `course_id` int DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Exam`
--

INSERT INTO `Exam` (`id`, `course_id`, `file_path`, `release_date`) VALUES
(1, 1, 'exams/db_security_exam.pdf', '2024-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `Grade`
--

CREATE TABLE `Grade` (
  `id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `grade` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `RolePermissions`
--

CREATE TABLE `RolePermissions` (
  `id` int NOT NULL,
  `role` varchar(50) NOT NULL,
  `permission` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `RolePermissions`
--

INSERT INTO `RolePermissions` (`id`, `role`, `permission`) VALUES
(1, 'student', 'view_course_catalogue'),
(2, 'student', 'enroll_in_course'),
(3, 'student', 'view_enrolled_courses'),
(4, 'student', 'submit_assignment'),
(5, 'student', 'view_grades'),
(6, 'professor', 'view_course_catalogue'),
(7, 'professor', 'create_course'),
(8, 'professor', 'manage_own_courses'),
(9, 'professor', 'view_students_in_course'),
(10, 'professor', 'grade_assignments'),
(11, 'secretary', 'manage_courses'),
(12, 'secretary', 'manage_students'),
(13, 'secretary', 'view_audit_logs'),
(14, 'admin', 'manage_all_users'),
(15, 'admin', 'manage_audit_logs'),
(16, 'admin', 'oversee_system');

-- --------------------------------------------------------

--
-- Table structure for table `Slide`
--

CREATE TABLE `Slide` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `professor_id` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `details` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Slide`
--

INSERT INTO `Slide` (`id`, `course_id`, `professor_id`, `file_path`, `details`) VALUES
(1, 33, 9, 'uploads/673a883e92816_5G Challenges - Google Docs.pdf', 'slide1'),
(2, 1, 2, 'uploads/673df063de546_Screenshot from 2024-11-20 11-07-48.png', 'new 4561871'),
(3, 33, 9, 'uploads/673df513f06ad_2-cha.png', 'slide2'),
(4, 33, 9, 'uploads/673df541c7ef6_2-cha.png', 'slide2'),
(12, 1, 2, 'uploads/673f33cde0087_2-cha.png', 'new123'),
(13, 1, 2, 'uploads/673f3cbf79e46_1-spark.png', 'new3'),
(14, 1, 2, 'uploads/673f3ccd1bd8a_1-spark.png', 'new4'),
(15, 1, 2, 'uploads/673f43489e543_2-spark.png', 'new4'),
(16, 1, 2, 'uploads/673f436848b1e_1-spark.png', 'new5'),
(18, 1, 2, 'uploads/673f43c0f2532_2-cha.png', 'new45'),
(21, 1, 2, 'uploads/673f4e8b05851_1-spark.png', 'lecture 1\r\nall the details are there');

-- --------------------------------------------------------

--
-- Table structure for table `Submission`
--

CREATE TABLE `Submission` (
  `id` int NOT NULL,
  `assignment_id` int DEFAULT NULL,
  `student_id` int DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `submission_date` datetime DEFAULT NULL,
  `status` enum('pending','graded','late') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Submitted_assignments`
--

CREATE TABLE `Submitted_assignments` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `professor_id` int NOT NULL,
  `course_id` int NOT NULL,
  `assignment_id` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submission_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `grade` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Submitted_assignments`
--

INSERT INTO `Submitted_assignments` (`id`, `student_id`, `professor_id`, `course_id`, `assignment_id`, `file_path`, `submission_date`, `grade`) VALUES
(1, 5, 9, 33, 4, 'uploads/assignments/5G Challenges - Google Docs.pdf', '2024-11-18 01:04:27', 60),
(2, 1, 2, 1, 3, 'uploads/assignments/Digial id.pdf', '2024-11-18 01:46:37', 50),
(3, 1, 2, 1, 8, 'uploads/assignments/1-cha.png', '2024-11-20 14:47:31', NULL),
(4, 1, 2, 1, 8, 'uploads/assignments/1-cha.png', '2024-11-20 14:52:52', NULL),
(5, 5, 9, 33, 6, 'uploads/assignments/1-rta.png', '2024-11-20 23:33:24', NULL),
(6, 5, 9, 33, 5, 'uploads/assignments/2-rta.png', '2024-11-20 23:43:04', NULL),
(7, 5, 9, 33, 9, 'uploads/assignments/1-spark.png', '2024-11-20 23:43:16', NULL),
(8, 5, 9, 33, 9, 'uploads/assignments/1-spark.png', '2024-11-20 23:43:37', NULL),
(9, 5, 9, 33, 9, 'uploads/assignments/1-spark.png', '2024-11-20 23:43:45', NULL),
(10, 1, 2, 1, 29, 'uploads/assignments/1-rta.png', '2024-11-21 20:19:14', NULL),
(11, 1, 2, 1, 33, 'uploads/assignments/1-rta.png', '2024-11-22 13:02:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('student','professor','secretary','admin','blocked') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `username`, `password_hash`, `email`, `role`) VALUES
(1, 'student1', '$2a$12$hcNCHJNhMJvNThk9R2khk.wDkJu4ZrU2VwKXVnUX/BPM6jdzQsLOO', 'student1@example.com', 'student'),
(2, 'professor1', '$2a$12$t3uVltAQl3AzVsTKA4QSoORB6e4TT0DiFI4tO0ny1bOLpN2V.zCUG', 'professor1@example.com', 'professor'),
(3, 'secretary1', '$2a$12$XvtxHsj7ySKa7kr5pTb3.u100TaEqmMZSA1hOTB03tCg5fLvXRcLW', 'secretary1@example.com', 'secretary'),
(4, 'admin1', '$2a$12$S5Q3KkbHVz5yrpVz2gViQOMPXUT5GjVBhblRb5fIj7jZq.mnExGAi', 'admin1@example.com', 'admin'),
(5, 'student2', '$2y$10$CDNNV4U4nv7UKfbN1.qzLeJigby3RK0teF/P6WaNTPnMU6PfdWcXO', 'student2@example.com', 'student'),
(6, 'student3', '$2a$12$WK/f280AhdwQxl/iHsgnLO/EjTIV/81YOpJ7iS5QERz.ZalVI5Ar2', 'student3@example.com', 'student'),
(7, 'student4', '$2a$12$Qa6ECCnPyQarhWzoj190CenzefegF9KGnivj7.aFqiX.Zr4H2FuCC', 'student4@example.com', 'student'),
(8, 'student5', '$2a$12$wl6nVgkh3LfD8itF9RxbUepcoBQO95w77PSYxq.aniBuOLeiPRUqC', 'student5@example.com', 'student'),
(9, 'professor2', '$2a$12$iTqk1l5653CunTY47xDuI.lsTp/ttCkwcwYI5xPsejxY9VLCc3NUi', 'professor2@example.com', 'professor'),
(10, 'student7', '$2y$10$KDvov5WWrdGt2CEKGR6CvuHhLMDOufCofSjxlzq1IsT3XX3M0rRVW', 'student7@student.uni.lu', 'student'),
(11, 'professor3', '$2y$10$NEPYr8HjUUuF0sr5Bv0WSe8UWYjwX1/VqLljvgnq6NdYibpfyIdjW', 'professor3@uni.lu', 'professor'),
(13, 'professor5', '$2y$10$SJPMUmKqZc4EJD5m9piKGuIThA8xsMaHY9NBih0Ob7j6PubIYXEmK', 'professor5@uni.lu', 'professor'),
(15, 'student10', '$2y$10$oH5PcuVtqVH5N973LKBKzemPbouc5Z85CvgYUIyyY6D5I.BUo3ATS', 'student10@student.uni.lu', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Assignment`
--
ALTER TABLE `Assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assignment_professor` (`professor_id`),
  ADD KEY `fk_course_assignment` (`course_id`);

--
-- Indexes for table `AuditLog`
--
ALTER TABLE `AuditLog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_auditlog_user` (`user_id`);

--
-- Indexes for table `Course`
--
ALTER TABLE `Course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_professor_id` (`professor_id`);

--
-- Indexes for table `Enrollment`
--
ALTER TABLE `Enrollment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student_id` (`student_id`),
  ADD KEY `fk_course_id` (`course_id`);

--
-- Indexes for table `Exam`
--
ALTER TABLE `Exam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_exam_course` (`course_id`);

--
-- Indexes for table `Grade`
--
ALTER TABLE `Grade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_grade_student` (`student_id`),
  ADD KEY `fk_grade_course` (`course_id`);

--
-- Indexes for table `RolePermissions`
--
ALTER TABLE `RolePermissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Slide`
--
ALTER TABLE `Slide`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `professor_id` (`professor_id`);

--
-- Indexes for table `Submission`
--
ALTER TABLE `Submission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_submission_assignment` (`assignment_id`),
  ADD KEY `fk_submission_student` (`student_id`);

--
-- Indexes for table `Submitted_assignments`
--
ALTER TABLE `Submitted_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `professor_id` (`professor_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `fk_assignment_submitted` (`assignment_id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Assignment`
--
ALTER TABLE `Assignment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `AuditLog`
--
ALTER TABLE `AuditLog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Course`
--
ALTER TABLE `Course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `Enrollment`
--
ALTER TABLE `Enrollment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `Exam`
--
ALTER TABLE `Exam`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Grade`
--
ALTER TABLE `Grade`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `RolePermissions`
--
ALTER TABLE `RolePermissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `Slide`
--
ALTER TABLE `Slide`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `Submission`
--
ALTER TABLE `Submission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Submitted_assignments`
--
ALTER TABLE `Submitted_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Assignment`
--
ALTER TABLE `Assignment`
  ADD CONSTRAINT `Assignment_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Assignment_ibfk_2` FOREIGN KEY (`professor_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_assignment_course` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`),
  ADD CONSTRAINT `fk_assignment_professor` FOREIGN KEY (`professor_id`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `fk_course_assignment` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `AuditLog`
--
ALTER TABLE `AuditLog`
  ADD CONSTRAINT `AuditLog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_auditlog_user` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);

--
-- Constraints for table `Course`
--
ALTER TABLE `Course`
  ADD CONSTRAINT `Course_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `User` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_professor_id` FOREIGN KEY (`professor_id`) REFERENCES `User` (`id`);

--
-- Constraints for table `Enrollment`
--
ALTER TABLE `Enrollment`
  ADD CONSTRAINT `Enrollment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Enrollment_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_course_id` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`),
  ADD CONSTRAINT `fk_enrollment_course` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`),
  ADD CONSTRAINT `fk_enrollment_student` FOREIGN KEY (`student_id`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`student_id`) REFERENCES `User` (`id`);

--
-- Constraints for table `Exam`
--
ALTER TABLE `Exam`
  ADD CONSTRAINT `Exam_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_exam_course` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`);

--
-- Constraints for table `Grade`
--
ALTER TABLE `Grade`
  ADD CONSTRAINT `fk_grade_course` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`),
  ADD CONSTRAINT `fk_grade_student` FOREIGN KEY (`student_id`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `Grade_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Grade_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Slide`
--
ALTER TABLE `Slide`
  ADD CONSTRAINT `Slide_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`),
  ADD CONSTRAINT `Slide_ibfk_2` FOREIGN KEY (`professor_id`) REFERENCES `User` (`id`);

--
-- Constraints for table `Submission`
--
ALTER TABLE `Submission`
  ADD CONSTRAINT `fk_submission_assignment` FOREIGN KEY (`assignment_id`) REFERENCES `Assignment` (`id`),
  ADD CONSTRAINT `fk_submission_student` FOREIGN KEY (`student_id`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `Submission_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `Assignment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Submission_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `User` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Submitted_assignments`
--
ALTER TABLE `Submitted_assignments`
  ADD CONSTRAINT `fk_assignment_submitted` FOREIGN KEY (`assignment_id`) REFERENCES `Assignment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Submitted_assignments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `Submitted_assignments_ibfk_2` FOREIGN KEY (`professor_id`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `Submitted_assignments_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`),
  ADD CONSTRAINT `Submitted_assignments_ibfk_4` FOREIGN KEY (`assignment_id`) REFERENCES `Assignment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
