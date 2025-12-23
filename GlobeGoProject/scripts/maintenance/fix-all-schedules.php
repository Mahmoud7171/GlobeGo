<?php
require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<h2>Fix All Tour Schedules</h2>";

try {
    // Get current date
    $current_date = date('Y-m-d');
    echo "<p>Current date: " . $current_date . "</p>";
    
    // Check existing schedules
    $check_stmt = $conn->query("SELECT COUNT(*) as count FROM tour_schedules WHERE tour_date >= CURDATE()");
    $result = $check_stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Current future schedules: " . $result['count'] . "</p>";
    
    // Delete old schedules
    $delete_stmt = $conn->prepare("DELETE FROM tour_schedules WHERE tour_date < CURDATE()");
    $delete_stmt->execute();
    $deleted_count = $delete_stmt->rowCount();
    echo "<p>Deleted " . $deleted_count . " old schedules</p>";
    
    // Get all active tours
    $tours_stmt = $conn->query("SELECT id, title, max_participants FROM tours WHERE status = 'active'");
    $tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Found " . count($tours) . " active tours</p>";
    
    // Create new schedules for each tour
    foreach ($tours as $tour) {
        echo "<h4>Creating schedules for: " . $tour['title'] . "</h4>";
        
        // Create 7 schedules starting from tomorrow
        for ($i = 1; $i <= 7; $i++) {
            $tour_date = date('Y-m-d', strtotime("+$i days"));
            $tour_time = ($i % 2 == 0) ? '14:00:00' : '10:00:00';
            $available_spots = $tour['max_participants'];
            
            $insert_stmt = $conn->prepare("
                INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) 
                VALUES (?, ?, ?, ?, 'available')
            ");
            
            $insert_stmt->execute([$tour['id'], $tour_date, $tour_time, $available_spots]);
            echo "<p>Created schedule: " . $tour_date . " at " . $tour_time . " (" . $available_spots . " spots)</p>";
        }
    }
    
    // Verify the fix
    $verify_stmt = $conn->query("SELECT COUNT(*) as count FROM tour_schedules WHERE tour_date >= CURDATE()");
    $verify_result = $verify_stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Total future schedules now: " . $verify_result['count'] . "</strong></p>";
    
    // Show some sample schedules
    $sample_stmt = $conn->query("
        SELECT ts.*, t.title 
        FROM tour_schedules ts 
        JOIN tours t ON ts.tour_id = t.id 
        WHERE ts.tour_date >= CURDATE() 
        ORDER BY ts.tour_date, ts.tour_time 
        LIMIT 10
    ");
    $samples = $sample_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Sample Schedules Created:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Tour</th><th>Date</th><th>Time</th><th>Available Spots</th></tr>";
    foreach ($samples as $sample) {
        echo "<tr>";
        echo "<td>" . $sample['title'] . "</td>";
        echo "<td>" . $sample['tour_date'] . "</td>";
        echo "<td>" . $sample['tour_time'] . "</td>";
        echo "<td>" . $sample['available_spots'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>✅ All Tour Schedules Fixed Successfully!</h3>";
    echo "<p>Now you can:</p>";
    echo "<ul>";
    echo "<li><a href='tours.php'>View All Tours</a></li>";
    echo "<li><a href='book-tour.php?id=2'>Test Ancient Rome Discovery Booking</a></li>";
    echo "<li><a href='tour-details.php?id=2'>View Ancient Rome Discovery Details</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

