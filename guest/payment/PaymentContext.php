<?php
namespace Guest\Payment;

class PaymentContext {
    private PaymentStrategyInterface $strategy;

    public function setStrategy(PaymentStrategyInterface $strategy): void {
        $this->strategy = $strategy;
    }

    public function executePayment(float $amount): string {
        return $this->strategy->pay($amount);
    }
}
