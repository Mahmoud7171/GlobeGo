<?php
/**
 * Script to add suspend_until field to users table
 * Run this once to update the database schema
 */

require_once 'config/config.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    if (!$conn) {
        die("Database connection failed!");
    }
    
    // Read and execute SQL file
    $sql = file_get_contents('database/add_suspend_delete_fields.sql');
    
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $conn->exec($statement);
            echo "✓ Executed: " . substr($statement, 0, 50) . "...\n";
        }
    }
    
    echo "\n✅ Database updated successfully! The suspend_until field has been added.\n";
    
} catch (PDOException $e) {
    // Check if column already exists
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "ℹ️  The suspend_until field already exists in the database.\n";
    } else {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

