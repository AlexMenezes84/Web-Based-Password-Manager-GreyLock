<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sign Up - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/signup.css">
    <script src="assets/signup.js" defer></script> 
</head>
<body>
    <header>
        <img src="assets/logo.png" alt="Grey Lock Logo">
        <h1>Sign Up for Grey Lock</h1>
    </header>
    <nav>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/">Home</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/about">About</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/contact">Contact Us</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a>
    </nav>
    <div class="content">
        <h2>Create an Account</h2>
        <form action="../includes/signup.inc.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <p id="username-error"></p>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <p id="email-error"></p>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br><br>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br><br>
            <p id="error-message"></p>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a></p>
    </div>
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>