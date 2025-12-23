<?php
/**
 * Strategy Interface for Payment Processing (Strategy Design Pattern)
 */
interface PaymentStrategyInterface {
    public function processPayment(float $amount, array $paymentData): array;
    public function getMethodName(): string;
}
?>

