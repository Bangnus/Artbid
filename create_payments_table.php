<?php
require_once __DIR__ . '/config/database.php';

try {
    $pdo = dbConnect();

    $sql = "
    CREATE TABLE IF NOT EXISTS payments (
        id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
        artwork_id UUID NOT NULL REFERENCES artworks(id),
        buyer_id UUID NOT NULL REFERENCES users(id),
        seller_id UUID NOT NULL REFERENCES users(id),
        amount DECIMAL(12,2) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending_buyer_payment',
        
        buyer_slip_image TEXT,
        buyer_payment_at TIMESTAMP,
        
        admin_verified_at TIMESTAMP,
        admin_verified_by UUID REFERENCES users(id),
        admin_notes TEXT,
        
        seller_paid_at TIMESTAMP,
        
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    
    CREATE INDEX IF NOT EXISTS idx_payments_status ON payments(status);
    CREATE INDEX IF NOT EXISTS idx_payments_buyer ON payments(buyer_id);
    CREATE INDEX IF NOT EXISTS idx_payments_seller ON payments(seller_id);
    ";

    $pdo->exec($sql);
    echo "Successfully created payments table!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
