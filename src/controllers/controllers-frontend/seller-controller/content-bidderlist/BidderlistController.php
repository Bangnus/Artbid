<?php
class BidderlistController
{
    public function bidderlist()
    {
        $title = "รายชื่อผู้เสนอราคา";

        ob_start();
        require __DIR__ . '/../../../../views/pages/seller/content-bidderlist/bidderlist.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/seller.php';
    }
}