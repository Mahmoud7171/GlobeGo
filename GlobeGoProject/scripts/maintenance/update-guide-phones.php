<?php
require_once __DIR__ . '/config/config.php';

// Simple utility script to assign random-looking European phone numbers
// to all guides who don't already have a phone number.

$database = new Database();
$conn = $database->getConnection();

echo "<h2>Update Guide Phone Numbers</h2>";

// Helper to generate a random professional European-style number
function generateEuPhone(string $countryCode, string $pattern): string {
    // $pattern uses X as digit placeholders, other chars copied as-is
    $number = '';
    foreach (str_split($pattern) as $ch) {
        if ($ch === 'X') {
            $number .= random_int(0, 9);
        } else {
            $number .= $ch;
        }
    }
    return $countryCode . ' ' . $number;
}

// Country patterns to rotate through
$countries = [
    ['+33', '1 4X XX XX XX'],   // France
    ['+34', '6XX XXX XXX'],     // Spain (mobile)
    ['+39', '3XX XXX XXXX'],    // Italy (mobile)
    ['+49', '1X XX XX XXXX'],   // Germany (mobile-style)
    ['+31', '6 XX XX XX XX'],   // Netherlands (mobile)
];

try {
    $stmt = $conn->query("SELECT id, first_name, last_name, phone FROM users WHERE role = 'guide'");
    $guides = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$guides) {
        echo "<p>No guides found.</p>";
        exit;
    }

    $update = $conn->prepare("UPDATE users SET phone = :phone WHERE id = :id");

    $i = 0;
    foreach ($guides as $guide) {
        // Skip guides that already have a phone number
        if (!empty($guide['phone'])) {
            echo "<p>Skipping {$guide['first_name']} {$guide['last_name']} (already has phone: {$guide['phone']})</p>";
            continue;
        }

        $country = $countries[$i % count($countries)];
        $phone = generateEuPhone($country[0], $country[1]);
        $i++;

        $update->execute([
            ':phone' => $phone,
            ':id' => $guide['id'],
        ]);

        echo "<p style='color: green;'>Set phone for {$guide['first_name']} {$guide['last_name']} to {$phone}</p>";
    }

    echo "<hr><p>Done. Refresh any booking / guide pages to see the updated contact numbers.</p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}


