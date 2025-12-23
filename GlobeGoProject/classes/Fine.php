<?php
require_once __DIR__ . '/../config/config.php';

class Fine {
    private $conn;
    private $table_name = "fines";

    public $id;
    public $tourist_id;
    public $booking_id;
    public $booking_reference;
    public $amount;
    public $original_price;
    public $status;
    public $payment_method;
    public $payment_reference;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new fine
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET tourist_id=:tourist_id, booking_id=:booking_id, booking_reference=:booking_reference,
                      amount=:amount, original_price=:original_price, status='pending'";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":tourist_id", $this->tourist_id);
        $stmt->bindParam(":booking_id", $this->booking_id);
        $stmt->bindParam(":booking_reference", $this->booking_reference);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":original_price", $this->original_price);
        
        return $stmt->execute();
    }

    // Get fines for a tourist
    public function getFinesByTourist($tourist_id, $status = null) {
        $query = "SELECT f.*, b.tour_schedule_id, ts.tour_date, ts.tour_time, t.title as tour_title
                  FROM " . $this->table_name . " f
                  LEFT JOIN bookings b ON f.booking_id = b.id
                  LEFT JOIN tour_schedules ts ON b.tour_schedule_id = ts.id
                  LEFT JOIN tours t ON ts.tour_id = t.id
                  WHERE f.tourist_id = :tourist_id";
        
        if ($status) {
            $query .= " AND f.status = :status";
        }
        
        $query .= " ORDER BY f.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tourist_id", $tourist_id);
        if ($status) {
            $stmt->bindParam(":status", $status);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get fine by ID
    public function getFineById($id) {
        $query = "SELECT f.*, b.tour_schedule_id, ts.tour_date, ts.tour_time, t.title as tour_title,
                         t.id as tour_id, a.name as attraction_name
                  FROM " . $this->table_name . " f
                  LEFT JOIN bookings b ON f.booking_id = b.id
                  LEFT JOIN tour_schedules ts ON b.tour_schedule_id = ts.id
                  LEFT JOIN tours t ON ts.tour_id = t.id
                  LEFT JOIN attractions a ON t.attraction_id = a.id
                  WHERE f.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Update fine payment status
    public function markAsPaid($id, $payment_method, $payment_reference) {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = 'paid', payment_method = :payment_method, 
                      payment_reference = :payment_reference, paid_at = NOW()
                  WHERE id = :id AND status = 'pending'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":payment_method", $payment_method);
        $stmt->bindParam(":payment_reference", $payment_reference);
        
        return $stmt->execute();
    }

    // Check if fine exists for a booking
    public function fineExistsForBooking($booking_id) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE booking_id = :booking_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":booking_id", $booking_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}


