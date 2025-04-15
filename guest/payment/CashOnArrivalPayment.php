<?php
namespace Guest\Payment;

class CashOnArrivalPayment implements PaymentStrategyInterface {
    public function pay(float $amount): string {
        return "Payment of $$amount will be made at check-in (Cash on Arrival).";
    }
}
