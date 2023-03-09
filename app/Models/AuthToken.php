<?php

namespace App\Models;

use App\Infrastructure\Model;

class AuthToken extends Model
{
    public string $table = 'auth_tokens';

    public string $foreignKey = 'auth_token_id';

    protected array $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    /**
     * Scope a query to only include valid tokens.
     * @return AuthToken
     */
    public function valid(): AuthToken
    {
        $this->builder->where(['expires_at', '>', date('Y-m-d H:i:s')]);
        return $this;
    }

    /**
     * Get the user that owns the AuthToken
     * @return User
     */
    public function user(): User
    {
        return $this->belongsTo(User::class);
    }
}
