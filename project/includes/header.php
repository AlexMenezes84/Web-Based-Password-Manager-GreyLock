<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = null;
$is_admin = false;
if (isset($_SESSION['user_id'])) {
    require 'dbh.inc.php';
    $stmt = $pdo->prepare("SELECT username, is_admin FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $user['username'] ?? null;
    $is_admin = !empty($user['is_admin']);
}
?>
<header>
    <img src="assets/logo.png" alt="Grey Lock Logo">
   <?php if ($username): ?>
        <div class="user-info-header">
            <span class="welcome-message">Welcome, <?= htmlspecialchars($username) ?>!</span>
            <a href="profile.php" class="profile-link">My Profile</a>
        </div>
    <?php endif; ?>
    <br>
    <h1>Grey Lock</h1>
    <br>
    
    <nav>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/">Home</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/about">About</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/contact">Contact Us</a>
        <?php if ($is_admin): ?>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/dashboard.php">Admin Dashboard</a>
        <?php endif; ?>
        <?php if ($username): ?>
            <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault">Vault</a>
            <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/logout">Logout</a>
        <?php else: ?>
            <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a>
        <?php endif; ?>
        <div class="darkmode-btn-container">
            <button id="darkMode" title="Dark Mode">🌓</button>
        </div>
    </nav>
</header>