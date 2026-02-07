<?php
require __DIR__ . '/../../../../config/database.php';

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

        $stmt = $pdo->prepare("SELECT * FROM artworks WHERE user_id = :user_id ORDER BY createat DESC");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function updateArtworkStatus($pdo)
    {
        try {
            // Convert DB server time (UTC) to Asia/Bangkok to match the stored local time in startbidding
            $sql = "UPDATE artworks 
                    SET type = 'Live' 
                    WHERE type = 'Upcoming' 
                    AND startbidding::timestamp <= (NOW() AT TIME ZONE 'Asia/Bangkok')";
            $pdo->exec($sql);
        } catch (PDOException $e) {
            // Log error or handle silently as this is a background task
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
        $now = date('Y-m-d H:i:s');
        $type = (strtotime($startbidding) > strtotime($now)) ? 'Upcoming' : 'Live';

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

        return true;
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
}
