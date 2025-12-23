<?php
require_once __DIR__ . '/PaymentStrategyInterface.php';

/**
 * Context class that uses Payment Strategy (Strategy Design Pattern)
 */
class PaymentContext {
    private $strategy;
    
    public function setStrategy(PaymentStrategyInterface $strategy): void {
        $this->strategy = $strategy;
    }
    
    public function executePayment(float $amount, array $paymentData): array {
        if ($this->strategy === null) {
            throw new Exception("Payment strategy not set");
        }
        return $this->strategy->processPayment($amount, $paymentData);
    }
    
    public function getPaymentMethod(): string {
        return $this->strategy ? $this->strategy->getMethodName() : '';
    }
}
?>

