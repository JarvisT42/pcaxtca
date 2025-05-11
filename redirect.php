<?php
// Show errors (for development only — disable on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Composer autoload and DB connection
require __DIR__ . "/vendor/autoload.php";
require "connect/connection.php"; // should define $conn = new mysqli(...)

session_start();

// Setup Google Client
$is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

// Initialize Google Client
$client = new Google\Client();
$client->setClientId("195730534849-kr4fp84dqijnsctm7dgc8eji9aq3hk6h.apps.googleusercontent.com");

if ($is_local) {
    $client->setRedirectUri("http://localhost:3000/redirect.php");
} else {
    $client->setRedirectUri("http://pcaxtca.shop/redirect.php");
}

$client->setClientSecret("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");
$client->addScope("email");
$client->addScope("profile");





// Check for code from Google
if (!isset($_GET["code"])) {
    exit("Login failed: No authorization code received.");
}

// Get state (signin or signup)
$mode = $_GET["state"] ?? "signin";

// Fetch access token
$token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);

// Debug token (optional: comment this out in production)
if (isset($token["error"])) {
    var_dump($token);
    exit("Error fetching token: " . $token["error_description"]);
}

$client->setAccessToken($token["access_token"]);

// Get user info
$oauth = new Google\Service\Oauth2($client);
$userinfo = $oauth->userinfo->get();

$email = $userinfo->email;
$name = $userinfo->name;

// Handle signup or signin
if ($mode === "signup") {
    // Insert or update user
    $stmt = $conn->prepare("SELECT name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Already registered, redirect back with message
        header("Location: sign-up?registered_already=1");
        exit;
    }
    $auth_provider = "google";
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (email, name, auth_provider) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $name, $auth_provider);

    if ($stmt->execute()) {

        header("Location: user/index");
    } else {
        echo "Error inserting user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
} else {
    // Signin process
    $stmt = $conn->prepare("SELECT id, email, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_logged_in'] = true; // ✅ Add this
        header("Location: shop");
        exit;
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (email, name) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $name);

    if ($stmt->execute()) {
        header("Location: user/dashboard");
    } else {
        echo "Error inserting user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}
