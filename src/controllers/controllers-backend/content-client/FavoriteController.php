<?php
require_once __DIR__ . '/../../../../config/database.php';

class FavoriteController
{
    /**
     * Toggle favorite status for an artwork
     */
    public static function toggleFavorite()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบก่อน']);
            exit;
        }

        $artworkId = $_POST['artwork_id'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;

        if (!$artworkId || !$userId) {
            echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
            exit;
        }

        try {
            $pdo = dbConnect();

            // Check if already favorited
            $checkStmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = :user_id AND artwork_id = :artwork_id");
            $checkStmt->execute([':user_id' => $userId, ':artwork_id' => $artworkId]);
            $existing = $checkStmt->fetch();

            if ($existing) {
                // Remove from favorites
                $deleteStmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = :user_id AND artwork_id = :artwork_id");
                $deleteStmt->execute([':user_id' => $userId, ':artwork_id' => $artworkId]);
                echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'ลบออกจากรายการที่ชอบแล้ว']);
            } else {
                // Add to favorites
                $insertStmt = $pdo->prepare("INSERT INTO favorites (user_id, artwork_id) VALUES (:user_id, :artwork_id)");
                $insertStmt->execute([':user_id' => $userId, ':artwork_id' => $artworkId]);
                echo json_encode(['success' => true, 'action' => 'added', 'message' => 'เพิ่มในรายการที่ชอบแล้ว']);
            }
        } catch (PDOException $e) {
            error_log("Favorite error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในระบบ']);
        }
        exit;
    }

    /**
     * Check if artwork is favorited by current user
     */
    public static function isFavorited($artworkId)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            return false;
        }

        try {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = :user_id AND artwork_id = :artwork_id");
            $stmt->execute([
                ':user_id' => $_SESSION['user']['id'],
                ':artwork_id' => $artworkId
            ]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Get all favorites for current user
     */
    public static function getUserFavorites()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            return [];
        }

        try {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("
                SELECT a.* 
                FROM artworks a
                INNER JOIN favorites f ON a.id = f.artwork_id
                WHERE f.user_id = :user_id 
                AND a.status = true
                ORDER BY f.created_at DESC
            ");
            $stmt->execute([':user_id' => $_SESSION['user']['id']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get favorites error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get favorite count for current user
     */
    public static function getFavoriteCount()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            return 0;
        }

        try {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $_SESSION['user']['id']]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }
}
