<?php

namespace App\Repository;

use App\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PaymentRepository
 * @package App\Repository
 */
class PaymentRepository implements RepositoryInterface
{
    /**
     * @param int $id
     * @return Builder|Payment
     */
    public function getById(int $id)
    {
        return Payment::query()->findOrFail($id);
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        return Payment::all();
    }

    /**
     * @param array $accountsIds
     * @return int
     */
    public function getClientPaymentsPerLastHour(array $accountsIds)
    {
        /** @var Carbon $now */
        $now = Carbon::now();
        /** @var Carbon $hourBefore */
        $hourBefore = $now->subHour();

        /** @var Collection $payments */
        $payments = Payment::query()
            ->whereIn('account_id', $accountsIds)
            ->where('created_at', '>=', $hourBefore)
            ->get();

        $paymentsCount = [];

        foreach ($payments as $payment) {
            $paymentsCount[] = $payment->id;
        }

        return count($paymentsCount);
    }

    /**
     * @param array $accountsIds
     * @return float
     */
    public function getPaymentsAmountCount(array $accountsIds)
    {
        /** @var Carbon $dayStart */
        $dayStart = Carbon::now()->setTime(0, 0, 0);
        /** @var Carbon $dayEnd */
        $dayEnd = Carbon::now()->setTime(23, 59, 59);

        /** @var Collection $payments */
        $payments = Payment::query()
            ->whereIn('account_id', $accountsIds)
            ->whereBetween('created_at', [$dayStart, $dayEnd])
            ->get();

        return $payments->sum(function ($payment) {
            return $payment->amount;
        });
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        /** @var Payment $payment */
        $payment = Payment::query()->create($data);

        return [
            'client_id' => $payment->account()->first()->client_id,
            'account_id' => $payment->account_id,
            'details' => $payment->details,
            'receiver_account' => $payment->receiver_account,
            'receiver_name' => $payment->receiver_name,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
        ];
    }


    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param int $accountId
     * @return Payment|Builder
     */
    public function getUnconfirmedPayment(int $accountId)
    {
        return Payment::query()
            ->where('account_id', $accountId)
            ->where('status', Payment::STATUS_WAITING)
            ->orderByDesc('id')
            ->limit(1)
            ->first();
    }

    /**
     * @param int $paymentId
     * @return array
     */
    public function confirmPayment(int $paymentId)
    {
        /** @var Payment $payment */
        $payment = $this->getById($paymentId);
        $payment->status = Payment::STATUS_APPROVED;
        $payment->save();

        return [
            'payment_id' => $payment->id,
            'details' => $payment->details,
            'receiver_account' => $payment->receiver_account,
            'receiver_name' => $payment->receiver_name,
            'amount' => $payment->amount,
            'fee' => $payment->fee,
            'status' => $payment->status,
        ];
    }

    /**
     * @param int $paymentId
     * @return array
     */
    public function rejectPayment(int $paymentId)
    {
        /** @var Payment $payment */
        $payment = $this->getById($paymentId);
        $payment->status = Payment::STATUS_REJECTED;
        $payment->save();

        return [
            'payment_id' => $payment->id,
            'details' => $payment->details,
            'receiver_account' => $payment->receiver_account,
            'receiver_name' => $payment->receiver_name,
            'amount' => $payment->amount,
            'fee' => $payment->fee,
            'status' => $payment->status,
        ];
    }

    /**
     * @param array $clientAccountsIds
     * @return Collection
     */
    public function getConfirmedClientPayment(array $clientAccountsIds)
    {
        return Payment::query()
            ->whereIn('account_id', $clientAccountsIds)
            ->where('status', Payment::STATUS_APPROVED)
            ->get();
    }

    /**
     * @param array $paymentsIds
     * @return array
     */
    public function processClientPayments(array $paymentsIds)
    {
        Payment::query()->whereIn('id', $paymentsIds)
            ->update([
                'status' => Payment::STATUS_COMPLETED
            ]);

        /** @var Payment $payments */
        $payments = Payment::query()
            ->whereIn('id', $paymentsIds)
            ->get();

        $paymentsArray = [];

        foreach ($payments as $payment) {
            $paymentsArray[] = [
                'payment_id' => $payment->id,
                'details' => $payment->details,
                'receiver_account' => $payment->receiver_account,
                'receiver_name' => $payment->receiver_name,
                'amount' => $payment->amount,
                'fee' => $payment->fee,
                'status' => $payment->status,
            ];
        }

        return $paymentsArray;
    }
}
