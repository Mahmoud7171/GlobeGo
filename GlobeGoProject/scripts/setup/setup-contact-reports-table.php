<?php
/**
 * Setup script to create contact_reports table
 * Run this once to create the table in your database
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        die("Database connection failed!");
    }

    echo "<h2>Setting up Contact Reports Table</h2>";

    // Read and execute SQL
    $sql = file_get_contents(__DIR__ . '/database/create_contact_reports_table.sql');
    
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $conn->exec($statement);
            echo "<p class='success'>✓ Executed successfully</p>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'already exists') !== false || 
                strpos($e->getMessage(), 'Duplicate') !== false) {
                echo "<p class='info'>ℹ️ Table or index already exists - skipping</p>";
            } else {
                echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }

    echo "<p class='success'>✅ Contact reports table setup completed!</p>";
    echo "<p><a href='admin/reports.php'>Go to Reports Page</a></p>";

} catch (PDOException $e) {
    echo "<p class='error'>❌ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ General error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: orange; }
</style>











