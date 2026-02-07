<?php

require __DIR__ . '/../../../../../config/database.php';

class UserManagementController
{
    public function index()
    {
        $pdo = dbConnect();

        // Get all users
        $stmt = $pdo->prepare("SELECT id, firstname, lastname, username, email, role_id, picture, verifyemail, create_at FROM users ORDER BY create_at DESC");
        $stmt->execute();
        $users = $stmt->fetchAll();

        $title = 'จัดการผู้ใช้';
        $content = $this->renderContent($users);
        require __DIR__ . '/../../../../views/layouts/admin.php';
    }

    private function renderContent($users)
    {
        ob_start();
        include __DIR__ . '/../../../../views/pages/admin/content-users/users.php';
        return ob_get_clean();
    }

    public static function getUserDetails()
    {
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');

        if (!isset($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'User ID required']);
            exit;
        }

        try {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute([':id' => $_GET['id']]);
            $user = $stmt->fetch();

            if ($user) {
                // Remove password from response
                unset($user['password']);
                echo json_encode(['success' => true, 'user' => $user]);
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public static function updateUserRole()
    {
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['user_id']) || !isset($data['role_id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        try {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("UPDATE users SET role_id = :role_id WHERE id = :id");
            $stmt->execute([':role_id' => $data['role_id'], ':id' => $data['user_id']]);

            echo json_encode(['success' => true, 'message' => 'อัปเดต Role สำเร็จ']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public static function createUser()
    {
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // Validation
        if (empty($data['firstname']) || empty($data['lastname']) || empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
            exit;
        }

        $role_id = isset($data['role_id']) ? intval($data['role_id']) : 1;

        try {
            $pdo = dbConnect();

            // Check duplicate email or username
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
            $stmt->execute([':email' => $data['email'], ':username' => $data['username']]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'อีเมลหรือชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว']);
                exit;
            }

            // Insert new user
            $sql = "INSERT INTO users (firstname, lastname, username, email, password, role_id, create_at, verifyemail) 
                    VALUES (:firstname, :lastname, :username, :email, :password, :role_id, NOW(), 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':firstname' => $data['firstname'],
                ':lastname' => $data['lastname'],
                ':username' => $data['username'],
                ':email' => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
                ':role_id' => $role_id
            ]);

            echo json_encode(['success' => true, 'message' => 'สร้างผู้ใช้สำเร็จ']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }
}
