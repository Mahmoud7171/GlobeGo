<?php
require_once __DIR__ . '/ObserverInterface.php';

/**
 * Concrete Observer: Admin Logging (Observer Pattern)
 */
class AdminLogObserver implements ObserverInterface {
    public function update(string $eventType, array $data): void {
        $logMessage = sprintf(
            "[%s] Booking Event: %s - Reference: %s, Tourist: %s, Guide: %s",
            date('Y-m-d H:i:s'),
            $eventType,
            $data['booking_reference'] ?? 'N/A',
            $data['tourist_id'] ?? 'N/A',
            $data['guide_id'] ?? 'N/A'
        );
        error_log($logMessage);
    }
}
?>

