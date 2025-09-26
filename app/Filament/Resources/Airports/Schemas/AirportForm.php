<?php

namespace App\Filament\Resources\Airports\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AirportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ident')
                    ->required(),
                TextInput::make('type'),
                TextInput::make('name'),
                TextInput::make('latitude_deg')
                    ->numeric(),
                TextInput::make('longitude_deg')
                    ->numeric(),
                TextInput::make('elevation_ft')
                    ->numeric(),
                TextInput::make('continent'),
                TextInput::make('iso_country'),
                TextInput::make('iso_region'),
                TextInput::make('municipality'),
                Toggle::make('scheduled_service')
                    ->required(),
                TextInput::make('icao_code'),
                TextInput::make('iata_code'),
                TextInput::make('gps_code'),
                TextInput::make('local_code'),
                TextInput::make('home_link'),
                TextInput::make('wikipedia_link'),
                Textarea::make('keywords')
                    ->columnSpanFull(),
            ]);
    }
}
