<?php
// Load existing database configuration
require_once __DIR__ . '/../config/database.php';

// Script to create password_resets table
try {
    // Use existing database connection
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Connected to database successfully</h2>";
    
    // SQL to create password_resets table
    $sql = "
    -- Drop existing table if it exists with wrong type
    DROP TABLE IF EXISTS password_resets;
    
    -- Create table with correct UUID type
    CREATE TABLE password_resets (
        id SERIAL PRIMARY KEY,
        user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
        token VARCHAR(255) NOT NULL UNIQUE,
        used BOOLEAN DEFAULT FALSE,
        expires_at TIMESTAMP NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    
    -- Create indexes
    CREATE INDEX IF NOT EXISTS idx_password_resets_token ON password_resets(token);
    CREATE INDEX IF NOT EXISTS idx_password_resets_user_id ON password_resets(user_id);
    CREATE INDEX IF NOT EXISTS idx_password_resets_expires_at ON password_resets(expires_at);
    ";
    
    // Execute SQL
    $pdo->exec($sql);
    
    echo "<h2>✅ Password resets table created successfully!</h2>";
    
    // Verify table exists
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'password_resets'");
    $table = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($table) {
        echo "<p>✅ Table 'password_resets' exists and is ready to use.</p>";
        echo "<p><a href='/Artbid/forgot-password'>Test forgot password feature</a></p>";
    } else {
        echo "<p>❌ Error: Table was not created.</p>";
    }
    
} catch (PDOException $e) {
    echo "<h2>❌ Database Error: " . $e->getMessage() . "</h2>";
} catch (Exception $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
}
?>
