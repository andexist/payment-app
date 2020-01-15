<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * @package App
 * @property int $account_id
 * @property float $fee
 * @property float $amount
 * @property string $currency
 * @property string $payer_account
 * @property string $payer_name
 * @property string $receiver_account
 * @property string $receiver_name
 * @property string $details
 * @property string $status
 */
class Payment extends Model
{
    const STATUS_WAITING = 'WAITING';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_COMPLETED = 'COMPLETED';

    const PAYMENT_PROVIDER = [
        'megacash' => 'MEGACASH',
        'supermoney' => 'SUPERMONEY',
    ];

    protected $table = 'payments';

    protected $fillable = [
        'account_id',
        'payment_provider',
        'fee',
        'amount',
        'currency',
        'payer_account',
        'payer_name',
        'receiver_account',
        'receiver_name',
        'details',
        'status',
    ];
}
