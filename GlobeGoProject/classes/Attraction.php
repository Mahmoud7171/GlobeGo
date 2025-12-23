<?php
require_once __DIR__ . '/../config/config.php';

class Attraction {
    private $conn;
    private $table_name = "attractions";

    public $id;
    public $name;
    public $description;
    public $location;
    public $latitude;
    public $longitude;
    public $category;
    public $image_url;
    public $rating;
    public $total_reviews;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new attraction
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=:name, description=:description, location=:location, 
                      latitude=:latitude, longitude=:longitude, category=:category, image_url=:image_url";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":latitude", $this->latitude);
        $stmt->bindParam(":longitude", $this->longitude);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":image_url", $this->image_url);
        
        return $stmt->execute();
    }

    // Get all attractions with filters
    public function getAttractions($filters = []) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        $params = [];
        
        if (!empty($filters['location'])) {
            $query .= " AND location LIKE :location";
            $params[':location'] = '%' . $filters['location'] . '%';
        }
        
        if (!empty($filters['category'])) {
            $query .= " AND category = :category";
            $params[':category'] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (name LIKE :search OR description LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $query .= " ORDER BY rating DESC, total_reviews DESC";
        
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get popular attractions
    public function getPopularAttractions() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  ORDER BY rating DESC, total_reviews DESC 
                  LIMIT 8";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get attraction by ID
    public function getAttractionById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Get tours for an attraction
    public function getToursForAttraction($attraction_id) {
        $query = "SELECT t.*, u.first_name, u.last_name, u.profile_image 
                  FROM tours t 
                  LEFT JOIN users u ON t.guide_id = u.id 
                  WHERE t.attraction_id = :attraction_id AND t.status = 'active' 
                  ORDER BY t.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":attraction_id", $attraction_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update attraction
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, description=:description, location=:location, 
                      latitude=:latitude, longitude=:longitude, category=:category, image_url=:image_url 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":latitude", $this->latitude);
        $stmt->bindParam(":longitude", $this->longitude);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Delete attraction
    public function delete($attraction_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $attraction_id);
        return $stmt->execute();
    }

    // Get attraction categories
    public function getCategories() {
        $query = "SELECT DISTINCT category FROM " . $this->table_name . " WHERE category IS NOT NULL ORDER BY category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Search attractions
    public function searchAttractions($search_term) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE name LIKE :search OR description LIKE :search OR location LIKE :search 
                  ORDER BY rating DESC";
        
        $stmt = $this->conn->prepare($query);
        $search_term = '%' . $search_term . '%';
        $stmt->bindParam(":search", $search_term);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get attractions by location
    public function getAttractionsByLocation($location) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE location LIKE :location 
                  ORDER BY rating DESC";
        
        $stmt = $this->conn->prepare($query);
        $location = '%' . $location . '%';
        $stmt->bindParam(":location", $location);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
