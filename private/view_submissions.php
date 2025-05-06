<?php
// Basic authentication
$username = 'admin';
$password = 'your-secure-password'; // Change this to a secure password

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $username || $_SERVER['PHP_AUTH_PW'] !== $password) {
    header('WWW-Authenticate: Basic realm="Submissions"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
}

// Read and display CSV file
$csvFile = 'submissions.csv';
if (file_exists($csvFile)) {
    echo '<html><head><title>Form Submissions</title>';
    echo '<style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style></head><body>';
    
    echo '<h1>Form Submissions</h1>';
    echo '<table>';
    
    $handle = fopen($csvFile, 'r');
    $headers = fgetcsv($handle);
    
    // Print headers
    echo '<tr>';
    foreach ($headers as $header) {
        echo '<th>' . htmlspecialchars($header) . '</th>';
    }
    echo '</tr>';
    
    // Print data
    while (($data = fgetcsv($handle)) !== FALSE) {
        echo '<tr>';
        foreach ($data as $cell) {
            echo '<td>' . htmlspecialchars($cell) . '</td>';
        }
        echo '</tr>';
    }
    
    fclose($handle);
    echo '</table></body></html>';
} else {
    echo 'No submissions yet.';
}
?> 