<?php
/**
 * Atlantis Asia Blog - Database Configuration
 * 
 * Configure your database connection settings here
 */

// Enable mysqli error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('DB_NAME', 'atlantis_blog');

// Create database connection
function getDBConnection()
{
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Set charset to utf8mb4
        $conn->set_charset("utf8mb4");

        return $conn;
    } catch (Exception $e) {
        // Log error server-side (in production, log to file)
        error_log("Database connection error: " . $e->getMessage());
        throw new Exception("Database connection failed. Please try again later.");
    }
}

// Configure secure session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Set secure flag if using HTTPS
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF Token Generation and Validation
function generateCSRFToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>