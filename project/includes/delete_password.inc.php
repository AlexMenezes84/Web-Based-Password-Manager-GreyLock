<?php
/**
 * delete_password.inc.php
 * 
 * Handles deletion of a password entry from the user's vault in Grey Lock Password Manager.
 * 
 * Features:
 * - Receives POST data with the password entry ID to delete.
 * - Deletes the specified password entry for the logged-in user.
 * - Redirects with success or error messages based on the outcome.
 * 
 * Security:
 * - Uses prepared statements to prevent SQL injection.
 * - Ensures only the owner (logged-in user) can delete their own password entries.
 * 
 * Dependencies:
 * - dbh.inc.php: Database connection (PDO).
 * - Session variable: user_id (must be set for authentication).
 * 
 * Usage:
 * - Called via POST from the password vault interface.
 *   Example: password_vault.php (form) -> delete_password.inc.php (handler)
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php';

    $user_id = $_SESSION['user_id'];
    $password_id = htmlspecialchars($_POST['password_id']);

    try {
        // Delete the password entry from the database
        $stmt = $pdo->prepare("DELETE FROM passwords WHERE id = :password_id AND user_id = :user_id");
        $stmt->bindParam(':password_id', $password_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault?success=deleted");
        exit();
    } catch (PDOException $e) {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault?error=deletefailed");
        exit();
    }
} else {
    header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault");
    exit();
}