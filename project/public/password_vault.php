<?php
require '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require '../includes/dbh.inc.php';

// Encryption configuration
$encryption_key = bin2hex(random_bytes(32)); 
$encryption_method = 'AES-256-CBC';

// Fetch the user's saved passwords
$stmt = $pdo->prepare("SELECT id, service_name, service_username, encrypted_password, website_link FROM passwords WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Decrypt function
function decrypt_password($encrypted_password, $encryption_key, $encryption_method) {
    $parts = explode('::', base64_decode($encrypted_password), 2);
    if (count($parts) !== 2) {
        return null; // Return null if the encrypted data is invalid
    }
    list($iv, $encrypted_data) = $parts;

    return openssl_decrypt($encrypted_data, $encryption_method, $encryption_key, 0, $iv);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Vault - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/vault.css">
</head>
<body>
    <main>
        <div class="container">
            <h2>Your Password Vault</h2>
            <button id="addNewButton">Add New</button>
            <table>
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Website Link</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($passwords) > 0): ?>
                        <?php foreach ($passwords as $password): ?>
                            <tr>
                                <td><?= htmlspecialchars($password['service_name']) ?></td>
                                <td><a href="<?= htmlspecialchars($password['website_link']) ?>" target="_blank"><?= htmlspecialchars($password['website_link']) ?></a></td>
                                <td><?= htmlspecialchars($password['service_username']) ?></td>
                                <td>
                                    <input type="password" value="<?= htmlspecialchars(decrypt_password($password['encrypted_password'], $encryption_key, $encryption_method)) ?>" readonly>
                                    <button onclick="togglePassword(this)">Show</button>
                                </td>
                                <td>
                                    <form action="../public/modify_password.php" method="GET" style="display:inline;">
                                        <input type="hidden" name="password_id" value="<?= $password['id'] ?>">
                                        <button type="submit" class="modifyButton">Modify</button>
                                    </form>
                                    <form action="../includes/delete_password.inc.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="password_id" value="<?= $password['id'] ?>">
                                        <button type="submit" class="deleteButton">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No passwords saved yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        function togglePassword(button) {
            const input = button.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = 'Hide';
            } else {
                input.type = 'password';
                button.textContent = 'Show';
            }
        }
    </script>
</body>
</html>