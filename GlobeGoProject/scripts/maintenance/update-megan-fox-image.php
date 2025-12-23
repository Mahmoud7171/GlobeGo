<?php
/**
 * Update Megan Fox's profile image
 * Access via: http://localhost/GlobeGoProject/update-megan-fox-image.php
 */
require_once __DIR__ . '/config/config.php';

// Check if admin (optional security check)
if (!isLoggedIn() || !isAdmin()) {
    die("Access denied. Admin access required.");
}

$database = new Database();
$db = $database->getConnection();

echo "<h2>Updating Megan Fox's Profile Image</h2>";
echo "<pre>";

try {
    // Find Megan Fox
    $check_query = "SELECT id, first_name, last_name, profile_image FROM users WHERE first_name = 'Megan' AND last_name = 'Fox' AND role = 'guide' LIMIT 1";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->execute();
    $megan_fox = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$megan_fox) {
        echo "✗ Megan Fox not found in database!\n";
        exit;
    }

    echo "Found: {$megan_fox['first_name']} {$megan_fox['last_name']} (ID: {$megan_fox['id']})\n";
    echo "Current image: " . ($megan_fox['profile_image'] ?: 'None') . "\n\n";

    // Set Megan Fox's profile image to TourGuide6.jpg
    $new_image = 'images/TourGuide6.jpg';
    
    $update_query = "UPDATE users SET profile_image = :profile_image WHERE id = :id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':profile_image', $new_image);
    $update_stmt->bindParam(':id', $megan_fox['id']);
    
    if ($update_stmt->execute()) {
        echo "✓ Successfully updated Megan Fox's profile image\n";
        echo "  Changed from: " . ($megan_fox['profile_image'] ?: 'None') . "\n";
        echo "  Changed to: $new_image\n\n";
        
        // Verify
        $verify_query = "SELECT first_name, last_name, profile_image FROM users WHERE id = :id";
        $verify_stmt = $db->prepare($verify_query);
        $verify_stmt->bindParam(':id', $megan_fox['id']);
        $verify_stmt->execute();
        $updated = $verify_stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "✓ Verification:\n";
        echo "  Name: {$updated['first_name']} {$updated['last_name']}\n";
        echo "  Profile Image: " . ($updated['profile_image'] ?: 'None') . "\n";
    } else {
        echo "✗ Failed to update profile image!\n";
    }

} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
echo "<p><a href='tour-details.php?id=24'>View Tour Details</a></p>";
?>

