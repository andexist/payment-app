<?php

namespace App\Http\Controllers;

use App\Account;
use App\Exceptions\ApiException;
use App\Http\Controllers\Requests\CreatePaymentRequest;
use App\Payment;
use App\Services\ClientService\ClientService;
use App\Services\PaymentService\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * Class PaymentController
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * PaymentController constructor.
     * @param PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }


    /**
     * @param CreatePaymentRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function createPayment(CreatePaymentRequest $request)
    {
        /** @var string $content */
        $content = $request->getContent();
        /** @var array $decodedContent */
        $decodedContent = json_decode($content, true);

        if ($decodedContent === null) {
            return response()->json(['message' => ClientService::CONTENT_RESPONSE_ERROR])
                ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var Account $account */
        $account = $this->paymentService->getClientIdByAccountId($decodedContent['accountId']);
        /** @var int $clientId */
        $clientId = $account->client()->first()->client_id;

        /** @var int $paymentsCount */
        $paymentsCount = $this->paymentService->getClientPaymentsPerLastHour($clientId);

        $this->paymentService->getPaymentsAmountCount($clientId);

        if ($paymentsCount >= PaymentService::MAX_PAYMENT_PER_HOUR) {
            throw new ApiException(PaymentService::MAX_PAYMENT_PER_HOUR_ERROR);
        }
        /** @var Payment $newPayment */
        $newPayment = $this->paymentService->create($decodedContent);

        return response()->json($newPayment)->setStatusCode(Response::HTTP_OK);
    }

}
