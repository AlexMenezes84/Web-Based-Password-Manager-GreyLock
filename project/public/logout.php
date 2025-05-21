<?php
/**
 * logout.php
 * 
 * Handles user logout for Grey Lock Password Manager.
 * 
 * Features:
 * - Destroys the current user session to log the user out.
 * - Redirects the user to the login page after logout.
 * 
 * Security:
 * - Ensures all session data is cleared to prevent unauthorized access.
 * 
 * Usage:
 * - Accessed when the user clicks "Logout" in the application.
 * 
 * Dependencies:
 * - None (uses PHP session functions).
 * 
 * @author Alexandre De Menezes - P2724348
 * @version 1.0
 */

session_start();      // Start the session if not already started
session_unset();      // Unset all session variables
session_destroy();    // Destroy the session data on the server
header("Location: login"); // Redirect user to login page
exit();              // Terminate script execution