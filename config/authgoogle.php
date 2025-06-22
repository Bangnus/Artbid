<?php
require_once __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/../credentials.json');
$client->setRedirectUri('http://localhost/Artbid/config/authgoogle.php');
$client->addScope('email');
$client->addScope('profile'); // ขอสิทธิ์เข้าถึงชื่อ + รูป

$authUrl = $client->createAuthUrl();
header("Location: $authUrl");
exit;
