<?php
/**
 * dashboard.php
 * 
 * Admin dashboard for Grey Lock Password Manager.
 * 
 * Features:
 * - Displays all users with options for bulk actions (reset password, block, unblock, set admin).
 * - Shows all contact messages submitted via the contact form.
 * - Displays login logs, highlighting honeypot triggers.
 * - Includes a "select all" checkbox for bulk user management.
 * 
 * Security:
 * - Only accessible to authenticated admin users.
 * - All user input and output is sanitized to prevent XSS.
 * 
 * Dependencies:
 * - dbh.inc.php: Database connection.
 * - header.php: Page header and navigation.
 * - assets/css/dashboard.css: Dashboard styling.
 * - assets/js/darkmode.js: Dark mode support.
 * 
 * Usage:
 * - Accessed by admin users to manage users, view messages, and monitor logs.
 * 
 * @author Alexandre De Menezes - P2724348 
 * @version 1.0
 */

session_start();
require '../includes/dbh.inc.php';
require '../includes/header.php';

// Check if user admin is logged in
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login");
    exit();
}

// Handle bulk actions for users
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_ids'])) {
    $user_ids = $_POST['user_ids'];
    if (isset($_POST['reset_password'])) {
        // Reset selected users' passwords to Default123!
        $new_password = password_hash('Default123!', PASSWORD_DEFAULT);
        $in = str_repeat('?,', count($user_ids) - 1) . '?';
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id IN ($in)");
        foreach ($user_ids as $id) {
            $stmt->execute([$new_password, $id]);
        }
        $message = "Selected users' passwords have been reset to Default123!";
    }
    if (isset($_POST['block'])) {
        // Block selected users
        $in = str_repeat('?,', count($user_ids) - 1) . '?';
        $stmt = $pdo->prepare("UPDATE users SET blocked = 1 WHERE id IN ($in)");
        $stmt->execute($user_ids);
        $message = "Selected users have been blocked.";
    }
    if (isset($_POST['unblock'])) {
        // Unblock selected users
        $in = str_repeat('?,', count($user_ids) - 1) . '?';
        $stmt = $pdo->prepare("UPDATE users SET blocked = 0 WHERE id IN ($in)");
        $stmt->execute($user_ids);
        $message = "Selected users have been unblocked.";
    }
}

// Handle admin status changes
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

// Fetch all users for display
$userStmt = $pdo->query("SELECT id, username, email, blocked, is_admin FROM users");
$users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all contact messages
$contactStmt = $pdo->query("SELECT id, name, email, message, created_at FROM contact_messages ORDER BY created_at DESC");
$contactMessages = $contactStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all login logs
$logsStmt = $pdo->query("SELECT id, username, status, ip_address, forwarded_ips, created_at FROM login_logs ORDER BY created_at DESC");
$loginLogs = $logsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Grey Lock</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="assets/js/darkmode.js"></script>
    <style>
        /* Inline styles for dashboard tables and highlights */
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 30px;}
        .admin-table th, .admin-table td { border: 1px solid #ccc; padding: 8px; text-align: center;}
        .admin-table th { background: #eee; }
        .admin-actions { margin-top: 20px; }
        .success-message { color: green; text-align: center; margin-bottom: 10px;}
        .contact-table, .logs-table { width: 100%; border-collapse: collapse; margin-top: 30px;}
        .contact-table th, .contact-table td, .logs-table th, .logs-table td { border: 1px solid #ccc; padding: 8px; text-align: center;}
        .contact-table th, .logs-table th { background: #eee; }
        .honeypot-row { background-color: #ffe0e0 !important; font-weight: bold; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>
        <!-- Display success or info messages -->
        <?php if (!empty($message)): ?>
            <div class="success-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <!-- User Management Table -->
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

        <!-- Contact Messages Section -->
        <h3 style="margin-top:40px;">Contact Messages</h3>
        <table class="contact-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contactMessages as $msg): ?>
                <tr>
                    <td><?= $msg['id'] ?></td>
                    <td><?= htmlspecialchars($msg['name']) ?></td>
                    <td><?= htmlspecialchars($msg['email']) ?></td>
                    <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                    <td><?= htmlspecialchars($msg['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Login Logs Section -->
        <h3 style="margin-top:40px;">Login Logs</h3>
        <table class="logs-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>IP Address</th>
                    <th>Forwarded IPs</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loginLogs as $log): ?>
                <tr<?= $log['status'] === 'HONEYPOT_TRIGGERED' ? ' class="honeypot-row"' : '' ?>>
                    <td><?= $log['id'] ?></td>
                    <td><?= htmlspecialchars($log['username']) ?></td>
                    <td><?= htmlspecialchars($log['status']) ?></td>
                    <td><?= htmlspecialchars($log['ip_address']) ?></td>
                    <td><?= htmlspecialchars($log['forwarded_ips']) ?></td>
                    <td><?= htmlspecialchars($log['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        /**
         * Toggles all user checkboxes in the user management table.
         * @param {HTMLInputElement} source - The "select all" checkbox.
         */
        function toggleAll(source) {
            checkboxes = document.getElementsByName('user_ids[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
     <!-- Footer -->
    <footer class="footer">
        &copy; 2025 Grey Lock &mdash; Secure your digital life.<br>
        <a href="about">About</a> &nbsp;|&nbsp;
        <a href="contact">Contact</a>
    </footer>
</body>
</html>