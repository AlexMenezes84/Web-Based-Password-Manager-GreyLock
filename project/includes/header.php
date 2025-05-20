<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_honeypot = !empty($_SESSION['honeypot_mode']);
$displayUser = $is_honeypot ? ($_SESSION['honeypot_user'] ?? null) : ($_SESSION['username'] ?? null);
$is_admin = !$is_honeypot && !empty($_SESSION['is_admin']);

if (!$is_honeypot && isset($_SESSION['user_id'])) {
    require 'dbh.inc.php';
    $stmt = $pdo->prepare("SELECT username, is_admin FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $displayUser = $user['username'] ?? $displayUser;
    $is_admin = !empty($user['is_admin']);
}
?>
<header>
    <img src="assets/logo.png" alt="Grey Lock Logo">
   <?php if ($displayUser): ?>
        <div class="user-info-header">
            <span class="welcome-message">Welcome, <?= htmlspecialchars($displayUser) ?>!</span>
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
        <?php if ($displayUser): ?>
            <?php if ($is_honeypot): ?>
                <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/honeypot_vault.php">Vault</a>
            <?php else: ?>
                <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault.php">Vault</a>
            <?php endif; ?>
            <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/logout.php">Logout</a>
        <?php else: ?>
            <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login.php">Login</a>
        <?php endif; ?>
        <div class="darkmode-btn-container">
            <button id="darkMode" title="Dark Mode">🌓</button>
        </div>
    </nav>
</header>