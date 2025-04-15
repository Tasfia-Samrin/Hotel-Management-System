<?php
namespace Guest\Payment;

interface PaymentStrategyInterface {
    public function pay(float $amount): string;
}
