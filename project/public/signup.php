<?php
require '../includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sign Up - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/signup.css">
    <script src="assets/js/signup.js" defer></script> 
    <script src="assets/js/darkmode.js"></script>
</head>
<body>
      <div class="content">
        <h2>Create an Account</h2>

        <?php
        // Check for error query parameters
        $error = isset($_GET['error']) ? $_GET['error'] : '';

        // Retain form values
        $old_username = isset($_GET['username']) ? htmlspecialchars($_GET['username'], ENT_QUOTES, 'UTF-8') : '';
        $old_email = isset($_GET['email']) ? htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8') : '';
        ?>

        <form action="../includes/signup.inc.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $old_username; ?>" required>
            <?php if ($error === 'usernameexists'): ?>
                <p id="username-error" style="color: red;">Username already exists.</p>
            <?php endif; ?>
            <br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $old_email; ?>" required>
            <?php if ($error === 'invalidemail'): ?>
                <p id="email-error" style="color: red;">Invalid email format.</p>
            <?php elseif ($error === 'emailexists'): ?>
                <p id="email-error" style="color: red;">Email already exists.</p>
            <?php endif; ?>
            <br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br><br>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br><br>

            <?php if ($error === 'passwordmismatch'): ?>
                <p id="error-message" style="color: red;">Passwords do not match.</p>
            <?php elseif ($error === 'weakpassword'): ?>
                <p id="error-message" style="color: red;">Password must be at least 12 characters long, include uppercase, lowercase, a number, and a special character.</p>
            <?php endif; ?>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a></p>
    </div>
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>