<?php 
class ArtlistController {
    public function artlist() {
        $title = "Art Listings";

        ob_start();
        require __DIR__ . '/../../../../views/pages/seller/content-artList/artlist.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/seller.php';
    }
}