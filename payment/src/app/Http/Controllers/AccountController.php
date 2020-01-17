<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Controllers\Requests\CreateAccountRequest;
use App\Services\AccountService\AccountService;
use App\Services\ClientService\ClientService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
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
            /** @var Collection $accounts */
            $accounts = $this->accountService->getAll();
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        return response()->json($accounts)->setStatusCode(Response::HTTP_OK);
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
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        return response()->json($account)->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param int $accountId
     * @return JsonResponse
     * @throws ApiException
     */
    public function getAccountPayments(int $accountId)
    {
        try {
            /** @var Collection $accountPayments */
            $accountPayments = $this->accountService->getAccountPayments($accountId);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        return response()->json($accountPayments)->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param CreateAccountRequest $request
     * @return JsonResponse
     * @throws ApiException
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

        try {
            /** @var array $newAccount */
            $newAccount = $this->accountService->create($decodedContent);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        return response()->json($newAccount)->setStatusCode(Response::HTTP_OK);
    }
}
