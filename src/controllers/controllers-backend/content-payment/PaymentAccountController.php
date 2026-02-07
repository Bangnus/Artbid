<?php
require_once __DIR__ . '/../../../../config/database.php';

class PaymentAccountController
{
    // System user UUID for shared admin payment account
    const SYSTEM_USER_ID = '00000000-0000-0000-0000-000000000000';

    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $settings = self::getSettings();
        $title = 'จัดการบัญชีโอนเงิน';
        $content = $this->renderContent($settings);
        require __DIR__ . '/../../../views/layouts/admin.php';
    }

    private function renderContent($settings)
    {
        ob_start();
        include __DIR__ . '/../../../views/pages/admin/content-payment/manage-account.php';
        return ob_get_clean();
    }

    public function update()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');

        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            $pdo = dbConnect();

            // Use SYSTEM_USER_ID for shared admin account in user_payments table
            $stmt = $pdo->prepare("INSERT INTO user_payments (user_id, promptpay_number, updated_at) 
                                   VALUES (:user_id, :promptpay_number, NOW()) 
                                   ON CONFLICT (user_id) DO UPDATE 
                                   SET promptpay_number = EXCLUDED.promptpay_number, updated_at = NOW()");

            $stmt->execute([
                ':user_id' => self::SYSTEM_USER_ID,
                ':promptpay_number' => isset($input['promptpay_number']) ? trim($input['promptpay_number']) : ''
            ]);

            echo json_encode(['success' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }

    private static function getSettings()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            return ['promptpay_number' => ''];
        }

        try {
            $pdo = dbConnect();
            // Use SYSTEM_USER_ID for shared admin account
            $stmt = $pdo->prepare("SELECT promptpay_number FROM user_payments WHERE user_id = :user_id");
            $stmt->execute([':user_id' => self::SYSTEM_USER_ID]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'promptpay_number' => $row['promptpay_number'] ?? ''
            ];
        } catch (Exception $e) {
            return ['promptpay_number' => ''];
        }
    }

    // Method for client-side user-specific PromptPay update
    public function updateForUser()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');

        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $pdo = dbConnect();

            $stmt = $pdo->prepare("INSERT INTO user_payments (user_id, promptpay_number, updated_at) 
                                   VALUES (:user_id, :promptpay_number, NOW()) 
                                   ON CONFLICT (user_id) DO UPDATE 
                                   SET promptpay_number = EXCLUDED.promptpay_number, updated_at = NOW()");

            $stmt->execute([
                ':user_id' => $_SESSION['user']['id'],
                ':promptpay_number' => isset($input['promptpay_number']) ? trim($input['promptpay_number']) : ''
            ]);

            echo json_encode(['success' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }
}
