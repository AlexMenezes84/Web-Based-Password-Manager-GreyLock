<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php';

    $user_id = $_SESSION['user_id'];
    $password_id = htmlspecialchars($_POST['password_id']);
    $service_name = htmlspecialchars($_POST['service_name']);
    $website_link = htmlspecialchars($_POST['website_link']);
    $service_username = htmlspecialchars($_POST['service_username']);
    $service_password = htmlspecialchars($_POST['service_password']);

    try {
        // Update the password entry in the database
        $stmt = $pdo->prepare("UPDATE passwords SET service_name = :service_name, website_link = :website_link, service_username = :service_username, encrypted_password = :service_password WHERE id = :password_id AND user_id = :user_id");
        $stmt->bindParam(':service_name', $service_name, PDO::PARAM_STR);
        $stmt->bindParam(':website_link', $website_link, PDO::PARAM_STR);
        $stmt->bindParam(':service_username', $service_username, PDO::PARAM_STR);
        $stmt->bindParam(':service_password', $service_password, PDO::PARAM_STR);
        $stmt->bindParam(':password_id', $password_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../public/password_vault.php?success=modified");
        exit();
    } catch (PDOException $e) {
        header("Location: ../public/password_vault.php?error=modifyfailed");
        exit();
    }
} else {
    header("Location: ../public/password_vault.php");
    exit();
}