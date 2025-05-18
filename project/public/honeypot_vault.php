<?php
require '../includes/header.php';
require '../includes/dbh.inc.php';
session_start();

if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Try to map their fake username back to a real user (strip trailing digits)
$base  = preg_replace('/\d+$/', '', $_SESSION['username']);
$uStmt = $pdo->prepare("SELECT id, email FROM users WHERE username = ?");
$uStmt->execute([$base]);
$real = $uStmt->fetch(PDO::FETCH_ASSOC);

if ($real) {
    // Pull real vault entries
    $pStmt = $pdo->prepare("
      SELECT service_name, website_link, service_username
        FROM passwords
       WHERE user_id = ?
    ");
    $pStmt->execute([$real['id']]);
    $realPasswords = $pStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $realPasswords = [];
}

// Build fake entries
$passwords = [];
foreach ($realPasswords as $r) {
    $fakeU = $r['service_username'] . rand(100,999);
    $fakeP = bin2hex(random_bytes(6)); // 12‐char hex
    $passwords[] = [
      'service_name'     => htmlspecialchars($r['service_name']),
      'website_link'     => htmlspecialchars($r['website_link']),
      'service_username' => htmlspecialchars($fakeU),
      'fake_password'    => htmlspecialchars($fakeP)
    ];
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Secure Vault – Grey Lock</title>
  <link rel="stylesheet" href="assets/css/vault.css">
  <script src="assets/js/vault.js" defer></script>
</head>
<body>
  <main>
    <h2>Your Password Vault</h2>
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
            <td><?= $pw['service_name'] ?></td>
            <td><a href="<?= $pw['website_link'] ?>" target="_blank">
                 <?= $pw['website_link'] ?></a></td>
            <td><?= $pw['service_username'] ?></td>
            <td>
              <input type="password" value="<?= $pw['fake_password'] ?>" readonly>
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
