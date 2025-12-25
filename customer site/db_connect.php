/**
 * Database Connection Script
 * Uses PDO for secure MySQL connection with error handling.
 */

// Database configuration
$host = 'localhost';
$dbname = 'car_rental'; // Assuming database name from car_rental_full.sql
$user = 'root';
$pass = ''; // Default XAMPP MySQL password

try {
    // Create PDO instance with options for security
    // Use environment variables or a secure method to store credentials
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // Log error and show generic message (don't expose details in production)
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection error. Please try again later.");
}

<?php
// Database Connection Script
$host = 'localhost';
$dbname = 'car_rental'; // Matches car_rental_full.sql
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection error. Please try again later.");
}
