<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'dbh.inc.php'; // Include the database connection

    // Sanitize and validate the email
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        header("Location: ../public/forgot_password.php?error=invalidemail");
        exit();
    }

    try {
        // Check if the email exists in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            header("Location: ../public/forgot_password.php?error=emailnotfound");
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

        // Send the reset link via email
        $resetLink = "http://localhost/websites/GreyLock/Web-Based-Password-Manager-GreyLock/project/public/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Hello,\n\nWe received a request to reset your password. Click the link below to reset your password:\n\n$resetLink\n\nIf you did not request this, please ignore this email.\n\nThis link will expire in 1 hour.";
        $headers = "From: no-reply@greylock.com\r\n";
        $headers .= "Reply-To: no-reply@greylock.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($email, $subject, $message, $headers)) {
            header("Location: ../public/forgot_password.php?success=emailsent");
            exit();
        } else {
            header("Location: ../public/forgot_password.php?error=emailfailed");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: ../public/forgot_password.php");
    exit();
}