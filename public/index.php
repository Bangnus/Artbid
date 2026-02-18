<?php
date_default_timezone_set('Asia/Bangkok');

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$request = $_SERVER['REQUEST_URI'];

// Only output HTML for non-API routes
$isApiRoute = strpos($request, '/update') !== false ||
    strpos($request, '/toggle') !== false ||
    strpos($request, '/place-bid') !== false ||
    strpos($request, '/delete-profile') !== false ||
    strpos($request, '/user-details') !== false ||
    strpos($request, '/filter-artworks') !== false;

if (!$isApiRoute) {
    echo '<head><link rel="icon" type="image/png" href="' . $_ENV['APP_URL'] . '/icons/artbidlogo.png"></head>';
}

require __DIR__ . '/../src/routes/web.php';

