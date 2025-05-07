<?php

require __DIR__ . "/vendor/autoload.php";

$client = new Google\Client;

$client->setClientId("195730534849-kr4fp84dqijnsctm7dgc8eji9aq3hk6h.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost:3000/zzsample/redirect.php");
$client->addScope("email");
$client->addScope("profile");

$url = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Google Login Example</title>
</head>

<body>

    <a href="<?= $url ?>">Sign in with Google</a>

</body>

</html>