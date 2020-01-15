<?php

namespace App\Services\PaymentService\PaymentProviders;

use Illuminate\Support\Str;

/**
 * Class MegacashProvider
 * @package App\Services\PaymentService\PaymentProviders
 */
class MegacashProvider implements PaymentProviderInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function processPayment(array $data)
    {
        return Str::substr($data['details'], 20);
    }
}
