<?php

/**
 * generate_password.php
 * 
 * API endpoint for generating secure passwords in Grey Lock Password Manager.
 * 
 * Features:
 * - Receives password generation options via GET parameters.
 * - Calls a Python script to generate a password based on user-selected criteria.
 * - Returns the generated password as plain text.
 * 
 * Security:
 * - Uses escapeshellcmd and escapeshellarg to sanitize shell command inputs.
 * - Logs errors if the Python script fails to return output.
 * 
 * Dependencies:
 * - config.php: Configuration settings.
 * - ../includes/encryption_utils.py: Python script for password generation.
 * 
 * Usage:
 * - Called via AJAX or direct HTTP request from the frontend password generator.
 *   Example: generate_password.php?length=16&uppercase=true&lowercase=true&numbers=true&symbols=true
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

require '../includes/config.php';

/**
 * Generates a password by calling the Python script with the given options.
 *
 * @param int  $length         Desired password length.
 * @param bool $use_uppercase  Include uppercase letters.
 * @param bool $use_lowercase  Include lowercase letters.
 * @param bool $use_numbers    Include numbers.
 * @param bool $use_symbols    Include symbols.
 * @return string              The generated password or error message.
 */
function generate_password($length, $use_uppercase, $use_lowercase, $use_numbers, $use_symbols) {
    // Build and sanitize the shell command
    $command = escapeshellcmd("python ../includes/encryption_utils.py " . 
        escapeshellarg($length) . " " . 
        escapeshellarg($use_uppercase ? 'true' : 'false') . " " . 
        escapeshellarg($use_lowercase ? 'true' : 'false') . " " . 
        escapeshellarg($use_numbers ? 'true' : 'false') . " " . 
        escapeshellarg($use_symbols ? 'true' : 'false'));
    $output = shell_exec($command);
    if ($output === null) {
        error_log("Error: Python script did not return any output.");
        return "Error generating password.";
    }
    return trim($output); // Remove any trailing whitespace
}

// Get user inputs from GET parameters, with defaults
$length = isset($_GET['length']) ? (int)$_GET['length'] : 16;
$use_uppercase = isset($_GET['uppercase']) && $_GET['uppercase'] === 'true';
$use_lowercase = isset($_GET['lowercase']) && $_GET['lowercase'] === 'true';
$use_numbers = isset($_GET['numbers']) && $_GET['numbers'] === 'true';
$use_symbols = isset($_GET['symbols']) && $_GET['symbols'] === 'true';

// Log the generation request for debugging
error_log("Generating password with length: $length, Uppercase: $use_uppercase, Lowercase: $use_lowercase, Numbers: $use_numbers, Symbols: $use_symbols");

// Generate and return the password
echo generate_password($length, $use_uppercase, $use_lowercase, $use_numbers, $use_symbols);