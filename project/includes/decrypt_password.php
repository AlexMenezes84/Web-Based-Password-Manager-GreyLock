<?php
require_once 'config.php';

// Function to decrypt the password
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