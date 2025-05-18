<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$is_local = strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;

if ($is_local) {
    $request_uri = $_SERVER['REQUEST_URI'];

    // If it doesn't contain '.php' and doesn't point to a file directly
    if (!preg_match('/\.php$/', $request_uri) && !is_file(__DIR__ . $request_uri)) {
        // Try appending .php and see if that file exists
        $possible_php = __DIR__ . $request_uri . '.php';
        if (file_exists($possible_php)) {
            header("Location: " . $request_uri . ".php");
            exit;
        }
    }
}

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it is not started
}


$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!-- Title  -->
    <title>Essence - Fashion Ecommerce Template</title>

    <!-- Favicon  -->
    <link rel="icon" href="../img/core-img/favicon.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="../bootstrap-5.3.6/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/core-style.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <style>
        .slider-range-price {
            margin: 20px 0;
            height: 10px;
        }
    </style>
    
</head>