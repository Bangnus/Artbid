<?php
require_once __DIR__ . '/../../../../../config/database.php';

class DashboardAdminController
{
    public function dashboard()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role_id'] != 3) {
            header('Location: /signin');
            exit;
        }

        $pdo = dbConnect();

        // 1. Stats Cards
        // Total Users (excluding admins)
        $stmtUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role_id != 3");
        $totalUsers = $stmtUsers->fetchColumn();

        // Total Artworks
        $stmtArtworks = $pdo->query("SELECT COUNT(*) FROM artworks");
        $totalArtworks = $stmtArtworks->fetchColumn();

        // Total Sales (Completed Payments)
        $stmtSales = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'completed'");
        $totalSales = $stmtSales->fetchColumn() ?: 0;

        // Total Revenue (5%)
        $totalRevenue = floor($totalSales * 0.05);

        // 2. Chart Data (Last 6 Months Sales)
        $chartData = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = date('Y-m-01 00:00:00', strtotime("-$i months"));
            $monthEnd = date('Y-m-t 23:59:59', strtotime("-$i months"));

            $stmtMonth = $pdo->prepare("SELECT SUM(amount) FROM payments WHERE status = 'completed' AND seller_paid_at BETWEEN ? AND ?");
            $stmtMonth->execute([$monthStart, $monthEnd]);
            $amount = $stmtMonth->fetchColumn() ?: 0;

            $chartData[] = $amount;
            $thai_months = [
                1 => 'ม.ค.',
                2 => 'ก.พ.',
                3 => 'มี.ค.',
                4 => 'เม.ย.',
                5 => 'พ.ค.',
                6 => 'มิ.ย.',
                7 => 'ก.ค.',
                8 => 'ส.ค.',
                9 => 'ก.ย.',
                10 => 'ต.ค.',
                11 => 'พ.ย.',
                12 => 'ธ.ค.'
            ];
            $monthNum = (int) date('n', strtotime("-$i months"));
            $year = date('Y', strtotime("-$i months")) + 543;
            $months[] = $thai_months[$monthNum] . ' ' . substr($year, 2);
        }

        // 3. Recent Transactions
        $stmtRecent = $pdo->prepare("SELECT p.*, 
                                            a.title as artwork_title,
                                            s.firstname as seller_firstname, 
                                            s.lastname as seller_lastname,
                                            b.firstname as buyer_firstname,
                                            b.lastname as buyer_lastname
                                     FROM payments p
                                     LEFT JOIN artworks a ON p.artwork_id = a.id
                                     LEFT JOIN users s ON p.seller_id = s.id
                                     LEFT JOIN users b ON p.buyer_id = b.id
                                     WHERE p.status = 'completed'
                                     ORDER BY p.seller_paid_at DESC
                                     LIMIT 5");
        $stmtRecent->execute();
        $recentTransactions = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);

        $title = 'แดชบอร์ด';

        ob_start();
        require __DIR__ . '/../../../../views/pages/admin/content-dashboard/dashboard.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/admin.php';
    }
}
