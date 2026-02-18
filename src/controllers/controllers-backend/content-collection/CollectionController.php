<?php
require_once __DIR__ . '/../../../../config/database.php';

class CollectionController
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            header('Location: /signin');
            exit;
        }

        $collection = self::getMyCollection($_SESSION['user']['id']);

        $title = 'คอลเลกชัน';

        ob_start();
        require __DIR__ . '/../../../views/pages/client/content-collection/collection.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/main.php';
    }

    private static function getMyCollection($userId)
    {
        try {
            $pdo = dbConnect();
            // Get artworks that user has won and completed payment
            $stmt = $pdo->prepare("SELECT p.*, 
                                          a.id as artwork_id,
                                          a.title as artwork_title,
                                          a.picture as artwork_image,
                                          a.description as artwork_description,
                                          a.artist,
                                          a.categories_id,
                                          s.firstname as seller_firstname,
                                          s.lastname as seller_lastname,
                                          p.created_at as purchase_date
                                   FROM payments p
                                   LEFT JOIN artworks a ON p.artwork_id = a.id
                                   LEFT JOIN users s ON p.seller_id = s.id
                                   WHERE p.buyer_id = :user_id AND p.status = 'completed'
                                   ORDER BY p.created_at DESC");
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
