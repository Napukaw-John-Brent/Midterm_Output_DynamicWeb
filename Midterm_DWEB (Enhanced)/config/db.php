<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = new mysqli("localhost", "root", "", "budget_app");
if ($conn->connect_error) {
    die("Database connection failed");
}
$conn->set_charset("utf8mb4");
?>
