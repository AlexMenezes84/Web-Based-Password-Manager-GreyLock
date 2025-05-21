<?php
/**
 * honeypot_vault.php
 * 
 * Fake password vault page for honeypot users in Grey Lock Password Manager.
 * 
 * Features:
 * - Displays a fake password vault to users flagged as honeypot attackers.
 * - Shows fake password entries from the session.
 * - Prevents access by redirecting non-honeypot users to login.
 * - Styled to match the real vault for realism.
 * 
 * Security:
 * - Only accessible if the session contains 'honeypot_vault'.
 * - All output is sanitized to prevent XSS.
 * 
 * Dependencies:
 * - header.php: Page header and navigation.
 * - assets/css/vault.css: Styling for the vault table.
 * - assets/js/vault.js: Password show/hide functionality.
 * - assets/js/darkmode.js: Dark mode support.
 * 
 * Usage:
 * - Used to mislead attackers or unauthorized users.
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect non-honeypot users to login
if (!isset($_SESSION['honeypot_vault'])) {
    header("Location: login.php");
    exit();
}

// Retrieve fake passwords and display user from session
$passwords = $_SESSION['honeypot_vault'];
$displayUser = $_SESSION['honeypot_user'] ?? 'Attacker';

// Include page header
require '../includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Secure Vault – Grey Lock</title>
  <link rel="stylesheet" href="assets/css/vault.css">
  <script src="assets/js/vault.js" defer></script>
  <script src="assets/js/darkmode.js"></script>
</head>
<body>
  <main>
    <h2>Password Vault</h2>
    <table>
      <thead>
        <tr>
          <th>Service</th><th>Link</th><th>Username</th><th>Password</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($passwords): ?>
          <?php foreach ($passwords as $pw): ?>
          <tr>
            <!-- Display fake service name -->
            <td><?= htmlspecialchars($pw['service_name']) ?></td>
            <!-- Display fake website link -->
            <td><a href="<?= htmlspecialchars($pw['website_link']) ?>" target="_blank"><?= htmlspecialchars($pw['website_link']) ?></a></td>
            <!-- Display fake username -->
            <td><?= htmlspecialchars($pw['service_username']) ?></td>
            <!-- Display fake password with show/hide button -->
            <td>
              <input type="password" value="<?= htmlspecialchars($pw['fake_password']) ?>" readonly>
              <button onclick="togglePassword(this)">Show</button>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="4">No vault data found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
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