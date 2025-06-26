<?php
require __DIR__ . '/../../../../../config/database.php';

if (isset($_SESSION['user']['id'])) {
    $pdo = dbConnect();

    $userId = $_SESSION['user']['id'];

    $stmt = $pdo->prepare("UPDATE users SET verifyemail = true WHERE id = ?");
    $stmt->execute([$userId]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :userId");
    $stmt->execute(['userId' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['user'] = [
        'id'          => $user['id'],
        'username'    => $user['username'],
        'firstname'   => $user['firstname'],
        'lastname'    => $user['lastname'],
        'email'       => $user['email'],
        'role_id'     => $user['role_id'],
        'verifyemail' => $user['verifyemail'],
        'picture'     => $user['picture'] ?? null
    ];

    header('Location: seller');
} else {
    echo "❌ ไม่มีข้อมูลผู้ใช้ หรือ id ไม่ถูกต้อง";
}
