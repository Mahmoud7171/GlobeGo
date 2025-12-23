<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Setup Tour Images</h2>";

try {
    // First, let's check what we have
    echo "<h3>Current Database State:</h3>";
    
    // Check tours
    $tours_stmt = $conn->query("SELECT id, title, image_url FROM tours WHERE status = 'active' ORDER BY id");
    $tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h4>Current Tours:</h4>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Title</th><th>Current Image URL</th></tr>";
    foreach ($tours as $tour) {
        echo "<tr>";
        echo "<td>" . $tour['id'] . "</td>";
        echo "<td>" . $tour['title'] . "</td>";
        echo "<td>" . ($tour['image_url'] ?: 'None') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check attractions
    $attractions_stmt = $conn->query("SELECT id, name, image_url FROM attractions ORDER BY id");
    $attractions = $attractions_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h4>Current Attractions:</h4>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Current Image URL</th></tr>";
    foreach ($attractions as $attraction) {
        echo "<tr>";
        echo "<td>" . $attraction['id'] . "</td>";
        echo "<td>" . $attraction['name'] . "</td>";
        echo "<td>" . ($attraction['image_url'] ?: 'None') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check if image files exist
    echo "<h3>Image Files Check:</h3>";
    $image_files = [
        'images/Paris.png' => 'Paris Image',
        'images/Rome.png' => 'Rome Image', 
        'images/NYC.png' => 'NYC Image'
    ];
    
    $all_files_exist = true;
    foreach ($image_files as $file => $description) {
        if (file_exists($file)) {
            echo "<p style='color: green;'>✓ " . $description . " (" . $file . ") - Found</p>";
        } else {
            echo "<p style='color: red;'>✗ " . $description . " (" . $file . ") - Missing</p>";
            $all_files_exist = false;
        }
    }
    
    if (!$all_files_exist) {
        echo "<p style='color: red;'><strong>Warning:</strong> Some image files are missing. Please ensure all image files are in the images/ directory.</p>";
        echo "<p>Expected files:</p>";
        echo "<ul>";
        foreach ($image_files as $file => $description) {
            echo "<li>" . $file . "</li>";
        }
        echo "</ul>";
        exit;
    }
    
    // Update tours
    echo "<h3>Updating Tours:</h3>";
    $tour_updates = [
        ['title' => 'Paris Evening Walk', 'image_url' => 'images/Paris.png'],
        ['title' => 'Ancient Rome Discovery', 'image_url' => 'images/Rome.png'],
        ['title' => 'NYC Food Tour', 'image_url' => 'images/NYC.png']
    ];
    
    foreach ($tour_updates as $update) {
        $stmt = $conn->prepare("UPDATE tours SET image_url = :image_url WHERE title = :title");
        $stmt->bindParam(':image_url', $update['image_url']);
        $stmt->bindParam(':title', $update['title']);
        
        if ($stmt->execute()) {
            $affected_rows = $stmt->rowCount();
            if ($affected_rows > 0) {
                echo "<p style='color: green;'>✓ Updated tour: " . $update['title'] . "</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Tour not found: " . $update['title'] . "</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Failed to update: " . $update['title'] . "</p>";
        }
    }
    
    // Update attractions
    echo "<h3>Updating Attractions:</h3>";
    $attraction_updates = [
        ['name' => 'Eiffel Tower', 'image_url' => 'images/Paris.png'],
        ['name' => 'Colosseum', 'image_url' => 'images/Rome.png'],
        ['name' => 'Times Square', 'image_url' => 'images/NYC.png']
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
    
    // Final verification
    echo "<h3>Final Verification:</h3>";
    $final_tours_stmt = $conn->query("SELECT id, title, image_url FROM tours WHERE status = 'active' ORDER BY id");
    $final_tours = $final_tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Title</th><th>New Image URL</th><th>Status</th></tr>";
    foreach ($final_tours as $tour) {
        $status = file_exists($tour['image_url']) ? '✓ Ready' : '✗ File Missing';
        $color = file_exists($tour['image_url']) ? 'green' : 'red';
        echo "<tr>";
        echo "<td>" . $tour['id'] . "</td>";
        echo "<td>" . $tour['title'] . "</td>";
        echo "<td>" . $tour['image_url'] . "</td>";
        echo "<td style='color: " . $color . ";'>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>✅ Tour Images Setup Complete!</h3>";
    echo "<p>Your tours now have proper images:</p>";
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

