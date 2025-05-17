<?php
require '../includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Forgot Password - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/forgot_password.css">
    <script src="assets/js/darkmode.js"></script>
</head>
<body>
    <div class="forgot-container">
        <h2>Reset Your Password</h2>
        <form action="../includes/forgot_password.inc.php" method="POST">
            <label for="email">Enter your email address:</label>
            <input type="email" id="email" name="email" required>
            <br><br>
            <button type="submit">Send Reset Link</button>
            <p>Remembered your password? <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a></p>

        </form>
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
