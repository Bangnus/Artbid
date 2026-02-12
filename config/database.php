<?php

// ตั้งค่า Timezone เป็น Asia/Bangkok
date_default_timezone_set('Asia/Bangkok');

if (!function_exists('dbConnect')) {
    function dbConnect()
    {
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];

        return new PDO("pgsql:host=$host;dbname=$dbname;sslmode=require", $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}

try {
    $conn = dbConnect();
    if ($conn) {
        // echo 'เชื่อมต่อเเล้ว';
    }
} catch (PDOException $e) {
    echo 'เชื่อมต่อไม่สำเร็จ: ' . $e->getMessage();
}
