

# NextMoodle

NextMoodle is a robust and secure educational management platform designed to streamline operations such as course enrollment, assignment submission, grading, and administrative management. It provides a role-based system catering to the specific needs of students, professors, secretaries, and administrators, ensuring a seamless and secure user experience.

## Table of Contents

1. [Overview](#overview)
2. [Features](#features)
3. [Technology Stack](#technology-stack)
4. [Role-Based Functionality](#role-based-functionality)
5. [Security Features](#security-features)
6. [Conflict Management](#conflict-management)

---

## Overview

NextMoodle simplifies educational workflows by offering:
- Secure user authentication and role management.
- Tools for students to enroll in courses and submit assignments.
- Tools for professors to manage courses and grade assignments.
- Administrative tools for secretaries and administrators to manage users, courses, and audit logs.
- Decentralized application (DApp) integration for blockchain-based solutions.

---

## Click here to see [Database Design and Relationships]([https://github.com/secured-git/NextMoodle/uml.png](https://github.com/secured-git/NextMoodle/blob/main/uml.png))

## Features

- **User Authentication:** Provides secure login and registration systems tailored for role-based access.
- **Course Management:** Allows users to create, view, and enroll in courses.
- **Assignment Management:** Streamlines assignment submissions, grading, and feedback.
- **Audit Logs:** Tracks and records user activities for security and operational insights.

---

## Technology Stack

- **Frontend:** HTML, CSS, JavaScript, Tailwind CSS
- **Backend:** PHP
- **Database:** MySQL
- **Database Management:** phpMyAdmin

---

## Role-Based Functionality

### **Student Workflow**
1. **View Courses:**
   - Students can browse the available course catalog, exploring detailed course descriptions and requirements.
2. **Enroll in Courses:**
   - Enrollments are processed in real-time, ensuring data integrity and avoiding overbooking.
3. **Submit Assignments:**
   - Students upload assignments directly through a secure portal, categorized by their enrolled courses.
4. **View Grades:**
   - Grades and feedback are accessible immediately after submission evaluations by professors.

### **Professor Workflow**
1. **Course Creation and Management:**
   - Professors create and manage courses, defining course details and schedules.
2. **View and Manage Students:**
   - A list of enrolled students is displayed for each course, along with their progress and submissions.
3. **Grade Assignments:**
   - Submitted assignments are evaluated, with grades and comments being updated in real time.

### **Secretary Workflow**
1. **Manage Courses and Students:**
   - Secretaries oversee course operations, ensuring student records and courses are up to date.
2. **Audit Logs:**
   - All user actions are recorded and accessible for compliance and review purposes.

### **Admin Workflow**
1. **System Oversight:**
   - Administrators have the authority to manage users, courses, and logs at a system-wide level.
2. **Maintenance and Backups:**
   - System health is maintained through backup and recovery tools.

---

## Security Features

### **Password Hashing**
- All passwords are stored securely using bcrypt hashing.

### **SQL Injection Prevention**
- Prepared statements are utilized to prevent unauthorized database access.

### **Data Integrity**
- Foreign key constraints and input validations enforce consistency and accuracy across the platform.

---

## Working

### **Registration and Authentication**
- Registration is role-specific, determined by email domains such as `student.mit.edu` for students and `mit.edu` for professors.
- Two-Factor Authentication (2FA) is temporarily disabled during testing to simplify access.

### **Course and Assignment Management**
- Students can view and enroll in courses, ensuring they meet prerequisites.
- Assignments can be uploaded and are accessible to professors for grading.
- Professors manage courses, view student lists, and provide feedback on submitted work.

### **Audit and Conflict Resolution**
- Actions such as enrollment, submission, and grading are recorded in audit logs.
- Role-Based Access Control (RBAC) ensures users operate strictly within their permissions.

---

## Conflict Management

1. **Concurrent Access:**
   - The system prevents over-enrollment by dynamically checking course capacity during enrollment actions.
   - Simultaneous course updates by multiple users are handled through database locks.

2. **Role Conflicts:**
   - Users with overlapping roles (e.g., a professor who is also a student) are assigned permissions dynamically, prioritizing higher-level privileges.

3. **Data Validation:**
   - Students cannot submit assignments for courses they are not enrolled in.
   - Professors cannot grade assignments for courses they do not manage.

