<?php

namespace App\Services\PaymentService;

use App\Account;
use App\Repository\PaymentRepository;
use App\Services\AccountService\AccountService;
use App\Services\PaymentService\Utils\PaymentHelper;
use Illuminate\Database\Eloquent\Builder;
use App\Payment;
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
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->paymentHelper = $paymentHelper;
        $this->accountService = $accountService;
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        return $this->paymentRepository->getAll();
    }

    /**
     * @param int $id
     * @return Payment|Builder
     */
    public function getById(int $id)
    {
        return $this->paymentRepository->getById($id);
    }

    /**
     * @param int $clientId
     * @return array
     */
    private function getClientAccountsIds(int $clientId)
    {
        return $this->accountService->getByClientId($clientId);
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
     * @return Collection
     */
    public function getPaymentsByClientId(int $clientId)
    {
        /** @var array $accountIds */
        $accountIds = $this->getClientAccountsIds($clientId);

        return $this->paymentRepository->getByAccountsIds($accountIds);
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
     * @param array $data
     * @param float $totalPaymentsAmount
     * @param string $provider
     * @return Payment|Builder
     */
    public function create(array $data, float $totalPaymentsAmount, string $provider)
    {
        /** @var array $preparedData */
        $preparedData = $this->paymentHelper->prepareData($data, $totalPaymentsAmount, $provider);

        return $this->paymentRepository->create($preparedData);
    }

    /**
     * @param int $accountId
     * @return Account|Builder
     */
    public function getClientIdByAccountId(int $accountId)
    {
        return $this->accountService->getById($accountId);
    }

    public function confirmClientPayments(Collection $clientPayments)
    {
        return $this->paymentRepository->confirmClientPayments($clientPayments);
    }
}
