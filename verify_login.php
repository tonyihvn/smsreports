<?php
session_start();

// Sample hardcoded credentials (replace these with actual credentials)
$validUsername = "user";
$validPassword = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $validUsername && password_verify($password,$validPassword)) {
        // Valid credentials, set the session variable to indicate logged-in status
        $_SESSION['logged_in'] = true;
        header("Location: index.php"); // Redirect to your main page
        exit();
    } else {
        // Invalid credentials, redirect back to login page or show an error message
        header("Location: login.php"); // Redirect back to login page
        exit();
    }
}
?>
