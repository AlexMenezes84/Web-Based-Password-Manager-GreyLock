<?php
/**
 * password_vault.php
 * 
 * Main password vault page for Grey Lock Password Manager.
 * 
 * Features:
 * - Displays all saved passwords for the authenticated user.
 * - Allows adding, modifying, and deleting password entries.
 * - Provides a password generator modal.
 * - Supports password visibility toggling.
 * - Redirects honeypot users to a fake vault.
 * 
 * Security:
 * - Requires user authentication.
 * - Sanitizes all output for XSS protection.
 * - Passwords are stored encrypted and decrypted only for display.
 * 
 * Dependencies:
 * - header.php: Page header and navigation.
 * - config.php: Configuration settings.
 * - decrypt_password.php: Password decryption logic.
 * - dbh.inc.php: Database connection (PDO).
 * - add_password.inc.php: Handles adding new passwords.
 * - delete_password.inc.php: Handles deleting passwords.
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

// Include required files and start session if needed
require '../includes/header.php';
require '../includes/config.php';
require '../includes/decrypt_password.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect honeypot users to fake vault
if (!empty($_SESSION['honeypot_vault'])) {
    header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/vault");
    exit();
}

// Redirect unauthenticated users to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require '../includes/dbh.inc.php';

// Fetch the user's saved passwords from the database
$stmt = $pdo->prepare("SELECT id, service_name, service_username, encrypted_password, website_link FROM passwords WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Vault - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/vault.css">
    <script src="assets/js/vault.js" defer></script>
    <script src="assets/js/darkmode.js"></script>
</head>
<body>
    <main>
        <div class="vault-container">
            <h2>Your Password Vault</h2>
            <!-- Add New Password Button -->
            <button id="addNewButton">Add New</button>
            <!-- Generate Password Button -->
            <button id="generatePasswordButton">Generate Password</button>

            <!-- Password List Table -->
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
                                <!-- Display service name -->
                                <td><?= htmlspecialchars($password['service_name']) ?></td>
                                <!-- Display website link as clickable URL -->
                                <td><a href="<?= htmlspecialchars($password['website_link']) ?>" target="_blank"><?= htmlspecialchars($password['website_link']) ?></a></td>
                                <!-- Display service username -->
                                <td><?= htmlspecialchars($password['service_username']) ?></td>
                                <!-- Display decrypted password in a password field with show/hide toggle -->
                                <td>
                                    <input type="password" value="<?= htmlspecialchars(decrypt_password($password['encrypted_password'])) ?>" readonly>
                                    <button onclick="togglePassword(this)">Show</button>
                                </td>
                                <!-- Actions: Modify and Delete -->
                                <td>
                                    <button onclick='openModifyModal(<?= json_encode([
                                        "id" => $password["id"],
                                        "service_name" => $password["service_name"],
                                        "website_link" => $password["website_link"],
                                        "service_username" => $password["service_username"],
                                        "password" => decrypt_password($password["encrypted_password"])
                                    ]) ?>)'>Modify</button>
                                    <form action="../includes/delete_password.inc.php" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
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

            <!-- Modal Overlay for modals -->
            <div id="modalOverlay"></div>

            <!-- Add Password Modal -->
            <div id="addPasswordModal" style="display: none;">
                <h3>Add New Password</h3>
                <form action="../includes/add_password.inc.php" method="POST">
                    <label for="service_name">Service Name:</label>
                    <input type="text" id="service_name" name="service_name" required>
                    <br>
                    <label for="website_link">Website Link:</label>
                    <input type="text" id="website_link" name="website_link" required>
                    <br>
                    <label for="service_username">Username:</label>
                    <input type="text" id="service_username" name="service_username" required>
                    <br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="button" onclick="toggleAddPasswordVisibility()">Show</button>
                    <br>
                    <button type="submit">Save</button>
                    <button type="button" onclick="closeModal()">Cancel</button>
                </form>
            </div>

            <!-- Generate Password Modal -->
            <div id="generatePasswordModal" style="display: none;">
                <h3>Generate Password</h3>
                <label for="password_length">Length:</label>
                <input type="number" id="password_length" name="password_length" value="16" min="8" max="64">
                <br>
                <label><input type="checkbox" id="use_uppercase" checked> A-Z</label>
                <label><input type="checkbox" id="use_lowercase" checked> a-z</label>
                <label><input type="checkbox" id="use_numbers" checked> 0-9</label>
                <label><input type="checkbox" id="use_symbols" checked> !@#$%</label>
                <br>
                <button type="button" onclick="generatePassword()">Generate</button>
                <br>
                <label for="generated_password">Generated Password:</label>
                <input type="text" id="generated_password" readonly>
                <br>
                <button type="button" onclick="showGeneratedPasswords()">Show All Generated Passwords</button>
                <br>
                <div id="generatedPasswordsList" style="display: none; margin-top: 10px;">
                    <h4>Generated Passwords:</h4>
                    <ul id="generatedPasswords"></ul>
                </div>
                <button type="button" onclick="closeModal()">Close</button>
            </div>

            <!-- Modify Password Modal -->
            <div id="modifyPasswordModal" style="display: none;">
                <h3>Modify Password Details</h3>
                <form id="modifyPasswordForm" action="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/modify_password" method="POST">
                    <input type="hidden" id="modify_password_id" name="password_id">
                    <label for="modify_service_name">Service Name:</label>
                    <input type="text" id="modify_service_name" name="service_name" required>
                    <br>
                    <label for="modify_website_link">Website Link:</label>
                    <input type="text" id="modify_website_link" name="website_link" required>
                    <br>
                    <label for="modify_service_username">Username:</label>
                    <input type="text" id="modify_service_username" name="service_username" required>
                    <br>
                    <label for="modify_password">Password:</label>
                    <input type="text" id="modify_password" name="password" required>
                    <br>
                    <button type="submit">Save Changes</button>
                    <button type="button" onclick="closeModal()">Cancel</button>
                </form>
            </div>
        </div>
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