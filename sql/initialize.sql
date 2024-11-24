-- Create Database
CREATE DATABASE cms_project;

USE cms_project;

-- User Table
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('student', 'professor', 'secretary', 'admin') NOT NULL DEFAULT 'student'
);

-- Course Table
CREATE TABLE Course (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    professor_id INT,
    FOREIGN KEY (professor_id) REFERENCES User(id) ON DELETE SET NULL
);

-- Enrollment Table
CREATE TABLE Enrollment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    FOREIGN KEY (student_id) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Course(id) ON DELETE CASCADE
);

-- Assignment Table
CREATE TABLE Assignment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    description TEXT,
    course_id INT,
    professor_id INT,
    visibility_date DATE,
    FOREIGN KEY (course_id) REFERENCES Course(id) ON DELETE CASCADE,
    FOREIGN KEY (professor_id) REFERENCES User(id) ON DELETE CASCADE
);

-- Submission Table
CREATE TABLE Submission (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT,
    student_id INT,
    file_path VARCHAR(255),
    submission_date DATETIME,
    FOREIGN KEY (assignment_id) REFERENCES Assignment(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES User(id) ON DELETE CASCADE
);

-- Slide Table
CREATE TABLE Slide (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    professor_id INT,
    file_path VARCHAR(255),
    FOREIGN KEY (course_id) REFERENCES Course(id) ON DELETE CASCADE,
    FOREIGN KEY (professor_id) REFERENCES User(id) ON DELETE CASCADE
);

-- Exam Table
CREATE TABLE Exam (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    file_path VARCHAR(255),
    release_date DATE,
    FOREIGN KEY (course_id) REFERENCES Course(id) ON DELETE CASCADE
);

-- Grade Table
CREATE TABLE Grade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    grade DECIMAL(5,2),
    FOREIGN KEY (student_id) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Course(id) ON DELETE CASCADE
);

-- AuditLog Table
CREATE TABLE AuditLog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255),
    timestamp DATETIME,
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE
);

-- Insert Sample Data for Testing
INSERT INTO User (username, password_hash, email, role) VALUES
('student1', '$2y$10$1234567890abcdefghijklmnopqrstuv', 'student1@example.com', 'student'),
('professor1', '$2y$10$1234567890abcdefghijklmnopqrstuv', 'professor1@example.com', 'professor'),
('secretary1', '$2y$10$1234567890abcdefghijklmnopqrstuv', 'secretary1@example.com', 'secretary'),
('admin1', '$2y$10$1234567890abcdefghijklmnopqrstuv', 'admin1@example.com', 'admin');

INSERT INTO Course (name, professor_id) VALUES
('Database Security', 2),
('Web Development', 2);

INSERT INTO Enrollment (student_id, course_id) VALUES
(1, 1),
(1, 2);

INSERT INTO Assignment (title, description, course_id, professor_id, visibility_date) VALUES
('Assignment 1', 'Complete the SQL exercise', 1, 2, '2024-11-20'),
('Assignment 2', 'Build a web form', 2, 2, '2024-11-22');

INSERT INTO Slide (course_id, professor_id, file_path) VALUES
(1, 2, 'slides/db_security.pdf'),
(2, 2, 'slides/web_dev.pdf');

INSERT INTO Exam (course_id, file_path, release_date) VALUES
(1, 'exams/db_security_exam.pdf', '2024-12-01'),
(2, 'exams/web_dev_exam.pdf', '2024-12-05');

