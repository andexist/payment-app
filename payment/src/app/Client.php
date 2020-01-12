<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Client
 * @package App
 * @property int $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 */
class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
    ];

    /**
     * @return HasMany
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
