<?php
/**
 * forgot_password.php
 * 
 * Password reset request page for Grey Lock Password Manager.
 * 
 * Features:
 * - Presents a form for users to request a password reset link via email.
 * - Displays error and success messages based on the reset process outcome.
 * - Provides navigation links to login, about, and contact pages.
 * 
 * Security:
 * - Requires a valid email address.
 * - Does not reveal whether an email is registered for privacy.
 * 
 * Dependencies:
 * - header.php: Page header and navigation.
 * - assets/css/forgot_password.css: Styling for the forgot password page.
 * - assets/js/darkmode.js: Dark mode support.
 * - ../includes/forgot_password.inc.php: Handles the reset logic and email sending.
 * 
 * Usage:
 * - Accessed by users who have forgotten their password.
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

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
        <!-- Password Reset Form -->
        <form action="../includes/forgot_password.inc.php" method="POST">
            <label for="email">Enter your email address:</label>
            <input type="email" id="email" name="email" required>
            <br><br>
            <button type="submit">Send Reset Link</button>
            <p>Remembered your password? <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a></p>
        </form>
        <!-- Display error or success messages -->
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
    </div>
    <!-- Footer -->
    <footer class="footer">
        &copy; 2025 Grey Lock &mdash; Secure your digital life.<br>
        <a href="about">About</a> &nbsp;|&nbsp;
        <a href="contact">Contact</a>
    </footer>
</body>
</html>