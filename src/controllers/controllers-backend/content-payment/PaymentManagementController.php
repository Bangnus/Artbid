<?php
require_once __DIR__ . '/../../../../config/database.php';

class PaymentManagementController
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $payments = self::getPayments();
        $title = 'จัดการรายการโอนเงิน';
        $content = $this->renderContent($payments);
        require __DIR__ . '/../../../views/layouts/admin.php';
    }

    private function renderContent($payments)
    {
        ob_start();
        include __DIR__ . '/../../../views/pages/admin/content-payment/manage-payments.php';
        return ob_get_clean();
    }

    public static function getPayments($status = null)
    {
        try {
            $pdo = dbConnect();

            $sql = "SELECT p.*, 
                           a.title as artwork_title, 
                           a.picture as artwork_image,
                           b.firstname as buyer_firstname, 
                           b.lastname as buyer_lastname,
                           b.email as buyer_email,
                           s.firstname as seller_firstname, 
                           s.lastname as seller_lastname,
                           s.email as seller_email,
                           up.promptpay_number as seller_promptpay
                    FROM payments p
                    LEFT JOIN artworks a ON p.artwork_id = a.id
                    LEFT JOIN users b ON p.buyer_id = b.id
                    LEFT JOIN users s ON p.seller_id = s.id
                    LEFT JOIN user_payments up ON s.id = up.user_id
                    WHERE p.status NOT IN ('expired', 'pending_buyer_payment')
                    ORDER BY p.created_at DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function verifyPayment()
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
            $paymentId = $input['payment_id'] ?? null;

            if (!$paymentId) {
                echo json_encode(['success' => false, 'message' => 'Payment ID required']);
                exit;
            }

            $pdo = dbConnect();
            $stmt = $pdo->prepare("UPDATE payments 
                                   SET status = 'verified', 
                                       admin_verified_at = NOW(),
                                       admin_verified_by = :admin_id,
                                       updated_at = NOW()
                                   WHERE id = :payment_id AND status = 'pending_admin_verify'");

            $stmt->execute([
                ':admin_id' => $_SESSION['user']['id'],
                ':payment_id' => $paymentId
            ]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'ยืนยันรับเงินสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่พบรายการหรือสถานะไม่ถูกต้อง']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function markPaid()
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
            $paymentId = $input['payment_id'] ?? null;

            if (!$paymentId) {
                echo json_encode(['success' => false, 'message' => 'Payment ID required']);
                exit;
            }

            $pdo = dbConnect();
            $stmt = $pdo->prepare("UPDATE payments 
                                   SET status = 'completed', 
                                       seller_paid_at = NOW(),
                                       updated_at = NOW()
                                   WHERE id = :payment_id AND status = 'verified'");

            $stmt->execute([':payment_id' => $paymentId]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'ยืนยันโอนเงินให้ผู้ขายสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่พบรายการหรือสถานะไม่ถูกต้อง']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function rejectPayment()
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
            $paymentId = $input['payment_id'] ?? null;
            $notes = $input['notes'] ?? '';

            if (!$paymentId) {
                echo json_encode(['success' => false, 'message' => 'Payment ID required']);
                exit;
            }

            $pdo = dbConnect();
            $stmt = $pdo->prepare("UPDATE payments 
                                   SET status = 'rejected', 
                                       admin_notes = :notes,
                                       updated_at = NOW()
                                   WHERE id = :payment_id");

            $stmt->execute([
                ':payment_id' => $paymentId,
                ':notes' => $notes
            ]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'ปฏิเสธรายการสำเร็จ']);
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่พบรายการ']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
