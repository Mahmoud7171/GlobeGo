<?php
// Database configuration with Singleton Design Pattern
class Database {
    // Singleton Pattern: Static instance variable
    private static $instance = null;
    // Singleton Pattern: Shared connection across all instances
    private static $connection = null;
    
    private $host = "localhost";
    private $db_name = "globego_db";
    private $username = "root";
    private $password = "";
    public $conn;

    // Public constructor for backward compatibility
    // Even though constructor is public, Singleton pattern ensures single connection
    public function __construct() {
        // If singleton connection exists, reuse it
        if (self::$connection !== null) {
            $this->conn = self::$connection;
        }
    }

    // Singleton Pattern: getInstance() method - ensures only one instance exists
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        // Singleton Pattern: If connection already exists and is valid, reuse it
        if (self::$connection !== null) {
            try {
                // Test if connection is still alive
                self::$connection->query("SELECT 1");
                $this->conn = self::$connection;
                return $this->conn;
            } catch (PDOException $e) {
                // Connection is dead, create new one
                self::$connection = null;
            }
        }

        // Singleton Pattern: Create new connection only if one doesn't exist
        // This ensures only ONE database connection exists throughout the application
        try {
            self::$connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", 
                                      $this->username, $this->password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            $this->conn = self::$connection;
        } catch(PDOException $exception) {
            // Check if database doesn't exist
            if ($exception->getCode() == 1049) {
                die("
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px;'>
                    <h2 style='color: #856404;'>⚠️ Database Not Found</h2>
                    <p style='color: #856404;'><strong>Error:</strong> The database '{$this->db_name}' does not exist.</p>
                    <p style='color: #856404;'><strong>Solution:</strong> Please set up the database first.</p>
                    <div style='margin-top: 20px; padding: 15px; background: white; border-radius: 5px;'>
                        <h3 style='color: #856404; margin-top: 0;'>Option 1: Automatic Setup (Recommended)</h3>
                        <p>Visit: <a href='setup-database-auto.php' style='color: #0066cc; font-weight: bold;'>setup-database-auto.php</a></p>
                    </div>
                    <div style='margin-top: 15px; padding: 15px; background: white; border-radius: 5px;'>
                        <h3 style='color: #856404; margin-top: 0;'>Option 2: Manual Setup</h3>
                        <p>Run the SQL file in phpMyAdmin: <code>database/complete-setup.sql</code></p>
                    </div>
                </div>
                ");
            } else {
                die("
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background: #f8d7da; border: 2px solid #dc3545; border-radius: 8px;'>
                    <h2 style='color: #721c24;'>❌ Database Connection Error</h2>
                    <p style='color: #721c24;'><strong>Error:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>
                    <p style='color: #721c24;'><strong>Code:</strong> " . $exception->getCode() . "</p>
                    <div style='margin-top: 20px; padding: 15px; background: white; border-radius: 5px;'>
                        <h3 style='color: #721c24; margin-top: 0;'>Troubleshooting:</h3>
                        <ul style='color: #721c24;'>
                            <li>Make sure MySQL is running in XAMPP</li>
                            <li>Check database credentials in config/database.php</li>
                            <li>Verify the database exists</li>
                        </ul>
                    </div>
                </div>
                ");
            }
        }
        return $this->conn;
    }
}
?>
