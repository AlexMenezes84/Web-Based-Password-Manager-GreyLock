<?php
/**
 * activity_log.inc.php
 * 
 * Provides a function to log user activities for Grey Lock Password Manager.
 * 
 * Features:
 * - Logs user actions (such as login, logout, password changes, etc.) to the user_activity_logs table.
 * - Records user ID, username, activity description, and IP address.
 * 
 * Security:
 * - Uses prepared statements to prevent SQL injection.
 * - Captures the user's IP address for auditing.
 * 
 * Dependencies:
 * - dbh.inc.php: Database connection (PDO) must be passed as $pdo.
 * - user_activity_logs table: Must exist in the database.
 * 
 * Usage:
 * - Call log_user_activity($pdo, $user_id, $username, $activity) whenever you want to record an action.
 *   Example: log_user_activity($pdo, $_SESSION['user_id'], $_SESSION['username'], 'User logged in');
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

/**
 * Logs a user activity to the user_activity_logs table.
 *
 * @param PDO    $pdo      The PDO database connection.
 * @param int    $user_id  The user's ID.
 * @param string $username The user's username.
 * @param string $activity The activity description.
 */
function log_user_activity($pdo, $user_id, $username, $activity) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $stmt = $pdo->prepare("INSERT INTO user_activity_logs (user_id, username, activity, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $username, $activity, $ip]);
}