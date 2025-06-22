<?php
require __DIR__ . '/../../../../config/database.php';


class AuthController
{

    public static function register($username, $password, $firstname, $lastname, $email, $role_id = 0)
    {
        try {
            $pdo = dbConnect();

            // ตรวจสอบ username ซ้ำ
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            if ($stmt->fetch()) {
                echo "Username already exists";
                return false;
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("
            INSERT INTO users 
            (id, username, firstname, lastname, email, password, verifyemail, role_id)
            VALUES (gen_random_uuid(), :username, :firstname, :lastname, :email, :password, false, :role_id)
        ");
            $result = $stmt->execute([
                'username'   => $username,
                'firstname'  => $firstname,
                'lastname'   => $lastname,
                'email'      => $email,
                'password'   => $hashedPassword,
                'role_id'    => $role_id
            ]);

            if ($result) {
                echo "Register success";
                return true;
            } else {
                echo "Register failed";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
            return false;
        }
    }
    // public static function login($username, $password) {
    //     $pdo = dbConnect();
    //     $stmt = $pdo->prepare("")
    // }
}
