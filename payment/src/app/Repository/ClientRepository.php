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
     * @return array
     */
    public function create(array $data)
    {
        Client::query()->create($data);

        /** @var Client $client */
        $client = Client::query()
            ->where('username', $data['username'])
            ->get();

        return [
            'username' => $client->username,
            'firstName' => $client->first_name,
            'lastName' => $client->last_name,
            'created' => $client->created_at
        ];
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }
}
