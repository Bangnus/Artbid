<?php

class LandingController
{
    public function index()
    {
        $title = "Home Page";

        // ดึงเนื้อหาจาก views/pages/home.php
        ob_start();
        require __DIR__ . '/../../../../../src/views/pages/client/content-home/home.php';
        $content = ob_get_clean();

        // แสดง layout พร้อม $title และ $content
        require __DIR__ . '/../../../../../src/views/layouts/main.php';
    }
}
class HomeController
{
    public function index()
    {
        $title = "Home Page";
        $mainClass = 'w-full';

        // Update status on page load (moved from getAllArtwork2 for performance)
        require_once __DIR__ . '/../../../../../src/controllers/controllers-backend/content-client/ClientController.php';
        $pdo = dbConnect();
        ClientController::updateArtworkStatus($pdo);

        // ดึงเนื้อหาจาก views/pages/home.php
        ob_start();
        require __DIR__ . '/../../../../../src/views/pages/client/content-home/mainhome.php';
        $content = ob_get_clean();

        // แสดง layout พร้อม $title และ $content
        require __DIR__ . '/../../../../../src/views/layouts/main.php';
    }
}
