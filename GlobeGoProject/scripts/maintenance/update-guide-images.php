<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Update Guide Images with TourGuide Images</h2>";

try {
    // Check if TourGuide images exist
    echo "<h3>Checking TourGuide Images:</h3>";
    $tourguide_images = [
        'images/TourGuide1.png',
        'images/TourGuide2.jpg', 
        'images/TourGuide3.jpg',
        'images/TourGuide4.jpg',
        'images/TourGuide5.png'
    ];
    
    foreach ($tourguide_images as $index => $file) {
        if (file_exists($file)) {
            echo "<p style='color: green;'>✓ TourGuide " . ($index + 1) . " (" . $file . ") - Found</p>";
        } else {
            echo "<p style='color: red;'>✗ TourGuide " . ($index + 1) . " (" . $file . ") - Missing</p>";
        }
    }
    
    // Get all guides
    echo "<h3>Current Guides:</h3>";
    $guides_stmt = $conn->query("SELECT id, first_name, last_name, email, profile_image FROM users WHERE role = 'guide' ORDER BY id");
    $guides = $guides_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($guides)) {
        echo "<p style='color: red;'>No guides found in database!</p>";
        echo "<p>Run <a href='assign-tourguide-images.php'>assign-tourguide-images.php</a> to create guides and assign images.</p>";
        exit;
    }
    
    echo "<p>Found " . count($guides) . " guides</p>";
    
    // Update guide profile images
    echo "<h3>Updating Guide Profile Images:</h3>";
    
    foreach ($guides as $index => $guide) {
        if ($index < count($tourguide_images)) {
            $image_file = $tourguide_images[$index];
            
            $update_stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
            $update_stmt->execute([$image_file, $guide['id']]);
            
            echo "<p style='color: green;'>✓ Updated " . $guide['first_name'] . " " . $guide['last_name'] . " → " . $image_file . "</p>";
        } else {
            echo "<p style='color: orange;'>⚠ No image available for " . $guide['first_name'] . " " . $guide['last_name'] . " (only " . count($tourguide_images) . " images available)</p>";
        }
    }
    
    // Show updated guides
    echo "<h3>Updated Guides:</h3>";
    $updated_guides_stmt = $conn->query("SELECT id, first_name, last_name, profile_image FROM users WHERE role = 'guide' ORDER BY id");
    $updated_guides = $updated_guides_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Profile Image</th><th>Status</th></tr>";
    foreach ($updated_guides as $guide) {
        $status = file_exists($guide['profile_image']) ? '✓ Ready' : '✗ File Missing';
        $color = file_exists($guide['profile_image']) ? 'green' : 'red';
        echo "<tr>";
        echo "<td>" . $guide['id'] . "</td>";
        echo "<td>" . $guide['first_name'] . " " . $guide['last_name'] . "</td>";
        echo "<td>" . $guide['profile_image'] . "</td>";
        echo "<td style='color: " . $color . ";'>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Show tours with their guides
    echo "<h3>Tours and Their Guides:</h3>";
    $tours_stmt = $conn->query("
        SELECT t.id, t.title, u.first_name, u.last_name, u.profile_image, a.name as attraction_name
        FROM tours t 
        LEFT JOIN users u ON t.guide_id = u.id 
        LEFT JOIN attractions a ON t.attraction_id = a.id 
        WHERE t.status = 'active'
        ORDER BY t.id
    ");
    $tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($tours)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Tour ID</th><th>Tour Title</th><th>Guide</th><th>Attraction</th><th>Guide Image</th></tr>";
        foreach ($tours as $tour) {
            echo "<tr>";
            echo "<td>" . $tour['id'] . "</td>";
            echo "<td>" . $tour['title'] . "</td>";
            echo "<td>" . $tour['first_name'] . " " . $tour['last_name'] . "</td>";
            echo "<td>" . ($tour['attraction_name'] ?: 'N/A') . "</td>";
            echo "<td>" . $tour['profile_image'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>No tours found.</p>";
    }
    
    echo "<hr>";
    echo "<h3>✅ Guide Images Updated Successfully!</h3>";
    echo "<p>Your guides now have professional TourGuide images:</p>";
    echo "<ul>";
    echo "<li><a href='tours.php'>View All Tours</a></li>";
    echo "<li><a href='tour-details.php?id=1'>View First Tour</a></li>";
    echo "<li><a href='attractions.php'>View Attractions</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

