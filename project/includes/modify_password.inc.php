<?php
/**
 * modify_password.inc.php
 * 
 * Handles password modification for stored credentials in Grey Lock Password Manager.
 * 
 * Features:
 * - Receives POST data to update an existing password entry.
 * - Encrypts the new password using AES-256-CBC before storing.
 * - Updates service name, website link, username, and encrypted password in the database.
 * - Redirects with success or error messages based on the outcome.
 * 
 * Security:
 * - Uses prepared statements to prevent SQL injection.
 * - Encrypts passwords with a secure key and IV.
 * 
 * Dependencies:
 * - config.php: Contains ENCRYPTION_KEY constant.
 * - dbh.inc.php: Database connection (PDO).
 * 
 * Usage:
 * - Called via POST from the password modification form in the vault.
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

require '../includes/config.php';
require '../includes/dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_id = $_POST['password_id'];
    $service_name = $_POST['service_name'];
    $website_link = $_POST['website_link'];
    $service_username = $_POST['service_username'];
    $password = $_POST['password'];

    // Encrypt the password before saving
    $encryption_key = ENCRYPTION_KEY;
    $iv_length = openssl_cipher_iv_length('AES-256-CBC');
    $iv = random_bytes($iv_length);
    $encrypted_password = base64_encode($iv . openssl_encrypt($password, 'AES-256-CBC', $encryption_key, 0, $iv));

    // Update the password details in the database
    $stmt = $pdo->prepare("UPDATE passwords SET service_name = :service_name, website_link = :website_link, service_username = :service_username, encrypted_password = :encrypted_password WHERE id = :id");
    $stmt->bindParam(':service_name', $service_name);
    $stmt->bindParam(':website_link', $website_link);
    $stmt->bindParam(':service_username', $service_username);
    $stmt->bindParam(':encrypted_password', $encrypted_password);
    $stmt->bindParam(':id', $password_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault?success=Password updated successfully");
        exit();
    } else {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault?error=Failed to update password");
        exit();
    }
}

/**
 * Decrypts an encrypted password using AES-256-CBC.
 * 
 * @param string $encrypted_password The base64-encoded encrypted password.
 * @return string|false The decrypted password, or false on failure.
 */
function decrypt_password($encrypted_password) {
    $encryption_key = ENCRYPTION_KEY;

    // Decode the encrypted password (assuming it's base64 encoded)
    $encrypted_data = base64_decode($encrypted_password);

    // Extract the initialization vector and encrypted data
    $iv_length = openssl_cipher_iv_length('AES-256-CBC');
    $iv = substr($encrypted_data, 0, $iv_length);
    $encrypted_text = substr($encrypted_data, $iv_length);

    // Decrypt the password
    return openssl_decrypt($encrypted_text, 'AES-256-CBC', $encryption_key, 0, $iv);
}