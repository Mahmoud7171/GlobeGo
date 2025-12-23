<?php
/**
 * Quick setup script to create fines table
 * Run this in your browser: http://localhost/GlobeGoProject/create-fines-table.php
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        die("Database connection failed!");
    }

    echo "<!DOCTYPE html><html><head><title>Setup Fines Table</title></head><body>";
    echo "<div style='font-family: Arial, sans-serif; padding: 40px; max-width: 800px; margin: 0 auto; background: #f5f5f5;'>";
    echo "<h2 style='color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px;'>Setting up Fines Table</h2>";

    // Check if table already exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'fines'");
    if ($checkTable->rowCount() > 0) {
        echo "<p style='color: orange;'>ℹ️ Fines table already exists - no action needed!</p>";
        echo "<p><a href='fines.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Go to Fines Page</a></p>";
        echo "</div></body></html>";
        exit;
    }

    // Read and execute SQL
    $sql_file = __DIR__ . '/database/create_fines_table.sql';
    if (!file_exists($sql_file)) {
        echo "<p style='color: red;'>❌ SQL file not found: " . htmlspecialchars($sql_file) . "</p>";
        echo "</div></body></html>";
        exit;
    }

    $sql = file_get_contents($sql_file);
    
    try {
        // Execute each statement separately to handle multiple statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $statement) {
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                $conn->exec($statement);
            }
        }
        echo "<p style='color: green;'>✅ Fines table created successfully!</p>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false || 
            strpos($e->getMessage(), 'Duplicate') !== false ||
            strpos($e->getMessage(), 'Table') !== false && strpos($e->getMessage(), 'exists') !== false) {
            echo "<p style='color: orange;'>ℹ️ Table already exists - that's okay!</p>";
        } else {
            echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>You can also run this SQL manually in phpMyAdmin:</strong></p>";
            echo "<pre style='background: #fff; padding: 15px; border: 1px solid #ddd; overflow-x: auto;'>" . htmlspecialchars($sql) . "</pre>";
            echo "</div></body></html>";
            exit;
        }
    }

    echo "<p style='color: green; font-weight: bold;'>✅ Setup completed successfully!</p>";
    echo "<p><a href='fines.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Go to Fines Page</a></p>";
    echo "</div></body></html>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div></body></html>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ General error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div></body></html>";
}
?>


