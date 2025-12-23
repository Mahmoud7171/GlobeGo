<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Create London & Barcelona Tours</h2>";

try {
    // Check if image files exist
    echo "<h3>Checking Image Files:</h3>";
    $image_files = [
        'images/TowerBridge.jpg' => 'Tower Bridge Image',
        'images/SargadaFamilia.jpg' => 'Sagrada Familia Image'
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
        echo "<p style='color: red;'><strong>Error:</strong> Some image files are missing. Please ensure all image files are in the images/ directory.</p>";
        exit;
    }
    
    // Get a guide ID (use the first guide found)
    $guide_stmt = $conn->query("SELECT id FROM users WHERE role = 'guide' LIMIT 1");
    $guide = $guide_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$guide) {
        echo "<p style='color: red;'>No guides found. Creating a test guide...</p>";
        
        $guide_password = password_hash('guide123', PASSWORD_DEFAULT);
        $create_guide_stmt = $conn->prepare("
            INSERT INTO users (email, password, first_name, last_name, role, verified, status) 
            VALUES ('guide@test.com', :password, 'Test', 'Guide', 'guide', 1, 'active')
        ");
        $create_guide_stmt->bindParam(':password', $guide_password);
        $create_guide_stmt->execute();
        
        $guide_id = $conn->lastInsertId();
        echo "<p style='color: green;'>Created guide with ID: " . $guide_id . "</p>";
    } else {
        $guide_id = $guide['id'];
        echo "<p>Using existing guide ID: " . $guide_id . "</p>";
    }
    
    // Get attraction IDs
    $attractions_stmt = $conn->query("SELECT id, name FROM attractions WHERE name IN ('Tower Bridge', 'Sagrada Familia')");
    $attractions = $attractions_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Found Attractions:</h3>";
    $attraction_ids = [];
    foreach ($attractions as $attraction) {
        echo "<p>" . $attraction['name'] . " (ID: " . $attraction['id'] . ")</p>";
        $attraction_ids[$attraction['name']] = $attraction['id'];
    }
    
    // Check if tours already exist
    $existing_tours_stmt = $conn->query("
        SELECT t.title, a.name as attraction_name 
        FROM tours t 
        LEFT JOIN attractions a ON t.attraction_id = a.id 
        WHERE a.name IN ('Tower Bridge', 'Sagrada Familia')
    ");
    $existing_tours = $existing_tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($existing_tours)) {
        echo "<h3>Existing Tours Found:</h3>";
        foreach ($existing_tours as $tour) {
            echo "<p>" . $tour['title'] . " (" . $tour['attraction_name'] . ")</p>";
        }
        echo "<p style='color: orange;'>Tours already exist. Updating images instead...</p>";
    } else {
        echo "<h3>Creating New Tours:</h3>";
        
        // Create Tower Bridge tour
        if (isset($attraction_ids['Tower Bridge'])) {
            $tower_bridge_tour = [
                'guide_id' => $guide_id,
                'attraction_id' => $attraction_ids['Tower Bridge'],
                'title' => 'London Tower Bridge Experience',
                'description' => 'Discover the iconic Tower Bridge with a guided tour including the high-level walkways and Victorian engine rooms. Learn about the bridge\'s history and enjoy stunning views of the Thames.',
                'price' => 55.00,
                'duration_hours' => 2,
                'max_participants' => 20,
                'meeting_point' => 'Tower Bridge Exhibition Entrance',
                'category' => 'Historical Tour',
                'image_url' => 'images/TowerBridge.jpg',
                'status' => 'active'
            ];
            
            $insert_tour_stmt = $conn->prepare("
                INSERT INTO tours (guide_id, attraction_id, title, description, price, duration_hours, 
                                 max_participants, meeting_point, category, image_url, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $insert_tour_stmt->execute([
                $tower_bridge_tour['guide_id'],
                $tower_bridge_tour['attraction_id'],
                $tower_bridge_tour['title'],
                $tower_bridge_tour['description'],
                $tower_bridge_tour['price'],
                $tower_bridge_tour['duration_hours'],
                $tower_bridge_tour['max_participants'],
                $tower_bridge_tour['meeting_point'],
                $tower_bridge_tour['category'],
                $tower_bridge_tour['image_url'],
                $tower_bridge_tour['status']
            ]);
            
            $tower_bridge_tour_id = $conn->lastInsertId();
            echo "<p style='color: green;'>✓ Created Tower Bridge tour (ID: " . $tower_bridge_tour_id . ")</p>";
        }
        
        // Create Sagrada Familia tour
        if (isset($attraction_ids['Sagrada Familia'])) {
            $sagrada_familia_tour = [
                'guide_id' => $guide_id,
                'attraction_id' => $attraction_ids['Sagrada Familia'],
                'title' => 'Sagrada Familia & Gaudí\'s Barcelona',
                'description' => 'Explore Antoni Gaudí\'s masterpiece, the Sagrada Familia, and discover the architectural genius behind this unfinished basilica. Includes skip-the-line access and expert commentary.',
                'price' => 75.00,
                'duration_hours' => 3,
                'max_participants' => 15,
                'meeting_point' => 'Sagrada Familia Main Entrance',
                'category' => 'Cultural Tour',
                'image_url' => 'images/SargadaFamilia.jpg',
                'status' => 'active'
            ];
            
            $insert_tour_stmt = $conn->prepare("
                INSERT INTO tours (guide_id, attraction_id, title, description, price, duration_hours, 
                                 max_participants, meeting_point, category, image_url, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $insert_tour_stmt->execute([
                $sagrada_familia_tour['guide_id'],
                $sagrada_familia_tour['attraction_id'],
                $sagrada_familia_tour['title'],
                $sagrada_familia_tour['description'],
                $sagrada_familia_tour['price'],
                $sagrada_familia_tour['duration_hours'],
                $sagrada_familia_tour['max_participants'],
                $sagrada_familia_tour['meeting_point'],
                $sagrada_familia_tour['category'],
                $sagrada_familia_tour['image_url'],
                $sagrada_familia_tour['status']
            ]);
            
            $sagrada_familia_tour_id = $conn->lastInsertId();
            echo "<p style='color: green;'>✓ Created Sagrada Familia tour (ID: " . $sagrada_familia_tour_id . ")</p>";
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
    
    // Create tour schedules for new tours
    echo "<h3>Creating Tour Schedules:</h3>";
    
    // Get all tours for Tower Bridge and Sagrada Familia
    $tours_stmt = $conn->query("
        SELECT t.id, t.title, t.max_participants, a.name as attraction_name
        FROM tours t 
        LEFT JOIN attractions a ON t.attraction_id = a.id 
        WHERE a.name IN ('Tower Bridge', 'Sagrada Familia') AND t.status = 'active'
    ");
    $tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($tours as $tour) {
        echo "<h4>Creating schedules for: " . $tour['title'] . "</h4>";
        
        // Create 5 schedules starting from tomorrow
        for ($i = 1; $i <= 5; $i++) {
            $tour_date = date('Y-m-d', strtotime("+$i days"));
            $tour_time = ($i % 2 == 0) ? '14:00:00' : '10:00:00';
            $available_spots = $tour['max_participants'];
            
            $insert_schedule_stmt = $conn->prepare("
                INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) 
                VALUES (?, ?, ?, ?, 'available')
            ");
            
            $insert_schedule_stmt->execute([$tour['id'], $tour_date, $tour_time, $available_spots]);
            echo "<p>Created schedule: " . $tour_date . " at " . $tour_time . " (" . $available_spots . " spots)</p>";
        }
    }
    
    // Final verification
    echo "<h3>Final Verification:</h3>";
    $final_tours_stmt = $conn->query("
        SELECT t.id, t.title, t.image_url, a.name as attraction_name, a.location
        FROM tours t 
        LEFT JOIN attractions a ON t.attraction_id = a.id 
        WHERE a.name IN ('Tower Bridge', 'Sagrada Familia') AND t.status = 'active'
        ORDER BY t.id
    ");
    $final_tours = $final_tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Title</th><th>Attraction</th><th>Location</th><th>Image URL</th><th>Status</th></tr>";
    foreach ($final_tours as $tour) {
        $status = file_exists($tour['image_url']) ? '✓ Ready' : '✗ File Missing';
        $color = file_exists($tour['image_url']) ? 'green' : 'red';
        echo "<tr>";
        echo "<td>" . $tour['id'] . "</td>";
        echo "<td>" . $tour['title'] . "</td>";
        echo "<td>" . $tour['attraction_name'] . "</td>";
        echo "<td>" . $tour['location'] . "</td>";
        echo "<td>" . $tour['image_url'] . "</td>";
        echo "<td style='color: " . $color . ";'>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>✅ London & Barcelona Tours Created Successfully!</h3>";
    echo "<p>Your new tours are ready:</p>";
    echo "<ul>";
    echo "<li><a href='tours.php'>View All Tours</a></li>";
    if (!empty($final_tours)) {
        foreach ($final_tours as $tour) {
            echo "<li><a href='tour-details.php?id=" . $tour['id'] . "'>" . $tour['title'] . "</a></li>";
        }
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

