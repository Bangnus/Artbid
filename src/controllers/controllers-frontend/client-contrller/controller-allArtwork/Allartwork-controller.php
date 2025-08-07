<?php 

class AllArtworkController
{
    public function live()
    {
        $title = "Live Artworks";

        ob_start();
        require __DIR__ . '/../../../../views/pages/client/content-artwork/allArtwork-live.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/main.php';
    }

    public function upcoming()
    {
        $title = "Upcoming Artworks";

        ob_start();
        require __DIR__ . '/../../../../views/pages/client/content-artwork/allArtwork-upcoming.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/main.php';
    }
}