<?php

namespace App\Filament\Resources\Routes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RouteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_id')
                    ->relationship('event', 'name')
                    ->required(),
                TextInput::make('hourOrigin'),
                TextInput::make('hourDestination'),
                TextInput::make('flight_number')
                    ->required(),
                TextInput::make('airline'),
                TextInput::make('origin')
                    ->required(),
                TextInput::make('name_airport_origin'),
                TextInput::make('longitude_origin'),
                TextInput::make('latitude_origin'),
                TextInput::make('iato_code_origin'),
                TextInput::make('gate_origin'),
                TextInput::make('destination')
                    ->required(),
                TextInput::make('name_airport_destination'),
                TextInput::make('longitude_destination'),
                TextInput::make('latitude_destination'),
                TextInput::make('iato_code_destination'),
                TextInput::make('gate_destination'),
                TextInput::make('aircraft_type'),
                Select::make('type')
                    ->options(['Arrival' => 'Arrival', 'Departure' => 'Departure', 'none' => 'None'])
                    ->default('none')
                    ->required(),
                Toggle::make('is_commercial')
                    ->required(),
                Toggle::make('is_international')
                    ->required(),
                Toggle::make('is_cargo')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
