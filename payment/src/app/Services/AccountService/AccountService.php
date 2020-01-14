<?php


namespace App\Services\AccountService;

use App\Repository\AccountRepository;
use App\Services\AccountService\Utils\AccountHelper;
use Illuminate\Database\Eloquent\Collection;
use App\Account;
use Illuminate\Database\Eloquent\Builder;
use Exception;
use Illuminate\Http\Response;

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
     * @return Collection
     */
    public function getAll()
    {
        return $this->accountRepository->getAll();
    }

    /**
     * @param int $id
     * @return Account|Builder
     */
    public function getById(int $id)
    {
        return $this->accountRepository->getById($id);
    }

    /**
     * @param $accountId
     * @return Collection
     */
    public function getAccountBalance($accountId)
    {
        /** @var Account $account */
        $account = $this->getById($accountId);

        return $account->balance()->get();
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
     * @param array $data
     * @return Account|Builder
     */
    public function create(array $data)
    {
        /** @var array $preparedData */
        $preparedData = $this->accountHelper->prepareData($data);

        // create
        return $this->accountRepository->create($preparedData);
    }
}
