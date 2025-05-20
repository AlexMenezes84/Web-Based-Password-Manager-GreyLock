<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['honeypot_vault'])) {
    header("Location: login.php");
    exit();
}
$passwords = $_SESSION['honeypot_vault'];
$displayUser = $_SESSION['honeypot_user'] ?? 'Attacker';
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
            <td><?= htmlspecialchars($pw['service_name']) ?></td>
            <td><a href="<?= htmlspecialchars($pw['website_link']) ?>" target="_blank"><?= htmlspecialchars($pw['website_link']) ?></a></td>
            <td><?= htmlspecialchars($pw['service_username']) ?></td>
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
  <footer>&copy; 2025 Grey Lock</footer>
</body>
</html>