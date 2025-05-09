<?php
// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    return htmlspecialchars(stripcslashes(trim($conn->real_escape_string($data))));
}

// Function to display error messages
function displayError($message) {
    return '<div class="error-message">' . $message . '</div>';
}

// Function to display success messages
function displaySuccess($message) {
    return '<div class="success-message">' . $message . '</div>';
}
?>