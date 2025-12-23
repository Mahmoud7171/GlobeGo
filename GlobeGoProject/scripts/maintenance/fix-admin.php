<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Fix Admin Account</h2>";

try {
    // Delete existing admin if exists
    $delete_stmt = $conn->prepare("DELETE FROM users WHERE email = 'admin@globego.com' OR email = 'admin@globeGo.com'");
    $delete_stmt->execute();
    echo "<p>Deleted existing admin accounts</p>";
    
    // Create new admin account with simple password
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $insert_stmt = $conn->prepare("
        INSERT INTO users (email, password, first_name, last_name, role, verified, status) 
        VALUES ('admin@globego.com', :password, 'Admin', 'User', 'admin', 1, 'active')
    ");
    $insert_stmt->bindParam(':password', $admin_password);
    $insert_stmt->execute();
    echo "<p style='color: green;'>Admin account created successfully!</p>";
    
    // Create test tourist account
    $tourist_password = password_hash('tourist123', PASSWORD_DEFAULT);
    $tourist_stmt = $conn->prepare("
        INSERT INTO users (email, password, first_name, last_name, role, verified, status) 
        VALUES ('tourist@test.com', :password, 'Test', 'Tourist', 'tourist', 1, 'active')
    ");
    $tourist_stmt->bindParam(':password', $tourist_password);
    $tourist_stmt->execute();
    echo "<p style='color: green;'>Test tourist account created!</p>";
    
    // Create test guide account
    $guide_password = password_hash('guide123', PASSWORD_DEFAULT);
    $guide_stmt = $conn->prepare("
        INSERT INTO users (email, password, first_name, last_name, role, verified, status) 
        VALUES ('guide@test.com', :password, 'Test', 'Guide', 'guide', 1, 'active')
    ");
    $guide_stmt->bindParam(':password', $guide_password);
    $guide_stmt->execute();
    echo "<p style='color: green;'>Test guide account created!</p>";
    
    echo "<hr>";
    echo "<h3>Test Accounts Created:</h3>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin@globego.com / admin123</li>";
    echo "<li><strong>Tourist:</strong> tourist@test.com / tourist123</li>";
    echo "<li><strong>Guide:</strong> guide@test.com / guide123</li>";
    echo "</ul>";
    
    echo "<p><a href='auth/login.php'>Go to Login Page</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
