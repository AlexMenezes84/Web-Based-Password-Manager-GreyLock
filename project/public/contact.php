<?php
require '../includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contact - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/contact.css">
    <script src="assets/js/darkmode.js"></script>
</head>
<body>
    <!-- Main Content -->
    <div class="contact-container">
        <h2>Contact Us</h2>
        <form action="submit_contact.php" method="POST">
            <div>
                <label for="name">Name:</label><br>
                <input type="text" id="name" placeholder="Your Name" required>
            </div>
            <div>
                <label for="email">Email:</label><br>
                <input type="email" id="email" placeholder="Your Email" required>
            </div>
            <div>
                <label for="message">Message:</label><br>
                <textarea id="message" placeholder="Enter your message" rows="5" required></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
    <!-- Footer -->
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>