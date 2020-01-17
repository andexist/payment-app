<?php

namespace App\Http\Controllers;

use App\Account;
use App\Exceptions\ApiException;
use App\Http\Controllers\Requests\CreatePaymentRequest;
use App\Http\Controllers\Requests\ProcessPaymentRequest;
use App\Services\ClientService\ClientService;
use App\Services\PaymentService\PaymentService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Exception;

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
        /** @var int $paymentsCount */
        $paymentsCount = $this->paymentService->getClientPaymentsPerLastHour($account->client_id);
        /** @var float $totalPaymentsAmount */
        $totalPaymentsAmount = $this->paymentService->getPaymentsAmountCount($account->client_id);
        /** @var float $limitLeft */
        $limitLeft = PaymentService::MAX_TOTAL_AMOUNT - $totalPaymentsAmount;

        // limit almost reached
        if ($totalPaymentsAmount + $decodedContent['amount'] > PaymentService::MAX_TOTAL_AMOUNT) {
            throw new ApiException(PaymentService::ALMOST_REACHED_LIMIT . $limitLeft);
        }

        // limit reached
        if ($totalPaymentsAmount > PaymentService::MAX_TOTAL_AMOUNT) {
            throw new ApiException(PaymentService::MAX_TOTAL_AMOUNT_MESSAGE);
        }

        // reached hourly payments limit
        if ($paymentsCount >= PaymentService::MAX_PAYMENT_PER_HOUR) {
            throw new ApiException(PaymentService::MAX_PAYMENT_PER_HOUR_ERROR);
        }

        try {
            /** @var array $newPayment */
            $newPayment = $this->paymentService->create($decodedContent, $totalPaymentsAmount);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        return response()->json($newPayment)->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param ProcessPaymentRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function approvePayments(ProcessPaymentRequest $request)
    {
        /** @var string $content */
        $content = $request->getContent();
        /** @var array $decodedContent */
        $decodedContent = json_decode($content, true);

        if ($decodedContent === null) {
            return response()->json(['message' => ClientService::CONTENT_RESPONSE_ERROR])
                ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var Collection $waitingPayments */
        $waitingPayments = $this->paymentService->getWaitingPaymentsByClientId($decodedContent['clientId']);

        if ($waitingPayments->isEmpty()) {
            return response()->json()->setStatusCode(Response::HTTP_NO_CONTENT);
        }

        try {
            $confirmedPayments = $this->paymentService->confirmClientPayments($waitingPayments);
        } catch (Exception $exception) {
            throw  new ApiException($exception->getMessage());
        }

        return response()->json($confirmedPayments)->setStatusCode(Response::HTTP_OK);
    }
}
