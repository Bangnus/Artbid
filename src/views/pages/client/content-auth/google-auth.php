<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';

// session_start();

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/../../../../../credentials.json');
$baseUrl = $_ENV['APP_URL'] ?? (($_SERVER['HTTPS'] ?? 'off') === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
$client->setRedirectUri($baseUrl . '/google-callback');
$client->addScope('email');
$client->addScope('profile');

$authUrl = $client->createAuthUrl();

?>
<a href="<?= htmlspecialchars($authUrl) ?>"
    class="bg-white text-[#1E9CF5] border border-gray-300 w-full text-[14px] font-primaryMedium py-[12px]  rounded-lg flex items-center justify-center">
    <img src="/icons/google.png" alt="" class="w-6 h-auto mr-2">
    <p>
        Sign in with Google
    </p>
</a>