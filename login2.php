<?php
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID');
$client->setClientSecret('YOUR_CLIENT_SECRET');
$client->setRedirectUri('http://localhost/login-with-google/callback.php');
$client->addScope('email');
$client->addScope('profile');

$loginUrl = $client->createAuthUrl();
?>

<a href="<?= htmlspecialchars($loginUrl) ?>">Sign Up with Google</a>