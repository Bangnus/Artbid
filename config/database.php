<?php

// ตั้งค่า Timezone เป็น Asia/Bangkok
date_default_timezone_set('Asia/Bangkok');

function dbConnect()
{
    $host = 'dpg-d288q795pdvs738agmn0-a.singapore-postgres.render.com';
    $dbname = 'artbid_vp0j';
    $user = 'artbid';
    $password = 'a30qAwwqNb6yodDRQbYYAxbKWwDbD3pv';

    return new PDO("pgsql:host=$host;dbname=$dbname", $user, $password, [
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
