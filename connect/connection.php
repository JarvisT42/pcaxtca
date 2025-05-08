<?php
// Check if the current environment is local or remote
$is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

// Echo environment status

// Set database connection settings based on environment
if ($is_local) {
  $servername = "srv1865.hstgr.io";
} else {
  $servername = "localhost";
}



// Production environment settings

$username = "u756490121_userpcaxtca";
$password = "?7AdT?s!+*?S";
$dbname = "u756490121_pcaxtca";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
