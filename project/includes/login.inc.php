<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php'; // Include the database connection
    session_start(); // Start the session

    // Sanitize and validate input
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password']; // Do not sanitize password as it needs to match the hash
    $remember = isset($_POST['remember']); // Check if "Remember Me" is selected

    if (empty($username) || empty($password)) {
        header("Location: ../public/login.php?error=emptyfields");
        exit();
    }

    try {
        // Query to fetch user by username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Set a cookie if "Remember Me" is checked
            if ($remember) {
                setcookie('user_id', $user['id'], time() + (30 * 24 * 60 * 60), "/"); // 30 days
                setcookie('username', $user['username'], time() + (30 * 24 * 60 * 60), "/");
            }

            // Redirect to the dashboard or vault page
            header("Location: ../public/vault.php?login=success");
            exit();
        } else {
            // Invalid credentials
            header("Location: ../public/login.php?error=invalidcredentials");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: ../public/login.php");
    exit();
}
