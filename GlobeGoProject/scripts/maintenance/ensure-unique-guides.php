<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Ensure Each Tour Has a Unique Guide</h2>";

try {
    // Check TourGuide images
    echo "<h3>Checking TourGuide Images:</h3>";
    $tourguide_images = [
        'images/TourGuide1.png',
        'images/TourGuide2.jpg', 
        'images/TourGuide3.jpg',
        'images/TourGuide4.jpg',
        'images/TourGuide5.png'
    ];
    
    $available_images = [];
    foreach ($tourguide_images as $index => $file) {
        if (file_exists($file)) {
            echo "<p style='color: green;'>✓ TourGuide " . ($index + 1) . " (" . $file . ") - Found</p>";
            $available_images[] = $file;
        } else {
            echo "<p style='color: red;'>✗ TourGuide " . ($index + 1) . " (" . $file . ") - Missing</p>";
        }
    }
    
    if (empty($available_images)) {
        echo "<p style='color: red;'>No TourGuide images found!</p>";
        exit;
    }
    
    // Get current tours
    echo "<h3>Current Tours:</h3>";
    $tours_stmt = $conn->query("
        SELECT t.id, t.title, t.guide_id, u.first_name, u.last_name, u.profile_image, a.name as attraction_name
        FROM tours t 
        LEFT JOIN users u ON t.guide_id = u.id 
        LEFT JOIN attractions a ON t.attraction_id = a.id 
        WHERE t.status = 'active'
        ORDER BY t.id
    ");
    $tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($tours)) {
        echo "<p style='color: red;'>No tours found!</p>";
        echo "<p>Run <a href='assign-unique-guides.php'>assign-unique-guides.php</a> to create tours with unique guides.</p>";
        exit;
    }
    
    echo "<p>Found " . count($tours) . " tours</p>";
    
    // Check if guides already have unique images
    $current_guide_images = [];
    $tours_needing_update = [];
    
    foreach ($tours as $tour) {
        if (!empty($tour['profile_image'])) {
            $current_guide_images[] = $tour['profile_image'];
        }
        $tours_needing_update[] = $tour;
    }
    
    $unique_current_images = array_unique($current_guide_images);
    
    if (count($current_guide_images) === count($unique_current_images) && count($current_guide_images) === count($tours)) {
        echo "<p style='color: green;'>✓ All tours already have unique guide images!</p>";
    } else {
        echo "<p style='color: orange;'>Some tours share guide images or have missing images. Updating...</p>";
        
        // Create/update guides to ensure uniqueness
        echo "<h3>Creating/Updating Guides for Uniqueness:</h3>";
        
        // Delete existing guides
        $delete_guides_stmt = $conn->prepare("DELETE FROM users WHERE role = 'guide'");
        $delete_guides_stmt->execute();
        echo "<p>Cleared existing guides</p>";
        
        // Create unique guides
        $guide_data = [
            ['Alex', 'Martinez', 'alex.martinez@globego.com', 'images/TourGuide1.png', 'English, Spanish', 'Experienced tour guide specializing in historical sites.'],
            ['Sarah', 'Johnson', 'sarah.johnson@globego.com', 'images/TourGuide2.jpg', 'English, French', 'Professional guide with expertise in art and architecture.'],
            ['Michael', 'Brown', 'michael.brown@globego.com', 'images/TourGuide3.jpg', 'English, Italian', 'Local expert passionate about authentic experiences.'],
            ['Emma', 'Wilson', 'emma.wilson@globego.com', 'images/TourGuide4.jpg', 'English, German', 'Certified guide with deep historical knowledge.'],
            ['David', 'Davis', 'david.davis@globego.com', 'images/TourGuide5.png', 'English, Portuguese', 'Adventure specialist offering unique perspectives.']
        ];
        
        $guide_password = password_hash('guide123', PASSWORD_DEFAULT);
        $created_guides = [];
        
        foreach ($guide_data as $index => $data) {
            $insert_guide_stmt = $conn->prepare("
                INSERT INTO users (email, password, first_name, last_name, role, profile_image, languages, bio, verified, status) 
                VALUES (?, ?, ?, ?, 'guide', ?, ?, ?, 1, 'active')
            ");
            $insert_guide_stmt->execute([
                $data[2], // email
                $guide_password, // password
                $data[0], // first_name
                $data[1], // last_name
                $data[3], // profile_image
                $data[4], // languages
                $data[5]  // bio
            ]);
            
            $guide_id = $conn->lastInsertId();
            $created_guides[] = [
                'id' => $guide_id,
                'name' => $data[0] . ' ' . $data[1],
                'image' => $data[3]
            ];
            
            echo "<p style='color: green;'>✓ Created guide: " . $data[0] . " " . $data[1] . " (ID: " . $guide_id . ") with image: " . $data[3] . "</p>";
        }
        
        // Update tours to use unique guides
        echo "<h3>Updating Tours with Unique Guides:</h3>";
        foreach ($tours as $index => $tour) {
            if ($index < count($created_guides)) {
                $guide = $created_guides[$index];
                
                $update_tour_stmt = $conn->prepare("UPDATE tours SET guide_id = ? WHERE id = ?");
                $update_tour_stmt->execute([$guide['id'], $tour['id']]);
                
                echo "<p style='color: green;'>✓ Updated tour: " . $tour['title'] . " → Guide: " . $guide['name'] . " (" . $guide['image'] . ")</p>";
            }
        }
    }
    
    // Final verification
    echo "<h3>Final Verification - Unique Guide Assignment:</h3>";
    $final_tours_stmt = $conn->query("
        SELECT t.id, t.title, u.first_name, u.last_name, u.profile_image, a.name as attraction_name
        FROM tours t 
        LEFT JOIN users u ON t.guide_id = u.id 
        LEFT JOIN attractions a ON t.attraction_id = a.id 
        WHERE t.status = 'active'
        ORDER BY t.id
    ");
    $final_tours = $final_tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Tour ID</th><th>Tour Title</th><th>Guide Name</th><th>Guide Image</th><th>Attraction</th><th>Status</th></tr>";
    foreach ($final_tours as $tour) {
        $status = file_exists($tour['profile_image']) ? '✓ Ready' : '✗ Missing';
        $color = file_exists($tour['profile_image']) ? 'green' : 'red';
        echo "<tr>";
        echo "<td>" . $tour['id'] . "</td>";
        echo "<td>" . $tour['title'] . "</td>";
        echo "<td>" . $tour['first_name'] . " " . $tour['last_name'] . "</td>";
        echo "<td>" . $tour['profile_image'] . "</td>";
        echo "<td>" . $tour['attraction_name'] . "</td>";
        echo "<td style='color: " . $color . ";'>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Verify uniqueness
    echo "<h3>Uniqueness Verification:</h3>";
    $guide_images = array_column($final_tours, 'profile_image');
    $unique_images = array_unique($guide_images);
    
    if (count($guide_images) === count($unique_images)) {
        echo "<p style='color: green;'>✅ SUCCESS: All tours have unique guide images!</p>";
        echo "<p><strong>Guide Image Distribution:</strong></p>";
        echo "<ul>";
        foreach ($final_tours as $tour) {
            echo "<li>" . $tour['title'] . " → " . $tour['first_name'] . " " . $tour['last_name'] . " (" . $tour['profile_image'] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ ERROR: Some tours still share the same guide image</p>";
    }
    
    echo "<hr>";
    echo "<h3>✅ Unique Guide Assignment Complete!</h3>";
    echo "<p>Each tour now has a unique guide with a different TourGuide image:</p>";
    echo "<ul>";
    echo "<li><a href='tours.php'>View All Tours</a></li>";
    foreach ($final_tours as $tour) {
        echo "<li><a href='tour-details.php?id=" . $tour['id'] . "'>" . $tour['title'] . " (Guide: " . $tour['first_name'] . " " . $tour['last_name'] . ")</a></li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

