<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Fix Admin Account</h2>";

try {
    // Delete existing admin accounts
    $delete_stmt = $conn->prepare("DELETE FROM users WHERE email LIKE '%admin%' OR role = 'admin'");
    $delete_stmt->execute();
    echo "<p>Deleted existing admin accounts</p>";
    
    // Create new admin account
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $insert_stmt = $conn->prepare("
        INSERT INTO users (email, password, first_name, last_name, role, verified, status) 
        VALUES ('admin@globego.com', :password, 'Admin', 'User', 'admin', 1, 'active')
    ");
    $insert_stmt->bindParam(':password', $admin_password);
    $insert_stmt->execute();
    
    echo "<p style='color: green;'>Admin account created successfully!</p>";
    echo "<p><strong>Email:</strong> admin@globego.com</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    
    echo "<p><a href='auth/login.php'>Go to Login Page</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>


