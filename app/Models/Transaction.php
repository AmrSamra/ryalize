<?php

namespace App\Models;

use App\Infrastructure\Model;

class Transaction extends Model
{
    public string $table = 'transactions';

    public string $foreignKey = 'transaction_id';

    protected array $fillable = [
        'user_id',
        'amount',
        'type',
    ];

    public static $types = [
        'deposit',
        'withdrawal',
    ];

    /**
     * Get the user that owns the Transaction
     * @return User
     */
    public function user(): User
    {
        return $this->belongsTo(User::class);
    }
}
