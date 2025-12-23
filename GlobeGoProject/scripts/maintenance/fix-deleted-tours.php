<?php
/**
 * Script to fix tours that should be hidden but are still showing
 * This will check for tours that might have been deleted but still have active status
 */

require_once __DIR__ . '/config/config.php';

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Fix Deleted Tours</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
    </style>
</head>
<body>";

try {
    echo "<h2>Fix Deleted Tours</h2>";
    
    // Get all tours
    $tours_stmt = $conn->query("
        SELECT t.id, t.title, t.status, 
               COUNT(b.id) as booking_count,
               COUNT(ts.id) as schedule_count
        FROM tours t
        LEFT JOIN bookings b ON b.tour_schedule_id IN (
            SELECT id FROM tour_schedules WHERE tour_id = t.id
        )
        LEFT JOIN tour_schedules ts ON ts.tour_id = t.id AND ts.tour_date >= CURDATE()
        GROUP BY t.id
        ORDER BY t.status, t.id
    ");
    $all_tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>All Tours Status:</h3>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Title</th><th>Status</th><th>Bookings</th><th>Future Schedules</th><th>Action</th></tr>";
    
    foreach ($all_tours as $tour) {
        $status_color = match($tour['status']) {
            'active' => 'green',
            'inactive' => 'orange',
            'cancelled' => 'red',
            default => 'gray'
        };
        
        echo "<tr>";
        echo "<td>" . $tour['id'] . "</td>";
        echo "<td><strong>" . htmlspecialchars($tour['title']) . "</strong></td>";
        echo "<td style='color: " . $status_color . ";'><strong>" . strtoupper($tour['status']) . "</strong></td>";
        echo "<td>" . $tour['booking_count'] . "</td>";
        echo "<td>" . $tour['schedule_count'] . "</td>";
        echo "<td>";
        
        if ($tour['status'] === 'active') {
            echo "<form method='POST' style='display: inline;' onsubmit='return confirm(\"Hide this tour from destinations page?\");'>";
            echo "<input type='hidden' name='tour_id' value='" . $tour['id'] . "'>";
            echo "<input type='hidden' name='action' value='hide'>";
            echo "<button type='submit' style='padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;'>Hide Tour</button>";
            echo "</form>";
        } else {
            echo "<form method='POST' style='display: inline;'>";
            echo "<input type='hidden' name='tour_id' value='" . $tour['id'] . "'>";
            echo "<input type='hidden' name='action' value='show'>";
            echo "<button type='submit' style='padding: 5px 10px; background: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer;'>Show Tour</button>";
            echo "</form>";
        }
        
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Handle actions
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
        $tour_id = (int)$_POST['tour_id'];
        $action = $_POST['action'];
        
        if ($action === 'hide') {
            $update_stmt = $conn->prepare("UPDATE tours SET status = 'cancelled' WHERE id = ?");
            $update_stmt->execute([$tour_id]);
            echo "<div class='success'>✓ Tour #$tour_id has been hidden (status set to 'cancelled'). It will no longer appear on the destinations page.</div>";
            echo "<script>setTimeout(function(){ location.reload(); }, 2000);</script>";
        } elseif ($action === 'show') {
            $update_stmt = $conn->prepare("UPDATE tours SET status = 'active' WHERE id = ?");
            $update_stmt->execute([$tour_id]);
            echo "<div class='success'>✓ Tour #$tour_id has been activated. It will now appear on the destinations page.</div>";
            echo "<script>setTimeout(function(){ location.reload(); }, 2000);</script>";
        }
    }
    
    echo "<div class='info'>";
    echo "<h3>How it works:</h3>";
    echo "<ul>";
    echo "<li><strong>Active</strong> tours appear on the destinations page</li>";
    echo "<li><strong>Cancelled/Inactive</strong> tours are hidden from the destinations page</li>";
    echo "<li>Click 'Hide Tour' to remove a tour from the destinations page</li>";
    echo "<li>Click 'Show Tour' to make a hidden tour visible again</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<a href='admin/tours.php' class='btn'>Go to Admin Tours Page</a>";
    echo "<a href='tours.php' class='btn'>View Destinations Page</a>";
    
} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "</body></html>";
?>











