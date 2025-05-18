<?php
session_start();
require '../includes/dbh.inc.php';
require '../includes/header.php';

// Simple admin check (replace with your own logic)
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login");
    exit();
}

// Handle bulk actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_ids'])) {
    $user_ids = $_POST['user_ids'];
    if (isset($_POST['reset_password'])) {
        $new_password = password_hash('Default123!', PASSWORD_DEFAULT);
        $in = str_repeat('?,', count($user_ids) - 1) . '?';
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id IN ($in)");
        foreach ($user_ids as $id) {
            $stmt->execute([$new_password, $id]);
        }
        $message = "Selected users' passwords have been reset to Default123!";
    }
    if (isset($_POST['block'])) {
        $in = str_repeat('?,', count($user_ids) - 1) . '?';
        $stmt = $pdo->prepare("UPDATE users SET blocked = 1 WHERE id IN ($in)");
        $stmt->execute($user_ids);
        $message = "Selected users have been blocked.";
    }
    if (isset($_POST['unblock'])) {
        $in = str_repeat('?,', count($user_ids) - 1) . '?';
        $stmt = $pdo->prepare("UPDATE users SET blocked = 0 WHERE id IN ($in)");
        $stmt->execute($user_ids);
        $message = "Selected users have been unblocked.";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_admin']) && isset($_POST['user_ids'])) {
    $user_ids = $_POST['user_ids'];
    $set_admin = ($_POST['set_admin'] === '1') ? 1 : 0;
    $in = str_repeat('?,', count($user_ids) - 1) . '?';
    $stmt = $pdo->prepare("UPDATE users SET is_admin = ? WHERE id IN ($in)");
    foreach ($user_ids as $id) {
        $stmt->execute([$set_admin, $id]);
    }
    $message = $set_admin ? "Selected users are now admins." : "Selected users are now normal users.";
}

// Fetch all users
$stmt = $pdo->query("SELECT id, username, email, blocked, is_admin FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Grey Lock</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="assets/js/darkmode.js"></script>

    <style>
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 30px;}
        .admin-table th, .admin-table td { border: 1px solid #ccc; padding: 8px; text-align: center;}
        .admin-table th { background: #eee; }
        .admin-actions { margin-top: 20px; }
        .success-message { color: green; text-align: center; margin-bottom: 10px;}
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>
        <?php if (!empty($message)): ?>
            <div class="success-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Administrator</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><input type="checkbox" name="user_ids[]" value="<?= $user['id'] ?>"></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['blocked'] ? '<span style="color:red;">Blocked</span>' : '<span style="color:green;">Active</span>' ?></td>
                        <td><?= $user['is_admin'] ? '<span style="color:blue;">Admin</span>' : 'Normal' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="admin-actions">
               <button type="submit" name="reset_password">Reset Password</button>
               <button type="submit" name="block">Block</button>
               <button type="submit" name="unblock">Unblock</button>
               <button type="submit" name="set_admin" value="1">Set as Admin</button>
               <button type="submit" name="set_admin" value="0">Set as Normal User</button>                
            </div>
        </form>
    </div>
    <script>
        function toggleAll(source) {
            checkboxes = document.getElementsByName('user_ids[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>