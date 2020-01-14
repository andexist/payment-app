<?php

namespace App\Services\PaymentService\Utils;

use App\Account;
use App\Payment;
use App\Services\FeeService\FeeService;

/**
 * Class PaymentHelper
 * @package Services\PaymentService\Utils
 */
class PaymentHelper
{
    /**
     * @var FeeService;
     */
    private $feeService;

    /**
     * PaymentHelper constructor.
     * @param FeeService $feeService
     */
    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    /**
     * @param array $data
     * @param float $totalAmount
     * @return array
     */
    public function prepareData(array $data, float $totalAmount)
    {
        /** @var float $fee */
        $fee = $this->feeService->calculateFee($data['amount'], $totalAmount);

        return [
            'account_id' => $data['accountId'],
            'fee' => $fee,
            'amount' => $data['amount'] + $fee,
            'currency' => Account::AVAILABLE_CURRENCIES['eur'],
            'payer_account' => 'random data',
            'payer_name' => "random data",
            'receiver_account' => $data['receiverAccount'],
            'receiver_name' => $data['receiverName'],
            'details' => $data['details'],
            'status' => Payment::STATUS_WAITING,
        ];
    }


}
