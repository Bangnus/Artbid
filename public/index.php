<?php
date_default_timezone_set('Asia/Bangkok');

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Init Database Session
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/config/DatabaseSessionHandler.php';

try {
    $pdo = dbConnect();
    if ($pdo) {
        $handler = new DatabaseSessionHandler($pdo);
        session_set_save_handler($handler, true);
    }
} catch (Exception $e) {
    error_log("Session Handler Error: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$request = $_SERVER['REQUEST_URI'];

// Only output HTML for non-API routes
$isApiRoute = strpos($request, '/update') !== false ||
    strpos($request, '/toggle') !== false ||
    strpos($request, '/place-bid') !== false ||
    strpos($request, '/delete-profile') !== false ||
    strpos($request, '/user-details') !== false ||
    strpos($request, '/filter-artworks') !== false;

if (!$isApiRoute) {
    // echo '<head><link rel="icon" type="image/png" href="/icons/artbidlogo.png"></head>';
}

require __DIR__ . '/../src/routes/web.php';
