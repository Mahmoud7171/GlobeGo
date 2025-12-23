<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

echo "<h2>Tour Locations Check</h2>";

// Check tours and their locations
$stmt = $conn->query("
    SELECT t.id, t.title, t.description, a.location, a.name as attraction_name 
    FROM tours t 
    LEFT JOIN attractions a ON t.attraction_id = a.id 
    WHERE t.status = 'active' 
    ORDER BY t.id
");

$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Tour ID</th><th>Title</th><th>Attraction Location</th><th>Attraction Name</th><th>Has Location?</th></tr>";

foreach ($tours as $tour) {
    $hasLocation = !empty($tour['location']) ? 'Yes' : 'No';
    $location = $tour['location'] ?? 'NULL';
    echo "<tr>";
    echo "<td>{$tour['id']}</td>";
    echo "<td>{$tour['title']}</td>";
    echo "<td>{$location}</td>";
    echo "<td>" . ($tour['attraction_name'] ?? 'NULL') . "</td>";
    echo "<td>{$hasLocation}</td>";
    echo "</tr>";
}

echo "</table>";

// Test search for "Egypt"
echo "<h3>Testing search for 'Egypt':</h3>";
$searchTerm = 'Egypt';
$testQuery = "
    SELECT t.id, t.title, a.location 
    FROM tours t 
    LEFT JOIN attractions a ON t.attraction_id = a.id 
    WHERE t.status = 'active' 
    AND (
        a.location LIKE :location 
        OR t.title LIKE :location 
        OR t.description LIKE :location
    )
";
$testStmt = $conn->prepare($testQuery);
$testStmt->bindValue(':location', '%' . $searchTerm . '%');
$testStmt->execute();
$results = $testStmt->fetchAll(PDO::FETCH_ASSOC);

echo "<p>Found " . count($results) . " tours matching '{$searchTerm}':</p>";
echo "<ul>";
foreach ($results as $result) {
    echo "<li>Tour ID {$result['id']}: {$result['title']} - Location: " . ($result['location'] ?? 'NULL') . "</li>";
}
echo "</ul>";











