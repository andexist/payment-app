<?php


namespace App\Repository;

use App\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AccountRepository
 * @package App\Repository
 */
class AccountRepository implements RepositoryInterface
{
    /**
     * @param int $id
     * @return Builder|Account
     */
    public function getById(int $id)
    {
        return Account::query()->findOrFail($id);
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        return Account::all();
    }

    /**
     * @param int $clientId
     * @return array
     */
    public function getByClientId(int $clientId)
    {
        return Account::query()
            ->where('client_id', $clientId)
            ->pluck('id')->toArray();
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        /** @var Account $account */
        $account = Account::query()->create($data);

        return [
            'client_id' => $account->client_id,
            'account_name' => $account->account_name,
            'iban' => $account->iban,
            'balance' => $account->balance,
            'currency' => $account->currency,
        ];
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }
}
