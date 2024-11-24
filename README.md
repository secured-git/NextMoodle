Certainly! Here is the revised README for your project, NextMoodle:

---

# NextMoodle

NextMoodle is a comprehensive educational management platform designed to streamline course enrollment, assignment submission, and grading processes. It ensures robust security and efficient project management, catering to the needs of students, professors, secretaries, and administrators.

## Table of Contents

1. [Features](#features)
2. [Technology Stack](#technology-stack)
3. [Working](#working)
4. [Role-Based Access Control (RBAC)](#role-based-access-control-rbac)
5. [Database Security](#database-security)
6. [Testing](#testing)
7. [Conflict Scenarios](#conflict-scenarios)

## Features

- **User Authentication:** Secure login, registration, and logout functionalities.
- **Course Management:** View course catalogues, enroll in courses, manage courses, and view enrolled courses.
- **Assignment Management:** Submit assignments, view grades, and grade assignments.
- **Audit Logs:** View and manage audit logs for tracking user activities.
- **Project Management:** Efficiently manage projects and perform system-wide actions.
- **Secure Web Applications:** Develop secure and user-friendly web applications.
- **DApp Development:** Build decentralized applications using blockchain technology.

## Technology Stack

- **Frontend:** HTML, CSS, JavaScript, Tailwind CSS
- **Backend:** PHP
- **Database:** MySQL
- **Database Management:** phpMyAdmin

## Working

### User Roles and Permissions

- **Student:**
  - View course catalogue.
  - Enroll in courses.
  - View enrolled courses.
  - Submit assignments.
  - View grades.

- **Professor:**
  - View course catalogue.
  - Create and manage courses.
  - View students in courses.
  - Grade assignments.

- **Secretary:**
  - Manage courses and students.
  - View audit logs.


### Registration Process

If you register with username@student.mit.edu or username@mit.edu email, you will be able to access the platform with your assigned role and permissions. (Student or Professor)

### Two-Factor Authentication (2FA)

2FA is purposefully turned off for testing purposes, allowing students to register with their student email IDs and faculty members with their university IDs.

## Role-Based Access Control (RBAC)

RBAC ensures that users have access only to the resources and actions permitted by their roles. The `RolePermissions` table defines the permissions for each role:

- **Student:** view_course_catalogue, enroll_in_course, view_enrolled_courses, submit_assignment, view_grades
- **Professor:** view_course_catalogue, create_course, manage_own_courses, view_students_in_course, grade_assignments
- **Secretary:** manage_courses, manage_students, view_audit_logs


## Database Security

### Hashing

Passwords are hashed using bcrypt to ensure they are stored securely in the database.

### SQL Injection Prevention

Prepared statements are used to prevent SQL injection attacks.

### Data Integrity

Foreign key constraints and data validation ensure data integrity and consistency.

## Testing

### General Test Cases

1. **Login:**
   - Verify that users can log in with their credentials.
   - Test invalid login attempts (wrong username/password).

2. **Register:**
   - Verify that new users can register.
   - Test registration with invalid data (e.g., duplicate username, weak password).

3. **Logout:**
   - Verify that users can log out successfully.

### User-Specific Test Cases

#### Student

1. **View Course Catalogue:**
   - Verify that students can view the list of available courses.

2. **Enroll in Course:**
   - Verify that students can enroll in a course.
   - Test enrolling in a course that is already full or not available.

3. **View Enrolled Courses:**
   - Verify that students can view the courses they are enrolled in.

4. **Submit Assignment:**
   - Verify that students can submit assignments for their enrolled courses.
   - Test submitting an assignment after the deadline.

5. **View Grades:**
   - Verify that students can view their grades for submitted assignments.

#### Professor

1. **View Course Catalogue:**
   - Verify that professors can view the list of available courses.

2. **Create Course:**
   - Verify that professors can create new courses.
   - Test creating a course with invalid data (e.g., missing required fields).

3. **Manage Own Courses:**
   - Verify that professors can manage (edit, delete) their own courses.
   - Test editing a course with invalid data.

4. **View Students in Course:**
   - Verify that professors can view the list of students enrolled in their courses.

5. **Grade Assignments:**
   - Verify that professors can grade submitted assignments.
   - Test grading an assignment with invalid data (e.g., grade out of range).

#### Secretary

1. **Manage Courses:**
   - Verify that secretaries can manage (edit, delete) all courses.
   - Test editing a course with invalid data.

2. **Manage Students:**
   - Verify that secretaries can manage (edit, delete) student records.
   - Test editing a student record with invalid data.

3. **View Audit Logs:**
   - Verify that secretaries can view audit logs.

#### Admin

1. **Manage All Users:**
   - Verify that admins can manage (edit, delete) all user records.
   - Test editing a user record with invalid data.

2. **Manage Audit Logs:**
   - Verify that admins can view and manage audit logs.

3. **Oversee System:**
   - Verify that admins can perform system-wide actions (e.g., backups, maintenance).

## Conflict Scenarios

1. **Concurrent Access:**
   - Test what happens when multiple users try to enroll in the same course simultaneously.
   - Test what happens when multiple professors try to create or edit the same course simultaneously.

2. **Permission Overlap:**
   - Test what happens when a user has multiple roles (e.g., a professor who is also a student).
   - Verify that the highest permission level is applied correctly.

3. **Data Integrity:**
   - Test what happens when a user tries to submit an assignment for a course they are not enrolled in.
   - Test what happens when a professor tries to grade an assignment for a course they do not manage.

