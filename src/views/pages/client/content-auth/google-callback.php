<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';
require __DIR__ . '/../../../../../config/database.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/../../../../../credentials.json');
$client->setRedirectUri('http://localhost/Artbid/public/google-callback'); // ให้ตรงกับ Google Console
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Get user profile info
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();



    try {
        $pdo = dbConnect();

        $check = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $check->execute(['email' => $userInfo->email]);
        $existingUser = $check->fetch();

        if (!$existingUser) {
            $stmt = $pdo->prepare(
                "
            INSERT INTO users
             (id, username, firstname, lastname, email, password, verifyemail, role_id, picture)
            VALUES 
            (
            gen_random_uuid(), 
            :username, 
            :firstname, 
            :lastname, 
            :email, 
            '',
            :verifyemail, 
            '1', 
            :picture)
            "
            );

            $stmt->execute([
                'username' => $userInfo->name,
                'firstname' => $userInfo->given_name,
                'lastname'    => $userInfo->family_name,
                'email'       => $userInfo->email,
                'verifyemail' => $userInfo->verified_email ? 'true' : 'false',
                'picture'     => $userInfo->picture
            ]);
        }

        $checkid = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $checkid->execute(['email' => $userInfo->email]);
        $user = $checkid->fetch();

        $_SESSION['user'] = [
            'id'         => $user['id'],
            'username'   => $user['username'],
            'firstname'  => $user['firstname'],
            'lastname'   => $user['lastname'],
            'email'      => $user['email'],
            'verifyemail' => $user['verifyemail'],
            'role_id' => $user['role_id'],
            'picture'    => $user['picture']

        ];
        $_SESSION['sign_in_success'] = true;
        header('Location: home');
        exit;
    } catch (PDOException $e) {
        echo "Error:" . $e->getMessage();
        return false;
    }
} else {
    echo "Login failed.";
}
