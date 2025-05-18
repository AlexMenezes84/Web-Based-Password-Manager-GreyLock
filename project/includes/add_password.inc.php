<?php
session_start();
require 'dbh.inc.php';
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login");
        exit();
    }

    // Get form data
    $user_id = $_SESSION['user_id'];
    $service_name = $_POST['service_name'];
    $website_link = $_POST['website_link'];
    $service_username = $_POST['service_username'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($service_name) || empty($website_link) || empty($service_username) || empty($password)) {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault?error=emptyfields");
        exit();
    }

    // Encrypt the password
    $iv = random_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD)); // Generate a 16-byte IV
    $encrypted_password = base64_encode($iv . '::' . openssl_encrypt($password, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv));

    // Insert into the database
    $sql = "INSERT INTO passwords (user_id, service_name, website_link, service_username, encrypted_password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$user_id, $service_name, $website_link, $service_username, $encrypted_password])) {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault?success=added");
        exit();
    } else {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault?error=sqlerror");
        exit();
    }
} else {
    header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault");
    exit();
}