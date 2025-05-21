<?php
/**
 * dbh.inc.php
 * 
 * Database connection handler for Grey Lock Password Manager.
 * 
 * Features:
 * - Establishes a PDO connection to the MySQL database.
 * - Sets error mode to throw exceptions for robust error handling.
 * - Starts a session if not already started (required for authentication and user tracking).
 * 
 * Security:
 * - Uses PDO with exception mode for secure and reliable database operations.
 * 
 * Dependencies:
 * - PHP PDO extension.
 * - MySQL database server.
 * 
 * Usage:
 * - Include this file wherever a database connection ($pdo) is required.
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection parameters
$host = 'localhost';
$dbname = 'greylockdb';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance for MySQL connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection errors gracefully
    die("Database connection failed: " . $e->getMessage());
}