<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = [
        'ident','name_airport','city','country','iata_code','icao_code','latitude_deg','longitude_deg','elevation_ft','timezone','dst','tz_database_time_zone','type','source'
    ];

    protected $casts = [
        'latitude_deg' => 'float',
        'longitude_deg' => 'float',
        'elevation_ft' => 'integer',
    ];
}
