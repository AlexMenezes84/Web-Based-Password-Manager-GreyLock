<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php';

    $user_id = $_SESSION['user_id'];
    $password_id = htmlspecialchars($_POST['password_id']);

    try {
        // Delete the password entry from the database
        $stmt = $pdo->prepare("DELETE FROM passwords WHERE id = :password_id AND user_id = :user_id");
        $stmt->bindParam(':password_id', $password_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../public/password_vault.php?success=deleted");
        exit();
    } catch (PDOException $e) {
        header("Location: ../public/password_vault.php?error=deletefailed");
        exit();
    }
} else {
    header("Location: ../public/password_vault.php");
    exit();
}