<?php

class artworkDetailController
{

    public function artworkDtail()
    {
        $title = "Artwork detail";

        ob_start();
        require __DIR__ . '/../../../../views/pages/client/content-artwork/artworkDetail.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/main.php';
    }
}
