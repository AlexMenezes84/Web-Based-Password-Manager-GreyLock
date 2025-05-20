<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php';

    // Brute force protection
    if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
    if (!isset($_SESSION['last_attempt_time'])) $_SESSION['last_attempt_time'] = time();
    if (time() - $_SESSION['last_attempt_time'] > 900) $_SESSION['login_attempts'] = 0;

    // IP helpers
    function get_client_ip() {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($ips as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
            return trim($ips[0]);
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return 'unknown';
    }
    function get_forwarded_ips() {
        return !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
    }
    function log_login_attempt($pdo, $username, $status) {
        $ip = get_client_ip();
        $forwarded_ips = get_forwarded_ips();
        $stmt = $pdo->prepare("INSERT INTO login_logs (username, status, ip_address, forwarded_ips) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $status, $ip, $forwarded_ips]);
    }

    // Sanitize and validate input
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($password)) {
        log_login_attempt($pdo, $username, 'EMPTY_FIELDS');
        header("Location: ../public/login.php?error=emptyfields");
        exit();
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Honeypot threshold
    $honeypot_threshold = 5;

    // Only trigger honeypot if user exists, is not admin, and too many failed attempts
    if ($user && !$user['is_admin'] && $_SESSION['login_attempts'] >= $honeypot_threshold) {
        // Clear all session data
        session_unset();

        // Fetch only this user's data for the fake vault
        $vault = [];
        $pstmt = $pdo->prepare("SELECT service_name, website_link, service_username FROM passwords WHERE user_id = ?");
        $pstmt->execute([$user['id']]);
        $realVault = $pstmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($realVault as $entry) {
            $fakeUser = $entry['service_username'] . rand(100,999);
            $fakePass = bin2hex(random_bytes(6));
            $vault[] = [
                'service_name'     => htmlspecialchars($entry['service_name']),
                'website_link'     => htmlspecialchars($entry['website_link']),
                'service_username' => htmlspecialchars($fakeUser),
                'fake_password'    => htmlspecialchars($fakePass)
            ];
        }

        // Set ONLY honeypot-specific session variables
        $_SESSION['honeypot_vault'] = $vault;
        $_SESSION['honeypot_user'] = $username;
        $_SESSION['honeypot_mode'] = true;

        log_login_attempt($pdo, $username, 'HONEYPOT_TRIGGERED');
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/honeypot_vault.php");
        exit();
    }

    try {
        // Query to fetch user by username (again for fresh $user)
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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = !empty($user['is_admin']);
                if ($remember) {
                    setcookie('user_id', $user['id'], time() + (30 * 24 * 60 * 60), "/");
                    setcookie('username', $user['username'], time() + (30 * 24 * 60 * 60), "/");
                    setcookie('is_admin', $user['is_admin'], time() + (30 * 24 * 60 * 60), "/");
                }
                $_SESSION['login_attempts'] = 0;
                log_login_attempt($pdo, $username, 'SUCCESS');
                header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault.php?login=success");
                exit();
            } else {
                // Password is incorrect, username exists: show error
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt_time'] = time();
                log_login_attempt($pdo, $username, 'INVALID_CREDENTIALS');
                header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login.php?error=invalidcredentials");
                exit();
            }
        } else {
            // Username does not exist: show error
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            log_login_attempt($pdo, $username, 'INVALID_CREDENTIALS');
            header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login.php?error=invalidcredentials");
            exit();
        }
    } catch (PDOException $e) {
        log_login_attempt($pdo, $username, 'DB_ERROR');
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login.php?error=databaseerror");
        exit();
    }
} else {
    header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login.php");
    exit();
}