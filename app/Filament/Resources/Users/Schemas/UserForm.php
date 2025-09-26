<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('firstname')
                    ->required(),
                TextInput::make('lastname')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('vid_ivao')
                    ->required(),
                TextInput::make('division_id')
                    ->required(),
                TextInput::make('country_id')
                    ->required(),
                TextInput::make('pilotRating_name')
                    ->required(),
                TextInput::make('pilotRating_short')
                    ->required(),
                TextInput::make('rank_ivao'),
            ]);
    }
}
