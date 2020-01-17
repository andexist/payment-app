<?php

namespace App\Services\PaymentService\Utils;

use App\Account;
use App\Client;
use App\Payment;
use App\Services\AccountService\AccountService;
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
     * @var AccountService
     */
    private $accountService;

    /**
     * PaymentHelper constructor.
     * @param FeeService $feeService
     * @param MegacashProvider $megacashProvider
     * @param SupermoneyProvider $supermoneyProvider
     * @param AccountService $accountService
     */
    public function __construct(
        FeeService $feeService,
        MegacashProvider $megacashProvider,
        SupermoneyProvider $supermoneyProvider,
        AccountService $accountService
    ) {
        $this->feeService = $feeService;
        $this->megacashProvider = $megacashProvider;
        $this->supermoneyProvider = $supermoneyProvider;
        $this->accountService = $accountService;
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
        /** @var string $details */
        $details = $this->processPayment($data);
        /** @var Account $payerDetails */
        $payerDetails = $this->getPayerDetails($data['accountId']);
        /** @var Client $client */
        $client = $payerDetails->client()->first();

        return [
            'account_id' => $data['accountId'],
            'payment_provider' => $data['paymentProvider'],
            'fee' => $fee,
            'amount' => $data['amount'],
            'currency' => Account::AVAILABLE_CURRENCIES['eur'],
            'payer_account' => $payerDetails->iban,
            'payer_name' => $client->first_name . ' ' . $client->last_name,
            'receiver_account' => $data['receiverAccount'],
            'receiver_name' => $data['receiverName'],
            'details' => $details,
            'status' => Payment::STATUS_WAITING,
        ];
    }

    /**
     * @param int $accountId
     * @return Account
     */
    private function getPayerDetails(int $accountId)
    {
        return $this->accountService->getById($accountId);
    }

    /**
     * @param array $data
     * @return string
     */
    private function processPayment(array $data)
    {
        if ($data['paymentProvider'] === Payment::PAYMENT_PROVIDER['megacash']) {
            return $this->megacashProvider->processPayment($data);
        } else if ($data['paymentProvider'] === Payment::PAYMENT_PROVIDER['supermoney']) {
            return $this->supermoneyProvider->processPayment($data);
        }
    }
}
