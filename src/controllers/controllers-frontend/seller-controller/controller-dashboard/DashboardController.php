<?php
require_once __DIR__ . '/../../../../../config/database.php';

class DashboardController
{
    public function dashboaed()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role_id'] != 2) {
            header('Location: ' . $_ENV['APP_URL'] . '/signin');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $pdo = dbConnect();

        // 1. Stats Cards
        // Total Artworks
        $stmtArtworks = $pdo->prepare("SELECT COUNT(*) FROM artworks WHERE user_id = ?");
        $stmtArtworks->execute([$userId]);
        $totalArtworks = $stmtArtworks->fetchColumn();

        // Total Sales (Gross) & Total Earnings (Net 95%) & Total Sold Count
        $stmtSales = $pdo->prepare("SELECT SUM(amount), COUNT(*) FROM payments WHERE seller_id = ? AND status = 'completed'");
        $stmtSales->execute([$userId]);
        $salesData = $stmtSales->fetch(PDO::FETCH_NUM);
        $totalSales = $salesData[0] ?: 0;
        $totalSold = $salesData[1] ?: 0;
        $totalEarnings = floor($totalSales * 0.95);

        // Pending Orders (Waiting for transfer)
        $stmtPending = $pdo->prepare("SELECT COUNT(*) FROM payments WHERE seller_id = ? AND (status = 'verified' OR status = 'pending_admin_verify')");
        $stmtPending->execute([$userId]);
        $pendingOrders = $stmtPending->fetchColumn();

        // 2. Line Chart: Monthly Sales (Last 6 Months)
        $lineChartData = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = date('Y-m-01 00:00:00', strtotime("-$i months"));
            $monthEnd = date('Y-m-t 23:59:59', strtotime("-$i months"));

            $stmtMonth = $pdo->prepare("SELECT SUM(amount) FROM payments WHERE seller_id = ? AND status = 'completed' AND seller_paid_at BETWEEN ? AND ?");
            $stmtMonth->execute([$userId, $monthStart, $monthEnd]);
            $amount = $stmtMonth->fetchColumn() ?: 0;

            $lineChartData[] = $amount;
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

        // 3. Bar Chart: Sales by Category
        $barChartLabels = [];
        $barChartData = [];
        try {
            // Using LEFT JOIN to ensure we get data even if category is missing/deleted
            // And including 'verified' status to show "Sold" items pending final admin approval
            $stmtCat = $pdo->prepare("SELECT COALESCE(c.category_name, 'Uncategorized') as name, COUNT(p.id) as count 
                                      FROM payments p
                                      JOIN artworks a ON p.artwork_id = a.id
                                      LEFT JOIN categories c ON a.categories_id = c.id
                                      WHERE p.seller_id = ? AND p.status IN ('completed', 'verified')
                                      GROUP BY c.id, c.category_name");
            $stmtCat->execute([$userId]);
            $catData = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
            foreach ($catData as $d) {
                $barChartLabels[] = $d['name'];
                $barChartData[] = $d['count'];
            }
        } catch (Exception $e) {
            // Fallback
        }

        // 4. Pie Chart: Artwork Status
        // Update statuses first to ensure accuracy
        require_once __DIR__ . '/../../../../controllers/controllers-backend/content-seller/SellerController.php';
        SellerController::updateArtworkStatus($pdo);

        $pieChartLabels = ['กำลังประมูล', 'ขายแล้ว', 'ปิดประมูล/ไม่ได้ขาย'];
        $pieChartData = [0, 0, 0];

        $stmtStatus = $pdo->prepare("SELECT type, COUNT(*) FROM artworks WHERE user_id = ? GROUP BY type");
        $stmtStatus->execute([$userId]);
        $statusData = $stmtStatus->fetchAll(PDO::FETCH_ASSOC);

        foreach ($statusData as $s) {
            $type = strtolower($s['type']);
            if ($type == 'live' || $type == 'upcoming') {
                $pieChartData[0] += $s['count'];
            } elseif ($type == 'sold') {
                $pieChartData[1] += $s['count'];
            } else {
                // Unsold or others
                $pieChartData[2] += $s['count'];
            }
        }

        // 5. Recent Orders
        $stmtRecent = $pdo->prepare("SELECT p.*, 
                                            a.title as artwork_title,
                                            a.picture as artwork_image,
                                            b.firstname as buyer_firstname,
                                            b.lastname as buyer_lastname
                                     FROM payments p
                                     LEFT JOIN artworks a ON p.artwork_id = a.id
                                     LEFT JOIN users b ON p.buyer_id = b.id
                                     WHERE p.seller_id = ? 
                                     ORDER BY p.created_at DESC
                                     LIMIT 5");
        $stmtRecent->execute([$userId]);
        $recentOrders = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);

        $title = "แดชบอร์ด";

        ob_start();
        require __DIR__ . '/../../../../views/pages/seller/content-dashboard/dashboard.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../../../views/layouts/seller.php';
    }
}