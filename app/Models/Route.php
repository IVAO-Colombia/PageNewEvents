<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Route extends Model
{
    protected $fillable = [
        'event_id',
        'hourOrigin',
        'hourDestination',
        'flight_number',
        'airline',
        'origin',
        'name_airport_origin',
        'longitude_origin',
        'latitude_origin',
        'iato_code_origin',
        'gate_origin',
        'destination',
        'name_airport_destination',
        'longitude_destination',
        'latitude_destination',
        'iato_code_destination',
        'gate_destination',
        'aircraft_type',
        'type',
        'is_commercial',
        'is_international',
        'is_cargo',
        'is_active',
    ];

    protected $casts = [
        'is_commercial' => 'boolean',
        'is_international' => 'boolean',
        'is_cargo' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
