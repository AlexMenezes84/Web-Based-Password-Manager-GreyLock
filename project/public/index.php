<?php
/**
 * index.php
 * 
 * Main entry point and route definitions for Grey Lock Password Manager (Slim Framework).
 * 
 * Features:
 * - Defines all public-facing routes (home, about, contact, login, signup, forgot password, vault, logout, dashboard, honeypot vault).
 * - Uses Slim Framework for routing and middleware.
 * - Each route includes a PHP file and writes its output to the response.
 * - Handles POST route for password modification.
 * - Adds error middleware for debugging and custom 404 handling.
 * 
 * Security:
 * - Only includes files from the current directory for each route.
 * - POST route for password modification uses a dedicated include.
 * 
 * Dependencies:
 * - Slim Framework.
 * - Various PHP files for each route (homepage.php, about.php, etc.).
 * - ../includes/modify_password.inc.php for password modification logic.
 * 
 * Usage:
 * - This file is accessed as the main entry point for all web requests.
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

require '../GreyLock/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Create Slim app instance
$app = AppFactory::create();
$app->setBasePath('/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public');

// Home Page Route
$app->get('/', function (Request $request, Response $response) {
    // Use output buffering to capture included file output
    ob_start();
    include 'homepage.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// About Page Route
$app->get('/about', function (Request $request, Response $response) {
    ob_start();
    include 'about.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Contact Page Route
$app->get('/contact', function (Request $request, Response $response) {
    ob_start();
    include 'contact.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Login Page Route
$app->get('/login', function (Request $request, Response $response) {
    ob_start();
    include 'login.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Sign-up Page Route
$app->get('/signup', function (Request $request, Response $response) {
    ob_start();
    include 'signup.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Forgot-password Page Route
$app->get('/forgot_password', function (Request $request, Response $response) {
    ob_start();
    include 'forgot_password.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Vault Page Route
$app->get('/password_vault', function (Request $request, Response $response) {
    ob_start();
    include 'password_vault.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Logout Page Route
$app->get('/logout', function (Request $request, Response $response) {
    ob_start();
    include 'logout.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Dashboard Page Route
$app->get('/dashboard', function (Request $request, Response $response) {
    ob_start();
    include 'dashboard.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Honeypot Vault Page Route
$app->get('/vault', function (Request $request, Response $response) {
    ob_start();
    include 'honeypot_vault.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Modify Password POST Route
$app->post('/modify_password', function ($request, $response) {
    // Handles password modification logic
    require '../includes/modify_password.inc.php';
    return $response;
});

// Custom 404 Not Found handler
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(
    Slim\Exception\HttpNotFoundException::class,
    function ($request, $exception, $displayErrorDetails) use ($app) {
        $response = new \Slim\Psr7\Response();
        ob_start();
        include '404.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response->withStatus(404);
    }
);

// Run the Slim application
try {
    $app->run();
} catch (Exception $e) {
    // Handle exceptions and errors
    echo 'An error occurred: ' . $e->getMessage();
    die();
}