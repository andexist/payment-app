<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Account
 * @package App
 */
class Account extends Model
{
    // add more currencies if needed
    const AVAILABLE_CURRENCIES = [
        'eur' => 'EUR',
        'usd' => 'USD',
        'gbp' => 'GBP',
    ];


    protected $table = 'accounts';

    protected $fillable = [
        'client_id',
        'account_name',
        'iban',
        'amount',
        'currency'
    ];

    /**
     * @return BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return HasOne
     */
    public function balance()
    {
        return $this->hasOne(Balance::class);
    }
}
