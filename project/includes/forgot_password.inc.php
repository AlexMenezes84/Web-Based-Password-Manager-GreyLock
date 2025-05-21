<?php

/**
 * forgot_password.inc.php
 * 
 * Handles password reset requests for Grey Lock Password Manager.
 * 
 * Features:
 * - Receives a POST request with the user's email address.
 * - Validates and sanitizes the email input.
 * - Checks if the email exists in the users table.
 * - Generates a secure, unique reset token and expiration time.
 * - Stores the token and expiration in the password_resets table.
 * - Sends a password reset link to the user's email address using PHPMailer (Gmail SMTP).
 * - Redirects with appropriate success or error messages.
 * 
 * Security:
 * - Uses prepared statements to prevent SQL injection.
 * - Does not reveal whether an email is registered for privacy.
 * - Tokens are cryptographically secure and expire after 1 hour.
 * 
 * Dependencies:
 * - dbh.inc.php: Database connection (PDO).
 * - password_resets table: Stores reset tokens and expiration.
 * - PHPMailer: For sending emails via Gmail SMTP.
 * 
 * Usage:
 * - Called via POST from the forgot password form.
 *   Example: forgot_password.php (form) -> forgot_password.inc.php (handler)
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php'; // Include the database connection

    // Sanitize and validate the email
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/forgot_password?error=invalidemail");
        exit();
    }

    try {
        // Check if the email exists in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            // For privacy, do not reveal if the email exists
            header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/forgot_password?success=emailsent");
            exit();
        }

        // Generate a unique reset token
        $token = bin2hex(random_bytes(32));
        $expires = date("U") + 3600; // Token expires in 1 hour

        // Insert the token into the database
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires) VALUES (:email, :token, :expires)");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':expires', $expires, PDO::PARAM_INT);
        $stmt->execute();

        // Prepare the reset link and email content
        $resetLink = "http://localhost/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/reset_password?token=$token";
        $subject = "Password Reset Request";
        $message = "Hello,\n\nWe received a request to reset your password. Click the link below to reset your password:\n\n$resetLink\n\nIf you did not request this, please ignore this email.\n\nThis link will expire in 1 hour.";

        // Send the reset link via Gmail SMTP using PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'grey.lock.web@gmail.com'; // Your Gmail address
            $mail->Password = 'wcpa lyrr ftuq kxcj';     // Your Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('grey.lock.web@gmail.com', 'Grey Lock');
            $mail->addAddress($email);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/forgot_password?success=emailsent");
            exit();
        } catch (Exception $e) {
            header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/forgot_password?error=emailfailed");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/forgot_password?error=servererror");
        exit();
    }
} else {
    header("Location: /websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/forgot_password");
    exit();
}