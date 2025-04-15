<?php
namespace Guest\Payment;

class PayPalPayment implements PaymentStrategyInterface {
    public function pay(float $amount): string {
        return "Paid $$amount via PayPal.";
    }
}

