<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Assign Unique Guides to Each Tour</h2>";

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
    
    // Create 5 unique guides (one for each TourGuide image)
    echo "<h3>Creating Unique Guides:</h3>";
    
    // First, delete existing guides to start fresh
    $delete_guides_stmt = $conn->prepare("DELETE FROM users WHERE role = 'guide'");
    $delete_guides_stmt->execute();
    echo "<p>Cleared existing guides</p>";
    
    // Create 5 unique guides
    $guide_data = [
        ['Alex', 'Martinez', 'alex.martinez@globego.com', 'images/TourGuide1.png', 'English, Spanish', 'Experienced tour guide specializing in historical sites and cultural experiences.'],
        ['Sarah', 'Johnson', 'sarah.johnson@globego.com', 'images/TourGuide2.jpg', 'English, French', 'Professional guide with expertise in art, architecture, and local cuisine.'],
        ['Michael', 'Brown', 'michael.brown@globego.com', 'images/TourGuide3.jpg', 'English, Italian', 'Local expert passionate about sharing hidden gems and authentic experiences.'],
        ['Emma', 'Wilson', 'emma.wilson@globego.com', 'images/TourGuide4.jpg', 'English, German', 'Certified guide with deep knowledge of local history and traditions.'],
        ['David', 'Davis', 'david.davis@globego.com', 'images/TourGuide5.png', 'English, Portuguese', 'Adventure specialist offering unique perspectives on local culture and nature.']
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
    
    // Get or create attractions
    echo "<h3>Setting up Attractions:</h3>";
    $attractions_stmt = $conn->query("SELECT id, name FROM attractions ORDER BY id LIMIT 5");
    $attractions = $attractions_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($attractions)) {
        echo "<p style='color: orange;'>No attractions found. Creating attractions...</p>";
        
        $attraction_data = [
            ['Eiffel Tower', 'Iconic iron tower and symbol of Paris, offering panoramic city views from its observation decks.', 'Paris, France', 'Landmark', 'images/Paris.png'],
            ['Colosseum', 'Ancient Roman amphitheater and one of the most famous landmarks in the world.', 'Rome, Italy', 'Historical', 'images/Rome.png'],
            ['Times Square', 'Major commercial intersection and tourist destination in Manhattan.', 'New York, USA', 'Landmark', 'images/NYC.png'],
            ['Tower Bridge', 'Victorian suspension bridge over the River Thames in London.', 'London, UK', 'Landmark', 'images/TowerBridge.jpg'],
            ['Sagrada Familia', 'Unfinished basilica designed by Antoni Gaudí in Barcelona.', 'Barcelona, Spain', 'Religious', 'images/SargadaFamilia.jpg']
        ];
        
        foreach ($attraction_data as $attr) {
            $insert_attr_stmt = $conn->prepare("
                INSERT INTO attractions (name, description, location, category, image_url) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $insert_attr_stmt->execute($attr);
        }
        
        // Refresh attractions
        $attractions_stmt = $conn->query("SELECT id, name FROM attractions ORDER BY id LIMIT 5");
        $attractions = $attractions_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo "<p>Found " . count($attractions) . " attractions</p>";
    
    // Clear existing tours
    echo "<h3>Creating Unique Tours:</h3>";
    $delete_tours_stmt = $conn->prepare("DELETE FROM tours");
    $delete_tours_stmt->execute();
    echo "<p>Cleared existing tours</p>";
    
    // Create 5 unique tours (one for each guide)
    $tour_data = [
        [
            'title' => 'Paris Evening Walk',
            'description' => 'Experience the magic of Paris at night with a guided walk through the City of Light. Discover hidden gems and iconic landmarks.',
            'price' => 45.00,
            'duration_hours' => 2,
            'max_participants' => 12,
            'meeting_point' => 'Trocadéro Metro Station',
            'category' => 'Walking Tour'
        ],
        [
            'title' => 'Ancient Rome Discovery',
            'description' => 'Explore the Colosseum and surrounding ancient Roman ruins with an expert guide. Uncover the secrets of the Roman Empire.',
            'price' => 65.00,
            'duration_hours' => 3,
            'max_participants' => 15,
            'meeting_point' => 'Colosseum Main Entrance',
            'category' => 'Historical Tour'
        ],
        [
            'title' => 'NYC Food Adventure',
            'description' => 'Taste the best of New York City through its diverse culinary scene. Sample authentic flavors from around the world.',
            'price' => 85.00,
            'duration_hours' => 4,
            'max_participants' => 10,
            'meeting_point' => 'Union Square Park',
            'category' => 'Food Tour'
        ],
        [
            'title' => 'London Tower Bridge Experience',
            'description' => 'Discover the iconic Tower Bridge with a guided tour including the high-level walkways and Victorian engine rooms.',
            'price' => 55.00,
            'duration_hours' => 2,
            'max_participants' => 20,
            'meeting_point' => 'Tower Bridge Exhibition Entrance',
            'category' => 'Historical Tour'
        ],
        [
            'title' => 'Sagrada Familia & Gaudí\'s Barcelona',
            'description' => 'Explore Antoni Gaudí\'s masterpiece, the Sagrada Familia, and discover the architectural genius behind this unfinished basilica.',
            'price' => 75.00,
            'duration_hours' => 3,
            'max_participants' => 15,
            'meeting_point' => 'Sagrada Familia Main Entrance',
            'category' => 'Cultural Tour'
        ]
    ];
    
    $created_tours = [];
    
    foreach ($created_guides as $index => $guide) {
        if ($index < count($tour_data) && $index < count($attractions)) {
            $tour = $tour_data[$index];
            $attraction = $attractions[$index];
            
            $insert_tour_stmt = $conn->prepare("
                INSERT INTO tours (guide_id, attraction_id, title, description, price, duration_hours, 
                                 max_participants, meeting_point, category, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
            ");
            
            $insert_tour_stmt->execute([
                $guide['id'],
                $attraction['id'],
                $tour['title'],
                $tour['description'],
                $tour['price'],
                $tour['duration_hours'],
                $tour['max_participants'],
                $tour['meeting_point'],
                $tour['category']
            ]);
            
            $tour_id = $conn->lastInsertId();
            $created_tours[] = [
                'id' => $tour_id,
                'title' => $tour['title'],
                'guide_name' => $guide['name'],
                'guide_image' => $guide['image']
            ];
            
            echo "<p style='color: green;'>✓ Created tour: " . $tour['title'] . " (ID: " . $tour_id . ") for " . $guide['name'] . "</p>";
            
            // Create tour schedules for this tour
            for ($i = 1; $i <= 3; $i++) {
                $tour_date = date('Y-m-d', strtotime("+$i days"));
                $tour_time = ($i % 2 == 0) ? '14:00:00' : '10:00:00';
                
                $insert_schedule_stmt = $conn->prepare("
                    INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) 
                    VALUES (?, ?, ?, ?, 'available')
                ");
                
                $insert_schedule_stmt->execute([$tour_id, $tour_date, $tour_time, $tour['max_participants']]);
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
    echo "<h3>Uniqueness Check:</h3>";
    $guide_images = array_column($final_tours, 'profile_image');
    $unique_images = array_unique($guide_images);
    
    if (count($guide_images) === count($unique_images)) {
        echo "<p style='color: green;'>✓ All tours have unique guide images!</p>";
    } else {
        echo "<p style='color: red;'>✗ Some tours share the same guide image</p>";
    }
    
    echo "<hr>";
    echo "<h3>✅ Unique Guide Assignment Complete!</h3>";
    echo "<p>Each tour now has a unique guide with a different TourGuide image:</p>";
    echo "<ul>";
    echo "<li><a href='tours.php'>View All Tours</a></li>";
    foreach ($created_tours as $tour) {
        echo "<li><a href='tour-details.php?id=" . $tour['id'] . "'>" . $tour['title'] . " (Guide: " . $tour['guide_name'] . ")</a></li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

