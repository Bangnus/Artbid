<?php

// ตั้งค่า Timezone เป็น Asia/Bangkok
date_default_timezone_set('Asia/Bangkok');

function dbConnect()
{
    $host = 'dpg-d4hci0ili9vc73e4bq7g-a.singapore-postgres.render.com';
    $dbname = 'artbid_yl26';
    $user = 'artbid';   
    $password = 'nT3nEWXo94Eso5HNvz65cwmuAFprHAUZ';

    return new PDO("pgsql:host=$host;dbname=$dbname;sslmode=require", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}

try {
    $conn = dbConnect();
    if ($conn) {
        // echo 'เชื่อมต่อเเล้ว';
    }
} catch (PDOException $e) {
    echo 'เชื่อมต่อไม่สำเร็จ: ' . $e->getMessage();
}
