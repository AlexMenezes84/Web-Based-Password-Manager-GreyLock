<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Forgot Password - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/signup.css"> <!-- Reusing signup.css -->
</head>
<body>
    <header>
        <img src="assets/logo.png" alt="Grey Lock Logo">
        <h1>Forgot Password</h1>
    </header>
    <nav>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/">Home</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/about">About</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/contact">Contact Us</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a>
    </nav>
    <div class="content">
        <h2>Reset Your Password</h2>
        <form action="../includes/forgot_password.inc.php" method="POST">
            <label for="email">Enter your email address:</label>
            <input type="email" id="email" name="email" required>
            <br><br>
            <button type="submit">Send Reset Link</button>
        </form>
        <p>Remembered your password? <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a></p>
    </div>
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>
<?php
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'invalidemail') {
        echo "<p style='color: red;'>Invalid email address.</p>";
    } elseif ($_GET['error'] === 'emailnotfound') {
        echo "<p style='color: red;'>Email not found in our records.</p>";
    } elseif ($_GET['error'] === 'emailfailed') {
        echo "<p style='color: red;'>Failed to send the reset email. Please try again later.</p>";
    }
} elseif (isset($_GET['success']) && $_GET['success'] === 'emailsent') {
    echo "<p style='color: green;'>A password reset link has been sent to your email.</p>";
}
?>
