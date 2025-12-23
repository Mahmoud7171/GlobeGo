<?php
require_once __DIR__ . '/ObserverInterface.php';

/**
 * Subject Interface for Observer Design Pattern
 */
interface SubjectInterface {
    public function attach(ObserverInterface $observer): void;
    public function detach(ObserverInterface $observer): void;
    public function notify(string $eventType, array $data): void;
}
?>

