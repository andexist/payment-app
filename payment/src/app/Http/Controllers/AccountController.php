<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Controllers\Requests\CreateAccountRequest;
use App\Services\AccountService\AccountService;
use App\Services\ClientService\ClientService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;

/**
 * Class AccountController
 * @package App\Http\Controllers
 */
class AccountController extends Controller
{
    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * AccountController constructor.
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @return JsonResponse
     * @throws ApiException
     */
    public function getAccounts()
    {
        try {
            $accounts = $this->accountService->getAll();

            return response()->json($accounts)->setStatusCode(Response::HTTP_OK);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ApiException
     */
    public function getAccount(int $id)
    {
        try {
            $account = $this->accountService->getById($id);

            return response()->json($account)->setStatusCode(Response::HTTP_OK);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }
    }

    /**
     * @param int $accountId
     * @return JsonResponse
     * @throws ApiException
     */
    public function getAccountBalance(int $accountId)
    {
        try {
            $accountBalance = $this->accountService->getAccountBalance($accountId);

            return response()->json($accountBalance)->setStatusCode(Response::HTTP_OK);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }
    }

    /**
     * @param CreateAccountRequest $request
     * @return JsonResponse
     */
    public function createAccount(CreateAccountRequest $request)
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
        $this->accountService->create($decodedContent);

        return response()->json()->setStatusCode(Response::HTTP_OK);
    }
}
