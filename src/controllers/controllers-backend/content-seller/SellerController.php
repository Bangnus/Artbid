<?php
require_once __DIR__ . '/../../../../config/database.php';

class SellerController
{

    // art-listings
    public static function addArtlisting()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $pdo = dbConnect();

            $title = $_POST['title'];
            $artworkname = $_POST['artworkname'];
            $artist = $_POST['artist'];
            $startbidding = $_POST['startbidding'];
            $startbidding = str_replace('T', ' ', $startbidding);
            $endbidding = $_POST['endbidding'];
            $endbidding = str_replace('T', ' ', $endbidding);

            // Validation: End bidding must be after Start bidding
            if (strtotime($endbidding) <= strtotime($startbidding)) {
                // You might want to handle this error more gracefully, e.g., throw exception or return specific error code
                return false;
            }
            $yearcreated = $_POST['yearcreated'];
            $dimensions = $_POST['dimensions'];
            $description = $_POST['description'];
            $startingprice = $_POST['startingprice'];
            $status = $_POST['status'];
            $categories_id = $_POST['categories_id'];

            // กำหนด type เป็น Upcoming หรือ Live (เปรียบเทียบแบบวันและเวลา)
            $now = date('Y-m-d H:i:s');
            $type = (strtotime($startbidding) > strtotime($now)) ? 'Upcoming' : 'Live';

            // อัปโหลดรูปภาพ
            $picture = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['image']['tmp_name'];
                $fileType = $_FILES['image']['type'];
                $data = file_get_contents($fileTmpPath);
                $base64 = base64_encode($data);
                $picture = 'data:' . $fileType . ';base64,' . $base64;
            }

            $user_id = $_SESSION['user']['id'] ?? null;

            $stmt = $pdo->prepare("INSERT INTO artworks
            (id, title, artworkname, artist, startbidding, endbidding, yearcreated, dimensions, description, type, startingprice, status, picture, categories_id, user_id, createat)
            VALUES (gen_random_uuid(), :title, :artworkname, :artist, :startbidding, :endbidding, :yearcreated, :dimensions, :description, :type, :startingprice, :status, :picture, :categories_id, :user_id, NOW())");

            $stmt->execute([
                'title' => $title,
                'artworkname' => $artworkname,
                'artist' => $artist,
                'startbidding' => $startbidding,
                'endbidding' => $endbidding,
                'yearcreated' => $yearcreated,
                'dimensions' => $dimensions,
                'description' => $description,
                'type' => $type,
                'startingprice' => $startingprice,
                'status' => $status,
                'picture' => $picture,
                'categories_id' => $categories_id,
                'user_id' => $user_id
            ]);

            return true;
        } catch (PDOException $e) {
            // จัดการ error ตามต้องการ เช่น log หรือแสดงข้อความ
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public static function getAllArtwork()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['user']['id'] ?? null;
        $pdo = dbConnect();

        // Update status for artworks that have reached their start time
        self::updateArtworkStatus($pdo);

        $stmt = $pdo->prepare("SELECT a.*, 
                               (SELECT status FROM payments WHERE artwork_id = a.id ORDER BY created_at DESC LIMIT 1) as payment_status 
                               FROM artworks a 
                               WHERE a.user_id = :user_id 
                               AND (
                                   a.type != 'Sold' 
                                   OR (
                                       SELECT status FROM payments WHERE artwork_id = a.id ORDER BY created_at DESC LIMIT 1
                                   ) IS DISTINCT FROM 'verified'
                                   AND (
                                       SELECT status FROM payments WHERE artwork_id = a.id ORDER BY created_at DESC LIMIT 1
                                    ) IS DISTINCT FROM 'completed'
                               )
                               ORDER BY a.createat DESC");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSalesHistory()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['user']['id'] ?? null;
        $pdo = dbConnect();

        $stmt = $pdo->prepare("SELECT a.*, 
                               (SELECT status FROM payments WHERE artwork_id = a.id ORDER BY created_at DESC LIMIT 1) as payment_status,
                               b.firstname as bidby_firstname,
                               b.lastname as bidby_lastname
                               FROM artworks a 
                               LEFT JOIN users b ON a.bidby = b.id
                               WHERE a.user_id = :user_id 
                               AND a.type = 'Sold'
                               AND (
                                   (SELECT status FROM payments WHERE artwork_id = a.id ORDER BY created_at DESC LIMIT 1) = 'verified'
                                   OR 
                                   (SELECT status FROM payments WHERE artwork_id = a.id ORDER BY created_at DESC LIMIT 1) = 'completed'
                               )
                               ORDER BY a.createat DESC");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateArtworkStatus($pdo)
    {
        try {
            // Convert DB server time (UTC) to Asia/Bangkok to match the stored local time in startbidding
            $sql = "UPDATE artworks 
                    SET type = 'Live' 
                    WHERE type = 'Upcoming' 
                    AND startbidding::timestamp <= (NOW() AT TIME ZONE 'Asia/Bangkok')";
            $pdo->exec($sql);
        } catch (PDOException $e) {
            // Log error
        }

        try {
            // Expire payments older than 24 hours
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
                $artIds = implode("','", $expiredArtworks);
                $pdo->exec("UPDATE artworks SET type = 'Unsold', status = false WHERE id IN ('$artIds')");
            }

            // Catch-all: Ensure any Sold artwork with an expired latest payment is set to Unsold
            // This fixes any desync issues from previous bugs
            $pdo->exec("UPDATE artworks a
                        SET type = 'Unsold', status = false
                        WHERE a.type = 'Sold'
                        AND (SELECT status FROM payments WHERE artwork_id = a.id ORDER BY created_at DESC LIMIT 1) = 'expired'");

        } catch (PDOException $e) {
            // Log error
        }
    }

    public static function getArtworkById($id)
    {
        $pdo = dbConnect();
        $stmt = $pdo->prepare("SELECT * FROM artworks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function updateArtwork($id, $data)
    {
        $pdo = dbConnect();

        // Check if artwork is sold and payment is verified -> Prevent Edit
        $checkStatus = $pdo->prepare("
            SELECT a.type, p.status as payment_status 
            FROM artworks a 
            LEFT JOIN payments p ON p.artwork_id = a.id 
            WHERE a.id = :id
        ");
        $checkStatus->execute([':id' => $id]);
        $artStatus = $checkStatus->fetch();

        if ($artStatus['type'] === 'Sold' && $artStatus['payment_status'] === 'verified') {
            return 3; // Error Code 3: Cannot edit verified sold item
        }

        // Removed incorrect $data['type'] check. Logic moved to after type calculation.

        // Fetch existing artwork to get current picture
        $stmt = $pdo->prepare("SELECT picture FROM artworks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $currentArtwork = $stmt->fetch();
        $picture = $currentArtwork['picture'] ?? null;

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileType = $_FILES['image']['type'];
            $fileData = file_get_contents($fileTmpPath);
            $base64 = base64_encode($fileData);
            $picture = 'data:' . $fileType . ';base64,' . $base64;
        }

        // Calculate type based on startbidding
        $startbidding = str_replace('T', ' ', $data['startbidding']);
        $endbidding = str_replace('T', ' ', $data['endbidding']);

        // Validation: End bidding must be after Start bidding
        if (strtotime($endbidding) <= strtotime($startbidding)) {
            return 2; // Error Code 2: Date validation failed
        }
        $now = date('Y-m-d H:i:s');
        $type = (strtotime($startbidding) > strtotime($now)) ? 'Upcoming' : 'Live';

        // Check if re-auctioning (Sold/Unsold -> Live/Upcoming) OR just changing to Live/Upcoming
        // We use the calculated $type here
        if (in_array($type, ['Live', 'Upcoming'])) {
            // Check if it was previously Sold or Unsold, OR if we just want to force clear for new rounds
            // The user wants "re-auction" to clear payments.
            // If the new state is Live/Upcoming, it implies a new round, so we should clear old "pending" stuff.
            // But if it was already "Live" and we are just editing a typo, do we delete payments?
            // "re-auctioning" implies coming from a non-active state.

            // However, the user said "เวลาจะเปิดประมูลใหม่" (Time to open new auction).
            // Let's stick to the condition: if transitioning FROM Sold/Unsold TO Live/Upcoming
            // User Request (3517): "When pressing update... set bidby to empty and delete payments".
            // To comply with the user's latest request to explicitly clear bidby and payments on update:

            // Clear bids (Table is bid_history, not bids as previously assumed)
            $pdo->prepare("DELETE FROM bid_history WHERE artwork_id = :id")->execute(['id' => $id]);
            // Clear old payments as requested
            $pdo->prepare("DELETE FROM payments WHERE artwork_id = :id")->execute(['id' => $id]);

            // Clear bidby (winner/current bidder)
            $pdo->prepare("UPDATE artworks SET bidby = NULL WHERE id = :aid")->execute([':aid' => $id]);
        }

        $stmt = $pdo->prepare("UPDATE artworks SET title = :title, artworkname = :artworkname, artist = :artist, startbidding = :startbidding, endbidding = :endbidding, yearcreated = :yearcreated, dimensions = :dimensions, description = :description, type = :type, startingprice = :startingprice, status = :status, picture = :picture, categories_id = :categories_id WHERE id = :id");

        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'artworkname' => $data['artworkname'],
            'artist' => $data['artist'],
            'startbidding' => $startbidding,
            'endbidding' => $endbidding,
            'yearcreated' => $data['yearcreated'],
            'dimensions' => $data['dimensions'],
            'description' => $data['description'],
            'type' => $type,
            'startingprice' => $data['startingprice'],
            'status' => $data['status'],
            'picture' => $picture,
            'categories_id' => $data['categories_id']
        ]);

        return 1; // Success
    }

    public static function deleteArtwork($id)
    {
        $pdo = dbConnect();
        // ดึงชื่อไฟล์รูปจาก DB ก่อนลบ
        $stmt = $pdo->prepare("SELECT picture FROM artworks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        $picture = $row && !empty($row['picture']) ? $row['picture'] : null;

        // ลบ record ใน DB
        $stmt = $pdo->prepare("DELETE FROM artworks WHERE id = :id");
        $stmt->execute(['id' => $id]);

        // ไม่ต้องลบไฟล์รูปแล้ว เพราะเก็บเป็น Base64 ใน DB

        return true;
    }

    public static function getWinningBidders()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $seller_id = $_SESSION['user']['id'] ?? null;
        $pdo = dbConnect();

        $stmt = $pdo->prepare("
            SELECT 
                p.id as payment_id,
                p.amount,
                p.status as payment_status,
                p.created_at as won_at,
                a.id as artwork_id,
                a.artworkname,
                a.artist,
                a.picture,
                a.endbidding,
                u.id as buyer_id,
                u.firstname as buyer_firstname,
                u.lastname as buyer_lastname,
                u.email as buyer_email,
                u.picture as buyer_picture
            FROM payments p
            INNER JOIN artworks a ON p.artwork_id = a.id
            INNER JOIN users u ON p.buyer_id = u.id
            WHERE p.seller_id = :seller_id
            ORDER BY p.created_at DESC
        ");
        $stmt->execute(['seller_id' => $seller_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCurrentBidders()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $seller_id = $_SESSION['user']['id'] ?? null;
        $pdo = dbConnect();

        // Query bid_history for Live artworks
        $stmt = $pdo->prepare("
            SELECT 
                bh.id as bid_id,
                bh.bid_amount as amount,
                'in_progress' as payment_status, 
                bh.created_at as bid_at,
                a.id as artwork_id,
                a.artworkname,
                a.artist,
                a.picture,
                a.endbidding,
                u.id as buyer_id,
                u.firstname as buyer_firstname,
                u.lastname as buyer_lastname,
                u.email as buyer_email,
                u.picture as buyer_picture
            FROM bid_history bh
            INNER JOIN artworks a ON bh.artwork_id = a.id
            INNER JOIN users u ON bh.user_id = u.id
            WHERE a.user_id = :seller_id 
            AND a.type = 'Live'
            ORDER BY bh.created_at DESC
        ");
        $stmt->execute(['seller_id' => $seller_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
