<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('firstname')
                    ->label('First name')
                    ->searchable(),
                TextColumn::make('lastname')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('vid_ivao')
                    ->label('VID')
                    ->searchable(),
                TextColumn::make('division_id')
                    ->label('Division')
                    ->searchable(),
                TextColumn::make('country_id')
                    ->label('Country')
                    ->searchable(),
                TextColumn::make('pilotRating_name')
                    ->label('Pilot Rating Name')
                    ->searchable(),
                TextColumn::make('pilotRating_short')
                    ->label('Pilot Rating Short')
                    ->searchable(),
                TextColumn::make('rank_ivao')
                    ->label('Rank IVAO')
                    ->searchable(),
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
                SelectFilter::make('division_id')
                    ->label('Division')
                    ->options(fn () => DB::table('users')
                        ->whereNotNull('division_id')
                        ->where('division_id', '<>', '')
                        ->distinct()
                        ->orderBy('division_id')
                        ->pluck('division_id', 'division_id')
                        ->toArray()
                    )
                    ->placeholder('All')
                    ->searchable(),
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
