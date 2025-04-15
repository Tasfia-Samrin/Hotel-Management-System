<?php
namespace Guest\Payment;

class CreditCardPayment implements PaymentStrategyInterface {
    public function pay(float $amount): string {
        return "Paid $$amount using Credit Card.";
    }
}
