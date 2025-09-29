<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'imagen',
        'name',
        'description',
        'start_time',
        'end_time',
        'name_airport',
        'longitude',
        'latitude',
    ];

    protected $dates = [
        'start_time',
        'end_time',
    ];

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }
}
