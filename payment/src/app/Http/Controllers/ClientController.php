<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Controllers\Requests\CreateClientRequest;
use App\Services\ClientService\ClientService;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    /**
     * @var ClientService
     */
    private $clientService;

    /**
     * ClientController constructor.
     * @param ClientService $clientService
     */
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * @return JsonResponse
     * @throws ApiException
     */
    public function getClients()
    {
        try {
            $clients = $this->clientService->getAll();

            return response()->json($clients)->setStatusCode(Response::HTTP_OK);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ApiException
     */
    public function getClient(int $id)
    {
        try {
            $client = $this->clientService->getById($id);

            return response()->json($client)->setStatusCode(Response::HTTP_OK);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }
    }

    /**
     * @param int $clientId
     * @return JsonResponse
     * @throws ApiException
     */
    public function getClientAccounts(int $clientId)
    {
        try {
            $clientAccounts = $this->clientService->getClientAccount($clientId);

            return response()->json($clientAccounts)->setStatusCode(Response::HTTP_OK);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }
    }

    /**
     * @param CreateClientRequest $request
     * @return JsonResponse
     */
    public function createClient(CreateClientRequest $request)
    {
        /** @var string $content */
        $content = $request->getContent();
        /** @var array $decodedContent */
        $decodedContent = json_decode($content, true);

        if ($decodedContent === null) {
            return response()->json(['message' => ClientService::CONTENT_RESPONSE_ERROR])
                ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // create new client
        $newClient = $this->clientService->create($decodedContent);

        return response()->json($newClient)->setStatusCode(Response::HTTP_OK);
    }
}
