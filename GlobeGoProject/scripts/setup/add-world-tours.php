<?php
/**
 * Script to add multiple tours from around the world
 * Configure the tours array below with your tour details
 */

require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Add World Tours</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        h3 { color: #555; margin-top: 30px; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
        .warning { color: orange; padding: 10px; background: #fff3cd; border-radius: 5px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        textarea { height: 100px; }
    </style>
</head>
<body>
<div class='container'>";

try {
    // Get all guides
    $guides_stmt = $conn->query("SELECT id, first_name, last_name, email FROM users WHERE role = 'guide' AND status = 'active' ORDER BY id");
    $guides = $guides_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($guides)) {
        echo "<div class='error'><h3>⚠️ No Guides Found</h3>";
        echo "<p>You need to have at least one active guide in the database before adding tours.</p>";
        echo "<p>Guides can be created through the registration system or admin panel.</p></div>";
        echo "</div></body></html>";
        exit;
    }
    
    echo "<h2>🌍 Add Tours from Around the World</h2>";
    
    // Display available guides
    echo "<h3>Available Guides:</h3>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
    foreach ($guides as $guide) {
        echo "<tr><td>" . $guide['id'] . "</td><td>" . $guide['first_name'] . " " . $guide['last_name'] . "</td><td>" . $guide['email'] . "</td></tr>";
    }
    echo "</table>";
    
    // ============================================
    // CONFIGURE YOUR TOURS HERE
    // ============================================
    // 
    // SUGGESTED WORLD TOURS TO ADD:
    // 1. The Grand Egyptian Museum (Cairo, Egypt) - Already configured below
    // 2. Machu Picchu (Peru)
    // 3. Taj Mahal (Agra, India)
    // 4. Great Wall of China (Beijing, China)
    // 5. Christ the Redeemer (Rio de Janeiro, Brazil)
    // 6. Sydney Opera House (Sydney, Australia)
    // 7. Angkor Wat (Siem Reap, Cambodia)
    // 8. Petra (Jordan)
    // 9. Stonehenge (Wiltshire, UK)
    // 10. Shibuya Crossway (Tokyo, Japan)
    //
    $tours_to_add = [
        [
            'title' => 'The Grand Egyptian Museum',
            'description' => 'Explore the magnificent Grand Egyptian Museum, home to the world\'s largest collection of ancient Egyptian artifacts. Discover the treasures of Tutankhamun and experience the rich history of ancient Egypt with our expert guide.',
            'location' => 'Cairo, Egypt',
            'category' => 'Museum Tour',
            'price' => 85.00,
            'duration_hours' => 4,
            'max_participants' => 20,
            'meeting_point' => 'Grand Egyptian Museum Main Entrance',
            'image_url' => 'images/egypt.webp',
            'guide_id' => null, // Assign guide ID from table above
            'attraction_name' => 'Grand Egyptian Museum'
        ],
        [
            'title' => 'Machu Picchu Adventure',
            'description' => 'Journey to the ancient Incan citadel of Machu Picchu, one of the New Seven Wonders of the World. Explore the mysterious ruins, learn about Incan culture, and enjoy breathtaking mountain views.',
            'location' => 'Cusco, Peru',
            'category' => 'Historical Tour',
            'price' => 120.00,
            'duration_hours' => 8,
            'max_participants' => 15,
            'meeting_point' => 'Aguas Calientes Train Station',
            'image_url' => 'images/MachuPicchu.jpeg',
            'guide_id' => null, // Assign guide ID from table above
            'attraction_name' => 'Machu Picchu'
        ],
        [
            'title' => 'Taj Mahal Experience',
            'description' => 'Visit the iconic Taj Mahal, a symbol of eternal love and one of the most beautiful buildings in the world. Learn about Mughal architecture and the romantic story behind this UNESCO World Heritage site.',
            'location' => 'Agra, India',
            'category' => 'Cultural Tour',
            'price' => 75.00,
            'duration_hours' => 3,
            'max_participants' => 20,
            'meeting_point' => 'Taj Mahal East Gate',
            'image_url' => 'images/TajMahal.jpg',
            'guide_id' => null, // Assign guide ID from table above
            'attraction_name' => 'Taj Mahal'
        ],
        [
            'title' => 'Great Wall of China',
            'description' => 'Walk along the magnificent Great Wall of China, one of the greatest architectural achievements in human history. Experience breathtaking views and learn about the wall\'s fascinating history spanning over 2,000 years.',
            'location' => 'Beijing, China',
            'category' => 'Historical Tour',
            'price' => 95.00,
            'duration_hours' => 5,
            'max_participants' => 18,
            'meeting_point' => 'Badaling Great Wall Visitor Center',
            'image_url' => 'images/GreatWallOfChina.jpg',
            'guide_id' => null, // Assign guide ID from table above
            'attraction_name' => 'Great Wall of China'
        ],
        [
            'title' => 'Christ the Redeemer',
            'description' => 'Visit the iconic Christ the Redeemer statue, one of the New Seven Wonders of the World. Enjoy panoramic views of Rio de Janeiro from the top of Corcovado Mountain and learn about this symbol of Brazilian culture.',
            'location' => 'Rio de Janeiro, Brazil',
            'category' => 'Cultural Tour',
            'price' => 70.00,
            'duration_hours' => 3,
            'max_participants' => 20,
            'meeting_point' => 'Corcovado Train Station',
            'image_url' => 'images/Christ.jpg',
            'guide_id' => null, // Assign guide ID from table above
            'attraction_name' => 'Christ the Redeemer'
        ],
        [
            'title' => 'Sydney Opera House',
            'description' => 'Explore the world-famous Sydney Opera House, an architectural masterpiece and UNESCO World Heritage site. Take a guided tour of the iconic building and learn about its design, history, and cultural significance.',
            'location' => 'Sydney, Australia',
            'category' => 'Cultural Tour',
            'price' => 80.00,
            'duration_hours' => 2,
            'max_participants' => 25,
            'meeting_point' => 'Sydney Opera House Main Entrance',
            'image_url' => 'images/OperaHouse.jpg',
            'guide_id' => null, // Assign guide ID from table above
            'attraction_name' => 'Sydney Opera House'
        ],
        [
            'title' => 'Angkor Wat Temple Complex',
            'description' => 'Discover the magnificent Angkor Wat, the largest religious monument in the world. Explore the ancient Khmer temples, witness stunning sunrise views, and learn about the rich history of the Khmer Empire.',
            'location' => 'Siem Reap, Cambodia',
            'category' => 'Historical Tour',
            'price' => 90.00,
            'duration_hours' => 6,
            'max_participants' => 15,
            'meeting_point' => 'Angkor Wat Main Entrance',
            'image_url' => 'images/AngkorWat.jpg',
            'guide_id' => null, // Assign guide ID from table above
            'attraction_name' => 'Angkor Wat'
        ],
        [
            'title' => 'Petra - The Rose City',
            'description' => 'Explore the ancient city of Petra, carved into rose-red sandstone cliffs. Walk through the Siq, discover the Treasury, and learn about the Nabataean civilization that created this architectural wonder.',
            'location' => 'Petra, Jordan',
            'category' => 'Historical Tour',
            'price' => 100.00,
            'duration_hours' => 5,
            'max_participants' => 18,
            'meeting_point' => 'Petra Visitor Center',
            'image_url' => 'images/Petra.jpg',
            'guide_id' => null, // Assign guide ID from table above
            'attraction_name' => 'Petra'
        ],
        [
            'title' => 'Stonehenge Mystery Tour',
            'description' => 'Visit the mysterious Stonehenge, one of the world\'s most famous prehistoric monuments. Learn about the theories surrounding its construction and purpose, and experience the mystical atmosphere of this ancient site.',
            'location' => 'Wiltshire, UK',
            'category' => 'Historical Tour',
            'price' => 65.00,
            'duration_hours' => 3,
            'max_participants' => 20,
            'meeting_point' => 'Stonehenge Visitor Center',
            'image_url' => 'images/Stonehenge.jpg',
            'guide_id' => null, // Assign guide ID from table above
            'attraction_name' => 'Stonehenge'
        ],
        [
            'title' => 'Shibuya Crossway Experience',
            'description' => 'Experience the world\'s busiest pedestrian crossing in the heart of Tokyo. Watch thousands of people cross simultaneously and explore the vibrant Shibuya district with its shopping, dining, and entertainment.',
            'location' => 'Tokyo, Japan',
            'category' => 'City Tour',
            'price' => 55.00,
            'duration_hours' => 2,
            'max_participants' => 15,
            'meeting_point' => 'Hachiko Statue, Shibuya Station',
            'image_url' => 'images/Shibuya.jpg',
            'guide_id' => null, // Assign guide ID from table above
            'attraction_name' => 'Shibuya Crossway'
        ],
    ];
    
    // Check if tours are configured
    $tours_ready = false;
    foreach ($tours_to_add as $tour) {
        if (!empty($tour['title']) && !empty($tour['image_url'])) {
            $tours_ready = true;
            break;
        }
    }
    
    if ($tours_ready) {
        echo "<div class='success'>";
        echo "<h3>✅ All Tours Configured!</h3>";
        echo "<p><strong>" . count($tours_to_add) . " tours</strong> are ready to be created with their images assigned.</p>";
        echo "<p>Guides will be <strong>automatically assigned</strong> in rotation if not specified.</p>";
        echo "<p><strong>Note:</strong> To assign specific guides to specific tours, edit the <code>guide_id</code> field in the tours array.</p>";
        echo "</div>";
    }
    
    echo "<h3>Creating Tours:</h3>";
    
    $created_count = 0;
    $updated_count = 0;
    $error_count = 0;
    $guide_index = 0; // For rotating through guides
    
    foreach ($tours_to_add as $index => $tour_data) {
        // Validate required fields
        if (empty($tour_data['title']) || empty($tour_data['image_url'])) {
            echo "<div class='warning'>⚠️ Skipping tour: " . ($tour_data['title'] ?: 'Untitled') . " - Missing required fields (title or image_url)</div>";
            $error_count++;
            continue;
        }
        
        // Auto-assign guide if not specified
        if (empty($tour_data['guide_id']) || $tour_data['guide_id'] === null) {
            if (!empty($guides)) {
                $tour_data['guide_id'] = $guides[$guide_index % count($guides)]['id'];
                $guide_name = $guides[$guide_index % count($guides)]['first_name'] . " " . $guides[$guide_index % count($guides)]['last_name'];
                echo "<div class='info'>ℹ️ Auto-assigned guide: " . $guide_name . " (ID: " . $tour_data['guide_id'] . ") to tour: " . $tour_data['title'] . "</div>";
                $guide_index++;
            } else {
                echo "<div class='error'>✗ No guides available for tour: " . $tour_data['title'] . "</div>";
                $error_count++;
                continue;
            }
        }
        
        // Validate guide exists
        $guide_check = $conn->prepare("SELECT id FROM users WHERE id = ? AND role = 'guide' AND status = 'active'");
        $guide_check->execute([$tour_data['guide_id']]);
        if ($guide_check->rowCount() === 0) {
            echo "<div class='error'>✗ Invalid guide ID: " . $tour_data['guide_id'] . " for tour: " . $tour_data['title'] . "</div>";
            $error_count++;
            continue;
        }
        
        // Get or create attraction
        $attraction_stmt = $conn->prepare("SELECT id FROM attractions WHERE name = ?");
        $attraction_stmt->execute([$tour_data['attraction_name']]);
        $attraction = $attraction_stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$attraction) {
            // Create attraction
            $create_attraction_stmt = $conn->prepare("
                INSERT INTO attractions (name, description, location, category, image_url) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $attraction_description = "Visit " . $tour_data['attraction_name'] . " in " . $tour_data['location'];
            $create_attraction_stmt->execute([
                $tour_data['attraction_name'],
                $attraction_description,
                $tour_data['location'],
                $tour_data['category'],
                $tour_data['image_url']
            ]);
            $attraction_id = $conn->lastInsertId();
            echo "<div class='info'>✓ Created attraction: " . $tour_data['attraction_name'] . " (ID: " . $attraction_id . ")</div>";
        } else {
            $attraction_id = $attraction['id'];
            // Update attraction image if provided
            if (!empty($tour_data['image_url'])) {
                $update_attraction_stmt = $conn->prepare("UPDATE attractions SET image_url = ? WHERE id = ?");
                $update_attraction_stmt->execute([$tour_data['image_url'], $attraction_id]);
            }
        }
        
        // Check if tour already exists
        $existing_tour_stmt = $conn->prepare("SELECT id FROM tours WHERE title = ? AND guide_id = ?");
        $existing_tour_stmt->execute([$tour_data['title'], $tour_data['guide_id']]);
        $existing_tour = $existing_tour_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing_tour) {
            // Update existing tour
            $update_tour_stmt = $conn->prepare("
                UPDATE tours 
                SET attraction_id = ?, description = ?, price = ?, duration_hours = ?, 
                    max_participants = ?, meeting_point = ?, category = ?, image_url = ?, status = 'active'
                WHERE id = ?
            ");
            $update_tour_stmt->execute([
                $attraction_id,
                $tour_data['description'],
                $tour_data['price'],
                $tour_data['duration_hours'],
                $tour_data['max_participants'],
                $tour_data['meeting_point'],
                $tour_data['category'],
                $tour_data['image_url'],
                $existing_tour['id']
            ]);
            echo "<div class='success'>✓ Updated tour: " . $tour_data['title'] . " (ID: " . $existing_tour['id'] . ")</div>";
            $updated_count++;
            $tour_id = $existing_tour['id'];
        } else {
            // Create new tour
            $create_tour_stmt = $conn->prepare("
                INSERT INTO tours (guide_id, attraction_id, title, description, price, duration_hours, 
                                 max_participants, meeting_point, category, image_url, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
            ");
            $create_tour_stmt->execute([
                $tour_data['guide_id'],
                $attraction_id,
                $tour_data['title'],
                $tour_data['description'],
                $tour_data['price'],
                $tour_data['duration_hours'],
                $tour_data['max_participants'],
                $tour_data['meeting_point'],
                $tour_data['category'],
                $tour_data['image_url']
            ]);
            $tour_id = $conn->lastInsertId();
            echo "<div class='success'>✓ Created tour: " . $tour_data['title'] . " (ID: " . $tour_id . ")</div>";
            $created_count++;
        }
        
        // Create tour schedules (5 schedules starting from tomorrow)
        $schedule_count = 0;
        for ($i = 1; $i <= 5; $i++) {
            $tour_date = date('Y-m-d', strtotime("+$i days"));
            $tour_time = ($i % 2 == 0) ? '14:00:00' : '10:00:00';
            
            // Check if schedule already exists
            $check_schedule_stmt = $conn->prepare("
                SELECT id FROM tour_schedules 
                WHERE tour_id = ? AND tour_date = ? AND tour_time = ?
            ");
            $check_schedule_stmt->execute([$tour_id, $tour_date, $tour_time]);
            
            if ($check_schedule_stmt->rowCount() === 0) {
                $create_schedule_stmt = $conn->prepare("
                    INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) 
                    VALUES (?, ?, ?, ?, 'available')
                ");
                $create_schedule_stmt->execute([
                    $tour_id,
                    $tour_date,
                    $tour_time,
                    $tour_data['max_participants']
                ]);
                $schedule_count++;
            }
        }
        
        if ($schedule_count > 0) {
            echo "<div class='info'>  → Created " . $schedule_count . " tour schedules</div>";
        }
    }
    
    // Summary
    echo "<hr>";
    echo "<h3>✅ Summary</h3>";
    echo "<div class='success'>";
    echo "<p><strong>Tours Created:</strong> " . $created_count . "</p>";
    echo "<p><strong>Tours Updated:</strong> " . $updated_count . "</p>";
    if ($error_count > 0) {
        echo "<p><strong>Errors:</strong> " . $error_count . "</p>";
    }
    echo "</div>";
    
    // Display created tours
    if ($created_count > 0 || $updated_count > 0) {
        echo "<h3>Created/Updated Tours:</h3>";
        $final_tours_stmt = $conn->query("
            SELECT t.id, t.title, t.image_url, t.price, a.name as attraction_name, a.location,
                   u.first_name, u.last_name
            FROM tours t 
            LEFT JOIN attractions a ON t.attraction_id = a.id 
            LEFT JOIN users u ON t.guide_id = u.id
            WHERE t.status = 'active'
            ORDER BY t.id DESC
            LIMIT " . ($created_count + $updated_count) . "
        ");
        $final_tours = $final_tours_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Title</th><th>Attraction</th><th>Location</th><th>Guide</th><th>Price</th><th>Image URL</th></tr>";
        foreach ($final_tours as $tour) {
            echo "<tr>";
            echo "<td>" . $tour['id'] . "</td>";
            echo "<td><strong>" . htmlspecialchars($tour['title']) . "</strong></td>";
            echo "<td>" . htmlspecialchars($tour['attraction_name']) . "</td>";
            echo "<td>" . htmlspecialchars($tour['location']) . "</td>";
            echo "<td>" . htmlspecialchars($tour['first_name'] . " " . $tour['last_name']) . "</td>";
            echo "<td>$" . number_format($tour['price'], 2) . "</td>";
            echo "<td><a href='" . htmlspecialchars($tour['image_url']) . "' target='_blank'>View Image</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<div style='margin-top: 30px;'>";
    echo "<a href='tours.php' class='btn'>View All Tours</a>";
    echo "<a href='index.php' class='btn'>Go to Homepage</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'><h3>❌ Error:</h3><p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</div></body></html>";
?>

