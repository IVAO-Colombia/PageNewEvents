<?php

namespace App\Filament\Resources\Routes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoutesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.name')
                    ->label('Event')
                    ->searchable(),
                TextColumn::make('hourOrigin')
                    ->label('Scheduled time')
                    ->searchable(),
                TextColumn::make('flight_number')
                    ->label('Flight Number')
                    ->searchable(),
                TextColumn::make('airline')
                    ->label('Airline')
                    ->searchable(),
                TextColumn::make('name_airport_origin')
                    ->label('Airport Origin')
                    ->searchable(),
                TextColumn::make('name_airport_destination')
                    ->label('Airport Destination')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge(),
                IconColumn::make('is_commercial')
                    ->label('Commercial')
                    ->boolean(),
                IconColumn::make('is_international')
                    ->label('International')
                    ->boolean(),
                IconColumn::make('is_cargo')
                    ->label('Cargo')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
