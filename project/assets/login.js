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
    // Example: window.location.href = "dashboard.html";
  } else {
    alert('Invalid username or password.');
  }
});
