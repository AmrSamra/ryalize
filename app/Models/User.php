<?php

namespace App\Models;

use App\Infrastructure\Model;

class User extends Model
{
    public string $table = 'users';

    public string $foreignKey = 'user_id';

    protected array $fillable = ['name', 'email', 'password'];

    protected array $hidden = ['password'];

    /**
     * Get the authTokens for the User
     * @return AuthToken
     */
    public function authTokens(): AuthToken
    {
        return $this->hasMany(AuthToken::class);
    }

    /**
     * Get the transactions for the User
     * @return Transaction
     */
    public function transactions(): Transaction
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the locations for the User
     * @return Location
     */
    public function locations(): Location
    {
        return $this->hasMany(Location::class);
    }

    /**
     * Generate new token for user
     * @return AuthToken
     */
    public function generateToken(): AuthToken
    {
        $token = bin2hex(random_bytes(64));
        $interval = config('app.token_expire', 86400);
        return (new AuthToken())->create([
            'user_id'       => $this->id,
            'token'         => $token,
            'expires_at'    => date('Y-m-d H:i:s', strtotime("+{$interval} minute"))
        ]);
    }
}
