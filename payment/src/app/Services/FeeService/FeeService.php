<?php

namespace App\Services\FeeService;

/**
 * Class FeeService
 * @package App\Services\FeeService
 */
class FeeService
{
    /**
     * @param float $amount
     * @param float $totalPaymentsAmount
     * @return string
     */
    public function calculateFee(float $amount, float $totalPaymentsAmount)
    {
        if ($totalPaymentsAmount > 100.00) {
            $fee = ($amount * 5) / 100;
        } else if ($totalPaymentsAmount === 0.00 && $amount > 100.00) {
            $fee = ($amount * 5) / 100;
        } else {
            $fee = ($amount * 10) / 100;
        }

        return number_format((float)$fee, 2, '.', '');
    }
}
