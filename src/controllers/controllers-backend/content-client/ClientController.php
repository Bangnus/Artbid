<?php
require __DIR__ . '/../../../../config/database.php';

class ClientController
{

    public static function getAllArtwork($type = null, $limit = null)
    {
        try {
            $pdo = dbConnect();

            // Update status for artworks that have reached their start time
            self::updateArtworkStatus($pdo);

            $sql = "SELECT * FROM artworks WHERE status = true";
            $params = [];

            if ($type) {
                $sql .= " AND type = :type";
                $params['type'] = $type;
            }

            $sql .= " ORDER BY createat DESC";

            if ($limit) {
                $sql .= " LIMIT :limit";
            }

            $stmt = $pdo->prepare($sql);

            if ($limit) {
                $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            }

            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public static function updateArtworkStatus($pdo)
    {
        try {
            // 1. Update Upcoming to Live when start time arrives
            $sql1 = "UPDATE artworks 
                    SET type = 'Live' 
                    WHERE type = 'Upcoming' 
                    AND startbidding <= (NOW() AT TIME ZONE 'Asia/Bangkok')";
            $pdo->exec($sql1);

            // 1.1 Cleanup: Ensure ALL Sold/Unsold/Expired items are inactive (status=false)
            $pdo->exec("UPDATE artworks SET status = false WHERE type IN ('Sold', 'Unsold', 'Expired') AND status = true");

            // 2. Find ended auctions that need processing (Live and time expired)
            // We select ALL ended live auctions, even if payment exists, to ensure we fix any status inconsistencies
            $stmt = $pdo->prepare("
                SELECT a.id, a.bidby, a.startingprice, a.user_id as seller_id
                FROM artworks a
                WHERE a.type = 'Live'
                AND a.endbidding <= (NOW() AT TIME ZONE 'Asia/Bangkok')
            ");
            $stmt->execute();
            $endedAuctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($endedAuctions as $auction) {
                if (!empty($auction['bidby'])) {
                    // Has winner - create payment record if not exists
                    $checkPay = $pdo->prepare("SELECT id FROM payments WHERE artwork_id = :aid");
                    $checkPay->execute([':aid' => $auction['id']]);

                    if (!$checkPay->fetch()) {
                        // DELETE existing payment first (as requested to fix issues)
                        $deletePay = $pdo->prepare("DELETE FROM payments WHERE artwork_id = :aid");
                        $deletePay->execute([':aid' => $auction['id']]);

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
                    } else {
                        // Force delete and recreate even if it exists (per user request "delete problematic payment")
                        // DELETE existing payment first
                        $deletePay = $pdo->prepare("DELETE FROM payments WHERE artwork_id = :aid");
                        $deletePay->execute([':aid' => $auction['id']]);

                        // Create NEW payment record
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
                    }

                    // Mark as Sold and Inactive (Hidden from main list) - FORCE UPDATE
                    $updateArt = $pdo->prepare("UPDATE artworks SET type = 'Sold', status = false WHERE id = :id");
                    $updateArt->execute([':id' => $auction['id']]);
                } else {
                    // No bidder - mark as Unsold
                    $updateArt = $pdo->prepare("UPDATE artworks SET type = 'Unsold', status = false WHERE id = :id");
                    $updateArt->execute([':id' => $auction['id']]);
                }
            }

            // 3. Expire payments older than 24 hours
            $stmtExpire = $pdo->prepare("
                UPDATE payments 
                SET status = 'expired', updated_at = NOW()
                WHERE status = 'pending_buyer_payment' 
                AND created_at < NOW() - INTERVAL '1 DAY'
                RETURNING artwork_id
            ");
            $stmtExpire->execute();
            $expiredArtworks = $stmtExpire->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($expiredArtworks)) {
                // Set artworks back to Unsold
                $artIds = implode(',', array_map('intval', $expiredArtworks));
                $pdo->exec("UPDATE artworks SET type = 'Unsold', status = false WHERE id IN ($artIds)");
            }
        } catch (PDOException $e) {
            // Log error or handle silently as this is a background task
            error_log("updateArtworkStatus Error: " . $e->getMessage());
            return false;
        }
    }
    public static function getAllArtwork2($filters = [], $page = 1, $limit = 9)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $pdo = dbConnect();

        // Update status for artworks that have reached their start time
        // self::updateArtworkStatus($pdo);

        $offset = ($page - 1) * $limit;
        $params = [];

        // Base query for counting total
        $countSql = "SELECT COUNT(*) FROM artworks WHERE status = true";

        // Base query for fetching data
        $sql = "SELECT * FROM artworks WHERE status = true";

        // Filter by Type (Live/Upcoming)
        if (!empty($filters['type'])) {
            $placeholders = [];
            foreach ($filters['type'] as $key => $type) {
                $placeholder = ":type_$key";
                $placeholders[] = $placeholder;
                $params[$placeholder] = $type;
            }
            $condition = " AND type IN (" . implode(',', $placeholders) . ")";
            $countSql .= $condition;
            $sql .= $condition;
        }

        // Filter by Category
        if (!empty($filters['categories'])) {
            $placeholders = [];
            foreach ($filters['categories'] as $key => $catId) {
                $placeholder = ":cat_$key";
                $placeholders[] = $placeholder;
                $params[$placeholder] = $catId;
            }
            $condition = " AND categories_id IN (" . implode(',', $placeholders) . ")";
            $countSql .= $condition;
            $sql .= $condition;
        }

        // Filter by Search (Title or Artist)
        if (!empty($filters['search'])) {
            $search = "%" . $filters['search'] . "%";
            $condition = " AND (title ILIKE :search OR artist ILIKE :search)";
            $params[':search'] = $search;
            $countSql .= $condition;
            $sql .= $condition;
        }

        // Get total count
        $stmtCount = $pdo->prepare($countSql);
        $stmtCount->execute($params);
        $total = $stmtCount->fetchColumn();
        $totalPages = ceil($total / $limit);

        // Add sorting and pagination to main query
        $sql .= " ORDER BY createat DESC LIMIT :limit OFFSET :offset";

        // Bind pagination params
        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();
        $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'artworks' => $artworks,
            'total' => $total,
            'total_pages' => $totalPages,
            'current_page' => $page
        ];
    }

    public static function getArtworkById($id)
    {
        $pdo = dbConnect();
        $sql = "SELECT a.*, u.username as bidder_name, u.firstname as bidder_firstname, u.lastname as bidder_lastname 
                FROM artworks a 
                LEFT JOIN users u ON a.bidby = u.id 
                WHERE a.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
