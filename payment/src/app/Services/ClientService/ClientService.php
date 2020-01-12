<?php

namespace App\Services\ClientService;

use App\Client;
use App\Repository\ClientRepository;
use App\Services\ClientService\Utils\ClientHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ClientService
 * @package App\Services\ClientService
 */
class ClientService
{
    const CONTENT_RESPONSE_ERROR = 'Could not parse content';

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var ClientHelper
     */
    private $clientHelper;

    /**
     * ClientService constructor.
     * @param ClientRepository $clientRepository
     * @param ClientHelper $clientHelper
     */
    public function __construct(
        ClientRepository $clientRepository,
        ClientHelper $clientHelper
    ) {
        $this->clientRepository = $clientRepository;
        $this->clientHelper = $clientHelper;
    }


    /**
     * @return Collection
     */
    public function getAll()
    {
        return $this->clientRepository->getAll();
    }

    /**
     * @param int $id
     * @return Client|Builder
     */
    public function getById(int $id)
    {
        return $this->clientRepository->getById($id);
    }

    public function create(array $data)
    {
        /** @var array $preparedData */
        $preparedData = $this->clientHelper->prepareData($data);
        // create
        return $this->clientRepository->create($preparedData);
    }

    /**
     * @param int $clientId
     * @return Collection
     */
    public function getClientAccount(int $clientId)
    {
        /** @var Client $client */
        $client = $this->getById($clientId);

        return $client->accounts()->get();
    }
}
