<?php
require __DIR__ . '/../../../../config/database.php';


class AuthController
{

    public static function signup($username, $password, $firstname, $lastname, $email, $role_id = 1)
    {
        try {
            $pdo = dbConnect();

            // ตรวจสอบ username ซ้ำ
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username, 'email' => $email]);
            if ($stmt->fetch()) {
                echo "<script>
            const notyf = new Notyf({position: { x: 'right', y: 'top' });
            notyf.error('Username or Email already exists!');
            setTimeout(function() {
                window.location.href = 'signin';
            }, 1500);
        </script>";
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
                // ดึงข้อมูล user กลับมาใหม่
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
                $stmt->execute(['username' => $username]);
                $user = $stmt->fetch();

                session_start();
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

    public static function signin($username, $password)
    {
        $pdo = dbConnect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = [
                'id'        => $user['id'],
                'username'  => $user['username'],
                'firstname' => $user['firstname'],
                'lastname'  => $user['lastname'],
                'email'     => $user['email'],
                'role_id'   => $user['role_id'],
                'verifyemail' => $user['verifyemail'],
                'picture'
            ];
            return true;
        } else {
            return false;
        }
    }
}
