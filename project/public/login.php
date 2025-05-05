<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
    <script src="assets/login.js"></script>
</head>
<body>
    <header>
        <img src="assets/logo.png" alt="Grey Lock Logo">
        <h1>Login Grey Lock</h1>
    </header>
    <nav>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/">Home</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/about">About</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/contact">Contact Us</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a>
    </nav>
    <div class="content">
        <h2>Login Page</h2>
        <form action="../includes/login.inc.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br><br>
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
            <br><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/signup">Sign Up</a></p>
        <p><a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/forgot_password">Forgot Password?</a></p>
    </div>
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>
<?php
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'emptyfields') {
        echo "<p style='color: red;'>Please fill in all fields.</p>";
    } elseif ($_GET['error'] === 'invalidcredentials') {
        echo "<p style='color: red;'>Invalid username or password.</p>";
    }
}
?>