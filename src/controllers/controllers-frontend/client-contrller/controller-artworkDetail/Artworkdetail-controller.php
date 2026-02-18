<?php

class artworkDetailController
{

    public function artworkDtail()
    {
        require_once __DIR__ . '/../../../../controllers/controllers-backend/content-client/ClientController.php';

        $id = $_GET['id'] ?? null;
        $artwork = null;

        if ($id) {
            $pdo = dbConnect();
            ClientController::updateArtworkStatus($pdo);
            $artwork = ClientController::getArtworkById($id);
        }

        // Mock data for missing fields if necessary, or handle in view
        $title = $artwork ? $artwork['title'] : "Artwork Detail";

        if ($artwork) {
            $og_image = ($_ENV['APP_URL'] ?? '') . '/image?id=' . $artwork['id'];
            $og_description = "ผลงานโดย " . $artwork['artist'] . " - ราคาปัจจุบัน: ฿" . number_format($artwork['startingprice']);
        }

        ob_start();
        require __DIR__ . '/../../../../views/pages/client/content-artwork/artworkDetail.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/main.php';
    }
}
