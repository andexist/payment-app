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
     * @param int $accountId
     * @return Builder[]|Collection
     */
    public function getByAccountId(int $accountId)
    {
        return Payment::query()
            ->where('account_id', $accountId)
            ->get();
    }

    /**
     * @param array $accountsIds
     * @return Collection
     */
    public function getByAccountsIds(array $accountsIds)
    {
        return Payment::query()
            ->whereIn('account_id', $accountsIds)
            ->where('status', Payment::STATUS_WAITING)
            ->get();
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
     * @return Builder|Payment
     */
    public function create(array $data)
    {
        Payment::query()->create($data);

        return Payment::query()->latest()->first([
            'id',
            'account_id',
            'details',
            'receiver_account',
            'receiver_name',
            'amount',
            'currency',
        ]);
    }


    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function confirmClientPayments(Collection $clientPayments)
    {
        $approvedPayments = [];

        foreach ($clientPayments as $clientPayment) {
            $payment = $this->getById($clientPayment->id);
            $payment->status = Payment::STATUS_APPROVED;
            $payment->save();
            $approvedPayments[] = $payment->id;
        }

        return Payment::query()
            ->whereIn('id', $approvedPayments)
            ->get();
    }
}
