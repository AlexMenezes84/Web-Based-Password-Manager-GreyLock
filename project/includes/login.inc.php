<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if not already started
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php'; // Include the database connection
    session_start(); // Start the session

    // Brute force protection: track attempts in session
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }
    if (!isset($_SESSION['last_attempt_time'])) {
        $_SESSION['last_attempt_time'] = time();
    }
    // Reset attempts after 15 minutes of inactivity
    if (time() - $_SESSION['last_attempt_time'] > 900) {
        $_SESSION['login_attempts'] = 0;
    }

   // Get the external/public IP address
    function get_client_ip() {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // HTTP_X_FORWARDED_FOR can contain multiple IPs, take the first one
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            // Trim and return the first non-empty IP
            foreach ($ips as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip; // Return the first public IP
                }
            }
            // If none are public, return the first one anyway
            return trim($ips[0]);
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return 'unknown';
    }

    // Get all forwarded IPs (if any)
    function get_forwarded_ips() {
        return !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
    }

    // Logging function
    function log_login_attempt($pdo, $username, $status) {
        $ip = get_client_ip();
        $forwarded_ips = get_forwarded_ips();
        $stmt = $pdo->prepare("INSERT INTO login_logs (username, status, ip_address, forwarded_ips) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $status, $ip, $forwarded_ips]);
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

    // Brute force threshold
    $honeypot_threshold = 5;
    // Query to fetch user by username (to check admin status before honeypot)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Only trigger honeypot if NOT admin
    $is_admin = ($user && !empty($user['is_admin']));
    if ($_SESSION['login_attempts'] >= $honeypot_threshold) {
        // Fetch real user data
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $vault = [];
        if ($user) {
            $pstmt = $pdo->prepare("SELECT service_name, website_link, service_username FROM passwords WHERE user_id = ?");
            $pstmt->execute([$user['id']]);
            $realVault = $pstmt->fetchAll(PDO::FETCH_ASSOC);

            //  Obfuscate/Salt Data
            foreach ($realVault as $entry) {
                $fakeUser = $entry['service_username'] . rand(100,999);
                $fakePass = bin2hex(random_bytes(6)); // 12-char hex
                $vault[] = [
                    'service_name'     => htmlspecialchars($entry['service_name']),     
                    'website_link'     => htmlspecialchars($entry['website_link']),      
                    'service_username' => htmlspecialchars($fakeUser),                   
                    'fake_password'    => htmlspecialchars($fakePass)  
                ];
            }
        }

        // Store in session
        $_SESSION['honeypot_vault'] = $vault;
        $_SESSION['honeypot_user'] = $username;

         // Set fake login session variables for realism
        $_SESSION['user_id'] = 1; 
        $_SESSION['username'] = $username; 
        $_SESSION['is_admin'] = 0; 

        // Optionally log this event as a honeypot trigger
        log_login_attempt($pdo, $username, 'HONEYPOT_TRIGGERED');

        // Redirect to honeypot vault
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/vault");
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

                // Reset brute force counter on success
                $_SESSION['login_attempts'] = 0;

                log_login_attempt($pdo, $username, 'SUCCESS');
                // Redirect to the dashboard or vault page
                header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault?login=success");
                exit();
            } else {
                // Increment brute force counter on failure
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt_time'] = time();

                log_login_attempt($pdo, $username, 'INVALID_CREDENTIALS');
                header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login?error=invalidcredentials");
                exit();
            }
        } else {
            // Increment brute force counter on failure
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();

            log_login_attempt($pdo, $username, 'INVALID_CREDENTIALS');
            header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login?error=invalidcredentials");
            exit();
        }
    } catch (PDOException $e) {
        log_login_attempt($pdo, $username, 'DB_ERROR');
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login?error=databaseerror");
        exit();
    }
} else {
    header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login");
    exit();
}