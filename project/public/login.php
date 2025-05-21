<?php

/**
 * login.php
 * 
 * User login page for Grey Lock Password Manager.
 * 
 * Features:
 * - Presents a login form for users.
 * - Supports "Remember Me" functionality.
 * - Displays error messages for invalid login attempts or blocked accounts.
 * - Provides links to registration and password reset.
 * 
 * Security:
 * - Requires both username and password.
 * - Sanitizes all user input for display.
 * 
 * Dependencies:
 * - header.php: Page header and navigation.
 * - login.inc.php: Handles authentication logic.
 * 
 * Usage:
 * - Accessed by users to log in to their account.
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

require '../includes/header.php'; // Include the site header
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
    <script src="assets/js/login.js"></script>
    <script src="assets/js/darkmode.js"></script>
</head>
<body>
    <div class="login-container">
        <h2>Login Page</h2>
        <!-- Login Form -->
        <form action="../includes/login.inc.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br><br>
            <div class="form-checkbox">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>
            <?php
                // Display error messages based on query parameter
                if (isset($_GET['error'])) {
                    if ($_GET['error'] === 'emptyfields') {
                        echo "<p style='color: red;'>Please fill in all fields.</p>";
                    } elseif ($_GET['error'] === 'invalidcredentials') {
                        echo "<p style='color: red;'>Invalid username or password.</p>";
                    } elseif ($_GET['error'] === 'blocked') {
                        echo "<p style='color: red;'>Your account has been blocked. Please contact support.</p>";
                    } else {
                        echo "<p style='color: red;'>An unknown error occurred. Please try again.</p>";
                    }
                }
            ?>
            <br><br>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/signup">Sign Up</a></p>
            <p><a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/forgot_password">Forgot Password?</a></p>
        </form>
    </div>
     <!-- Footer -->
    <footer class="footer">
        &copy; 2025 Grey Lock &mdash; Secure your digital life.<br>
        <a href="about">About</a> &nbsp;|&nbsp;
        <a href="contact">Contact</a>
    </footer>
</body>
</html>