<?php

require __DIR__ . "/vendor/autoload.php";

$client = new Google\Client;

$client->setClientId("195730534849-kr4fp84dqijnsctm7dgc8eji9aq3hk6h.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost:3000/zzsample/redirect.php");

if (! isset($_GET["code"])) {
    exit("Login failed");
}

$token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);

$client->setAccessToken($token["access_token"]);

$oauth = new Google\Service\Oauth2($client);

$userinfo = $oauth->userinfo->get();

var_dump(
    $userinfo->id,

    $userinfo->email,
    $userinfo->familyName,
    $userinfo->givenName,
    $userinfo->name
);
