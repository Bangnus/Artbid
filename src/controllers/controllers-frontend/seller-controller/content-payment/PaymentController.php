<?php

class PaymentController
{
    public function payment()
    {
        require __DIR__ . '/../../../../../config/database.php';

        $title = "ประวัติการรับเงิน";
        $userId = $_SESSION['user']['id'] ?? null;
        $payments = [];

        if ($userId) {
            try {
                $pdo = dbConnect();
                $sql = "SELECT p.*, 
                               a.title as artwork_title, 
                               a.picture as artwork_image,
                               b.firstname as buyer_firstname, 
                               b.lastname as buyer_lastname
                        FROM payments p
                        LEFT JOIN artworks a ON p.artwork_id = a.id
                        LEFT JOIN users b ON p.buyer_id = b.id
                        WHERE p.seller_id = :seller_id AND p.status = 'completed'
                        ORDER BY p.created_at DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['seller_id' => $userId]);
                $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $payments = [];
            }
        }

        ob_start();
        require __DIR__ . '/../../../../views/pages/seller/content-payment/payment.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/seller.php';
    }
}
