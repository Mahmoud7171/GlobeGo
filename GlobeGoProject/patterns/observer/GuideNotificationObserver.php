<?php
require_once __DIR__ . '/ObserverInterface.php';

/**
 * Concrete Observer: Guide Notifications (Observer Pattern)
 */
class GuideNotificationObserver implements ObserverInterface {
    public function update(string $eventType, array $data): void {
        if ($eventType === 'booking_created' || $eventType === 'booking_cancelled') {
            $guideId = $data['guide_id'] ?? null;
            $bookingRef = $data['booking_reference'] ?? 'N/A';
            error_log("Guide Notification: Event '{$eventType}' for Guide ID {$guideId}, Booking: {$bookingRef}");
        }
    }
}
?>

