/**
 * login.js
 * 
 * Handles client-side login form validation for Grey Lock Password Manager.
 * 
 * Features:
 * - Listens for login form submission.
 * - Retrieves and validates username and password input fields.
 * - Provides basic client-side validation and feedback.
 * 
 * Usage:
 * - Include this script on the login page.
 * - The login form should have the id 'loginForm'.
 * - Username and password input fields should have ids 'username' and 'password'.
 * 
 * Security:
 * - Only for demonstration; real authentication must be performed server-side.
 * - Do not use client-side validation alone for production authentication.
 * 
 * Author: Alexandre De Menezes - P2724348
 * Version: 1.0
 */

// Get the login form element.
const loginForm = document.getElementById('loginForm');

// Listen for form submission.
loginForm.addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent the form from refreshing the page.

  // Retrieve values from the form.
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;

  // Basic client-side validation (replace with server-side validation in production).
  // For demonstration, username: "user" and password: "pass" are considered valid.
  if (username === "user" && password === "pass") {
    alert('Login successful!');
    // Redirect or perform further actions on successful login.
  } else {
    alert('Invalid username or password.');
  }
});