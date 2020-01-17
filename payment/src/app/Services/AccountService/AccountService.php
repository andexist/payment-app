<?php


namespace App\Services\AccountService;

use App\Repository\AccountRepository;
use App\Services\AccountService\Utils\AccountHelper;
use Illuminate\Database\Eloquent\Collection;
use App\Account;

/**
 * Class AccountService
 * @package App\Services\AccountService
 */
class AccountService
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var AccountHelper
     */
    private $accountHelper;

    /**
     * AccountService constructor.
     * @param AccountRepository $accountRepository
     * @param AccountHelper $accountHelper
     */
    public function __construct(
        AccountRepository $accountRepository,
        AccountHelper $accountHelper
    ) {
        $this->accountRepository = $accountRepository;
        $this->accountHelper = $accountHelper;
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        /** @var array $preparedData */
        $preparedData = $this->accountHelper->prepareData($data);

        return $this->accountRepository->create($preparedData);
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        return $this->accountRepository->getAll();
    }

    /**
     * @param int $id
     * @return Account
     */
    public function getById(int $id)
    {
        return $this->accountRepository->getById($id);
    }

    /**
     * @param int $clientId
     * @return array
     */
    public function getByClientId(int $clientId)
    {
        return $this->accountRepository->getByClientId($clientId);
    }

    /**
     * @param int $accountId
     * @return Collection
     */
    public function getAccountPayments(int $accountId)
    {
        /** @var Account $account */
        $account = $this->getById($accountId);

        return $account->payments()->get();
    }
}
