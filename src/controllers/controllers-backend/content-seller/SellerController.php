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
        $stmt = $pdo->prepare("SELECT * FROM artworks WHERE user_id = :user_id ORDER BY createat DESC");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $stmt = $pdo->prepare("UPDATE artworks SET title = :title, artworkname = :artworkname, artist = :artist, startbidding = :startbidding, endbidding = :endbidding, yearcreated = :yearcreated, dimensions = :dimensions, description = :description, type = :type, startingprice = :startingprice, status = :status, picture = :picture, categories_id = :categories_id WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'artworkname' => $data['artworkname'],
            'artist' => $data['artist'],
            'startbidding' => $data['startbidding'],
            'endbidding' => $data['endbidding'],
            'yearcreated' => $data['yearcreated'],
            'dimensions' => $data['dimensions'],
            'description' => $data['description'],
            'type' => $data['type'],
            'startingprice' => $data['startingprice'],
            'status' => $data['status'],
            'picture' => $data['picture'],
            'categories_id' => $data['categories_id']
        ]);

        // handle image upload
        // handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileType = $_FILES['image']['type'];
            $data = file_get_contents($fileTmpPath);
            $base64 = base64_encode($data);
            $picture = 'data:' . $fileType . ';base64,' . $base64;
            $data['picture'] = $picture;
        } else {
            $data['picture'] = $artwork['picture'] ?? null;
        }
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
}
