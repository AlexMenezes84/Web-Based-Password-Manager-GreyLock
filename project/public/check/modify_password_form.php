<?php
session_start();
require '../includes/config.php';
require '../includes/dbh.inc.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the password_id is provided
if (!isset($_POST['password_id'])) {
    header("Location: password_vault.php?error=missingid");
    exit();
}

$password_id = $_POST['password_id'];

// Fetch the password details from the database
$stmt = $pdo->prepare("SELECT service_name, website_link, service_username, encrypted_password FROM passwords WHERE id = ? AND user_id = ?");
$stmt->execute([$password_id, $_SESSION['user_id']]);
$password_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$password_data) {
    header("Location: password_vault.php?error=notfound");
    exit();
}

// Decrypt the password
function decrypt_password($encrypted_password) {
    $parts = explode('::', base64_decode($encrypted_password), 2);
    if (count($parts) !== 2) {
        return null; // Return null if the encrypted data is invalid
    }
    list($iv, $encrypted_data) = $parts;

    return openssl_decrypt($encrypted_data, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv);
}

$decrypted_password = decrypt_password($password_data['encrypted_password']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Modify Password - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/vault.css">
</head>
<body>
    <main>
        <div class="container">
            <h2>Modify Password</h2>
            <form action="modify_password.php" method="POST">
                <input type="hidden" name="password_id" value="<?= htmlspecialchars($password_id) ?>">
                <label for="service_name">Service Name:</label>
                <input type="text" id="service_name" name="service_name" value="<?= htmlspecialchars($password_data['service_name']) ?>" required>
                <br>
                <label for="website_link">Website Link:</label>
                <input type="text" id="website_link" name="website_link" value="<?= htmlspecialchars($password_data['website_link']) ?>" required>
                <br>
                <label for="service_username">Username:</label>
                <input type="text" id="service_username" name="service_username" value="<?= htmlspecialchars($password_data['service_username']) ?>" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?= htmlspecialchars($decrypted_password) ?>" required>
                <button type="button" onclick="togglePasswordVisibility()">Show</button>
                <br>
                <button type="submit">Save Changes</button>
                <a href="password_vault.php"><button type="button">Cancel</button></a>
            </form>
        </div>
    </main>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
</body>
</html>