<?php

namespace App\Services\PaymentService\PaymentProviders;

/**
 * Interface PaymentProviderInterface
 * @package App\Services\PaymentService\PaymentProviders
 */
interface PaymentProviderInterface
{
    public function processPayment(array $data);
}
