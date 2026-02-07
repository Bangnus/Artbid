<?php
require_once __DIR__ . '/../../../../config/database.php';

class BuyerPaymentController
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            header('Location: /Artbid/public/signin');
            exit;
        }

        $payments = self::getMyPayments($_SESSION['user']['id']);
        $adminPromptpay = self::getAdminPromptpay();

        $title = 'ประวัติการชำระเงิน';

        ob_start();
        require __DIR__ . '/../../../views/pages/client/content-payment/my-payments.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/main.php';
    }

    public function singlePayment()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            header('Location: /Artbid/public/signin');
            exit;
        }

        $artworkId = $_GET['artwork_id'] ?? null;
        if (!$artworkId) {
            header('Location: /Artbid/public/my-auctions');
            exit;
        }

        $payment = self::getPaymentByArtwork($_SESSION['user']['id'], $artworkId);
        $adminPromptpay = self::getAdminPromptpay();

        $title = 'ชำระเงิน';

        ob_start();
        require __DIR__ . '/../../../views/pages/client/content-payment/payment-single.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/main.php';
    }

    private static function getPaymentByArtwork($userId, $artworkId)
    {
        try {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("SELECT p.*, 
                                          a.title as artwork_title,
                                          a.picture as artwork_image,
                                          s.firstname as seller_firstname,
                                          s.lastname as seller_lastname
                                   FROM payments p
                                   LEFT JOIN artworks a ON p.artwork_id = a.id
                                   LEFT JOIN users s ON p.seller_id = s.id
                                   WHERE p.buyer_id = :user_id AND p.artwork_id = :artwork_id
                                   LIMIT 1");
            $stmt->execute([':user_id' => $userId, ':artwork_id' => $artworkId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    private static function getMyPayments($userId)
    {
        try {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("SELECT p.*, 
                                          a.title as artwork_title,
                                          a.picture as artwork_image,
                                          s.firstname as seller_firstname,
                                          s.lastname as seller_lastname
                                   FROM payments p
                                   LEFT JOIN artworks a ON p.artwork_id = a.id
                                   LEFT JOIN users s ON p.seller_id = s.id
                                   WHERE p.buyer_id = :user_id
                                   ORDER BY p.created_at DESC");
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    private static function getAdminPromptpay()
    {
        try {
            $pdo = dbConnect();
            // Get admin's promptpay (role_id = 3 is admin)
            $stmt = $pdo->prepare("SELECT up.promptpay_number 
                                   FROM user_payments up
                                   JOIN users u ON up.user_id = u.id
                                   WHERE u.role_id = 3
                                   LIMIT 1");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['promptpay_number'] ?? '';
        } catch (Exception $e) {
            return '';
        }
    }

    public function uploadSlip()
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
            $slipImage = $input['slip_image'] ?? null;

            if (!$paymentId || !$slipImage) {
                echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบ']);
                exit;
            }

            $pdo = dbConnect();

            // Verify this payment belongs to the current user
            $stmt = $pdo->prepare("SELECT id FROM payments WHERE id = :payment_id AND buyer_id = :buyer_id AND status = 'pending_buyer_payment'");
            $stmt->execute([
                ':payment_id' => $paymentId,
                ':buyer_id' => $_SESSION['user']['id']
            ]);

            if (!$stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'ไม่พบรายการหรือไม่มีสิทธิ์']);
                exit;
            }

            // Update payment with slip
            $stmt = $pdo->prepare("UPDATE payments 
                                   SET buyer_slip_image = :slip,
                                       buyer_payment_at = NOW(),
                                       status = 'pending_admin_verify',
                                       updated_at = NOW()
                                   WHERE id = :payment_id");

            $stmt->execute([
                ':slip' => $slipImage,
                ':payment_id' => $paymentId
            ]);

            echo json_encode(['success' => true, 'message' => 'อัปโหลดสลิปสำเร็จ รอ Admin ตรวจสอบ']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
