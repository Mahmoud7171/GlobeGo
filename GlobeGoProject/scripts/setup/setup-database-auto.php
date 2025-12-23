<?php
/**
 * GlobeGo Database Auto-Setup Script
 * 
 * This script automatically creates the database and all tables
 * by reading and executing the SQL setup file.
 * 
 * Usage: Navigate to http://localhost/GlobeGoProject/setup-database-auto.php
 */

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database_name = "globego_db";

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlobeGo Database Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #dc3545;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #17a2b8;
        }
        .step {
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        pre {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌍 GlobeGo Database Setup</h1>
        
        <?php
        try {
            // Step 1: Connect to MySQL server (without database)
            echo '<div class="step">';
            echo '<strong>Step 1:</strong> Connecting to MySQL server...<br>';
            
            $conn = new PDO("mysql:host=$host", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            echo '<span class="success">✓ Connected successfully to MySQL server</span>';
            echo '</div>';
            
            // Step 2: Create database if it doesn't exist
            echo '<div class="step">';
            echo '<strong>Step 2:</strong> Creating database...<br>';
            
            $conn->exec("CREATE DATABASE IF NOT EXISTS `$database_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            echo '<span class="success">✓ Database "' . $database_name . '" created or already exists</span>';
            echo '</div>';
            
            // Step 3: Select the database
            echo '<div class="step">';
            echo '<strong>Step 3:</strong> Selecting database...<br>';
            
            $conn->exec("USE `$database_name`");
            
            echo '<span class="success">✓ Database selected</span>';
            echo '</div>';
            
            // Step 4: Read and execute SQL file
            echo '<div class="step">';
            echo '<strong>Step 4:</strong> Reading SQL setup file...<br>';
            
            $sql_file = __DIR__ . '/database/complete-setup.sql';
            
            if (!file_exists($sql_file)) {
                throw new Exception("SQL file not found: $sql_file");
            }
            
            $sql = file_get_contents($sql_file);
            
            if ($sql === false) {
                throw new Exception("Could not read SQL file: $sql_file");
            }
            
            echo '<span class="success">✓ SQL file loaded (' . number_format(strlen($sql)) . ' bytes)</span>';
            echo '</div>';
            
            // Step 5: Execute SQL statements
            echo '<div class="step">';
            echo '<strong>Step 5:</strong> Executing SQL statements...<br>';
            
            // Remove comments and split by semicolon
            $sql = preg_replace('/--.*$/m', '', $sql);
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
            
            // Split into individual statements
            $statements = array_filter(
                array_map('trim', explode(';', $sql)),
                function($stmt) {
                    return !empty($stmt) && !preg_match('/^(USE|SET)/i', $stmt);
                }
            );
            
            $executed = 0;
            $errors = [];
            
            foreach ($statements as $statement) {
                if (empty(trim($statement))) {
                    continue;
                }
                
                try {
                    $conn->exec($statement);
                    $executed++;
                } catch (PDOException $e) {
                    // Ignore "table already exists" errors during setup
                    if (strpos($e->getMessage(), 'already exists') === false && 
                        strpos($e->getMessage(), 'Duplicate') === false) {
                        $errors[] = $e->getMessage();
                    }
                }
            }
            
            echo '<span class="success">✓ Executed ' . $executed . ' SQL statements</span>';
            
            if (!empty($errors)) {
                echo '<div class="error">';
                echo '<strong>Warnings:</strong><br>';
                foreach ($errors as $error) {
                    echo htmlspecialchars($error) . '<br>';
                }
                echo '</div>';
            }
            echo '</div>';
            
            // Step 6: Verify setup
            echo '<div class="step">';
            echo '<strong>Step 6:</strong> Verifying database setup...<br>';
            
            $tables = ['users', 'attractions', 'tours', 'tour_schedules', 'bookings', 'reviews'];
            $missing_tables = [];
            
            foreach ($tables as $table) {
                $stmt = $conn->query("SHOW TABLES LIKE '$table'");
                if ($stmt->rowCount() == 0) {
                    $missing_tables[] = $table;
                }
            }
            
            if (empty($missing_tables)) {
                echo '<span class="success">✓ All tables created successfully</span><br>';
                
                // Count records
                $user_count = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
                $attraction_count = $conn->query("SELECT COUNT(*) FROM attractions")->fetchColumn();
                $tour_count = $conn->query("SELECT COUNT(*) FROM tours")->fetchColumn();
                $schedule_count = $conn->query("SELECT COUNT(*) FROM tour_schedules")->fetchColumn();
                
                echo '<div class="info">';
                echo '<strong>Database Summary:</strong><br>';
                echo '• Users: ' . $user_count . '<br>';
                echo '• Attractions: ' . $attraction_count . '<br>';
                echo '• Tours: ' . $tour_count . '<br>';
                echo '• Tour Schedules: ' . $schedule_count . '<br>';
                echo '</div>';
            } else {
                throw new Exception("Missing tables: " . implode(', ', $missing_tables));
            }
            echo '</div>';
            
            // Success message
            echo '<div class="success">';
            echo '<h2>✅ Database Setup Complete!</h2>';
            echo '<p>The GlobeGo database has been successfully created and populated with sample data.</p>';
            echo '<p><strong>Default Admin Login:</strong><br>';
            echo 'Email: <code>admin@globego.com</code><br>';
            echo 'Password: <code>password</code></p>';
            echo '<p><strong>Guide Logins:</strong><br>';
            echo 'All guides use the same password: <code>password</code><br>';
            echo 'Emails: guide1@globego.com, guide2@globego.com, etc.</p>';
            echo '</div>';
            
            echo '<a href="index.php" class="btn">Go to Homepage →</a>';
            
        } catch (PDOException $e) {
            echo '<div class="error">';
            echo '<h2>❌ Database Error</h2>';
            echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<p><strong>Code:</strong> ' . $e->getCode() . '</p>';
            echo '</div>';
            
            echo '<div class="info">';
            echo '<h3>Troubleshooting:</h3>';
            echo '<ul>';
            echo '<li>Make sure MySQL is running in XAMPP</li>';
            echo '<li>Check that the username and password are correct (default: root with no password)</li>';
            echo '<li>Verify you have permission to create databases</li>';
            echo '<li>Try running the SQL file manually in phpMyAdmin</li>';
            echo '</ul>';
            echo '</div>';
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<h2>❌ Setup Error</h2>';
            echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>

