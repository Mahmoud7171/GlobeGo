<?php
/**
 * Observer Interface for Observer Design Pattern
 */
interface ObserverInterface {
    public function update(string $eventType, array $data): void;
}
?>

