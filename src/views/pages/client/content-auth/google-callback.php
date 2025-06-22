<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/../../../../../credentials.json');
$client->setRedirectUri('http://localhost/Artbid/public/google-callback'); // ให้ตรงกับ Google Console
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Get user profile info
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Store info in session (หรือเก็บใน DB ได้)
    $_SESSION['user'] = [
        'username' => $userInfo->name,
        'firstname' => $userInfo->given_name,
        'lastname' => $userInfo->family_name,
        'email' => $userInfo->email,
        'verifyemail' => $userInfo->verified_email,
        'picture' => $userInfo->picture
    ];

    // Redirect to homepage
    header('Location: home');
    exit;
} else {
    echo "Login failed.";
}
