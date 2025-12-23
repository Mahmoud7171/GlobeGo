<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Assign TourGuide Images to Tours</h2>";

try {
    // Check if TourGuide images exist
    echo "<h3>Checking TourGuide Images:</h3>";
    $tourguide_images = [
        'images/TourGuide1.png' => 'TourGuide 1',
        'images/TourGuide2.jpg' => 'TourGuide 2', 
        'images/TourGuide3.jpg' => 'TourGuide 3',
        'images/TourGuide4.jpg' => 'TourGuide 4',
        'images/TourGuide5.png' => 'TourGuide 5'
    ];
    
    $all_images_exist = true;
    foreach ($tourguide_images as $file => $description) {
        if (file_exists($file)) {
            echo "<p style='color: green;'>✓ " . $description . " (" . $file . ") - Found</p>";
        } else {
            echo "<p style='color: red;'>✗ " . $description . " (" . $file . ") - Missing</p>";
            $all_images_exist = false;
        }
    }
    
    if (!$all_images_exist) {
        echo "<p style='color: red;'><strong>Error:</strong> Some TourGuide images are missing. Please ensure all image files are in the images/ directory.</p>";
        exit;
    }
    
    // Get all guides
    echo "<h3>Current Guides:</h3>";
    $guides_stmt = $conn->query("SELECT id, first_name, last_name, email, profile_image FROM users WHERE role = 'guide' ORDER BY id");
    $guides = $guides_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($guides)) {
        echo "<p style='color: red;'>No guides found in database!</p>";
        echo "<p>Creating test guides...</p>";
        
        // Create 5 test guides
        $test_guides = [
            ['Test', 'Guide', 'guide1@test.com', 'images/TourGuide1.png'],
            ['Sarah', 'Johnson', 'guide2@test.com', 'images/TourGuide2.jpg'],
            ['Michael', 'Brown', 'guide3@test.com', 'images/TourGuide3.jpg'],
            ['Emma', 'Wilson', 'guide4@test.com', 'images/TourGuide4.jpg'],
            ['David', 'Davis', 'guide5@test.com', 'images/TourGuide5.png']
        ];
        
        $guide_password = password_hash('guide123', PASSWORD_DEFAULT);
        
        foreach ($test_guides as $index => $guide_data) {
            $insert_guide_stmt = $conn->prepare("
                INSERT INTO users (email, password, first_name, last_name, role, profile_image, verified, status) 
                VALUES (?, ?, ?, ?, 'guide', ?, 1, 'active')
            ");
            $insert_guide_stmt->execute([
                $guide_data[2], // email
                $guide_password, // password
                $guide_data[0], // first_name
                $guide_data[1], // last_name
                $guide_data[3]  // profile_image
            ]);
            
            $guide_id = $conn->lastInsertId();
            echo "<p style='color: green;'>✓ Created guide: " . $guide_data[0] . " " . $guide_data[1] . " (ID: " . $guide_id . ")</p>";
        }
        
        // Refresh guides list
        $guides_stmt = $conn->query("SELECT id, first_name, last_name, email, profile_image FROM users WHERE role = 'guide' ORDER BY id");
        $guides = $guides_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo "<p>Found " . count($guides) . " guides</p>";
    
    // Update guide profile images
    echo "<h3>Updating Guide Profile Images:</h3>";
    $image_files = array_values($tourguide_images);
    
    foreach ($guides as $index => $guide) {
        if ($index < count($image_files)) {
            $image_file = $image_files[$index];
            
            $update_stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
            $update_stmt->execute([$image_file, $guide['id']]);
            
            echo "<p style='color: green;'>✓ Updated " . $guide['first_name'] . " " . $guide['last_name'] . " → " . $image_file . "</p>";
        }
    }
    
    // Get all tours and show which guide they belong to
    echo "<h3>Current Tours and Their Guides:</h3>";
    $tours_stmt = $conn->query("
        SELECT t.id, t.title, u.id as guide_id, u.first_name, u.last_name, u.profile_image, a.name as attraction_name
        FROM tours t 
        LEFT JOIN users u ON t.guide_id = u.id 
        LEFT JOIN attractions a ON t.attraction_id = a.id 
        WHERE t.status = 'active'
        ORDER BY t.id
    ");
    $tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($tours)) {
        echo "<p style='color: orange;'>No tours found. Creating sample tours...</p>";
        
        // Create sample tours for each guide
        $attractions_stmt = $conn->query("SELECT id, name FROM attractions LIMIT 5");
        $attractions = $attractions_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $sample_tours = [
            ['Paris City Walk', 'Explore the beautiful streets of Paris with a local guide.', 45.00, 2, 12, 'Trocadéro Metro Station', 'Walking Tour'],
            ['Rome Historical Tour', 'Discover ancient Roman history and architecture.', 65.00, 3, 15, 'Colosseum Main Entrance', 'Historical Tour'],
            ['NYC Food Adventure', 'Taste the best of New York City cuisine.', 85.00, 4, 10, 'Union Square Park', 'Food Tour'],
            ['London Bridge Experience', 'Walk across the iconic Tower Bridge.', 55.00, 2, 20, 'Tower Bridge Exhibition', 'Historical Tour'],
            ['Barcelona Architecture', 'Explore Gaudí\'s masterpieces in Barcelona.', 75.00, 3, 15, 'Sagrada Familia Entrance', 'Cultural Tour']
        ];
        
        foreach ($guides as $index => $guide) {
            if ($index < count($sample_tours) && $index < count($attractions)) {
                $tour_data = $sample_tours[$index];
                $attraction = $attractions[$index];
                
                $insert_tour_stmt = $conn->prepare("
                    INSERT INTO tours (guide_id, attraction_id, title, description, price, duration_hours, 
                                     max_participants, meeting_point, category, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
                ");
                
                $insert_tour_stmt->execute([
                    $guide['id'],
                    $attraction['id'],
                    $tour_data[0], // title
                    $tour_data[1], // description
                    $tour_data[2], // price
                    $tour_data[3], // duration_hours
                    $tour_data[4], // max_participants
                    $tour_data[5], // meeting_point
                    $tour_data[6]  // category
                ]);
                
                $tour_id = $conn->lastInsertId();
                echo "<p style='color: green;'>✓ Created tour: " . $tour_data[0] . " (ID: " . $tour_id . ") for " . $guide['first_name'] . " " . $guide['last_name'] . "</p>";
            }
        }
        
        // Refresh tours list
        $tours_stmt = $conn->query("
            SELECT t.id, t.title, u.id as guide_id, u.first_name, u.last_name, u.profile_image, a.name as attraction_name
            FROM tours t 
            LEFT JOIN users u ON t.guide_id = u.id 
            LEFT JOIN attractions a ON t.attraction_id = a.id 
            WHERE t.status = 'active'
            ORDER BY t.id
        ");
        $tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Tour ID</th><th>Tour Title</th><th>Guide</th><th>Attraction</th><th>Guide Image</th><th>Status</th></tr>";
    foreach ($tours as $tour) {
        $status = file_exists($tour['profile_image']) ? '✓ Ready' : '✗ File Missing';
        $color = file_exists($tour['profile_image']) ? 'green' : 'red';
        echo "<tr>";
        echo "<td>" . $tour['id'] . "</td>";
        echo "<td>" . $tour['title'] . "</td>";
        echo "<td>" . $tour['first_name'] . " " . $tour['last_name'] . "</td>";
        echo "<td>" . ($tour['attraction_name'] ?: 'N/A') . "</td>";
        echo "<td>" . $tour['profile_image'] . "</td>";
        echo "<td style='color: " . $color . ";'>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Create tour schedules for all tours
    echo "<h3>Creating Tour Schedules:</h3>";
    foreach ($tours as $tour) {
        // Get tour details to determine max participants
        $tour_details_stmt = $conn->prepare("SELECT max_participants FROM tours WHERE id = ?");
        $tour_details_stmt->execute([$tour['id']]);
        $tour_details = $tour_details_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($tour_details) {
            $max_participants = $tour_details['max_participants'];
            
            // Create 3 schedules starting from tomorrow
            for ($i = 1; $i <= 3; $i++) {
                $tour_date = date('Y-m-d', strtotime("+$i days"));
                $tour_time = ($i % 2 == 0) ? '14:00:00' : '10:00:00';
                
                $insert_schedule_stmt = $conn->prepare("
                    INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) 
                    VALUES (?, ?, ?, ?, 'available')
                ");
                
                $insert_schedule_stmt->execute([$tour['id'], $tour_date, $tour_time, $max_participants]);
            }
            echo "<p>Created 3 schedules for: " . $tour['title'] . "</p>";
        }
    }
    
    echo "<hr>";
    echo "<h3>✅ TourGuide Images Assigned Successfully!</h3>";
    echo "<p>Your tours now have professional guide images:</p>";
    echo "<ul>";
    echo "<li><a href='tours.php'>View All Tours</a></li>";
    echo "<li><a href='tour-details.php?id=1'>View First Tour</a></li>";
    echo "<li><a href='attractions.php'>View Attractions</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

