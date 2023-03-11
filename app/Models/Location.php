<?php

namespace App\Models;

use App\Infrastructure\Model;

class Location extends Model
{
    public string $table = 'locations';

    public string $foreignKey = 'location_id';

    protected array $fillable = [
        'user_id',
        'name',
        'city',
        'block'
    ];

    /**
     * Get the user that owns the Location
     * @return User
     */
    public function user(): User
    {
        return $this->belongsTo(User::class);
    }
}
