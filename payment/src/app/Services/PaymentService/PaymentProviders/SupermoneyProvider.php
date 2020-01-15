<?php

namespace App\Services\PaymentService\PaymentProviders;

/**
 * Class SupermoneyProvider
 * @package App\Services\PaymentService\PaymentProviders
 */
class SupermoneyProvider implements PaymentProviderInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function processPayment(array $data)
    {
        return $data['details'] . '_' . rand(1, 1000);
    }
}
