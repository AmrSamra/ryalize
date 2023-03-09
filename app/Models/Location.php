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
}
