<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Update Tour Images</h2>";

try {
    // Check if the image files exist
    $image_files = [
        'Paris.png' => 'images/Paris.png',
        'Rome.png' => 'images/Rome.png', 
        'NYC.png' => 'images/NYC.png'
    ];
    
    echo "<h3>Checking Image Files:</h3>";
    foreach ($image_files as $filename => $path) {
        if (file_exists($path)) {
            echo "<p style='color: green;'>✓ Found: " . $filename . "</p>";
        } else {
            echo "<p style='color: red;'>✗ Missing: " . $filename . "</p>";
        }
    }
    
    // Update tours with the correct images
    $tour_updates = [
        [
            'title' => 'Paris Evening Walk',
            'image_url' => 'images/Paris.png',
            'description' => 'Experience the magic of Paris at night with a guided walk through the City of Light.'
        ],
        [
            'title' => 'Ancient Rome Discovery', 
            'image_url' => 'images/Rome.png',
            'description' => 'Explore the Colosseum and surrounding ancient Roman ruins with an expert guide.'
        ],
        [
            'title' => 'NYC Food Tour',
            'image_url' => 'images/NYC.png', 
            'description' => 'Taste the best of New York City through its diverse culinary scene.'
        ]
    ];
    
    echo "<h3>Updating Tour Images:</h3>";
    
    foreach ($tour_updates as $update) {
        $stmt = $conn->prepare("
            UPDATE tours 
            SET image_url = :image_url 
            WHERE title = :title
        ");
        
        $stmt->bindParam(':image_url', $update['image_url']);
        $stmt->bindParam(':title', $update['title']);
        
        if ($stmt->execute()) {
            $affected_rows = $stmt->rowCount();
            if ($affected_rows > 0) {
                echo "<p style='color: green;'>✓ Updated: " . $update['title'] . " → " . $update['image_url'] . "</p>";
            } else {
                echo "<p style='color: orange;'>⚠ No tour found with title: " . $update['title'] . "</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Failed to update: " . $update['title'] . "</p>";
        }
    }
    
    // Also update attractions with the same images
    echo "<h3>Updating Attraction Images:</h3>";
    
    $attraction_updates = [
        [
            'name' => 'Eiffel Tower',
            'image_url' => 'images/Paris.png'
        ],
        [
            'name' => 'Colosseum',
            'image_url' => 'images/Rome.png'
        ],
        [
            'name' => 'Times Square', 
            'image_url' => 'images/NYC.png'
        ]
    ];
    
    foreach ($attraction_updates as $update) {
        $stmt = $conn->prepare("
            UPDATE attractions 
            SET image_url = :image_url 
            WHERE name = :name
        ");
        
        $stmt->bindParam(':image_url', $update['image_url']);
        $stmt->bindParam(':name', $update['name']);
        
        if ($stmt->execute()) {
            $affected_rows = $stmt->rowCount();
            if ($affected_rows > 0) {
                echo "<p style='color: green;'>✓ Updated attraction: " . $update['name'] . " → " . $update['image_url'] . "</p>";
            } else {
                echo "<p style='color: orange;'>⚠ No attraction found with name: " . $update['name'] . "</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Failed to update attraction: " . $update['name'] . "</p>";
        }
    }
    
    // Verify the updates
    echo "<h3>Verification - Current Tour Images:</h3>";
    $verify_stmt = $conn->query("
        SELECT id, title, image_url 
        FROM tours 
        WHERE status = 'active' 
        ORDER BY id
    ");
    $tours = $verify_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Title</th><th>Image URL</th></tr>";
    foreach ($tours as $tour) {
        echo "<tr>";
        echo "<td>" . $tour['id'] . "</td>";
        echo "<td>" . $tour['title'] . "</td>";
        echo "<td>" . $tour['image_url'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>✅ Tour Images Updated Successfully!</h3>";
    echo "<p>You can now view the tours with their new images:</p>";
    echo "<ul>";
    echo "<li><a href='tours.php'>View All Tours</a></li>";
    echo "<li><a href='tour-details.php?id=1'>Paris Evening Walk</a></li>";
    echo "<li><a href='tour-details.php?id=2'>Ancient Rome Discovery</a></li>";
    echo "<li><a href='tour-details.php?id=3'>NYC Food Tour</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

