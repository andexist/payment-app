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
     * @param array $data
     */
    public function create(array $data)
    {
        Account::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }
}
