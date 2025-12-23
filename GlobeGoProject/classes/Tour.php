<?php
require_once __DIR__ . '/../config/config.php';

class Tour {
    private $conn;
    private $table_name = "tours";

    public $id;
    public $guide_id;
    public $attraction_id;
    public $title;
    public $description;
    public $price;
    public $duration_hours;
    public $max_participants;
    public $meeting_point;
    public $category;
    public $image_url;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new tour
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET guide_id=:guide_id, attraction_id=:attraction_id, title=:title, 
                      description=:description, price=:price, duration_hours=:duration_hours, 
                      max_participants=:max_participants, meeting_point=:meeting_point, 
                      category=:category, image_url=:image_url";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":guide_id", $this->guide_id);
        $stmt->bindParam(":attraction_id", $this->attraction_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":duration_hours", $this->duration_hours);
        $stmt->bindParam(":max_participants", $this->max_participants);
        $stmt->bindParam(":meeting_point", $this->meeting_point);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":image_url", $this->image_url);
        
        return $stmt->execute();
    }

    // Get all tours with filters
    public function getTours($filters = []) {
        $query = "SELECT t.*, u.first_name, u.last_name, u.profile_image, a.name as attraction_name, 
                  COALESCE(a.location, t.meeting_point, 'Location not specified') as location 
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.guide_id = u.id 
                  LEFT JOIN attractions a ON t.attraction_id = a.id 
                  WHERE t.status = 'active'";
        
        $params = [];
        
        if (!empty($filters['location'])) {
            // Search in multiple fields: attraction location, tour title, description, and meeting point
            // Using OR so if any field matches, the tour is included
            $query .= " AND (
                        COALESCE(LOWER(a.location), '') LIKE LOWER(:location)
                        OR LOWER(t.title) LIKE LOWER(:location) 
                        OR LOWER(t.description) LIKE LOWER(:location)
                        OR LOWER(t.meeting_point) LIKE LOWER(:location)
                       )";
            $params[':location'] = '%' . trim($filters['location']) . '%';
        }
        
        if (!empty($filters['category'])) {
            // Map filter categories to database categories
            // Database uses "Historical Tour", "Cultural Tour", etc., but filter uses "Historical", "Cultural"
            $category_map = [
                'Historical' => 'Historical Tour',
                'Cultural' => 'Cultural Tour',
                'Adventure' => 'Adventure Tour',
                'Food Tour' => 'Food Tour',
                'Walking Tour' => 'Walking Tour',
                'Museum Tour' => 'Museum Tour'
            ];
            
            $db_category = $category_map[$filters['category']] ?? $filters['category'];
            
            // Special handling for "Adventure" - search in title and description too
            if ($filters['category'] === 'Adventure') {
                $query .= " AND (
                    t.category = :category 
                    OR t.category LIKE :category_like 
                    OR LOWER(t.title) LIKE LOWER(:adventure_search)
                    OR LOWER(t.description) LIKE LOWER(:adventure_search)
                )";
                $params[':category'] = $db_category;
                $params[':category_like'] = '%Adventure%';
                $params[':adventure_search'] = '%adventure%';
            } else {
                // Use LIKE for partial matching as fallback (handles both exact and partial matches)
                $query .= " AND (t.category = :category OR t.category LIKE :category_like)";
                $params[':category'] = $db_category;
                $params[':category_like'] = '%' . $filters['category'] . '%';
            }
        }
        
        if (!empty($filters['max_price'])) {
            $query .= " AND t.price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
        
        $query .= " ORDER BY t.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get featured tours
    public function getFeaturedTours() {
        $query = "SELECT t.*, u.first_name, u.last_name, u.profile_image, a.name as attraction_name, a.location 
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.guide_id = u.id 
                  LEFT JOIN attractions a ON t.attraction_id = a.id 
                  WHERE t.status = 'active' 
                  ORDER BY t.created_at DESC 
                  LIMIT 6";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get tour by ID
    public function getTourById($id) {
        $query = "SELECT t.*, u.first_name, u.last_name, u.profile_image, u.bio, u.languages, u.verified,
                         a.name as attraction_name, 
                         COALESCE(a.location, t.meeting_point, 'Location not specified') as location, 
                         a.description as attraction_description 
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.guide_id = u.id 
                  LEFT JOIN attractions a ON t.attraction_id = a.id 
                  WHERE t.id = :id AND t.status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Get tours by guide
    public function getToursByGuide($guide_id) {
        $query = "SELECT t.*, a.name as attraction_name, a.location,
                         (SELECT COUNT(*) FROM bookings b 
                          JOIN tour_schedules ts ON b.tour_schedule_id = ts.id 
                          WHERE ts.tour_id = t.id AND b.status = 'confirmed') as total_bookings
                  FROM " . $this->table_name . " t 
                  LEFT JOIN attractions a ON t.attraction_id = a.id 
                  WHERE t.guide_id = :guide_id 
                  ORDER BY t.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":guide_id", $guide_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update tour
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title=:title, description=:description, price=:price, duration_hours=:duration_hours, 
                      max_participants=:max_participants, meeting_point=:meeting_point, 
                      category=:category, image_url=:image_url 
                  WHERE id=:id AND guide_id=:guide_id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":duration_hours", $this->duration_hours);
        $stmt->bindParam(":max_participants", $this->max_participants);
        $stmt->bindParam(":meeting_point", $this->meeting_point);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":guide_id", $this->guide_id);
        
        return $stmt->execute();
    }

    // Update tour guide (for admin)
    public function updateGuide($tour_id, $new_guide_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET guide_id=:guide_id 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":guide_id", $new_guide_id);
        $stmt->bindParam(":id", $tour_id);
        
        return $stmt->execute();
    }

    // Delete tour
    public function delete($tour_id, $guide_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND guide_id = :guide_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $tour_id);
        $stmt->bindParam(":guide_id", $guide_id);
        return $stmt->execute();
    }

    // Get tour schedules
    public function getTourSchedules($tour_id) {
        $query = "SELECT * FROM tour_schedules 
                  WHERE tour_id = :tour_id AND status = 'available' 
                  AND tour_date >= CURDATE() 
                  ORDER BY tour_date, tour_time";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tour_id", $tour_id);
        $stmt->execute();
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // If no schedules found, create some default ones for the next 5 days
        if (empty($schedules)) {
            $this->createDefaultSchedules($tour_id);
            // Try again after creating schedules
            $stmt->execute();
            $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $schedules;
    }
    
    // Create default schedules for a tour
    private function createDefaultSchedules($tour_id) {
        // Get tour details to determine max participants
        $tour_query = "SELECT max_participants FROM tours WHERE id = :tour_id";
        $tour_stmt = $this->conn->prepare($tour_query);
        $tour_stmt->bindParam(":tour_id", $tour_id);
        $tour_stmt->execute();
        $tour = $tour_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($tour) {
            $max_participants = $tour['max_participants'];
            
            // Create 5 schedules starting from tomorrow
            for ($i = 1; $i <= 5; $i++) {
                $tour_date = date('Y-m-d', strtotime("+$i days"));
                $tour_time = ($i % 2 == 0) ? '14:00:00' : '10:00:00';
                $available_spots = $max_participants;
                
                $insert_stmt = $this->conn->prepare("
                    INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) 
                    VALUES (?, ?, ?, ?, 'available')
                ");
                $insert_stmt->execute([$tour_id, $tour_date, $tour_time, $available_spots]);
            }
        }
    }

    // Add tour schedule
    public function addTourSchedule($tour_id, $tour_date, $tour_time, $available_spots) {
        $query = "INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots) 
                  VALUES (:tour_id, :tour_date, :tour_time, :available_spots)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tour_id", $tour_id);
        $stmt->bindParam(":tour_date", $tour_date);
        $stmt->bindParam(":tour_time", $tour_time);
        $stmt->bindParam(":available_spots", $available_spots);
        
        return $stmt->execute();
    }

    // Get tours by attraction
    public function getToursByAttraction($attraction_id) {
        $query = "SELECT t.*, u.first_name, u.last_name, u.profile_image, a.name as attraction_name, a.location 
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.guide_id = u.id 
                  LEFT JOIN attractions a ON t.attraction_id = a.id 
                  WHERE t.attraction_id = :attraction_id AND t.status = 'active' 
                  ORDER BY t.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":attraction_id", $attraction_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get tour statistics
    public function getTourStats($tour_id) {
        $query = "SELECT 
                    COUNT(DISTINCT b.id) as total_bookings,
                    SUM(b.num_participants) as total_participants,
                    SUM(b.total_price) as total_revenue,
                    AVG(r.rating) as average_rating,
                    COUNT(r.id) as total_reviews
                  FROM bookings b 
                  LEFT JOIN reviews r ON b.id = r.booking_id 
                  JOIN tour_schedules ts ON b.tour_schedule_id = ts.id 
                  WHERE ts.tour_id = :tour_id AND b.status = 'confirmed'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tour_id", $tour_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all destinations with images for hero slideshow
    public function getHeroDestinations() {
        $query = "SELECT 
                    t.image_url,
                    COALESCE(a.location, t.meeting_point, 'Unknown Location') as location,
                    COALESCE(
                        SUBSTRING_INDEX(COALESCE(a.location, t.meeting_point), ',', 1), 
                        t.title
                    ) as city,
                    COALESCE(t.title, a.name) as tour_title,
                    COALESCE(
                        LEFT(t.description, 100), 
                        LEFT(a.description, 100), 
                        'Discover amazing tours and experiences'
                    ) as description,
                    a.name as attraction_name
                  FROM " . $this->table_name . " t
                  LEFT JOIN attractions a ON t.attraction_id = a.id
                  WHERE t.status = 'active' 
                  AND t.image_url IS NOT NULL 
                  AND t.image_url != ''
                  ORDER BY t.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format the destinations for the slideshow
        $formatted = [];
        $seen_locations = []; // Track to avoid exact duplicates
        
        foreach ($destinations as $dest) {
            // Create a unique key based on location and image to avoid exact duplicates
            $location_key = strtolower(trim($dest['location'] . '|' . $dest['image_url']));
            
            if (!in_array($location_key, $seen_locations)) {
                $seen_locations[] = $location_key;
                
                // Clean up tour title for display
                $tour_title = $dest['tour_title'];
                $tour_title = str_replace([',', '-'], ' ', $tour_title);
                $tour_title = preg_replace('/\s+/', ' ', $tour_title); // Replace multiple spaces with single space
                $tour_title = trim($tour_title);
                
                $formatted[] = [
                    'image_url' => $dest['image_url'],
                    'location' => $dest['location'],
                    'city' => $dest['city'],
                    'tour_title' => strtoupper($tour_title),
                    'description' => $dest['description']
                ];
            }
        }
        
        return $formatted;
    }
}
?>
