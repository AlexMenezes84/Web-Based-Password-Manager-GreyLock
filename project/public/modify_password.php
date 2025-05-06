<?php
session_start();
require '../includes/dbh.inc.php';
require '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Get form data
    $password_id = $_POST['password_id'];
    $service_name = $_POST['service_name'];
    $website_link = $_POST['website_link'];
    $service_username = $_POST['service_username'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($service_name) || empty($website_link) || empty($service_username) || empty($password)) {
        header("Location: password_vault.php?error=emptyfields");
        exit();
    }

    // Encrypt the updated password
    $iv = random_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD));
    $encrypted_password = base64_encode($iv . '::' . openssl_encrypt($password, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv));

    // Update the database
    $sql = "UPDATE passwords SET service_name = ?, website_link = ?, service_username = ?, encrypted_password = ? WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$service_name, $website_link, $service_username, $encrypted_password, $password_id, $_SESSION['user_id']])) {
        header("Location: password_vault.php?success=modified");
        exit();
    } else {
        header("Location: password_vault.php?error=sqlerror");
        exit();
    }
} else {
    header("Location: password_vault.php");
    exit();
}