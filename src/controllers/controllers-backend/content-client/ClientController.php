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

    private static function updateArtworkStatus($pdo)
    {
        try {
            // Convert DB server time (UTC) to Asia/Bangkok to match the stored local time in startbidding
            $sql1 = "UPDATE artworks 
                    SET type = 'Live' 
                    WHERE type = 'Upcoming' 
                    AND startbidding::timestamp <= (NOW() AT TIME ZONE 'Asia/Bangkok')";

            $sql2 = "UPDATE artworks
            SET status = false AND type = 'Unsold'
            WHERE type = 'Live'
            AND endbidding::timestamp <= (NOW() AT TIME ZONE 'Asia/Bangkok')";

            $pdo->exec($sql1);
            $pdo->exec($sql2);
        } catch (PDOException $e) {
            // Log error or handle silently as this is a background task
            echo "Error: " . $e->getMessage();
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
        self::updateArtworkStatus($pdo);

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
