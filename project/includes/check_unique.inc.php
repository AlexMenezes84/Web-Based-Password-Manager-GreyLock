<?php
/**
 * check_unique.inc.php
 * 
 * AJAX endpoint to check uniqueness of username or email for Grey Lock Password Manager.
 * 
 * Features:
 * - Receives GET parameters for 'field' (username or email) and 'value'.
 * - Checks if the value is unique in the users table.
 * - Returns a JSON response indicating uniqueness or error.
 * 
 * Security:
 * - Only allows 'username' or 'email' as valid fields to prevent SQL injection.
 * - Sanitizes the value to prevent XSS.
 * - Uses prepared statements for database queries.
 * 
 * Dependencies:
 * - dbh.inc.php: Database connection (PDO).
 * - users table: For checking existing usernames/emails.
 * 
 * Usage:
 * - Called via AJAX from registration or profile update forms to validate uniqueness.
 *   Example: check_unique.inc.php?field=username&value=testuser
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

if (isset($_GET['field']) && isset($_GET['value'])) {
    require 'dbh.inc.php'; // Include the database connection

    $field = $_GET['field'];
    $value = htmlspecialchars($_GET['value'], ENT_QUOTES, 'UTF-8');

    // Validate the field to prevent SQL injection
    if (!in_array($field, ['username', 'email'])) {
        echo json_encode(['error' => 'Invalid field']);
        exit();
    }
    // Check if the value is unique in the database
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE $field = :value");
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Return JSON response
        echo json_encode(['unique' => $count == 0]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}