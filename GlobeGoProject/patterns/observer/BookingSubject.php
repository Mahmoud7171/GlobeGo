<?php
require_once __DIR__ . '/SubjectInterface.php';
require_once __DIR__ . '/ObserverInterface.php';

/**
 * Concrete Subject: Booking Events (Observer Pattern)
 */
class BookingSubject implements SubjectInterface {
    private $observers = [];
    
    public function attach(ObserverInterface $observer): void {
        $this->observers[] = $observer;
    }
    
    public function detach(ObserverInterface $observer): void {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
            $this->observers = array_values($this->observers);
        }
    }
    
    public function notify(string $eventType, array $data): void {
        foreach ($this->observers as $observer) {
            try {
                $observer->update($eventType, $data);
            } catch (Exception $e) {
                error_log("Observer error: " . $e->getMessage());
            }
        }
    }
}
?>

