<?php
// Autoload dependencies (make sure you have installed Slim via Composer)
require 'GreyLock/vendor/autoload.php';
// include the autoload file for the Slim framework
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;



// Create Slim app
$app = AppFactory::create();
$routeCollector = $app->getRouteCollector();
$routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());
$app->setBasePath('/sites/GreyLock/project'); 
// Add Slim error middleware for debugging
$app->addErrorMiddleware(true, true, true);


// Home Page Route
$app->get('/', function (Request $request, Response $response) {
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/home.css">
</head>
<body>
    <header>
        <img src="assets/logo.png" alt="Grey Lock Logo">
        <h1>Welcome to Grey Lock</h1>
    </header>
    <nav>
        <a href="/sites/GreyLock/project/">Home</a>
        <a href="/sites/GreyLock/project/about">About</a>
        <a href="/sites/GreyLock/project/contact">Contact Us</a>
        <a href="/sites/GreyLock/project/login">Login</a>
    </nav>
    <div class="content">
        <h2>Home Page</h2>
        <p>This is the home page of Grey Lock. Enjoy your stay!</p>
    </div>
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>
HTML;

    $response->getBody()->write($html);
    return $response;
});

// About Page Route
$app->get('/about', function (Request $request, Response $response) {
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>About - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/about.css">
</head>
<body>
    <header>
        <img src="assets/logo.png" alt="Grey Lock Logo">
        <h1>About Grey Lock</h1>
    </header>
    <nav>
        <a href="/sites/GreyLock/project/">Home</a>
        <a href="/sites/GreyLock/project/about">About</a>
        <a href="/sites/GreyLock/project/contact">Contact Us</a>
        <a href="/sites/GreyLock/project/login">Login</a>
    </nav>
    <div class="content">
        <h2>About Page</h2>
        <p>Learn more about Grey Lock, our mission, and our team behind the success.</p>
    </div>
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>
HTML;

    $response->getBody()->write($html);
    return $response;
});

// Contact Page Route
$app->get('/contact', function (Request $request, Response $response) {
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contact - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/contact.css">
</head>
<body>
    <header>
        <img src="assets/logo.png" alt="Grey Lock Logo">
        <h1>Contact Grey Lock</h1>
    </header>
    <nav>
        <a href="/sites/GreyLock/project/">Home</a>
        <a href="/sites/GreyLock/project/about">About</a>
        <a href="/sites/GreyLock/project/contact">Contact Us</a>
        <a href="/sites/GreyLock/project/login">Login</a>
    </nav>
    <div class="content">
        <h2>Contact Page</h2>
        <p>Get in touch with us via email, phone, or our social media channels.</p>
    </div>
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>
HTML;

    $response->getBody()->write($html);
    return $response;
});
// Login Page Route
$app->get('/login', function (Request $request, Response $response) {
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
     <!-- Link to the external JavaScript file -->
  <script src="login.js" href="assets/login.js"></script>
</head>
<body>
    <header>
        <img src="assets/logo.png" alt="Grey Lock Logo">
        <h1>Login Grey Lock</h1>
    </header>
    <nav>
        <a href="/sites/GreyLock/project/">Home</a>
        <a href="/sites/GreyLock/project/about">About</a>
        <a href="/sites/GreyLock/project/contact">Contact Us</a>
        <a href="/sites/GreyLock/project/login">Login</a>
    </nav>
    <div class="content">
        <h2>Login Page</h2>
        <form id="loginForm">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
  </div>
    </div>
    <footer>
        &copy; 2025 Grey Lock
    </footer>
</body>
</html>
HTML;

    $response->getBody()->write($html);
    return $response;
});

// Run the Slim application
try {
    $app->run();
} catch (Exception $e) {
    // Log the error and display a user-friendly message
    error_log($e->getMessage());
    $response = new \Slim\Psr7\Response();
    $response->getBody()->write('An error occurred. Please try again later.');
    return $response->withStatus(500);
}