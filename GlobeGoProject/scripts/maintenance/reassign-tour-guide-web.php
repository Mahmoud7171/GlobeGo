<?php
/**
 * Web-accessible script to reassign tour ID 24 from Mina Hassan to Megan Fox
 * Access this file via browser: http://localhost/GlobeGoProject/reassign-tour-guide-web.php
 */
require_once __DIR__ . '/config/config.php';

// Check if admin (optional security check)
if (!isLoggedIn() || !isAdmin()) {
    die("Access denied. Admin access required.");
}

$database = new Database();
$db = $database->getConnection();

echo "<h2>Reassigning Tour Guide</h2>";
echo "<pre>";

try {
    // First, find or create Megan Fox as a guide
    $check_query = "SELECT id FROM users WHERE first_name = 'Megan' AND last_name = 'Fox' AND role = 'guide' LIMIT 1";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->execute();
    $megan_fox = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$megan_fox) {
        // Create Megan Fox as a guide
        $create_query = "INSERT INTO users (email, password, first_name, last_name, role, verified, status, bio, languages) 
                        VALUES (:email, :password, 'Megan', 'Fox', 'guide', 1, 'active', 
                        'Experienced tour guide specializing in historical and cultural tours. Passionate about sharing the mysteries and stories of ancient sites.', 
                        'English, Spanish')";
        $create_stmt = $db->prepare($create_query);
        $email = 'megan.fox@globego.com';
        $password = password_hash('password', PASSWORD_DEFAULT);
        $create_stmt->bindParam(':email', $email);
        $create_stmt->bindParam(':password', $password);
        $create_stmt->execute();
        $megan_fox_id = $db->lastInsertId();
        echo "✓ Created Megan Fox as a guide (ID: $megan_fox_id)\n";
    } else {
        $megan_fox_id = $megan_fox['id'];
        echo "✓ Found Megan Fox (ID: $megan_fox_id)\n";
    }

    // Get current tour info
    $tour_query = "SELECT t.*, u.first_name, u.last_name FROM tours t 
                   LEFT JOIN users u ON t.guide_id = u.id 
                   WHERE t.id = 24";
    $tour_stmt = $db->prepare($tour_query);
    $tour_stmt->execute();
    $tour = $tour_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tour) {
        echo "✗ Tour ID 24 not found!\n";
        exit;
    }

    echo "Current guide: {$tour['first_name']} {$tour['last_name']} (ID: {$tour['guide_id']})\n";
    echo "Tour: {$tour['title']}\n\n";

    // Update the tour's guide_id
    $update_query = "UPDATE tours SET guide_id = :new_guide_id WHERE id = 24";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':new_guide_id', $megan_fox_id);
    
    if ($update_stmt->execute()) {
        echo "✓ Successfully reassigned tour ID 24 to Megan Fox!\n\n";
        
        // Verify the change
        $verify_query = "SELECT t.title, u.first_name, u.last_name, u.email FROM tours t 
                         LEFT JOIN users u ON t.guide_id = u.id 
                         WHERE t.id = 24";
        $verify_stmt = $db->prepare($verify_query);
        $verify_stmt->execute();
        $updated_tour = $verify_stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "✓ Verification:\n";
        echo "  Tour: '{$updated_tour['title']}'\n";
        echo "  Guide: {$updated_tour['first_name']} {$updated_tour['last_name']}\n";
        echo "  Email: {$updated_tour['email']}\n";
        echo "\n✓ Done! You can now view the tour at: <a href='tour-details.php?id=24'>tour-details.php?id=24</a>\n";
    } else {
        echo "✗ Failed to update tour guide!\n";
    }

} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>



