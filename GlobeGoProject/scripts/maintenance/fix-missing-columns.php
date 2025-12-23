<?php
/**
 * Script to add all missing columns to the users table
 * This fixes the database schema issues
 */

require_once 'config/config.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    if (!$conn) {
        die("Database connection failed!");
    }
    
    echo "<h2>Adding Missing Columns to Users Table</h2>";
    echo "<style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; margin: 10px 0; }
        .error { color: red; margin: 10px 0; }
        .info { color: blue; margin: 10px 0; }
        .skip { color: orange; margin: 10px 0; }
    </style>";
    
    // Function to check if column exists
    function columnExists($conn, $table, $column) {
        $stmt = $conn->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $stmt->execute([$column]);
        return $stmt->rowCount() > 0;
    }
    
    // Function to add column if it doesn't exist
    function addColumnIfNotExists($conn, $table, $column, $definition, $after = null) {
        if (columnExists($conn, $table, $column)) {
            echo "<div class='skip'>⏭️  Column '$column' already exists - skipping</div>";
            return true;
        }
        
        try {
            $sql = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
            if ($after) {
                $sql .= " AFTER `$after`";
            }
            $conn->exec($sql);
            echo "<div class='success'>✓ Added column '$column'</div>";
            return true;
        } catch (PDOException $e) {
            echo "<div class='error'>✗ Error adding column '$column': " . $e->getMessage() . "</div>";
            return false;
        }
    }
    
    // Add all missing columns
    echo "<div class='info'><strong>Checking and adding missing columns...</strong></div><br>";
    
    // Guide-specific fields
    addColumnIfNotExists($conn, 'users', 'national_id', 'VARCHAR(50) NULL', 'phone');
    addColumnIfNotExists($conn, 'users', 'date_of_birth', 'DATE NULL', 'national_id');
    addColumnIfNotExists($conn, 'users', 'address', 'TEXT NULL', 'date_of_birth');
    addColumnIfNotExists($conn, 'users', 'criminal_records', 'BOOLEAN DEFAULT FALSE', 'address');
    addColumnIfNotExists($conn, 'users', 'application_status', "ENUM('pending', 'under_review', 'approved', 'rejected') DEFAULT 'pending'", 'criminal_records');
    addColumnIfNotExists($conn, 'users', 'application_notes', 'TEXT NULL', 'application_status');
    
    // Suspension field
    addColumnIfNotExists($conn, 'users', 'suspend_until', 'DATETIME NULL', 'status');
    
    // Add indexes
    echo "<br><div class='info'><strong>Adding indexes...</strong></div><br>";
    
    try {
        $conn->exec("CREATE INDEX idx_application_status ON users(application_status)");
        echo "<div class='success'>✓ Added index idx_application_status</div>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "<div class='skip'>⏭️  Index idx_application_status already exists</div>";
        } else {
            echo "<div class='error'>✗ Error adding index idx_application_status: " . $e->getMessage() . "</div>";
        }
    }
    
    try {
        $conn->exec("CREATE INDEX idx_suspend_until ON users(suspend_until)");
        echo "<div class='success'>✓ Added index idx_suspend_until</div>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "<div class='skip'>⏭️  Index idx_suspend_until already exists</div>";
        } else {
            echo "<div class='error'>✗ Error adding index idx_suspend_until: " . $e->getMessage() . "</div>";
        }
    }
    
    echo "<br><div class='success'><h3>✅ Database update complete!</h3>";
    echo "<p>All missing columns have been added to the users table.</p>";
    echo "<p><a href='admin/dashboard.php'>Go to Admin Dashboard</a> | <a href='index.php'>Go to Homepage</a></p></div>";
    
} catch (PDOException $e) {
    echo "<div class='error'><h3>❌ Error:</h3><p>" . $e->getMessage() . "</p></div>";
} catch (Exception $e) {
    echo "<div class='error'><h3>❌ Error:</h3><p>" . $e->getMessage() . "</p></div>";
}
?>











