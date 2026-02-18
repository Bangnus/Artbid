<?php
require __DIR__ . '/../../../../config/database.php';

class BidController
{
    public static function placeBid()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: signin');
            exit;
        }

        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: home');
            exit;
        }

        $artworkId = $_POST['artwork_id'] ?? null;
        $bidAmount = $_POST['bid_amount'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;

        // Validate inputs
        if (!$artworkId || !$bidAmount || !$userId) {
            self::redirectWithError($artworkId, 'ข้อมูลไม่ครบถ้วน');
            return;
        }

        $bidAmount = floatval($bidAmount);

        try {
            $pdo = dbConnect();

            // Get current artwork info
            $stmt = $pdo->prepare("SELECT id, startingprice, bidby, type, status, endbidding, user_id, min_bid_increment FROM artworks WHERE id = :id");
            $stmt->execute([':id' => $artworkId]);
            $artwork = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$artwork) {
                self::redirectWithError($artworkId, 'ไม่พบผลงานศิลปะ');
                return;
            }

            // Check if user is the owner (seller) of this artwork
            if ($artwork['user_id'] == $userId) {
                self::redirectWithError($artworkId, 'ผู้ขายไม่สามารถบิดผลงานของตัวเองได้');
                return;
            }

            // Check if artwork is live
            if ($artwork['type'] !== 'Live') {
                self::redirectWithError($artworkId, 'การประมูลยังไม่เริ่มหรือสิ้นสุดแล้ว');
                return;
            }

            // Check if auction is still active
            if (!$artwork['status']) {
                self::redirectWithError($artworkId, 'การประมูลสิ้นสุดแล้ว');
                return;
            }

            // Check if auction has ended (time-based)
            $endTime = new DateTime($artwork['endbidding']);
            $now = new DateTime();
            if ($now > $endTime) {
                self::redirectWithError($artworkId, 'การประมูลสิ้นสุดแล้ว');
                return;
            }

            // Get current price (remove commas if formatted)
            $currentPrice = floatval(str_replace(',', '', $artwork['startingprice']));
            $minIncrement = floatval($artwork['min_bid_increment'] ?? 100);
            $minBidAmount = $currentPrice + $minIncrement;

            // Check minimum bid increment
            if ($bidAmount < $minBidAmount) {
                self::redirectWithError($artworkId, "ราคาประมูลต้องมากกว่าราคาปัจจุบันอย่างน้อย " . number_format($minIncrement, 0) . " บาท (ขั้นต่ำ: ฿" . number_format($minBidAmount, 0) . ")");
                return;
            }

            // Check user is not bidding against themselves
            if ($artwork['bidby'] == $userId) {
                self::redirectWithError($artworkId, 'คุณเป็นผู้ประมูลสูงสุดอยู่แล้ว');
                return;
            }

            // Update artwork with new bid
            $updateStmt = $pdo->prepare("
                UPDATE artworks 
                SET startingprice = :bid_amount, bidby = :user_id 
                WHERE id = :artwork_id
            ");
            $updateStmt->execute([
                ':bid_amount' => $bidAmount,
                ':user_id' => $userId,
                ':artwork_id' => $artworkId
            ]);

            // Log the bid in bid history (optional - create table if needed)
            self::logBid($pdo, $artworkId, $userId, $bidAmount);

            // Redirect with success
            header("Location: artwork-detail?id=$artworkId&status=success");
            exit;

        } catch (PDOException $e) {
            error_log("Bid error: " . $e->getMessage());
            self::redirectWithError($artworkId, 'เกิดข้อผิดพลาดในระบบ กรุณาลองใหม่อีกครั้ง');
        }
    }

    private static function redirectWithError($artworkId, $message)
    {
        $encodedMessage = urlencode($message);
        header("Location: artwork-detail?id=$artworkId&status=error&message=$encodedMessage");
        exit;
    }

    private static function logBid($pdo, $artworkId, $userId, $bidAmount)
    {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO bid_history (artwork_id, user_id, bid_amount) 
                VALUES (:artwork_id, :user_id, :bid_amount)
            ");
            $stmt->execute([
                ':artwork_id' => $artworkId,
                ':user_id' => $userId,
                ':bid_amount' => $bidAmount
            ]);
        } catch (PDOException $e) {
            // Log silently - don't break the main flow
            error_log("Bid history log error: " . $e->getMessage());
        }
    }
}
