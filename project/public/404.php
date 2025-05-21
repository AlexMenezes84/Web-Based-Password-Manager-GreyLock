<!--
    404.php

    Custom 404 Not Found error page for Grey Lock Password Manager.

    Features:
    - Displays a user-friendly message and navigation options.
    - Styled to match the application's theme.

    Usage:
    - Included by Slim's notFoundHandler in index.php when a route is not found.

    @author Alexandre De Menezes - P2724348
    @version 1.0
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 Not Found - Grey Lock</title>
    <!-- Optional: External stylesheet for further customization -->
    <link rel="stylesheet" type="text/css" href="assets/css/404.css">
    <style>
        /* Page background and font styling */
        body {
            background: #181c20;
            color: #fff;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        /* Centered container for the 404 message */
        .notfound-container {
            max-width: 500px;
            margin: 80px auto;
            background: #23272b;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.3);
            padding: 40px 30px;
            text-align: center;
        }
        /* Large 404 number */
        .notfound-container h1 {
            font-size: 5em;
            margin: 0 0 10px 0;
            color: rgb(243, 245, 247);
            letter-spacing: 2px;
        }
        /* Subtitle */
        .notfound-container h2 {
            margin: 0 0 20px 0;
            font-size: 2em;
            color: #fff;
        }
        /* Description text */
        .notfound-container p {
            color: #b0b8c1;
            margin-bottom: 30px;
        }
        /* Navigation buttons */
        .notfound-container a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 28px;
            background: rgb(128, 128, 129);
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }
        .notfound-container a:hover {
            background: rgb(99, 100, 100);
        }
        /* Lock emoji styling */
        .notfound-emoji {
            font-size: 3em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Main 404 content -->
    <div class="notfound-container">
        <div class="notfound-emoji">🔒</div>
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>
            Sorry, the page you are looking for does not exist.<br>
            It may have been moved, deleted, or you may have mistyped the address.
        </p>
        <!-- Navigation links -->
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/">Go Home</a>
        <a href="/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/login">Login</a>
    </div>
     <!-- Footer -->
    <footer class="footer">
        &copy; 2025 Grey Lock &mdash; Secure your digital life.<br>
        <a href="about">About</a> &nbsp;|&nbsp;
        <a href="contact">Contact</a>
    </footer>
</body>
</html>