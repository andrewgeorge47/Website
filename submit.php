<?php
require_once 'github_handler.php';

// Configuration
$config = [
    'github_token' => 'YOUR_GITHUB_TOKEN', // You'll need to create a personal access token
    'github_owner' => 'andrewgeorge',      // Your GitHub username
    'github_repo' => 'private',            // Your private repository name
    'email' => 'andrewgeorge47@gmail.com'
];

// Set headers to prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? '';
$message = $_POST['message'] ?? '';
$timestamp = date('Y-m-d H:i:s');

// Sanitize data
$name = htmlspecialchars($name);
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$role = htmlspecialchars($role);
$message = htmlspecialchars($message);

// Create data array
$data = array($timestamp, $name, $email, $role, $message);

// Initialize GitHub handler
$github = new GitHubHandler(
    $config['github_token'],
    $config['github_owner'],
    $config['github_repo']
);

// Save to GitHub
$success = $github->appendToFile($data);

if (!$success) {
    // Log error or handle failure
    error_log('Failed to save submission to GitHub');
}

// Send email notification
$to = $config['email'];
$subject = 'New Vivolere Contact Form Submission';
$emailMessage = "New form submission received:\n\n";
$emailMessage .= "Name: $name\n";
$emailMessage .= "Email: $email\n";
$emailMessage .= "Role: $role\n";
$emailMessage .= "Message: $message\n";
$emailMessage .= "Timestamp: $timestamp\n";

$headers = 'From: ' . $email . "\r\n" .
    'Reply-To: ' . $email . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $emailMessage, $headers);

// Redirect to thank you page
header('Location: thanks.html');
exit;
?> 