<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use DateTime;

/**
 * Class Account
 * @package App
 * @property int $id
 * @property int $client_id
 * @property string $account_name
 * @property string $iban
 * @property float $balance
 * @property string $currency
 * @property DateTime $created_at
 * @property DateTime $updated_at
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
        'balance',
        'currency',
    ];

    /**
     * @return BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
