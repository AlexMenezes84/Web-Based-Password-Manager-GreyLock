<?php
session_start();
require 'config.php';
require 'dbh.inc.php';
require_once 'decrypt_password.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../public/login.php");
        exit();
    }

    // Get form data
    $password_id = $_POST['password_id'];
    $service_name = $_POST['service_name'];
    $website_link = $_POST['website_link'];
    $service_username = $_POST['service_username'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($service_name) || empty($website_link) || empty($service_username)) {
        header("Location: ../public/password_vault.php?error=emptyfields");
        exit();
    }

    // Initialize the encrypted password variable
    $encrypted_password = null;

    // Check if the password field is provided
    if (!empty($password)) {
        // Encrypt the updated password
        $encryption_key = ENCRYPTION_KEY;
        $iv_length = openssl_cipher_iv_length('AES-256-CBC');
        $iv = random_bytes($iv_length);
        $encrypted_password = base64_encode($iv . openssl_encrypt($password, 'AES-256-CBC', $encryption_key, 0, $iv));
    }

    // Prepare the SQL query
    $sql = "UPDATE passwords SET service_name = :service_name, website_link = :website_link, service_username = :service_username";
    if (!empty($encrypted_password)) {
        $sql .= ", encrypted_password = :encrypted_password";
    }
    $sql .= " WHERE id = :id AND user_id = :user_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':service_name', $service_name);
    $stmt->bindParam(':website_link', $website_link);
    $stmt->bindParam(':service_username', $service_username);
    $stmt->bindParam(':id', $password_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    // Bind the encrypted password only if it is provided
    if (!empty($encrypted_password)) {
        $stmt->bindParam(':encrypted_password', $encrypted_password);
    }

    // Execute the query
    if ($stmt->execute()) {
        header("Location: ../public/password_vault.php?success=Password updated successfully");
        exit();
    } else {
        header("Location: ../public/password_vault.php?error=Failed to update password");
        exit();
    }
} else {
    header("Location: ../public/password_vault.php");
    exit();
}