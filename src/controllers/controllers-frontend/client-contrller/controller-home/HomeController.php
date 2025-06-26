<?php

class HomeController
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
