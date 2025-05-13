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

// Generate a password
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

// Modify the currently generated password
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

// Show all generated passwords
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
// Open the "Modify Password" modal
function openModifyModal(password) {
    // Populate the modal fields with the password data
    document.getElementById('modify_password_id').value = password.id;
    document.getElementById('modify_service_name').value = password.service_name;
    document.getElementById('modify_website_link').value = password.website_link;
    document.getElementById('modify_service_username').value = password.service_username;

    // Fetch the decrypted password and populate the password field
    fetch(`decrypt_password.php?encrypted_password=${encodeURIComponent(password.encrypted_password)}`)
        .then(response => response.text())
        .then(decryptedPassword => {
            document.getElementById('modify_password').value = decryptedPassword.trim();
        })
        .catch(error => {
            console.error('Error decrypting password:', error);
            document.getElementById('modify_password').value = 'Error decrypting password';
        });

    // Display the modal and overlay
    document.getElementById('modifyPasswordModal').style.display = 'block';
    document.getElementById('modalOverlay').style.display = 'block';
}

// Close any open modal
function closeModal() {
    document.getElementById('addPasswordModal').style.display = 'none';
    document.getElementById('generatePasswordModal').style.display = 'none';
    document.getElementById('modifyPasswordModal').style.display = 'none';
    document.getElementById('modalOverlay').style.display = 'none';
}