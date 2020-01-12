<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Balance
 * @package App
 */
class Balance extends Model
{
    protected $table = 'balance';

    protected $fillable = [
        'account_id',
        'currency',
        'amount',
    ];

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return BelongsTo
     */
    public function balance()
    {
        return $this->belongsTo(Account::class);
    }
}
