<?php
// auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Securely regenerate the session ID to prevent fixation attacks.
 */
function regenerateSession()
{
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
}

/**
 * Check if the current user has access based on their role.
 *
 * @param array $allowed_roles List of roles allowed to access the page.
 */
function checkAccess(array $allowed_roles)
{
    regenerateSession();

    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        error_log('Unauthorized access attempt by user with role: ' . ($_SESSION['role'] ?? 'none'));

        header('Location: unauthorized.php');
        exit();
    }
}
