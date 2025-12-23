<?php
/**
 * Database Update Script - Add Guide Fields
 * Run this once to add the new guide-specific fields to the users table
 */

require_once __DIR__ . '/config/config.php';

$host = "localhost";
$username = "root";
$password = "";
$database_name = "globego_db";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Database - Guide Fields</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Update - Guide Fields</h1>
        
        <?php
        try {
            $conn = new PDO("mysql:host=$host;dbname=$database_name", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Read SQL file
            $sql_file = __DIR__ . '/database/add_guide_fields.sql';
            $sql = file_get_contents($sql_file);
            
            if ($sql === false) {
                throw new Exception("Could not read SQL file: $sql_file");
            }
            
            // Remove comments and split by semicolon
            $sql = preg_replace('/--.*$/m', '', $sql);
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
            
            // Split into statements
            $statements = array_filter(
                array_map('trim', explode(';', $sql)),
                function($stmt) {
                    return !empty($stmt);
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
                    // Check if column already exists
                    if (strpos($e->getMessage(), 'Duplicate column name') !== false || 
                        strpos($e->getMessage(), 'already exists') !== false) {
                        // Column already exists, that's okay
                        continue;
                    } else {
                        $errors[] = $e->getMessage();
                    }
                }
            }
            
            if (empty($errors)) {
                echo '<div class="success">';
                echo '<h2>✅ Database Updated Successfully!</h2>';
                echo '<p>The following fields have been added to the users table:</p>';
                echo '<ul>';
                echo '<li>national_id (VARCHAR)</li>';
                echo '<li>date_of_birth (DATE)</li>';
                echo '<li>address (TEXT)</li>';
                echo '<li>criminal_records (BOOLEAN)</li>';
                echo '<li>application_status (ENUM: pending, under_review, approved, rejected)</li>';
                echo '<li>application_notes (TEXT)</li>';
                echo '</ul>';
                echo '<p><strong>Executed ' . $executed . ' SQL statements</strong></p>';
                echo '</div>';
                
                echo '<div class="info">';
                echo '<p><strong>Note:</strong> If some columns already existed, that\'s normal. The update script is safe to run multiple times.</p>';
                echo '</div>';
            } else {
                echo '<div class="error">';
                echo '<h2>⚠️ Update Completed with Warnings</h2>';
                echo '<p>Some operations completed, but there were warnings:</p>';
                foreach ($errors as $error) {
                    echo '<p>' . htmlspecialchars($error) . '</p>';
                }
                echo '</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="error">';
            echo '<h2>❌ Database Error</h2>';
            echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<h2>❌ Error</h2>';
            echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
        ?>
        
        <div class="info" style="margin-top: 20px;">
            <p><strong>Next Steps:</strong></p>
            <ul>
                <li>You can now use the guide registration form</li>
                <li>Visit: <a href="<?php echo SITE_URL; ?>/auth/register-guide.php">Register as Tour Guide</a></li>
                <li>Or go back to: <a href="<?php echo SITE_URL; ?>/index.php">Homepage</a></li>
            </ul>
        </div>
    </div>
</body>
</html>

