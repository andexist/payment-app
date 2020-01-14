<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * @package App
 */
class Payment extends Model
{
    const STATUS_WAITING = 'WAITING';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_COMPLETED = 'COMPLETED';

    protected $table = 'payments';

    protected $fillable = [
        'account_id',
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
