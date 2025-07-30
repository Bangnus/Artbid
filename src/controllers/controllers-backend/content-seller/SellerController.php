<?php
require __DIR__ . '/../../../../config/database.php';

class SellerController
{

    // art-listings
    public static function addArtlisting()
    {
        try {
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
                $uploadDir = __DIR__ . '/../../../../public/uploads/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $picture = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $picture;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
            }

            $stmt = $pdo->prepare("INSERT INTO artworks
            (id, title, artworkname, artist, startbidding, endbidding, yearcreated, dimensions, description, type, startingprice, status, picture, categories_id)
            VALUES (gen_random_uuid(), :title, :artworkname, :artist, :startbidding, :endbidding, :yearcreated, :dimensions, :description, :type, :startingprice, :status, :picture, :categories_id)");

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
                'categories_id' => $categories_id
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
        $pdo = dbConnect();
        $stmt = $pdo->query("SELECT * FROM artworks  ORDER BY createat DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // public static function getSellerById($id) {
    //     $pdo = dbConnect();
    //     $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id AND role_id = 2");
    //     $stmt->execute(['id' => $id]);
    //     return $stmt->fetch();
    // }
}
