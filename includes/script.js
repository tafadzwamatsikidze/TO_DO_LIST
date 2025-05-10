// DOM Elements
const darkModeToggle = document.getElementById('dark-mode-toggle');
const darkModeStyle = document.getElementById('dark-mode-style');

// Check for saved dark mode preference
const darkModeEnabled = localStorage.getItem('darkMode') === 'enabled';

// Apply dark mode if enabled
if (darkModeEnabled) {
    darkModeStyle.disabled = false;
    darkModeToggle.textContent = '☀️';
}

// Toggle dark mode
darkModeToggle.addEventListener('click', () => {
    if (darkModeStyle.disabled) {
        // Enable dark mode
        darkModeStyle.disabled = false;
        darkModeToggle.textContent = '☀️';
        localStorage.setItem('darkMode', 'enabled');
    } else {
        // Disable dark mode
        darkModeStyle.disabled = true;
        darkModeToggle.textContent = '🌓';
        localStorage.setItem('darkMode', 'disabled');
    }
});

// Confirm before deleting a todo
document.querySelectorAll('.delete').forEach(button => {
    button.addEventListener('click', (e) => {
        if (!confirm('Are you sure you want to delete this task?')) {
            e.preventDefault();
        }
    });
});

// Simple animation for todo items when added
const todoItems = document.querySelectorAll('.todo-item');
todoItems.forEach(item => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    item.style.transition = 'opacity 0.3s, transform 0.3s';
    
    setTimeout(() => {
        item.style.opacity = '1';
        item.style.transform = 'translateY(0)';
    }, 100);
});