<?php
/**
 * Authentication Functions
 * Handles user login, logout, and session management
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current logged-in user
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $query = "SELECT user_id, email, first_name, last_name, role, date_of_birth 
              FROM Users WHERE user_id = ?";
    return fetchOne($query, [$_SESSION['user_id']]);
}

/**
 * Login user
 */
function login($email, $password) {
    $query = "SELECT user_id, email, first_name, last_name, role, password 
              FROM Users WHERE email = ?";
    $user = fetchOne($query, [$email]);
    
    if (!$user) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    // Verify password (plain text comparison)
    if ($password !== $user['password']) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['role'] = $user['role'];
    
    return ['success' => true, 'user' => $user];
}

/**
 * Logout user
 */
function logout() {
    session_unset();
    session_destroy();
    return true;
}

/**
 * Require login - redirect to login page if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /shelter-php/pages/login.php');
        exit();
    }
}

/**
 * Check if user has required role
 */
function hasRole($requiredRoles) {
    if (!isLoggedIn()) {
        return false;
    }
    
    if (!is_array($requiredRoles)) {
        $requiredRoles = [$requiredRoles];
    }
    
    return in_array($_SESSION['role'], $requiredRoles);
}

/**
 * Require specific role - redirect if user doesn't have permission
 */
function requireRole($requiredRoles) {
    requireLogin();
    
    if (!hasRole($requiredRoles)) {
        $_SESSION['error'] = 'You do not have permission to access this page.';
        header('Location: /shelter-php/index.php');
        exit();
    }
}

/**
 * Get user's full name
 */
function getUserFullName() {
    if (!isLoggedIn()) {
        return 'Guest';
    }
    return $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
}

/**
 * Get user's role
 */
function getUserRole() {
    if (!isLoggedIn()) {
        return null;
    }
    return $_SESSION['role'];
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return hasRole('Admin');
}

/**
 * Check if user is staff or admin
 */
function isStaffOrAdmin() {
    return hasRole(['Admin', 'Staff']);
}
?>
