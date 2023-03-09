<?php

namespace App\Models;

use App\Infrastructure\Model;

class Transaction extends Model
{
    public string $table = 'transactions';

    public string $foreignKey = 'transaction_id';

    protected array $fillable = [
        'user_id',
        'location_id',
        'amount',
        'type',
    ];

    public static $types = [
        'deposit',
        'withdrawal',
    ];
}
