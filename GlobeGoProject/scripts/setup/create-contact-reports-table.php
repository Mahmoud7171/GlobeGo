<?php
/**
 * Quick setup script to create contact_reports table
 * Run this in your browser: http://localhost/GlobeGoProject/create-contact-reports-table.php
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        die("Database connection failed!");
    }

    echo "<h2>Setting up Contact Reports Table</h2>";

    // Create table SQL
    $sql = "CREATE TABLE IF NOT EXISTS contact_reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    try {
        $conn->exec($sql);
        echo "<p style='color: green;'>✅ Contact reports table created successfully!</p>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false || 
            strpos($e->getMessage(), 'Duplicate') !== false) {
            echo "<p style='color: orange;'>ℹ️ Table already exists - that's okay!</p>";
        } else {
            echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            die();
        }
    }

    // Create indexes if they don't exist
    try {
        $conn->exec("CREATE INDEX IF NOT EXISTS idx_status ON contact_reports(status)");
        $conn->exec("CREATE INDEX IF NOT EXISTS idx_created_at ON contact_reports(created_at)");
        echo "<p style='color: green;'>✅ Indexes created successfully!</p>";
    } catch (PDOException $e) {
        echo "<p style='color: orange;'>ℹ️ Indexes may already exist - continuing...</p>";
    }

    echo "<p style='color: green; font-weight: bold;'>✅ Setup completed successfully!</p>";
    echo "<p><a href='admin/reports.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Go to Reports Page</a></p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ General error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<style>
    body { 
        font-family: Arial, sans-serif; 
        padding: 40px; 
        max-width: 800px; 
        margin: 0 auto;
        background: #f5f5f5;
    }
    h2 {
        color: #333;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }
</style>

