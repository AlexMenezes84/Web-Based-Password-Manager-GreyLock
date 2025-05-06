<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php';

    // Encryption configuration
    $encryption_key = bin2hex(random_bytes(32)); 
    $encryption_method = 'AES-256-CBC';

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../public/login.php");
        exit();
    }

    // Sanitize and validate input
    $user_id = $_SESSION['user_id'];
    $service_name = htmlspecialchars(trim($_POST['service_name']));
    $website_link = htmlspecialchars(trim($_POST['website_link']));
    $service_username = htmlspecialchars(trim($_POST['service_username']));
    $service_password = htmlspecialchars(trim($_POST['service_password']));

    // Validate required fields
    if (empty($service_name) || empty($website_link) || empty($service_username) || empty($service_password)) {
        header("Location: ../public/password_vault.php?error=emptyfields");
        exit();
    }

    // Validate URL
    if (!filter_var($website_link, FILTER_VALIDATE_URL)) {
        header("Location: ../public/password_vault.php?error=invalidurl");
        exit();
    }

    // Encrypt the password
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encryption_method));
    $encrypted_password = openssl_encrypt($service_password, $encryption_method, $encryption_key, 0, $iv);
    $encrypted_password = base64_encode($iv . '::' . $encrypted_password);

    try {
        // Insert the new password entry into the database
        $stmt = $pdo->prepare("INSERT INTO passwords (user_id, service_name, website_link, service_username, encrypted_password) VALUES (:user_id, :service_name, :website_link, :service_username, :encrypted_password)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':service_name', $service_name, PDO::PARAM_STR);
        $stmt->bindParam(':website_link', $website_link, PDO::PARAM_STR);
        $stmt->bindParam(':service_username', $service_username, PDO::PARAM_STR);
        $stmt->bindParam(':encrypted_password', $encrypted_password, PDO::PARAM_STR);
        $stmt->execute();

        // Redirect back to the vault page with success message
        header("Location: ../public/password_vault.php?success=added");
        exit();
    } catch (PDOException $e) {
        // Log the error and redirect with an error message
        error_log("Database Error: " . $e->getMessage());
        header("Location: ../public/password_vault.php?error=database");
        exit();
    }
} else {
    // Redirect if the request method is not POST
    header("Location: ../public/password_vault.php");
    exit();
}