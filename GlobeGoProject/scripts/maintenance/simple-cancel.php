<?php
require_once __DIR__ . '/config/config.php';

// Check if user is logged in
if (!isLoggedIn() || !isTourist()) {
    redirect(SITE_URL . '/auth/login.php');
}

$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$booking_id) {
    $_SESSION['error_message'] = "No booking ID provided.";
    redirect(SITE_URL . '/dashboard.php');
}

// Initialize database connection
$database = new Database();
$conn = $database->getConnection();

try {
    // Start transaction
    $conn->beginTransaction();
    
    // First, check if the booking exists and belongs to the user
    $check_query = "SELECT id, tourist_id, status, tour_schedule_id, num_participants 
                    FROM bookings 
                    WHERE id = :booking_id AND tourist_id = :user_id";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bindParam(":booking_id", $booking_id);
    $check_stmt->bindParam(":user_id", $_SESSION['user_id']);
    $check_stmt->execute();
    
    $booking_data = $check_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking_data) {
        throw new Exception("Booking not found or you don't have permission to cancel it.");
    }
    
    if (!in_array($booking_data['status'], ['pending', 'confirmed'])) {
        throw new Exception("This booking cannot be cancelled. Current status: " . $booking_data['status']);
    }
    
    // Update the booking status to cancelled
    $update_query = "UPDATE bookings 
                     SET status = 'cancelled', updated_at = NOW() 
                     WHERE id = :booking_id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(":booking_id", $booking_id);
    
    if (!$update_stmt->execute()) {
        throw new Exception("Failed to update booking status.");
    }
    
    // Update available spots in tour schedule
    $spots_query = "UPDATE tour_schedules 
                    SET available_spots = available_spots + :num_participants 
                    WHERE id = :schedule_id";
    $spots_stmt = $conn->prepare($spots_query);
    $spots_stmt->bindParam(":num_participants", $booking_data['num_participants']);
    $spots_stmt->bindParam(":schedule_id", $booking_data['tour_schedule_id']);
    
    if (!$spots_stmt->execute()) {
        throw new Exception("Failed to update available spots.");
    }
    
    // Commit transaction
    $conn->commit();
    
    $_SESSION['success_message'] = "Your booking has been cancelled successfully.";
    
} catch (Exception $e) {
    // Rollback transaction if it was started
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    $_SESSION['error_message'] = "Failed to cancel booking: " . $e->getMessage();
}

// Redirect back to dashboard
redirect(SITE_URL . '/dashboard.php');
?>
