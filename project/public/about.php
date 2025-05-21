<?php
/**
 * about.php
 * 
 * About page for Grey Lock Password Manager (final year project, BSc Computer Science, DMU).
 * 
 * Features:
 * - Presents project overview, mission, and key features.
 * - Introduces the developer and acknowledges contributors.
 * - Responsive and visually appealing design.
 * - Supports dark mode.
 * 
 * Security:
 * - No sensitive data is processed on this page.
 * 
 * Dependencies:
 * - header.php: Page header and navigation.
 * - assets/css/about.css: About page styling.
 * - assets/js/darkmode.js: Dark mode toggle support.
 * 
 * Usage:
 * - Accessed by users to learn about the project and its developer.
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
    <title>About - Grey Lock Password Manager</title>
    <link rel="stylesheet" type="text/css" href="assets/CSS/about.css">
    <script src="assets/js/darkmode.js"></script>
</head>
<body>
    <div class="about-container">
        <div class="about-title">About Grey Lock Password Manager</div>
        <div class="about-desc">
            <strong>Grey Lock</strong> is a secure, user-friendly password manager developed as a final year project for the BSc Computer Science degree at De Montfort University (DMU).
        </div>
        <div class="about-section-title">Project Mission</div>
        <div class="about-desc">
            The mission of Grey Lock is to empower users to protect their digital identities by providing a robust, easy-to-use, and privacy-focused password management solution. The project demonstrates advanced web security, encryption, and usability principles.
        </div>
        <div class="about-section-title">Key Features</div>
        <ul class="about-list">
            <li>End-to-end encryption for all stored passwords</li>
            <li>Strong password generator and one-click autofill</li>
            <li>Zero-knowledge architecture: only you can access your data</li>
            <li>Multi-device access and responsive design</li>
            <li>Honeypot vault for enhanced security research</li>
            <li>Dark mode and accessibility support</li>
        </ul>
        <div class="about-section-title">About the Developer</div>
        <div class="about-profile">
            <img src="assets/alex.jpg" alt="Developer Photo">
            <div class="about-profile-details">
                <strong>Alexandre De Menezes</strong><br>
                BSc (Hons) Computer Science, De Montfort University<br>
                Student ID: P2724348<br>
                <br>
                Alexandre is passionate about cybersecurity, software engineering, and building solutions that make technology safer and more accessible for everyone. Grey Lock is the culmination of his studies and practical experience at DMU.
            </div>
        </div>
        <div class="about-section-title">Acknowledgements</div>
        <div class="about-desc">
            Special thanks to the DMU Computer Science faculty and project supervisors for their support and guidance throughout the development of Grey Lock.
        </div>
        <div class="about-section-title">Contact &amp; Further Information</div>
        <div class="about-desc">
            For questions, feedback, or collaboration opportunities, please use the <a href="contact" style="color:#2d8cff;">contact page</a>.
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer">
        &copy; 2025 Grey Lock &mdash; Secure your digital life.<br>
        <a href="about">About</a> &nbsp;|&nbsp;
        <a href="contact">Contact</a>
    </footer>
</body>
</html>