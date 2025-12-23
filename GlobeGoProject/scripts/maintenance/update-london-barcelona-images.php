<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Update London & Barcelona Tour Images</h2>";

try {
    // Check if image files exist
    echo "<h3>Checking Image Files:</h3>";
    $image_files = [
        'images/TowerBridge.jpg' => 'Tower Bridge Image',
        'images/SargadaFamilia.jpg' => 'Sagrada Familia Image'
    ];
    
    foreach ($image_files as $file => $description) {
        if (file_exists($file)) {
            echo "<p style='color: green;'>✓ " . $description . " (" . $file . ") - Found</p>";
        } else {
            echo "<p style='color: red;'>✗ " . $description . " (" . $file . ") - Missing</p>";
        }
    }
    
    // Update attraction images
    echo "<h3>Updating Attraction Images:</h3>";
    
    $attraction_updates = [
        ['name' => 'Tower Bridge', 'image_url' => 'images/TowerBridge.jpg'],
        ['name' => 'Sagrada Familia', 'image_url' => 'images/SargadaFamilia.jpg']
    ];
    
    foreach ($attraction_updates as $update) {
        $stmt = $conn->prepare("UPDATE attractions SET image_url = :image_url WHERE name = :name");
        $stmt->bindParam(':image_url', $update['image_url']);
        $stmt->bindParam(':name', $update['name']);
        
        if ($stmt->execute()) {
            $affected_rows = $stmt->rowCount();
            if ($affected_rows > 0) {
                echo "<p style='color: green;'>✓ Updated attraction: " . $update['name'] . "</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Attraction not found: " . $update['name'] . "</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Failed to update: " . $update['name'] . "</p>";
        }
    }
    
    // Update tour images (if tours exist)
    echo "<h3>Updating Tour Images:</h3>";
    
    // Check what tours exist for these attractions
    $tours_stmt = $conn->query("
        SELECT t.id, t.title, a.name as attraction_name
        FROM tours t 
        LEFT JOIN attractions a ON t.attraction_id = a.id 
        WHERE a.name IN ('Tower Bridge', 'Sagrada Familia') AND t.status = 'active'
    ");
    $existing_tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($existing_tours)) {
        echo "<p style='color: orange;'>No tours found for Tower Bridge or Sagrada Familia attractions.</p>";
        echo "<p>Run <a href='create-london-barcelona-tours.php'>create-london-barcelona-tours.php</a> to create the tours first.</p>";
    } else {
        echo "<p>Found " . count($existing_tours) . " existing tours:</p>";
        
        $tour_updates = [
            ['attraction' => 'Tower Bridge', 'image_url' => 'images/TowerBridge.jpg'],
            ['attraction' => 'Sagrada Familia', 'image_url' => 'images/SargadaFamilia.jpg']
        ];
        
        foreach ($tour_updates as $update) {
            $stmt = $conn->prepare("
                UPDATE tours t 
                JOIN attractions a ON t.attraction_id = a.id 
                SET t.image_url = :image_url 
                WHERE a.name = :attraction_name AND t.status = 'active'
            ");
            $stmt->bindParam(':image_url', $update['image_url']);
            $stmt->bindParam(':attraction_name', $update['attraction']);
            
            if ($stmt->execute()) {
                $affected_rows = $stmt->rowCount();
                if ($affected_rows > 0) {
                    echo "<p style='color: green;'>✓ Updated tours for: " . $update['attraction'] . "</p>";
                } else {
                    echo "<p style='color: orange;'>⚠ No tours found for: " . $update['attraction'] . "</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ Failed to update tours for: " . $update['attraction'] . "</p>";
            }
        }
    }
    
    // Final verification
    echo "<h3>Final Verification:</h3>";
    
    // Show attractions
    $attractions_stmt = $conn->query("
        SELECT id, name, image_url, location 
        FROM attractions 
        WHERE name IN ('Tower Bridge', 'Sagrada Familia')
        ORDER BY name
    ");
    $attractions = $attractions_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h4>Attractions:</h4>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Location</th><th>Image URL</th><th>Status</th></tr>";
    foreach ($attractions as $attraction) {
        $status = file_exists($attraction['image_url']) ? '✓ Ready' : '✗ File Missing';
        $color = file_exists($attraction['image_url']) ? 'green' : 'red';
        echo "<tr>";
        echo "<td>" . $attraction['id'] . "</td>";
        echo "<td>" . $attraction['name'] . "</td>";
        echo "<td>" . $attraction['location'] . "</td>";
        echo "<td>" . $attraction['image_url'] . "</td>";
        echo "<td style='color: " . $color . ";'>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Show tours
    if (!empty($existing_tours)) {
        echo "<h4>Tours:</h4>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Title</th><th>Attraction</th><th>Image URL</th><th>Status</th></tr>";
        foreach ($existing_tours as $tour) {
            // Get the updated image URL
            $tour_stmt = $conn->prepare("SELECT image_url FROM tours WHERE id = ?");
            $tour_stmt->execute([$tour['id']]);
            $tour_data = $tour_stmt->fetch(PDO::FETCH_ASSOC);
            
            $status = file_exists($tour_data['image_url']) ? '✓ Ready' : '✗ File Missing';
            $color = file_exists($tour_data['image_url']) ? 'green' : 'red';
            echo "<tr>";
            echo "<td>" . $tour['id'] . "</td>";
            echo "<td>" . $tour['title'] . "</td>";
            echo "<td>" . $tour['attraction_name'] . "</td>";
            echo "<td>" . $tour_data['image_url'] . "</td>";
            echo "<td style='color: " . $color . ";'>" . $status . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<hr>";
    echo "<h3>✅ Image Updates Complete!</h3>";
    echo "<p>You can now view the updated attractions and tours:</p>";
    echo "<ul>";
    echo "<li><a href='tours.php'>View All Tours</a></li>";
    echo "<li><a href='attractions.php'>View All Attractions</a></li>";
    if (!empty($existing_tours)) {
        foreach ($existing_tours as $tour) {
            echo "<li><a href='tour-details.php?id=" . $tour['id'] . "'>" . $tour['title'] . "</a></li>";
        }
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

