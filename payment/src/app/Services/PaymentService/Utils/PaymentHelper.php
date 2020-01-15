<?php

namespace App\Services\PaymentService\Utils;

use App\Account;
use App\Payment;
use App\Services\FeeService\FeeService;
use App\Services\PaymentService\PaymentProviders\MegacashProvider;
use App\Services\PaymentService\PaymentProviders\SupermoneyProvider;

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
     * @var MegacashProvider
     */
    private $megacashProvider;

    /**
     * @var SupermoneyProvider
     */
    private $supermoneyProvider;

    /**
     * PaymentHelper constructor.
     * @param FeeService $feeService
     * @param MegacashProvider $megacashProvider
     * @param SupermoneyProvider $supermoneyProvider
     */
    public function __construct(
        FeeService $feeService,
        MegacashProvider $megacashProvider,
        SupermoneyProvider $supermoneyProvider
    ) {
        $this->feeService = $feeService;
        $this->megacashProvider = $megacashProvider;
        $this->supermoneyProvider = $supermoneyProvider;
    }

    /**
     * @param array $data
     * @param float $totalAmount
     * @param string $provider
     * @return array
     */
    public function prepareData(array $data, float $totalAmount, string $provider)
    {
        /** @var float $fee */
        $fee = $this->feeService->calculateFee($data['amount'], $totalAmount);
        /** @var string $details */
        $details = $this->processPayment($data, $provider);

        return [
            'account_id' => $data['accountId'],
            'payment_provider' => $data['paymentProvider'],
            'fee' => $fee,
            'amount' => $data['amount'],
            'currency' => Account::AVAILABLE_CURRENCIES['eur'],
            'payer_account' => 'random data',
            'payer_name' => "random data",
            'receiver_account' => $data['receiverAccount'],
            'receiver_name' => $data['receiverName'],
            'details' => $details,
            'status' => Payment::STATUS_WAITING,
        ];
    }

    /**
     * @param array $data
     * @param string $provider
     * @return string
     */
    private function processPayment(array $data, string $provider)
    {
        if ($provider === Payment::PAYMENT_PROVIDER['megacash']) {
            return $this->megacashProvider->processPayment($data);
        } else if ($provider === Payment::PAYMENT_PROVIDER['supermoney']) {
            return $this->supermoneyProvider->processPayment($data);
        }
    }
}
