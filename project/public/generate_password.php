<?php
require '../includes/config.php';

function generate_password($length, $use_uppercase, $use_lowercase, $use_numbers, $use_symbols) {
    $command = escapeshellcmd("python ../includes/encryption_utils.py " . escapeshellarg($length) . " " . escapeshellarg($use_uppercase ? 'true' : 'false') . " " . escapeshellarg($use_lowercase ? 'true' : 'false') . " " . escapeshellarg($use_numbers ? 'true' : 'false') . " " . escapeshellarg($use_symbols ? 'true' : 'false'));
    $output = shell_exec($command);
    if ($output === null) {
        error_log("Error: Python script did not return any output.");
        return "Error generating password.";
    }
    return trim($output); // Remove any trailing whitespace
}

// Get user inputs
$length = isset($_GET['length']) ? (int)$_GET['length'] : 16;
$use_uppercase = isset($_GET['uppercase']) && $_GET['uppercase'] === 'true';
$use_lowercase = isset($_GET['lowercase']) && $_GET['lowercase'] === 'true';
$use_numbers = isset($_GET['numbers']) && $_GET['numbers'] === 'true';
$use_symbols = isset($_GET['symbols']) && $_GET['symbols'] === 'true';

error_log("Generating password with length: $length, Uppercase: $use_uppercase, Lowercase: $use_lowercase, Numbers: $use_numbers, Symbols: $use_symbols");

// Generate and return the password
echo generate_password($length, $use_uppercase, $use_lowercase, $use_numbers, $use_symbols);