<?php


namespace App\Repository;

use App\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ClientRepository
 * @package App\Repository
 */
class ClientRepository implements RepositoryInterface
{
    /**
     * @param int $id
     * @return Client|Builder
     */
    public function getById(int $id)
    {
        return Client::query()->findOrFail($id);
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        return Client::all();
    }

    /**
     * @param array $data
     * @return Builder|Client
     */
    public function create(array $data)
    {
       Client::query()->create($data);

        return Client::query()->latest()->first([
            'username',
            'first_name',
            'last_name',
            'created_at'
        ]);
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }
}
