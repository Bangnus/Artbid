<?php

class MyAuctionsController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: ' . $_ENV['APP_URL'] . '/signin');
            exit;
        }

        require_once __DIR__ . '/../../../../../config/database.php';
        $pdo = dbConnect();
        $userId = $_SESSION['user']['id'];

        // Process ended auctions first (create payment records)
        self::processEndedAuctions($pdo);

        // Get won auctions (auction ended and user is highest bidder)
        $wonAuctions = [];
        try {
            $stmt = $pdo->prepare("
                SELECT a.*, 
                       CASE 
                           WHEN p.id IS NOT NULL THEN p.status 
                           ELSE 'pending_buyer_payment' 
                       END as payment_status
                FROM artworks a
                LEFT JOIN payments p ON p.artwork_id = a.id AND p.buyer_id = :user_id
                WHERE a.bidby = :user_id2 
                AND a.endbidding::timestamp <= (NOW() AT TIME ZONE 'Asia/Bangkok')
                ORDER BY a.endbidding DESC
            ");
            $stmt->execute([':user_id' => $userId, ':user_id2' => $userId]);
            $wonAuctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $wonAuctions = [];
        }

        // Get active bids (auctions user is currently bidding on)
        $activeBids = [];
        try {
            $stmt = $pdo->prepare("
                SELECT a.*, 
                       CASE WHEN a.bidby = :user_id THEN true ELSE false END as is_highest
                FROM artworks a
                WHERE a.bidby = :user_id2
                AND a.endbidding::timestamp > (NOW() AT TIME ZONE 'Asia/Bangkok')
                AND a.type = 'Live'
                AND a.status = true
                ORDER BY a.endbidding ASC
            ");
            $stmt->execute([':user_id' => $userId, ':user_id2' => $userId]);
            $activeBids = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $activeBids = [];
        }

        $title = "การประมูลของฉัน";

        ob_start();
        require __DIR__ . '/../../../../views/pages/client/content-auctions/my-auctions.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/main.php';
    }

    private static function processEndedAuctions($pdo)
    {
        try {
            // Find ended auctions with a bidder but no payment record yet
            $stmt = $pdo->prepare("
                SELECT a.id, a.bidby, a.startingprice, a.user_id as seller_id
                FROM artworks a
                LEFT JOIN payments p ON p.artwork_id = a.id
                WHERE a.type = 'Live'
                AND a.endbidding::timestamp <= (NOW() AT TIME ZONE 'Asia/Bangkok')
                AND a.bidby IS NOT NULL
                AND p.id IS NULL
            ");
            $stmt->execute();
            $endedAuctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($endedAuctions as $auction) {
                // Create payment record
                $insertPayment = $pdo->prepare("
                    INSERT INTO payments (id, artwork_id, buyer_id, seller_id, amount, status, created_at)
                    VALUES (gen_random_uuid(), :artwork_id, :buyer_id, :seller_id, :amount, 'pending_buyer_payment', NOW())
                ");
                $insertPayment->execute([
                    ':artwork_id' => $auction['id'],
                    ':buyer_id' => $auction['bidby'],
                    ':seller_id' => $auction['seller_id'],
                    ':amount' => $auction['startingprice']
                ]);

                // Mark artwork as Sold
                $updateArt = $pdo->prepare("UPDATE artworks SET type = 'Sold' WHERE id = :id");
                $updateArt->execute([':id' => $auction['id']]);
            }
        } catch (Exception $e) {
            error_log("processEndedAuctions Error: " . $e->getMessage());
        }
    }
}
