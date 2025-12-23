<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Fix Guide Images</h2>";

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
    
    // Get or create guides
    echo "<h3>Setting up Guides:</h3>";
    $guides_stmt = $conn->query("SELECT id, first_name, last_name, profile_image FROM users WHERE role = 'guide' ORDER BY id");
    $guides = $guides_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($guides)) {
        echo "<p style='color: orange;'>No guides found. Creating guides...</p>";
        
        $guide_data = [
            ['Test', 'Guide', 'guide1@test.com'],
            ['Sarah', 'Johnson', 'guide2@test.com'],
            ['Michael', 'Brown', 'guide3@test.com'],
            ['Emma', 'Wilson', 'guide4@test.com'],
            ['David', 'Davis', 'guide5@test.com']
        ];
        
        $guide_password = password_hash('guide123', PASSWORD_DEFAULT);
        
        foreach ($guide_data as $index => $data) {
            $profile_image = isset($available_images[$index]) ? $available_images[$index] : $available_images[0];
            
            $insert_stmt = $conn->prepare("
                INSERT INTO users (email, password, first_name, last_name, role, profile_image, verified, status) 
                VALUES (?, ?, ?, ?, 'guide', ?, 1, 'active')
            ");
            $insert_stmt->execute([
                $data[2], // email
                $guide_password, // password
                $data[0], // first_name
                $data[1], // last_name
                $profile_image
            ]);
            
            $guide_id = $conn->lastInsertId();
            echo "<p style='color: green;'>✓ Created guide: " . $data[0] . " " . $data[1] . " (ID: " . $guide_id . ") with image: " . $profile_image . "</p>";
        }
        
        // Refresh guides list
        $guides_stmt = $conn->query("SELECT id, first_name, last_name, profile_image FROM users WHERE role = 'guide' ORDER BY id");
        $guides = $guides_stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "<p>Found " . count($guides) . " existing guides. Updating their images...</p>";
        
        // Update existing guides with TourGuide images
        foreach ($guides as $index => $guide) {
            if ($index < count($available_images)) {
                $image_file = $available_images[$index];
                
                $update_stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                $update_stmt->execute([$image_file, $guide['id']]);
                
                echo "<p style='color: green;'>✓ Updated " . $guide['first_name'] . " " . $guide['last_name'] . " → " . $image_file . "</p>";
            }
        }
    }
    
    // Ensure we have tours for these guides
    echo "<h3>Setting up Tours:</h3>";
    $tours_stmt = $conn->query("SELECT COUNT(*) as count FROM tours WHERE status = 'active'");
    $tour_count = $tours_stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($tour_count == 0) {
        echo "<p style='color: orange;'>No tours found. Creating sample tours...</p>";
        
        // Get attractions
        $attractions_stmt = $conn->query("SELECT id, name FROM attractions LIMIT 5");
        $attractions = $attractions_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($attractions)) {
            echo "<p style='color: red;'>No attractions found. Creating attractions first...</p>";
            
            $attraction_data = [
                ['Eiffel Tower', 'Iconic iron tower in Paris', 'Paris, France', 'Landmark'],
                ['Colosseum', 'Ancient Roman amphitheater', 'Rome, Italy', 'Historical'],
                ['Times Square', 'Famous intersection in NYC', 'New York, USA', 'Landmark'],
                ['Tower Bridge', 'Victorian bridge in London', 'London, UK', 'Landmark'],
                ['Sagrada Familia', 'Gaudí\'s masterpiece in Barcelona', 'Barcelona, Spain', 'Religious']
            ];
            
            foreach ($attraction_data as $attr) {
                $insert_attr_stmt = $conn->prepare("
                    INSERT INTO attractions (name, description, location, category) 
                    VALUES (?, ?, ?, ?)
                ");
                $insert_attr_stmt->execute($attr);
            }
            
            // Refresh attractions
            $attractions_stmt = $conn->query("SELECT id, name FROM attractions LIMIT 5");
            $attractions = $attractions_stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        $sample_tours = [
            ['Paris Evening Walk', 'Experience the magic of Paris at night.', 45.00, 2, 12, 'Trocadéro Metro Station', 'Walking Tour'],
            ['Ancient Rome Discovery', 'Explore the Colosseum and Roman ruins.', 65.00, 3, 15, 'Colosseum Main Entrance', 'Historical Tour'],
            ['NYC Food Adventure', 'Taste the best of New York City cuisine.', 85.00, 4, 10, 'Union Square Park', 'Food Tour'],
            ['London Bridge Experience', 'Walk across the iconic Tower Bridge.', 55.00, 2, 20, 'Tower Bridge Exhibition', 'Historical Tour'],
            ['Sagrada Familia & Gaudí\'s Barcelona', 'Explore Gaudí\'s masterpieces in Barcelona.', 75.00, 3, 15, 'Sagrada Familia Main Entrance', 'Cultural Tour']
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
                
                // Create tour schedules
                for ($i = 1; $i <= 3; $i++) {
                    $tour_date = date('Y-m-d', strtotime("+$i days"));
                    $tour_time = ($i % 2 == 0) ? '14:00:00' : '10:00:00';
                    
                    $insert_schedule_stmt = $conn->prepare("
                        INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) 
                        VALUES (?, ?, ?, ?, 'available')
                    ");
                    
                    $insert_schedule_stmt->execute([$tour_id, $tour_date, $tour_time, $tour_data[4]]);
                }
            }
        }
    } else {
        echo "<p style='color: green;'>Found " . $tour_count . " existing tours</p>";
    }
    
    // Final verification
    echo "<h3>Final Verification:</h3>";
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
    echo "<tr><th>Tour ID</th><th>Tour Title</th><th>Guide</th><th>Guide Image</th><th>Status</th></tr>";
    foreach ($final_tours as $tour) {
        $status = file_exists($tour['profile_image']) ? '✓ Ready' : '✗ Missing';
        $color = file_exists($tour['profile_image']) ? 'green' : 'red';
        echo "<tr>";
        echo "<td>" . $tour['id'] . "</td>";
        echo "<td>" . $tour['title'] . "</td>";
        echo "<td>" . $tour['first_name'] . " " . $tour['last_name'] . "</td>";
        echo "<td>" . $tour['profile_image'] . "</td>";
        echo "<td style='color: " . $color . ";'>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>✅ Guide Images Fixed!</h3>";
    echo "<p>Your tours now have proper guide images:</p>";
    echo "<ul>";
    echo "<li><a href='tours.php'>View All Tours</a></li>";
    if (!empty($final_tours)) {
        echo "<li><a href='tour-details.php?id=" . $final_tours[0]['id'] . "'>View First Tour Details</a></li>";
        echo "<li><a href='tour-details.php?id=" . $final_tours[4]['id'] . "'>View Sagrada Familia Tour</a></li>";
    }
    echo "<li><a href='debug-guide-images.php'>Debug Guide Images</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

