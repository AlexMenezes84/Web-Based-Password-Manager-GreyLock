<?php
require '../GreyLock/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->setBasePath('/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public');
// Add Slim error middleware for debugging
$app->addErrorMiddleware(true, true, true);

// Home Page Route
$app->get('/', function (Request $request, Response $response) {
    // Start output buffering
    // This allows us to capture the output of the included file
    // and write it to the response body
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
$app->get('/vault', function (Request $request, Response $response) {
    ob_start();
    include 'password_vault.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

// Run the Slim application
try {
    $app->run();
} catch (Exception $e) {
    error_log($e->getMessage());
    $response = new \Slim\Psr7\Response();
    $response->getBody()->write('An error occurred. Please try again later.');
    return $response->withStatus(500);
}