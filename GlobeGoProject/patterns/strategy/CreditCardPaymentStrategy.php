<?php
require_once __DIR__ . '/PaymentStrategyInterface.php';

/**
 * Concrete Strategy: Credit Card Payment (Strategy Design Pattern)
 */
class CreditCardPaymentStrategy implements PaymentStrategyInterface {
    public function processPayment(float $amount, array $paymentData): array {
        $cardNumber = $paymentData['card_number'] ?? '';
        $cvv = $paymentData['cvv'] ?? '';
        $reference = 'CC-' . strtoupper(uniqid());
        
        return [
            'success' => true,
            'reference' => $reference,
            'message' => 'Credit card payment processed successfully'
        ];
    }
    
    public function getMethodName(): string {
        return 'credit_card';
    }
}
?>

