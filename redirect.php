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
$firstName = $userinfo->givenName;
$lastName = $userinfo->familyName;
// Handle signup or signin
if ($mode === "signup") {
    // Insert or update user
    $stmt = $conn->prepare("SELECT name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Already registered, redirect back with message
        header("Location: sign-up.php?registered_already=1");
        exit;
    }
    $auth_provider = "google";
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (email, name, first_name, last_name, auth_provider) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $email, $name, $firstName, $lastName, $auth_provider);

    if ($stmt->execute()) {
        $id = $stmt->insert_id; // Get inserted user's ID

        // Set session variables
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $email;
        $_SESSION['user_logged_in'] = true;

        header("Location: shop");
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

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        // Bind result variables
        $stmt->bind_result($id, $user_email, $name);
        $stmt->fetch();

        // Store user data in session variables
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $user_email;
        $_SESSION['user_logged_in'] = true; // ✅ Add this

        // Redirect to shop
        header("Location: shop");
        exit;
    } else {
        // User not found, redirect to signup
        header("Location: sign-in.php?account_0=1");
        exit;
    }


    $stmt->close();
    $conn->close();
    exit;
}
