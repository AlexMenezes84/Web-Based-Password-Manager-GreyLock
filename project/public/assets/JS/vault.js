/**
 * vault.js
 * 
 * Handles all client-side interactivity for the password vault in Grey Lock Password Manager.
 * 
 * Features:
 * - Opens and closes modals for adding, generating, and modifying passwords.
 * - Handles password generation via AJAX and displays generated passwords.
 * - Allows users to modify generated passwords before saving.
 * - Confirms deletion of passwords.
 * - Toggles password visibility in forms and lists.
 * - Maintains a list of all generated passwords in the session.
 * 
 * Usage:
 * - Include this script on the password vault page.
 * - Requires specific element IDs for modals, buttons, and input fields.
 * 
 * Security:
 * - Only handles UI logic; all sensitive operations (add, delete, modify) must be validated server-side.
 * - No sensitive data is stored in localStorage or cookies.
 * 
 * Author: Alexandre De Menezes - P2724348
 * Version: 1.0
 */

// Open the "Add New Password" modal
document.getElementById('addNewButton').addEventListener('click', function () {
    document.getElementById('addPasswordModal').style.display = 'block';
    document.getElementById('modalOverlay').style.display = 'block';
});

// Open the "Generate Password" modal
document.getElementById('generatePasswordButton').addEventListener('click', function () {
    document.getElementById('generatePasswordModal').style.display = 'block';
    document.getElementById('modalOverlay').style.display = 'block';
});

// Confirm before deleting a password
function confirmDelete() {
    return confirm('Are you sure you want to delete this password? This action cannot be undone.');
}

// Toggle password visibility in the Add Password form
function toggleAddPasswordVisibility() {
    const passwordInput = document.getElementById('password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
}

// Toggle password visibility in the password list
function togglePassword(button) {
    const passwordInput = button.previousElementSibling; // Get the input field before the button
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        button.textContent = 'Hide'; // Change button text to "Hide"
    } else {
        passwordInput.type = 'password';
        button.textContent = 'Show'; // Change button text to "Show"
    }
}

// Array to store generated passwords
let generatedPasswords = [];

/**
 * Generates a password using user-selected options and updates the UI.
 * Makes an AJAX request to the PHP endpoint for password generation.
 */
function generatePassword() {
    const length = document.getElementById('password_length').value;
    const useUppercase = document.getElementById('use_uppercase').checked;
    const useLowercase = document.getElementById('use_lowercase').checked;
    const useNumbers = document.getElementById('use_numbers').checked;
    const useSymbols = document.getElementById('use_symbols').checked;

    // Make an AJAX request to the PHP endpoint
    fetch(`generate_password.php?length=${length}&uppercase=${useUppercase}&lowercase=${useLowercase}&numbers=${useNumbers}&symbols=${useSymbols}`)
        .then(response => response.text())
        .then(password => {
            document.getElementById('generated_password').value = password;
            generatedPasswords.push(password); // Store the generated password
        })
        .catch(error => console.error('Error generating password:', error));
}

/**
 * Allows the user to modify the currently generated password.
 * Makes the input editable and saves the change on Enter.
 */
function modifyPassword() {
    const generatedPasswordInput = document.getElementById('generated_password');
    generatedPasswordInput.removeAttribute('readonly'); // Allow editing
    generatedPasswordInput.focus(); // Focus on the input field

    // Save the modified password when the user presses Enter
    generatedPasswordInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            generatedPasswordInput.setAttribute('readonly', true); // Make it readonly again
            const modifiedPassword = generatedPasswordInput.value;
            alert(`Password modified to: ${modifiedPassword}`);
        }
    });
}

/**
 * Displays all generated passwords in a list.
 */
function showGeneratedPasswords() {
    const listContainer = document.getElementById('generatedPasswordsList');
    const list = document.getElementById('generatedPasswords');
    list.innerHTML = ''; // Clear the list

    if (generatedPasswords.length === 0) {
        list.innerHTML = '<li>No passwords generated yet.</li>';
    } else {
        generatedPasswords.forEach((password, index) => {
            const listItem = document.createElement('li');
            listItem.textContent = `${index + 1}: ${password}`;
            list.appendChild(listItem);
        });
    }

    listContainer.style.display = 'block'; // Show the list
}

/**
 * Opens the "Modify Password" modal and populates it with the selected password's data.
 * @param {Object} password - The password object containing id, service_name, website_link, service_username, and password.
 */
function openModifyModal(password) {
    document.getElementById('modify_password_id').value = password.id;
    document.getElementById('modify_service_name').value = password.service_name;
    document.getElementById('modify_website_link').value = password.website_link;
    document.getElementById('modify_service_username').value = password.service_username;
    document.getElementById('modify_password').value = password.password;
    document.getElementById('modifyPasswordModal').style.display = 'block';
    document.getElementById('modalOverlay').style.display = 'block';
}

/**
 * Closes all open modals and the overlay.
 */
function closeModal() {
    document.getElementById('addPasswordModal').style.display = 'none';
    document.getElementById('generatePasswordModal').style.display = 'none';
    document.getElementById('modifyPasswordModal').style.display = 'none';
    document.getElementById('modalOverlay').style.display = 'none';
}