<?php
class SalesHistoryController
{
    public function index()
    {
        $title = "ประวัติการขาย";

        ob_start();
        require __DIR__ . '/../../../../views/pages/seller/content-sales-history/sales-history.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/seller.php';
    }
}
