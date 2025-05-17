<?php
session_start();
require '../includes/dbh.inc.php';
require '../includes/header.php';
require_once '../includes/activity_log.inc.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user info
$stmt = $pdo->prepare("SELECT username, email, password FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$success = '';
$error = '';

// Handle email change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_email'])) {
    $new_email = trim($_POST['email']);
    if (empty($new_email)) {
        $error = "Email cannot be empty.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $update = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
        if ($update->execute([$new_email, $_SESSION['user_id']])) {
            $success = "Email updated successfully.";
            $user['email'] = $new_email;
            log_user_activity($pdo, $_SESSION['user_id'], $_SESSION['username'], 'Changed email');
        } else {
            $error = "Error updating email.";
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Please fill in all password fields.";
    } elseif (!password_verify($current_password, $user['password'])) {
        $error = "Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } elseif (
        strlen($new_password) < 12 ||
        !preg_match('/[A-Z]/', $new_password) ||         // at least one uppercase
        !preg_match('/[a-z]/', $new_password) ||         // at least one lowercase
        !preg_match('/[0-9]/', $new_password) ||         // at least one digit
        !preg_match('/[\W_]/', $new_password)            // at least one special char
    ) {
        $error = "New password must be at least 12 characters and include uppercase, lowercase, number, and special character.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($update->execute([$hashed, $_SESSION['user_id']])) {
            $success = "Master password changed successfully.";
            log_user_activity($pdo, $_SESSION['user_id'], $_SESSION['username'], 'Changed master password');
        } else {
            $error = "Error updating password.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Profile - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/profile.css">
    <script src="assets/js/darkmode.js"></script>
    <style>
        .profile-action-btns {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .profile-action-btns button {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            background: #333;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
        }
        .profile-action-btns button:hover {
            background: #555;
        }
        .profile-form {
            margin-top: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>My Profile</h2>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <div class="profile-action-btns">
            <button type="button" onclick="showForm('email')">Change Email</button>
            <button type="button" onclick="showForm('password')">Change Password</button>
        </div>
        <?php if ($success): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Change Email Form -->
        <form method="POST" class="profile-form" id="emailForm" style="display:none;">
            <label for="email"><strong>New Email:</strong></label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <button type="submit" name="update_email">Update Email</button>
        </form>

        <!-- Change Password Form -->
        <form method="POST" class="profile-form" id="passwordForm" style="display:none;">
            <label for="current_password">Current Password:</label><br>
            <input type="password" id="current_password" name="current_password" required><br>
            <label for="new_password">New Password:</label><br>
            <input type="password" id="new_password" name="new_password" required><br>
            <label for="confirm_password">Confirm New Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br>
            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>
    <footer>
        &copy; 2025 Grey Lock
    </footer>
    <script>
        function showForm(type) {
            document.getElementById('emailForm').style.display = (type === 'email') ? 'block' : 'none';
            document.getElementById('passwordForm').style.display = (type === 'password') ? 'block' : 'none';
        }
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_email']) && $error): ?>
            showForm('email');
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password']) && $error): ?>
            showForm('password');
        <?php endif; ?>
    </script>
</body>
</html>