<?php
require __DIR__ . '/../../../../config/database.php';

class ClientController
{

    public static function getAllArtwork()
    {
        try {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("SELECT * FROM artworks ORDER BY createat DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
