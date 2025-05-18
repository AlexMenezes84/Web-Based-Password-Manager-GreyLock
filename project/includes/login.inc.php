<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php'; // Include the database connection
    session_start(); // Start the session

    // Get the real client IP address
    function get_client_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return 'unknown';
    }

    // Logging function
    function log_login_attempt($pdo, $username, $status) {
        $ip = get_client_ip();
        $stmt = $pdo->prepare("INSERT INTO login_logs (username, status, ip_address) VALUES (?, ?, ?)");
        $stmt->execute([$username, $status, $ip]);
    }

    // Sanitize and validate input
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password']; 
    $remember = isset($_POST['remember']); // Check if "Remember Me" is selected

    if (empty($username) || empty($password)) {
        log_login_attempt($pdo, $username, 'EMPTY_FIELDS');
        header("Location: ../public/login.php?error=emptyfields");
        exit();
    }

    try {
        // Query to fetch user by username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (!empty($user['blocked']) && $user['blocked']) {
                log_login_attempt($pdo, $username, 'BLOCKED_USER');
                header("Location: ../public/login.php?error=blocked");
                exit();
            }
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = !empty($user['is_admin']);
                // Set a cookie if "Remember Me" is checked
                if ($remember) {
                    setcookie('user_id', $user['id'], time() + (30 * 24 * 60 * 60), "/"); // 30 days
                    setcookie('username', $user['username'], time() + (30 * 24 * 60 * 60), "/");
                    setcookie('is_admin', $user['is_admin'], time() + (30 * 24 * 60 * 60), "/");
                }

                log_login_attempt($pdo, $username, 'SUCCESS');
                // Redirect to the dashboard or vault page
                header("Location: ../public/password_vault?login=success");
                exit();
            } else {
                log_login_attempt($pdo, $username, 'INVALID_CREDENTIALS');
                header("Location: ../public/login.php?error=invalidcredentials");
                exit();
            }
        } else {
            log_login_attempt($pdo, $username, 'INVALID_CREDENTIALS');
            header("Location: ../public/login.php?error=invalidcredentials");
            exit();
        }
    } catch (PDOException $e) {
        log_login_attempt($pdo, $username, 'DB_ERROR');
        header("Location: ../public/login.php?error=databaseerror");
        exit();
    } catch (PDOException $e) {
        log_login_attempt($pdo, $username, 'DB_ERROR');
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: ../public/login.php");
    exit();
}