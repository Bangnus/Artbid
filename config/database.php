<?php

// ตั้งค่า Timezone เป็น Asia/Bangkok
date_default_timezone_set('Asia/Bangkok');

function dbConnect()
{
    $host = 'dpg-d62d46vpm1nc73b5mbpg-a.singapore-postgres.render.com';
    $dbname = 'artbid_27qn';
    $user = 'artbid';
    $password = 'D9IfIuc4B8qcRgQIBhSgAMBSGy6sRwJW';

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
