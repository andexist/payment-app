<?php

namespace App\Http\Controllers;

use App\Account;
use App\Exceptions\ApiException;
use App\Http\Controllers\Requests\PaymentRequest\CreatePaymentRequest;
use App\Http\Controllers\Requests\PaymentRequest\ProcessPaymentRequest;
use App\Http\Controllers\Requests\PaymentRequest\RejectPaymentRequest;
use App\Payment;
use App\Services\PaymentService\PaymentService;
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
            return response()->json(['message' => ApiException::CONTENT_RESPONSE_ERROR])
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
        /** @var Payment $lastPayment */
        $unconfirmedPayment = $this->paymentService->getUnconfirmedPayment($account->client_id);

        // confirm or reject last payment before creating new one
        if ($unconfirmedPayment) {
            throw new ApiException(ApiException::UNCONFIRMED_PAYMENT_ERROR . $unconfirmedPayment->id);
        }

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
    public function approvePayment(ProcessPaymentRequest $request)
    {
        /** @var string $content */
        $content = $request->getContent();
        /** @var array $decodedContent */
        $decodedContent = json_decode($content, true);

        if ($decodedContent === null) {
            return response()->json(['message' => ApiException::CONTENT_RESPONSE_ERROR])
                ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            /** @var array $confirmedPayment */
            $confirmedPayment = $this->paymentService->confirmPayment($decodedContent['paymentId']);
        } catch (Exception $exception) {
            throw  new ApiException($exception->getMessage());
        }

        return response()->json($confirmedPayment)->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param RejectPaymentRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function rejectPayment(RejectPaymentRequest $request)
    {
        /** @var string $content */
        $content = $request->getContent();
        /** @var array $decodedContent */
        $decodedContent = json_decode($content, true);

        if ($decodedContent === null) {
            return response()->json(['message' => ApiException::CONTENT_RESPONSE_ERROR])
                ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            /** @var array $rejectedPayment */
            $rejectedPayment = $this->paymentService->rejectPayment($decodedContent['paymentId']);
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        return response()->json($rejectedPayment)->setStatusCode(Response::HTTP_OK);
    }
}
