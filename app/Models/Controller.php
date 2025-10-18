<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Controller extends Model
{
    protected $fillable = [
        'event_id',
        'vid',
        'position',
        'start',
        'end',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
