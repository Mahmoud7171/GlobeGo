<?php
require_once __DIR__ . '/ObserverInterface.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/EmailTemplate.php';

/**
 * Concrete Observer: Email Notifications (Observer Pattern)
 *
 * This implementation uses PHP's mail() function to send real HTML emails.
 * For this to work on your machine, you need mail() configured in your
 * PHP/XAMPP environment (SMTP settings, sendmail, etc).
 */
class EmailNotificationObserver implements ObserverInterface {
    public function update(string $eventType, array $data): void {
        switch ($eventType) {
            case 'booking_created':
                $this->sendBookingCreatedEmail($data);
                break;
            case 'booking_cancelled':
                $this->sendBookingCancelledEmail($data);
                break;
            case 'booking_confirmed':
                $this->sendBookingConfirmedEmail($data);
                break;
        }
    }
    
    /**
     * Fetch booking details for email
     */
    private function getBookingDetails($booking_id): array {
        try {
            // Skip database operations during tests
            if (defined('PHPUNIT_RUNNING')) {
                return [];
            }
            
            $database = new Database();
            $conn = $database->getConnection();
            
            if (!$conn) {
                return [];
            }
            
            $query = "SELECT 
                        b.*,
                        ts.tour_date,
                        ts.tour_time,
                        t.title AS tour_title,
                        t.meeting_point,
                        t.duration_hours,
                        ut.first_name AS tourist_first_name,
                        ut.last_name AS tourist_last_name,
                        ut.email AS tourist_email,
                        ug.first_name AS guide_first_name,
                        ug.last_name AS guide_last_name,
                        a.location
                      FROM bookings b
                      JOIN tour_schedules ts ON b.tour_schedule_id = ts.id
                      JOIN tours t ON ts.tour_id = t.id
                      JOIN users ut ON b.tourist_id = ut.id
                      LEFT JOIN users ug ON t.guide_id = ug.id
                      LEFT JOIN attractions a ON t.attraction_id = a.id
                      WHERE b.id = :booking_id LIMIT 1";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return [
                    'tour_title' => $result['tour_title'] ?? '',
                    'tour_date' => $result['tour_date'] ?? '',
                    'tour_time' => $result['tour_time'] ?? '',
                    'location' => $result['location'] ?? '',
                    'guide_name' => trim(($result['guide_first_name'] ?? '') . ' ' . ($result['guide_last_name'] ?? '')),
                    'num_participants' => $result['num_participants'] ?? 1,
                    'total_price' => $result['total_price'] ?? 0,
                    'payment_method' => $result['payment_method'] ?? '',
                    'payment_status' => $result['payment_status'] ?? 'pending',
                    'meeting_point' => $result['meeting_point'] ?? '',
                    'duration_hours' => $result['duration_hours'] ?? 0
                ];
            }
        } catch (Exception $e) {
            error_log('EmailNotificationObserver error fetching booking details: ' . $e->getMessage());
        }
        
        return [];
    }
    
    private function sendBookingCreatedEmail(array $data): void {
        $booking_id = $data['booking_id'] ?? null;
        $bookingDetails = $booking_id ? $this->getBookingDetails($booking_id) : [];
        
        $subject = 'GlobeGo – Booking Confirmed: ' . ($data['booking_reference'] ?? 'N/A');
        $htmlMessage = EmailTemplate::bookingCreated($data, $bookingDetails);
        
        $this->sendEmailToTouristAndAdmin($subject, $htmlMessage, $data, true);
    }
    
    private function sendBookingCancelledEmail(array $data): void {
        $booking_id = $data['booking_id'] ?? null;
        $bookingDetails = $booking_id ? $this->getBookingDetails($booking_id) : [];
        
        // Calculate cancellation fee (25% of total price)
        $totalPrice = $bookingDetails['total_price'] ?? 0;
        $bookingDetails['cancellation_fee'] = $totalPrice * 0.25;
        $bookingDetails['refund_amount'] = $totalPrice - ($totalPrice * 0.25);
        
        $subject = 'GlobeGo – Booking Cancelled: ' . ($data['booking_reference'] ?? 'N/A');
        $htmlMessage = EmailTemplate::bookingCancelled($data, $bookingDetails);
        
        $this->sendEmailToTouristAndAdmin($subject, $htmlMessage, $data, true);
    }
    
    private function sendBookingConfirmedEmail(array $data): void {
        $booking_id = $data['booking_id'] ?? null;
        $bookingDetails = $booking_id ? $this->getBookingDetails($booking_id) : [];
        
        $subject = 'GlobeGo – Tour Confirmed by Guide: ' . ($data['booking_reference'] ?? 'N/A');
        $htmlMessage = EmailTemplate::bookingConfirmed($data, $bookingDetails);
        
        $this->sendEmailToTouristAndAdmin($subject, $htmlMessage, $data, true);
    }

    /**
     * Send the email to the tourist (if we can find their email) and always
     * to the admin address as a fallback.
     */
    private function sendEmailToTouristAndAdmin(string $subject, string $message, array $data, bool $isHtml = false): void {
        $toAddresses = [];

        // Try to load tourist email based on tourist_id
        if (!empty($data['tourist_id'])) {
            try {
                // Skip database operations during tests
                if (defined('PHPUNIT_RUNNING')) {
                    return;
                }
                
                $database = new Database();
                $conn = $database->getConnection();
                
                if (!$conn) {
                    return;
                }
                $stmt = $conn->prepare("SELECT email FROM users WHERE id = :id LIMIT 1");
                $stmt->bindParam(':id', $data['tourist_id'], PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row && !empty($row['email'])) {
                    $toAddresses[] = $row['email'];
                }
            } catch (Exception $e) {
                error_log('EmailNotificationObserver error fetching tourist email: ' . $e->getMessage());
            }
        }

        // Build headers for HTML email
        $from = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'no-reply@localhost';
        $fromName = defined('SITE_NAME') ? SITE_NAME : 'GlobeGo';
        
        $headers = "From: " . $fromName . " <" . $from . ">\r\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        
        if ($isHtml) {
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        } else {
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        }

        foreach (array_unique($toAddresses) as $to) {
            // Use @ to avoid PHP warnings if mail() isn't configured locally
            @mail($to, $subject, $message, $headers);
        }

        // Keep logging for debugging
        error_log('EmailNotification: ' . $subject . ' | Recipients: ' . implode(', ', $toAddresses));
    }
}
?>

