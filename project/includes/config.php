<?php

/**
 * config.php
 * 
 * Configuration file for Grey Lock Password Manager.
 * 
 * Features:
 * - Defines constants for encryption key and method used throughout the application.
 * 
 * Security:
 * - The encryption key should be kept secret and never exposed publicly.
 * - Used for encrypting and decrypting sensitive data (e.g., stored passwords).
 * 
 * Dependencies:
 * - Used by any module that requires encryption/decryption.
 * 
 * Usage:
 * - Include or require this file wherever encryption or decryption is performed.
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

// Encryption key for AES-256-CBC (must be kept secret)
define('ENCRYPTION_KEY', '4/19FFCG7DmHtDXdkreD6xjXQ5u8rYVnJouSB2mZnbI');

// Encryption method for OpenSSL
define('ENCRYPTION_METHOD', 'AES-256-CBC');