<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php'; // Include the database connection

    // Sanitize and validate input
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/signup?error=invalidemail&username=$username&email=$email");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/signup?error=passwordmismatch&username=$username&email=$email");
        exit();
    }

    // Regular expression for strong password
    $strongPasswordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{12,}$/';

    // Validate password strength
    if (!preg_match($strongPasswordRegex, $password)) {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/signup?error=weakpassword&username=$username&email=$email");
        exit();
    }

    try {
        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/signup?error=usernameexists&email=$email");
            exit();
        }

        // Check if the email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/signup?error=emailexists&username=$username");
            exit();
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login?signup=success");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/signup");
    exit();
}