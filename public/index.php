<?php
date_default_timezone_set('Asia/Bangkok');
$request = $_SERVER['REQUEST_URI'];

// Only output HTML for non-API routes
$isApiRoute = strpos($request, '/update') !== false ||
    strpos($request, '/toggle') !== false ||
    strpos($request, '/place-bid') !== false ||
    strpos($request, '/delete-profile') !== false ||
    strpos($request, '/user-details') !== false;

if (!$isApiRoute) {
    // echo '<head><link rel="icon" type="image/png" href="/Artbid/public/icons/artbidlogo.png"></head>';
}

require __DIR__ . '/../src/routes/web.php';

