<?php
require_once __DIR__ . '/../../../../config/database.php';

class RevenueController
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role_id'] != 3) {
            header('Location: /Artbid/public/signin');
            exit;
        }

        $revenues = self::getCompletedPayments();
        $totalRevenue = 0;
        foreach ($revenues as $r) {
            $totalRevenue += floor($r['amount'] * 0.05);
        }

        $title = 'รายงานรายได้';

        ob_start();
        require __DIR__ . '/../../../views/pages/admin/content-payment/revenue.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/admin.php';
    }

    private static function getCompletedPayments()
    {
        try {
            $pdo = dbConnect();
            // Fetch all COMPLETED payments
            // Revenue is 5% of the amount
            $stmt = $pdo->prepare("SELECT p.*, 
                                          a.title as artwork_title,
                                          s.firstname as seller_firstname,
                                          s.lastname as seller_lastname
                                   FROM payments p
                                   LEFT JOIN artworks a ON p.artwork_id = a.id
                                   LEFT JOIN users s ON p.seller_id = s.id
                                   WHERE p.status = 'completed'
                                   ORDER BY p.seller_paid_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
