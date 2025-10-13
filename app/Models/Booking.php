<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'Name_event',
        'route_id',
        'Callsign',
        'Departure',
        'Arrival',
        'VID'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'Name_event', 'id');
    }
}
