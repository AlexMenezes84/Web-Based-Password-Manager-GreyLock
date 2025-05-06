<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = null;
if (isset($_SESSION['user_id'])) {
    require 'dbh.inc.php';
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $user['username'] ?? null;
}
?>
<header>
    <img src="assets/logo.png" alt="Grey Lock Logo">
    <?php if ($username): ?>
        <span class="welcome-message">Welcome, <?= htmlspecialchars($username) ?>!</span>
    <?php endif; ?>
    <h1>Grey Lock</h1>
    <nav>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/">Home</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/about">About</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/contact">Contact Us</a>
        <?php if ($username): ?>
            <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/password_vault">Vault</a>
            <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/logout">Logout</a>
        <?php else: ?>
            <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a>
        <?php endif; ?>
    </nav>
</header>