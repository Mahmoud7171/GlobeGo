<?php
/**
 * Quick check to see if suspend_until field exists
 */
require_once 'config/config.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Check if suspend_until column exists
    $stmt = $conn->query("SHOW COLUMNS FROM users LIKE 'suspend_until'");
    $exists = $stmt->rowCount() > 0;
    
    if ($exists) {
        echo "✅ suspend_until field exists in the database.\n";
        echo "All features are ready to use!\n";
    } else {
        echo "❌ suspend_until field does NOT exist.\n";
        echo "Please run: http://localhost/GlobeGoProject/update-database-suspend-fields.php\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

