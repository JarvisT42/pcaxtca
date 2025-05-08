<?php
// Check if the current environment is local or remote
$is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

// Echo environment status
echo $is_local ? "Localhost" : "Remote server";

// Set database connection settings based on environment
if ($is_local) {
    // Local environment settings
    $servername = "srv1865.hstgr.io";
    $username = "u756490121_userpcaxtca";
    $password = "?7AdT?s!+*?S";
    $dbname = "u756490121_pcaxtca";
} else {
    // Production environment settings
    $servername = "localhost";
    $username = "u756490121_userpcaxtca";
    $password = "?7AdT?s!+*?S";
    $dbname = "u756490121_pcaxtca";
}

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
