<?php
require_once __DIR__ . '/PaymentStrategyInterface.php';

/**
 * Concrete Strategy: PayPal Payment (Strategy Design Pattern)
 */
class PayPalPaymentStrategy implements PaymentStrategyInterface {
    public function processPayment(float $amount, array $paymentData): array {
        $paypalEmail = $paymentData['paypal_email'] ?? '';
        $reference = 'PP-' . strtoupper(uniqid());
        
        return [
            'success' => true,
            'reference' => $reference,
            'message' => 'PayPal payment processed successfully'
        ];
    }
    
    public function getMethodName(): string {
        return 'paypal';
    }
}
?>

