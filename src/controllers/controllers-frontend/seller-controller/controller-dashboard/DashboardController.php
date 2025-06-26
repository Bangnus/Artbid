<?php 

class DashboardController {
    public function dashboaed() {
        $title = "Dashboard";

        ob_start();
        require __DIR__ . '/../../../../views/pages/seller/content-dashboard/dashboard.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/seller.php';
    }
}