<?php
session_start(); // Start the session
session_destroy(); // Destroy the session

// Redirect to the login page
header("Location: ../index.html"); // Move up one level to the root directory
exit(); // Stop further script execution after redirect
?>
