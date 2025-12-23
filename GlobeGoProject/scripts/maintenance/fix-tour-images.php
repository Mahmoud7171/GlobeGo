<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Fix Tour Images</h2>";

try {
    // Check available tour images
    echo "<h3>Checking Available Tour Images:</h3>";
    $tour_images = [
        'images/Paris.png',
        'images/Rome.png', 
        'images/NYC.png',
        'images/TowerBridge.jpg',
        'images/SargadaFamilia.jpg'
    ];
    
    $available_images = [];
    foreach ($tour_images as $image) {
        if (file_exists($image)) {
            echo "<p style='color: green;'>✓ " . $image . " - Found</p>";
            $available_images[] = $image;
        } else {
            echo "<p style='color: red;'>✗ " . $image . " - Missing</p>";
        }
    }
    
    if (empty($available_images)) {
        echo "<p style='color: red;'>No tour images found!</p>";
        exit;
    }
    
    // Get current tours
    echo "<h3>Current Tours:</h3>";
    $tours_stmt = $conn->query("
        SELECT t.id, t.title, t.image_url, a.name as attraction_name
        FROM tours t 
        LEFT JOIN attractions a ON t.attraction_id = a.id 
        WHERE t.status = 'active'
        ORDER BY t.id
    ");
    $tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($tours)) {
        echo "<p style='color: red;'>No tours found!</p>";
        echo "<p>Run <a href='assign-unique-guides.php'>assign-unique-guides.php</a> to create tours first.</p>";
        exit;
    }
    
    echo "<p>Found " . count($tours) . " tours</p>";
    
    // Assign images to tours
    echo "<h3>Assigning Images to Tours:</h3>";
    
    $image_assignments = [
        'Paris Evening Walk' => 'images/Paris.png',
        'Ancient Rome Discovery' => 'images/Rome.png',
        'NYC Food Adventure' => 'images/NYC.png',
        'London Tower Bridge Experience' => 'images/TowerBridge.jpg',
        'Sagrada Familia & Gaudí\'s Barcelona' => 'images/SargadaFamilia.jpg'
    ];
    
    foreach ($tours as $tour) {
        $assigned_image = null;
        
        // Try to match by title
        foreach ($image_assignments as $title_pattern => $image) {
            if (strpos($tour['title'], $title_pattern) !== false || strpos($title_pattern, $tour['title']) !== false) {
                $assigned_image = $image;
                break;
            }
        }
        
        // If no match found, assign by order
        if (!$assigned_image) {
            $tour_index = array_search($tour['title'], array_column($tours, 'title'));
            if ($tour_index !== false && $tour_index < count($available_images)) {
                $assigned_image = $available_images[$tour_index];
            }
        }
        
        if ($assigned_image && file_exists($assigned_image)) {
            $update_stmt = $conn->prepare("UPDATE tours SET image_url = ? WHERE id = ?");
            $update_stmt->execute([$assigned_image, $tour['id']]);
            
            echo "<p style='color: green;'>✓ Updated " . $tour['title'] . " → " . $assigned_image . "</p>";
        } else {
            echo "<p style='color: orange;'>⚠ No suitable image found for " . $tour['title'] . "</p>";
        }
    }
    
    // Create default tour image if it doesn't exist
    echo "<h3>Creating Default Tour Image:</h3>";
    $default_tour_image = 'assets/images/default-tour.jpg';
    if (!file_exists($default_tour_image)) {
        // Create assets/images directory if it doesn't exist
        if (!is_dir('assets/images')) {
            mkdir('assets/images', 0755, true);
        }
        
        // Create a simple placeholder image
        $placeholder_content = '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
            <rect width="400" height="300" fill="#f8f9fa"/>
            <rect x="50" y="50" width="300" height="200" fill="#e9ecef" stroke="#dee2e6" stroke-width="2"/>
            <text x="200" y="130" text-anchor="middle" font-family="Arial" font-size="16" fill="#6c757d">Tour Image</text>
            <text x="200" y="160" text-anchor="middle" font-family="Arial" font-size="12" fill="#6c757d">Coming Soon</text>
        </svg>';
        
        file_put_contents($default_tour_image, $placeholder_content);
        echo "<p style='color: green;'>✓ Created default tour image: " . $default_tour_image . "</p>";
    } else {
        echo "<p style='color: green;'>✓ Default tour image already exists: " . $default_tour_image . "</p>";
    }
    
    // Final verification
    echo "<h3>Final Verification - Tour Images:</h3>";
    $final_tours_stmt = $conn->query("
        SELECT t.id, t.title, t.image_url, a.name as attraction_name
        FROM tours t 
        LEFT JOIN attractions a ON t.attraction_id = a.id 
        WHERE t.status = 'active'
        ORDER BY t.id
    ");
    $final_tours = $final_tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Tour ID</th><th>Title</th><th>Attraction</th><th>Image URL</th><th>Status</th></tr>";
    foreach ($final_tours as $tour) {
        $status = 'None';
        $color = 'red';
        
        if (!empty($tour['image_url'])) {
            if (file_exists($tour['image_url'])) {
                $status = '✓ Ready';
                $color = 'green';
            } else {
                $status = '✗ File Missing';
                $color = 'red';
            }
        }
        
        echo "<tr>";
        echo "<td>" . $tour['id'] . "</td>";
        echo "<td>" . $tour['title'] . "</td>";
        echo "<td>" . ($tour['attraction_name'] ?: 'N/A') . "</td>";
        echo "<td>" . ($tour['image_url'] ?: 'None') . "</td>";
        echo "<td style='color: " . $color . ";'>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Show image previews
    echo "<h3>Image Previews:</h3>";
    echo "<div style='display: flex; flex-wrap: wrap; gap: 20px;'>";
    foreach ($final_tours as $tour) {
        if (!empty($tour['image_url']) && file_exists($tour['image_url'])) {
            echo "<div style='border: 1px solid #ddd; padding: 10px; border-radius: 5px; width: 200px;'>";
            echo "<h6>" . $tour['title'] . "</h6>";
            echo "<img src='" . $tour['image_url'] . "' alt='" . $tour['title'] . "' style='width: 100%; height: 120px; object-fit: cover; border-radius: 3px;'>";
            echo "<p style='font-size: 12px; margin: 5px 0 0 0;'>" . $tour['image_url'] . "</p>";
            echo "</div>";
        }
    }
    echo "</div>";
    
    echo "<hr>";
    echo "<h3>✅ Tour Images Fixed!</h3>";
    echo "<p>Your tours now have proper images:</p>";
    echo "<ul>";
    echo "<li><a href='tours.php'>View Tours Page</a></li>";
    echo "<li><a href='index.php'>View Homepage</a></li>";
    foreach ($final_tours as $tour) {
        echo "<li><a href='tour-details.php?id=" . $tour['id'] . "'>" . $tour['title'] . "</a></li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

