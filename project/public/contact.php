<?php
require '../includes/header.php';
require '../includes/dbh.inc.php'; 

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if ($name && $email && $message) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$name, $email, $message])) {
            $success = "Your message has been sent!";
        } else {
            $error = "There was an error sending your message. Please try again.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
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
        <form action="contact.php" method="POST">
            <div>
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" placeholder="Your Name" required>
            </div>
            <div>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" placeholder="Your Email" required>
            </div>
            <div>
                <label for="message">Message:</label><br>
                <textarea id="message" name="message" placeholder="Enter your message" rows="5" required></textarea>
            </div>
            <?php if ($success): ?>
                <div class="success-message"><?= htmlspecialchars($success) ?></div>
            <?php elseif ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <button type="submit" name="submit">Send Message</button>
        </form>
    </div>
    <!-- Footer -->
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>