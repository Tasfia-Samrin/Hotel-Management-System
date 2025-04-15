<?php
namespace Guest\Payment;

class BankTransferPayment implements PaymentStrategyInterface {
    public function pay(float $amount): string {
        return "Paid $$amount via Bank Transfer.";
    }
}
