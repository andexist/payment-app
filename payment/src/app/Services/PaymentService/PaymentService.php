<?php

namespace App\Services\PaymentService;

use App\Account;
use App\Repository\PaymentRepository;
use App\Services\AccountService\AccountService;
use App\Services\PaymentService\Utils\PaymentHelper;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PaymentService
 * @package Services\PaymentService
 */
class PaymentService
{
    const MAX_PAYMENT_PER_HOUR = 10;
    const MAX_PAYMENT_PER_HOUR_ERROR = 'Maximum 10 payments per hour.';
    const MAX_TOTAL_AMOUNT = 1000;
    const MAX_TOTAL_AMOUNT_MESSAGE = 'You have reached maximum payments amount';
    const ALMOST_REACHED_LIMIT = 'Payment amount is to big. Your limit is ';

    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * @var PaymentHelper
     */
    private $paymentHelper;

    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * PaymentService constructor.
     * @param PaymentRepository $paymentRepository
     * @param PaymentHelper $paymentHelper
     * @param AccountService $accountService
     */
    public function __construct(
        PaymentRepository $paymentRepository,
        PaymentHelper $paymentHelper,
        AccountService $accountService
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->paymentHelper = $paymentHelper;
        $this->accountService = $accountService;
    }

    /**
     * @param array $data
     * @param float $totalPaymentsAmount
     * @return array
     */
    public function create(array $data, float $totalPaymentsAmount)
    {
        /** @var array $preparedData */
        $preparedData = $this->paymentHelper->prepareData($data, $totalPaymentsAmount);

        return $this->paymentRepository->create($preparedData);
    }

    /**
     * @param int $clientId
     * @return int
     */
    public function getClientPaymentsPerLastHour(int $clientId)
    {
        return $this->paymentRepository->getClientPaymentsPerLastHour(
            $this->getClientAccountsIds($clientId)
        );
    }

    /**
     * @param int $clientId
     * @return float
     */
    public function getPaymentsAmountCount(int $clientId)
    {
        return $this->paymentRepository->getPaymentsAmountCount(
            $this->getClientAccountsIds($clientId)
        );
    }

    /**
     * @param int $clientId
     * @return Collection
     */
    public function getWaitingPaymentsByClientId(int $clientId)
    {
        /** @var array $accountIds */
        $accountIds = $this->getClientAccountsIds($clientId);

        return $this->paymentRepository->getWaitingPaymentsByAccountsIds($accountIds);
    }

    /**
     * @param Collection $clientPayments
     * @return Collection
     */
    public function confirmClientPayments(Collection $clientPayments)
    {
        return $this->paymentRepository->confirmClientPayments($clientPayments);
    }

    /**
     * @param int $clientId
     * @return Collection
     */
    public function getConfirmedClientPayment(int $clientId)
    {
        /** @var array $clientAccountsIds */
        $clientAccountsIds = $this->getClientAccountsIds($clientId);

        return $this->paymentRepository->getConfirmedClientPayment($clientAccountsIds);
    }

    /**
     * @param array $paymentsIds
     * @return array
     */
    public function processClientPayments(array $paymentsIds)
    {
        return $this->paymentRepository->processClientPayments($paymentsIds);
    }

    /**
     * @param int $accountId
     * @return Account
     */
    public function getClientIdByAccountId(int $accountId)
    {
        return $this->accountService->getById($accountId);
    }

    /**
     * @param int $clientId
     * @return array
     */
    private function getClientAccountsIds(int $clientId)
    {
        return $this->accountService->getByClientId($clientId);
    }
}
