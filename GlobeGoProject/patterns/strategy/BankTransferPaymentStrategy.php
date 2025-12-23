<?php
require_once __DIR__ . '/PaymentStrategyInterface.php';

/**
 * Concrete Strategy: Bank Transfer Payment (Strategy Design Pattern)
 */
class BankTransferPaymentStrategy implements PaymentStrategyInterface {
    public function processPayment(float $amount, array $paymentData): array {
        $accountNumber = $paymentData['account_number'] ?? '';
        $reference = 'BT-' . strtoupper(uniqid());
        
        return [
            'success' => true,
            'reference' => $reference,
            'message' => 'Bank transfer initiated. Payment will be confirmed upon receipt.'
        ];
    }
    
    public function getMethodName(): string {
        return 'bank_transfer';
    }
}
?>

