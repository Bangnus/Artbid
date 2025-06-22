<?php

function dbConnect()
{
    $host = 'T73IK4XOW0oIIrjdYKCEUaXh4jUSjci2.singapore-postgres.render.com';
    $dbname = 'artbid_lgv1';
    $user = 'artbid';
    $password = 'T73IK4XOW0oIIrjdYKCEUaXh4jUSjci2';

    return new PDO("pgsql:host=$host;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}

try {
    $conn = dbConnect();
    if ($conn) {
        echo 'เชื่อมต่อเเล้ว';
    }
} catch (PDOException $e) {
    echo 'เชื่อมต่อไม่สำเร็จ: ' . $e->getMessage();
}
