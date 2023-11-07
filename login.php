<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password are correct
    if ($username === 'admin@gmail.com' && $password === 'admin.2023') {
        // Store the username in the session
        $_SESSION['username'] = $username;

        // Redirect to the admin dashboard
        header('Location: index.php');
        exit;
    } else {
        // Show an error message
        echo "Invalid username or password. Please try again.";
    }
}

if (isset($_GET['logout'])) {
    session_unset(); // Unset all of the session variables
    session_destroy(); // Destroy the session
    header('Location: login.php'); // Redirect to the login page
    exit;
}
?>
