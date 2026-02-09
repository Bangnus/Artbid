<?php
require_once __DIR__ . '/../../../../../config/database.php';

class SettingsController
{
    public static function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: signin');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $hasPassword = false;

        try {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->execute([':id' => $userId]);
            $user = $stmt->fetch();

            if ($user && !empty($user['password'])) {
                $hasPassword = true;
            }

            // Fetch user's PromptPay number
            $stmtPayment = $pdo->prepare("SELECT promptpay_number FROM user_payments WHERE user_id = :user_id");
            $stmtPayment->execute([':user_id' => $userId]);
            $paymentRow = $stmtPayment->fetch(PDO::FETCH_ASSOC);
            $userPromptpay = $paymentRow['promptpay_number'] ?? '';
        } catch (PDOException $e) {
            error_log("Error checking password: " . $e->getMessage());
            $userPromptpay = '';
        }

        $title = "การตั้งค่า";

        ob_start();
        require __DIR__ . '/../../../../views/pages/client/content-settings/settings.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/main.php';
    }

    public static function updateProfile()
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $username = $_POST['username'] ?? '';
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';

        try {
            $pdo = dbConnect();

            // Check if user has a password (not Google-only user)
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->execute([':id' => $userId]);
            $user = $stmt->fetch();
            $hasPassword = $user && !empty($user['password']);

            // Only verify password if user wants to CHANGE password
            if (!empty($newPassword)) {
                if ($hasPassword) {
                    if (empty($currentPassword)) {
                        $_SESSION['field_errors']['current_password'] = "กรุณากรอกรหัสผ่านปัจจุบัน";
                        header('Location: settings');
                        exit;
                    }

                    if (!password_verify($currentPassword, $user['password'])) {
                        $_SESSION['field_errors']['current_password'] = "รหัสผ่านปัจจุบันไม่ถูกต้อง";
                        header('Location: settings');
                        exit;
                    }
                }
            }

            // Handle Profile Picture Upload
            $picturePath = null;
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
                $fileName = $_FILES['profile_picture']['name'];
                $fileSize = $_FILES['profile_picture']['size'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                // File size limit: 2MB
                $maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
                if ($fileSize > $maxFileSize) {
                    $_SESSION['field_errors']['profile_picture'] = "ขนาดไฟล์ต้องไม่เกิน 2MB";
                    header('Location: settings');
                    exit;
                }

                $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
                if (!in_array($fileExtension, $allowedfileExtensions)) {
                    $_SESSION['field_errors']['profile_picture'] = "รองรับเฉพาะไฟล์ jpg, png, gif, webp";
                    header('Location: settings');
                    exit;
                }

                // Read valid image file
                $imageData = file_get_contents($fileTmpPath);
                $base64 = base64_encode($imageData);
                $mimeType = mime_content_type($fileTmpPath);

                // Create Base64 URI
                $picturePath = 'data:' . $mimeType . ';base64,' . $base64;
            }

            // Update basic info
            $query = "UPDATE users SET firstname = :firstname, lastname = :lastname";
            $params = [
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':id' => $userId
            ];

            // Only update username if it was submitted (i.e. user has password/not Google login)
            if (isset($_POST['username'])) {
                $query .= ", username = :username";
                $params[':username'] = $_POST['username'];
            }

            if ($picturePath) {
                $query .= ", picture = :picture";
                $params[':picture'] = $picturePath;
            }

            // Update password if new password provided
            if (!empty($newPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $query .= ", password = :password";
                $params[':password'] = $hashedPassword;
            }

            $query .= " WHERE id = :id";

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            // Update session
            $_SESSION['user']['firstname'] = $firstname;
            $_SESSION['user']['lastname'] = $lastname;

            if (isset($_POST['username'])) {
                $_SESSION['user']['username'] = $_POST['username'];
            }

            if ($picturePath) {
                $_SESSION['user']['picture'] = $picturePath;
            }

            $_SESSION['swal_success'] = "บันทึกข้อมูลบัญชีเรียบร้อยแล้ว";

            header('Location: settings');
            exit;
        } catch (PDOException $e) {
            error_log("Error updating profile: " . $e->getMessage());
            header('Location: settings');
            exit;
        }
    }

    public static function deleteProfilePicture()
    {
        // Clear any buffered output (like HTML headers)
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        try {
            $userId = $_SESSION['user']['id'];
            $pdo = dbConnect();

            $stmt = $pdo->prepare("UPDATE users SET picture = NULL WHERE id = :id");
            $stmt->execute([':id' => $userId]);

            $_SESSION['user']['picture'] = null;
            $_SESSION['swal_success'] = "ลบรูปโปรไฟล์เรียบร้อยแล้ว";

            echo json_encode(['success' => true]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    public static function updateAddress()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: signin');
            exit;
        }

        $userId = $_SESSION['user']['id'];

        try {
            $pdo = dbConnect();

            $stmt = $pdo->prepare("UPDATE users SET address = :address, city = :city, amphoe = :amphoe, province = :province, postal_code = :postal_code, phone = :phone WHERE id = :id");
            $stmt->execute([
                ':address' => $_POST['address'] ?? '',
                ':city' => $_POST['city'] ?? '',
                ':amphoe' => $_POST['amphoe'] ?? '',
                ':province' => $_POST['province'] ?? '',
                ':postal_code' => $_POST['postal_code'] ?? '',
                ':phone' => $_POST['phone'] ?? '',
                ':id' => $userId
            ]);

            // Update session
            $_SESSION['user']['address'] = $_POST['address'] ?? '';
            $_SESSION['user']['city'] = $_POST['city'] ?? '';
            $_SESSION['user']['amphoe'] = $_POST['amphoe'] ?? '';
            $_SESSION['user']['province'] = $_POST['province'] ?? '';
            $_SESSION['user']['postal_code'] = $_POST['postal_code'] ?? '';
            $_SESSION['user']['phone'] = $_POST['phone'] ?? '';

            $_SESSION['swal_success'] = "บันทึกข้อมูลเรียบร้อยแล้ว";

            header('Location: settings?tab=address');
            exit;
        } catch (PDOException $e) {
            error_log("Error updating address: " . $e->getMessage());
            header('Location: settings?tab=address');
            exit;
        }
    }
}
