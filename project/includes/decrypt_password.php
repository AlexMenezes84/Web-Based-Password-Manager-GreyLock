<?php
/**
 * decrypt_password.php
 * 
 * Utility script to decrypt an encrypted password for Grey Lock Password Manager.
 * 
 * Features:
 * - Receives an encrypted password (base64-encoded) via GET parameter.
 * - Decrypts the password using AES-256-CBC and a secure key/IV.
 * - Outputs the decrypted password, sanitized for safe display.
 * 
 * Security:
 * - Uses prepared key from config.php (ENCRYPTION_KEY).
 * - Sanitizes output to prevent XSS.
 * 
 * Dependencies:
 * - config.php: Contains ENCRYPTION_KEY constant.
 * - PHP OpenSSL extension.
 * 
 * Usage:
 * - Called via GET with ?encrypted_password=... (base64-encoded string).
 *   Example: decrypt_password.php?encrypted_password=...
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

require_once 'config.php';

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
// Check if the encrypted password is provided via GET
if (isset($_GET['encrypted_password'])) {
    $encrypted_password = $_GET['encrypted_password'];
    $decrypted_password = decrypt_password($encrypted_password);

    // Ensure the decrypted password is properly sanitized
    echo htmlspecialchars($decrypted_password, ENT_QUOTES, 'UTF-8');
}