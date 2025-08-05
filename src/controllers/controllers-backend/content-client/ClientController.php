<?php
require __DIR__ . '/../../../../config/database.php';

class ClientController
{

    public static function getAllArtwork($type = null)
    {
        try {
            $pdo = dbConnect();

            if (!$type) {
                $stmt = $pdo->prepare("SELECT * FROM artworks ORDER BY createat DESC");
                $stmt->execute();
            } else {
                $stmt = $pdo->prepare("SELECT * FROM artworks WHERE type = :type ORDER BY createat DESC");
                $stmt->execute(['type' => $type]);
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
