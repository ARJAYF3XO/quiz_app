<?php
// =========================
// Database Configuration
// =========================
$host = "localhost";
$user = "root";
$password = "";
$database = "quiz_app";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Start Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// =========================
// Helper Functions
// =========================

// Escape output
function e($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Redirect helper
function redirect($page)
{
    header("Location: $page");
    exit();
}
?>