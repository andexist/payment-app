<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Controllers\Requests\CreateClientRequest;
use App\Services\ClientService\ClientService;
use Exception;
use Illuminate\Http\Response;
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
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        return response()->json($clients)->setStatusCode(Response::HTTP_OK);
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

        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        return response()->json($client)->setStatusCode(Response::HTTP_OK);
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
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        return response()->json($clientAccounts)->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param CreateClientRequest $request
     * @return JsonResponse
     * @throws ApiException
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

        try {
            /** @var array $newClient */
            $newClient = $this->clientService->create($decodedContent);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        return response()->json($newClient)->setStatusCode(Response::HTTP_OK);
    }
}
