/**
 * signup.js
 * 
 * Handles client-side validation for the signup form in Grey Lock Password Manager.
 * 
 * Features:
 * - Checks if the username and email are unique via AJAX requests to the server.
 * - Validates password strength using a regular expression.
 * - Ensures password and confirm password fields match.
 * - Provides real-time feedback and error messages for each field.
 * 
 * Usage:
 * - Include this script on the signup/registration page.
 * - The signup form should have input fields with ids: 'username', 'email', 'password', 'confirm_password'.
 * - Error message elements should have ids: 'username-error', 'email-error', 'error-message'.
 * 
 * Security:
 * - Only for client-side validation; all checks must be repeated server-side.
 * - Prevents form submission if validation fails, but does not replace server-side checks.
 * 
 * Author: Alexandre De Menezes - P2724348
 * Version: 1.0
 */

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const username = document.getElementById("username");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");
    const errorMessage = document.getElementById("error-message");
    const usernameError = document.getElementById("username-error");
    const emailError = document.getElementById("email-error");

    /**
     * Checks if a field value (username or email) is unique by querying the server.
     * @param {string} field - The field to check ('username' or 'email').
     * @param {string} value - The value to check for uniqueness.
     * @returns {Promise<boolean>} - Resolves to true if unique, false otherwise.
     */
    async function checkUnique(field, value) {
        try {
            const response = await fetch(`../includes/check_unique.inc.php?field=${field}&value=${encodeURIComponent(value)}`);
            const data = await response.json();
            return data.unique; // Returns true if the field is unique
        } catch (error) {
            console.error(`Error checking ${field} uniqueness:`, error);
            return false;
        }
    }

    // Add event listener to the form for validation on submit
    form.addEventListener("submit", async (e) => {
        const usernameValue = username.value.trim();
        const emailValue = email.value.trim();
        const passwordValue = password.value;

        // Check if the username is unique
        const isUsernameUnique = await checkUnique("username", usernameValue);
        if (!isUsernameUnique) {
            e.preventDefault();
            usernameError.textContent = "Username is already taken. Please choose another.";
            usernameError.style.color = "red";
            return;
        } else {
            usernameError.textContent = ""; // Clear the error message if valid
        }

        // Check if the email is unique
        const isEmailUnique = await checkUnique("email", emailValue);
        if (!isEmailUnique) {
            e.preventDefault();
            emailError.textContent = "Email is already registered. Please use another.";
            emailError.style.color = "red";
            return;
        } else {
            emailError.textContent = ""; // Clear the error message if valid
        }

        // Regular expression for strong password
        const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/;

        // Validate password strength
        if (!strongPasswordRegex.test(passwordValue)) {
            e.preventDefault();
            errorMessage.textContent = "Password must be at least 12 characters long, include uppercase, lowercase, a number, and a special character.";
            errorMessage.style.color = "red";
        } else if (password.value !== confirmPassword.value) {
            e.preventDefault();
            errorMessage.textContent = "Passwords do not match.";
            errorMessage.style.color = "red";
        }
    });

    // Clear username error message when the user starts typing
    username.addEventListener("input", () => {
        usernameError.textContent = "";
    });

    // Clear email error message when the user starts typing
    email.addEventListener("input", () => {
        emailError.textContent = "";
    });
});