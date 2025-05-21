/**
 * darkmode.js
 * 
 * Handles dark mode toggling and persistence for Grey Lock Password Manager.
 * 
 * Features:
 * - Checks user's dark mode preference on page load and applies it.
 * - Allows toggling dark mode via a button with id 'darkMode'.
 * - Saves the user's preference in localStorage for persistence across sessions.
 * 
 * Usage:
 * - Include this script on pages with a dark mode toggle button.
 * - The button should have the id 'darkMode'.
 * 
 * Security:
 * - No sensitive data is stored; only UI preference in localStorage.
 * 
 * Author: Alexandre De Menezes - P2724348
 * Version: 1.0
 */

// On page load, check preference
window.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }
});

// Toggle dark mode and save preference
const dark = document.getElementById('darkMode');
if (dark) {
    dark.onclick = () => {
        document.body.classList.toggle('dark-mode');
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    };
}