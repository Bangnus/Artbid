<?php
class DashboardAdminController
{
    public function dashboard()
    {
        $title = 'Dashboard';

        ob_start();
        require __DIR__ . '/../../../../views/pages/admin/content-dashboard/dashboard.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/admin.php';
    }
}
