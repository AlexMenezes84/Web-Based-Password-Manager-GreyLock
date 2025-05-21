<?php
/**
 * homepage.php
 * 
 * Homepage for Grey Lock Password Manager.
 * 
 * Features:
 * - Hero section with app introduction and call-to-action buttons.
 * - Features section highlighting key benefits.
 * - Responsive and visually appealing design.
 * - Footer with navigation links.
 * 
 * Security:
 * - No sensitive data is processed on this page.
 * 
 * Dependencies:
 * - header.php: Page header and navigation.
 * - assets/css/home.css: Homepage styling.
 * - assets/js/darkmode.js: Dark mode toggle support.
 * 
 * Usage:
 * - This is the main landing page for new and returning users.
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

require '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Grey Lock Password Manager</title>
    <link rel="stylesheet" type="text/css" href="assets/CSS/home.css">
    <script src="assets/js/darkmode.js"></script>
</head>
<body>
    <!-- 
        Home Section: 
        - App title, subtitle, and call-to-action buttons for signup and login.
    -->
    <section class="home">
        <div class="home-text">
            <div class="home-title">Grey Lock Password Manager</div>
            <div class="home-subtitle">
                Securely store, manage, and generate strong passwords.<br>
                Your digital life, protected by military-grade encryption.
            </div>
            <a href="signup" class="cta-btn">Get Started</a>
            <a href="login" class="cta-btn" style="background:#fff; color:#888; border:1px solid rgb(89, 89, 90);">Login</a>
        </div>
    </section>

    <!-- 
        Features Section: 
        - Highlights the main features of the password manager.
    -->
    <section class="features-section">
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <div class="feature-title">End-to-End Encryption</div>
            <div class="feature-desc">
                All your passwords are encrypted with your master password. Only you can access your data.
            </div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🛡️</div>
            <div class="feature-title">Zero-Knowledge Security</div>
            <div class="feature-desc">
                We never see your master password. Your secrets stay yours, always.
            </div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔑</div>
            <div class="feature-title">Password Generator</div>
            <div class="feature-desc">
                Create strong, unique passwords for every account with our built-in generator.
            </div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📱</div>
            <div class="feature-title">Multi-Device Access</div>
            <div class="feature-desc">
                Access your vault securely from any device, anywhere, anytime.
            </div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🌙</div>
            <div class="feature-title">Light &amp; Dark Mode</div>
            <div class="feature-desc">
                Enjoy a beautiful, eye-friendly interface day or night.
            </div>
        </div>
    </section>

    <!-- 
        Footer: 
        - Copyright and navigation links.
    -->
    <footer class="footer">
        &copy; 2025 Grey Lock &mdash; Secure your digital life.<br>
        <a href="about">About</a> &nbsp;|&nbsp;
        <a href="contact">Contact</a>
    </footer>
</body>
</html>