<?php
require_once __DIR__ . '/../config/config.php';
// Observer Pattern includes
require_once __DIR__ . '/../patterns/observer/BookingSubject.php';
require_once __DIR__ . '/../patterns/observer/EmailNotificationObserver.php';
require_once __DIR__ . '/../patterns/observer/GuideNotificationObserver.php';
require_once __DIR__ . '/../patterns/observer/AdminLogObserver.php';

class Booking {
    private $conn;
    private $table_name = "bookings";
    private static $bookingSubject = null;

    public $id;
    public $tourist_id;
    public $tour_schedule_id;
    public $booking_reference;
    public $num_participants;
    public $total_price;
    public $status;
    public $payment_status;
    public $payment_method;
    public $payment_reference;
    public $booking_notes;

    public function __construct($db) {
        $this->conn = $db;
        // Initialize observer subject once (Observer Pattern)
        if (self::$bookingSubject === null) {
            self::$bookingSubject = new BookingSubject();
            self::$bookingSubject->attach(new EmailNotificationObserver());
            self::$bookingSubject->attach(new GuideNotificationObserver());
            self::$bookingSubject->attach(new AdminLogObserver());
        }
    }

    // Helper method to notify observers (Observer Pattern)
    private function notifyObservers(string $eventType, array $data): void {
        if (self::$bookingSubject !== null) {
            self::$bookingSubject->notify($eventType, $data);
        }
    }

    // Create new booking (MODIFIED to add Observer Pattern)
    public function create() {
        // Generate unique booking reference
        if (empty($this->booking_reference)) {
            $this->booking_reference = 'GG' . strtoupper(uniqid());
        }
        
        // Set default values if not provided
        if (empty($this->status)) {
            $this->status = 'pending';
        }
        if (empty($this->payment_status)) {
            $this->payment_status = 'pending';
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                  SET tourist_id=:tourist_id, tour_schedule_id=:tour_schedule_id, 
                      booking_reference=:booking_reference, num_participants=:num_participants, 
                      total_price=:total_price, booking_notes=:booking_notes,
                      status=:status, payment_status=:payment_status,
                      payment_method=:payment_method, payment_reference=:payment_reference";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":tourist_id", $this->tourist_id);
        $stmt->bindParam(":tour_schedule_id", $this->tour_schedule_id);
        $stmt->bindParam(":booking_reference", $this->booking_reference);
        $stmt->bindParam(":num_participants", $this->num_participants);
        $stmt->bindParam(":total_price", $this->total_price);
        $stmt->bindParam(":booking_notes", $this->booking_notes);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":payment_status", $this->payment_status);
        $stmt->bindParam(":payment_method", $this->payment_method);
        $stmt->bindParam(":payment_reference", $this->payment_reference);
        
        $result = $stmt->execute();
        
        // If booking created successfully, notify observers (Observer Pattern)
        if ($result) {
            $this->id = $this->conn->lastInsertId();
            
            // Get guide_id from tour_schedule for notification
            $guideQuery = "SELECT t.guide_id FROM tours t 
                          JOIN tour_schedules ts ON t.id = ts.tour_id 
                          WHERE ts.id = :schedule_id";
            $guideStmt = $this->conn->prepare($guideQuery);
            $guideStmt->bindParam(":schedule_id", $this->tour_schedule_id);
            $guideStmt->execute();
            $guideData = $guideStmt->fetch(PDO::FETCH_ASSOC);
            $guide_id = $guideData['guide_id'] ?? null;
            
            // Notify observers about booking creation (Observer Pattern)
            $this->notifyObservers('booking_created', [
                'booking_id' => $this->id,
                'booking_reference' => $this->booking_reference,
                'tourist_id' => $this->tourist_id,
                'guide_id' => $guide_id,
                'total_price' => $this->total_price,
                'num_participants' => $this->num_participants
            ]);
        }
        
        return $result;
    }

    // Get booking by ID (with full tourist and guide info)
    public function getBookingById($id) {
        $query = "SELECT 
                        b.*,
                        ts.tour_date,
                        ts.tour_time,
                        ts.available_spots,
                        t.title AS tour_title,
                        t.description AS tour_description,
                        t.price,
                        t.meeting_point,
                        t.duration_hours,
                        -- Tourist info
                        ut.first_name AS tourist_first_name,
                        ut.last_name AS tourist_last_name,
                        ut.email AS tourist_email,
                        ut.profile_image AS tourist_profile_image,
                        -- Guide info
                        ug.first_name AS guide_first_name,
                        ug.last_name AS guide_last_name,
                        ug.email AS guide_email,
                        ug.phone AS guide_phone,
                        ug.profile_image AS guide_profile_image,
                        ug.verified AS guide_verified,
                        ug.id AS guide_id,
                        -- Attraction info
                        a.name AS attraction_name,
                        a.location
                  FROM " . $this->table_name . " b
                  JOIN tour_schedules ts ON b.tour_schedule_id = ts.id
                  JOIN tours t ON ts.tour_id = t.id
                  JOIN users ut ON b.tourist_id = ut.id
                  JOIN users ug ON t.guide_id = ug.id
                  LEFT JOIN attractions a ON t.attraction_id = a.id
                  WHERE b.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Get bookings by tourist
    public function getBookingsByTourist($tourist_id) {
        $query = "SELECT b.*, ts.tour_date, ts.tour_time,
                         t.title as tour_title, t.price, t.meeting_point,
                         u.first_name as guide_first_name, u.last_name as guide_last_name,
                         a.name as attraction_name, a.location
                  FROM " . $this->table_name . " b
                  JOIN tour_schedules ts ON b.tour_schedule_id = ts.id
                  JOIN tours t ON ts.tour_id = t.id
                  JOIN users u ON t.guide_id = u.id
                  LEFT JOIN attractions a ON t.attraction_id = a.id
                  WHERE b.tourist_id = :tourist_id
                  ORDER BY ts.tour_date DESC, ts.tour_time DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tourist_id", $tourist_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get bookings by guide
    public function getBookingsByGuide($guide_id) {
        $query = "SELECT b.*, ts.tour_date, ts.tour_time,
                         t.title as tour_title, t.price, t.meeting_point,
                         u.first_name as tourist_first_name, u.last_name as tourist_last_name, u.email as tourist_email,
                         a.name as attraction_name, a.location
                  FROM " . $this->table_name . " b
                  JOIN tour_schedules ts ON b.tour_schedule_id = ts.id
                  JOIN tours t ON ts.tour_id = t.id
                  JOIN users u ON b.tourist_id = u.id
                  LEFT JOIN attractions a ON t.attraction_id = a.id
                  WHERE t.guide_id = :guide_id
                  ORDER BY ts.tour_date DESC, ts.tour_time DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":guide_id", $guide_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update booking status (MODIFIED to add Observer Pattern)
    public function updateBookingStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        
        $result = $stmt->execute();
        
        // If status changed to confirmed, notify observers (Observer Pattern)
        if ($result && $status === 'confirmed') {
            $bookingDetails = $this->getBookingById($id);
            if ($bookingDetails) {
                $this->notifyObservers('booking_confirmed', [
                    'booking_id' => $id,
                    'booking_reference' => $bookingDetails['booking_reference'] ?? '',
                    'tourist_id' => $bookingDetails['tourist_id'] ?? null,
                    'guide_id' => $bookingDetails['guide_id'] ?? null
                ]);
            }
        }
        
        return $result;
    }

    // Update payment status
    public function updatePaymentStatus($id, $payment_status, $payment_method = null, $payment_reference = null) {
        $query = "UPDATE " . $this->table_name . " 
                  SET payment_status = :payment_status, payment_method = :payment_method, 
                      payment_reference = :payment_reference 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":payment_status", $payment_status);
        $stmt->bindParam(":payment_method", $payment_method);
        $stmt->bindParam(":payment_reference", $payment_reference);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Cancel booking (MODIFIED to add Observer Pattern)
    public function cancelBooking($id, $tourist_id) {
        try {
            // Start transaction
            if (!$this->conn->inTransaction()) {
                $this->conn->beginTransaction();
            }
            
            // First, get booking details before cancelling (to get tour_schedule_id and num_participants)
            $bookingQuery = "SELECT tour_schedule_id, num_participants, status, total_price FROM " . $this->table_name . " 
                            WHERE id = :id AND tourist_id = :tourist_id";
            $bookingStmt = $this->conn->prepare($bookingQuery);
            $bookingStmt->bindParam(":id", $id, PDO::PARAM_INT);
            $bookingStmt->bindParam(":tourist_id", $tourist_id, PDO::PARAM_INT);
            $bookingStmt->execute();
            $bookingData = $bookingStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$bookingData) {
                if ($this->conn->inTransaction()) {
                    $this->conn->rollBack();
                }
                error_log("Cancel booking failed: Booking not found. ID: $id, Tourist ID: $tourist_id");
                return false;
            }
            
            // Check if status allows cancellation
            if (!in_array($bookingData['status'], ['pending', 'confirmed'])) {
                if ($this->conn->inTransaction()) {
                    $this->conn->rollBack();
                }
                error_log("Cancel booking failed: Invalid status. Booking ID: $id, Status: " . $bookingData['status']);
                return false;
            }
            
            // Update the booking status to cancelled
            $query = "UPDATE " . $this->table_name . " 
                      SET status = 'cancelled' 
                      WHERE id = :id AND tourist_id = :tourist_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":tourist_id", $tourist_id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Verify update succeeded (within transaction)
            $verifyStmt = $this->conn->prepare("SELECT status FROM " . $this->table_name . " WHERE id = :id");
            $verifyStmt->bindParam(":id", $id, PDO::PARAM_INT);
            $verifyStmt->execute();
            $verifyData = $verifyStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$verifyData || $verifyData['status'] !== 'cancelled') {
                $this->conn->rollBack();
                error_log("Cancel booking failed: Status not updated to cancelled. Booking ID: $id, Got status: " . ($verifyData['status'] ?? 'null'));
                return false;
            }
            
            // Update available spots in tour schedule
            $updateSpotsQuery = "UPDATE tour_schedules 
                                SET available_spots = available_spots + :num_participants
                                WHERE id = :schedule_id";
            $updateSpotsStmt = $this->conn->prepare($updateSpotsQuery);
            $updateSpotsStmt->bindParam(":num_participants", $bookingData['num_participants'], PDO::PARAM_INT);
            $updateSpotsStmt->bindParam(":schedule_id", $bookingData['tour_schedule_id'], PDO::PARAM_INT);
            $updateSpotsStmt->execute();
            
            // Get booking reference for fine record
            $bookingRefQuery = "SELECT booking_reference FROM " . $this->table_name . " WHERE id = :id";
            $bookingRefStmt = $this->conn->prepare($bookingRefQuery);
            $bookingRefStmt->bindParam(":id", $id, PDO::PARAM_INT);
            $bookingRefStmt->execute();
            $bookingRefData = $bookingRefStmt->fetch(PDO::FETCH_ASSOC);
            $booking_reference = $bookingRefData['booking_reference'] ?? '';
            
            // Create fine record (25% of total price)
            // Check if fines table exists and fine doesn't already exist for this booking
            try {
                // Check if fine already exists
                $checkFineQuery = "SELECT id FROM fines WHERE booking_id = :booking_id";
                $checkFineStmt = $this->conn->prepare($checkFineQuery);
                $checkFineStmt->bindParam(":booking_id", $id, PDO::PARAM_INT);
                $checkFineStmt->execute();
                
                if ($checkFineStmt->rowCount() == 0) {
                    // Fine doesn't exist, create it
                    $fine_amount = $bookingData['total_price'] * 0.25;
                    $fineQuery = "INSERT INTO fines (tourist_id, booking_id, booking_reference, amount, original_price, status)
                                 VALUES (:tourist_id, :booking_id, :booking_reference, :amount, :original_price, 'pending')";
                    $fineStmt = $this->conn->prepare($fineQuery);
                    $fineStmt->bindParam(":tourist_id", $tourist_id, PDO::PARAM_INT);
                    $fineStmt->bindParam(":booking_id", $id, PDO::PARAM_INT);
                    $fineStmt->bindParam(":booking_reference", $booking_reference);
                    $fineStmt->bindParam(":amount", $fine_amount);
                    $fineStmt->bindParam(":original_price", $bookingData['total_price']);
                    $fineStmt->execute();
                }
            } catch (PDOException $e) {
                // If fines table doesn't exist, log error but don't fail the cancellation
                error_log("Warning: Could not create fine record (table may not exist): " . $e->getMessage());
                // Continue with cancellation even if fine record creation fails
            }
            
            // Commit transaction
            $this->conn->commit();
            
            // Get booking details for notification (after commit)
            $bookingDetails = $this->getBookingById($id);
            if ($bookingDetails) {
                // Notify observers about booking cancellation (Observer Pattern)
                $this->notifyObservers('booking_cancelled', [
                    'booking_id' => $id,
                    'booking_reference' => $bookingDetails['booking_reference'] ?? '',
                    'tourist_id' => $tourist_id,
                    'guide_id' => $bookingDetails['guide_id'] ?? null,
                    'total_price' => $bookingDetails['total_price'] ?? 0
                ]);
            }
            
            return true;
            
        } catch (PDOException $e) {
            // Rollback on error
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log('PDO Error cancelling booking ID ' . $id . ': ' . $e->getMessage() . ' | Code: ' . $e->getCode());
            return false;
        } catch (Exception $e) {
            // Rollback on error
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log('Error cancelling booking ID ' . $id . ': ' . $e->getMessage() . ' | Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    // Update available spots after cancellation
    private function updateAvailableSpots($booking_id) {
        $query = "UPDATE tour_schedules ts 
                  SET available_spots = available_spots + (
                      SELECT num_participants FROM " . $this->table_name . " WHERE id = :booking_id
                  ) 
                  WHERE id = (
                      SELECT tour_schedule_id FROM " . $this->table_name . " WHERE id = :booking_id
                  )";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":booking_id", $booking_id);
        $stmt->execute();
    }

    // Check if tour schedule is available
    public function checkAvailability($tour_schedule_id, $num_participants) {
        $query = "SELECT available_spots FROM tour_schedules WHERE id = :id AND status = 'available'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $tour_schedule_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['available_spots'] >= $num_participants;
        }
        return false;
    }

    // Update available spots after booking
    public function updateAvailableSpotsAfterBooking($tour_schedule_id, $num_participants) {
        $query = "UPDATE tour_schedules 
                  SET available_spots = available_spots - :num_participants 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":num_participants", $num_participants);
        $stmt->bindParam(":id", $tour_schedule_id);
        return $stmt->execute();
    }

    // Get booking statistics
    public function getBookingStats($guide_id = null) {
        $query = "SELECT 
                    COUNT(*) as total_bookings,
                    SUM(total_price) as total_revenue,
                    AVG(num_participants) as avg_participants
                  FROM " . $this->table_name . " b";
        
        if ($guide_id) {
            $query .= " JOIN tour_schedules ts ON b.tour_schedule_id = ts.id
                        JOIN tours t ON ts.tour_id = t.id
                        WHERE t.guide_id = :guide_id AND b.status = 'confirmed'";
            $params = [':guide_id' => $guide_id];
        } else {
            $query .= " WHERE status = 'confirmed'";
            $params = [];
        }
        
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all bookings (for admin)
    public function getAllBookings() {
        $query = "SELECT b.*, ts.tour_date, ts.tour_time,
                         t.title as tour_title, t.price,
                         u1.first_name as tourist_first_name, u1.last_name as tourist_last_name, u1.email as tourist_email,
                         u2.first_name as guide_first_name, u2.last_name as guide_last_name,
                         a.name as attraction_name, a.location
                  FROM " . $this->table_name . " b
                  JOIN tour_schedules ts ON b.tour_schedule_id = ts.id
                  JOIN tours t ON ts.tour_id = t.id
                  JOIN users u1 ON b.tourist_id = u1.id
                  JOIN users u2 ON t.guide_id = u2.id
                  LEFT JOIN attractions a ON t.attraction_id = a.id
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
