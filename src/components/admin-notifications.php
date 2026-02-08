<?php
// Helper to get pending payment verification count
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Only run for admin
if (isset($_SESSION['user']) && $_SESSION['user']['role_id'] == 3) {
    require_once __DIR__ . '/../../config/database.php';

    try {
        if (!isset($conn)) {
            $conn = dbConnect();
        }
        $pdo = $conn;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM payments WHERE status = 'pending_admin_verify'");
        $stmt->execute();
        $pendingPaymentCount = $stmt->fetchColumn();
    } catch (Exception $e) {
        $pendingPaymentCount = 0;
    }
} else {
    $pendingPaymentCount = 0;
}
?>